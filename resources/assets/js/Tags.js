var defaultTagsOptions = {
    autocomplete: false,
    autocomplateUrl: "",

    suggestedTags: [],

    $tagInput: $(''),
    $tagServerInput: $(''),
    $suggestedTagsContainer: $(''),
    $addedTagsContainer: $(''),
    $autocompleteContainer: $('')

};

function Tags(options) {
    this.options = Object.assign({}, defaultTagsOptions, options);
    this._selectedTags = [];

    this._init();
}

//Model
Tags.prototype.isAlredySelected = function (tag) {
    return this._selectedTags.indexOf(tag) > -1;
};

Tags.prototype.selectTag = function (tag) {
    return this._selectedTags.push(tag);
};

Tags.prototype.deselectTag = function (tag) {
    var index = this._selectedTags.indexOf(tag);
    if (index > -1) {
        this._selectedTags.splice(index, 1);
    }
};

Tags.prototype.suggestedTagsThatAreNotSelected = function () {
    var selectedTags = this._selectedTags;

    return this.options.suggestedTags.filter(function (tag) {
        return selectedTags.indexOf(tag) < 0;
    });
};

Tags.prototype.getAutocompleteResults = function(cb, value){
    var that = this;
    if(this._xhrAutocomplete){
        this._xhrAutocomplete.abort()
    }

    this._xhrAutocomplete = $.ajax({
        type: 'GET',
        dataType: 'json',
        url: that.options.autocomplateUrl,
        data: {
            search: value,
            _token: that.options.csrfToken
        },
        success: function(data){
            cb(data);
        }
    });
};


//Controller
Tags.prototype._init = function () {
    this._setEvents();
    this._generateSuggestedTagContainer();

    this.showSuggestedTags();
}
;

Tags.prototype.addTags = function (tags) {
    for (var i = 0, len = tags.length; i < len; i++) {
        var tag = tags[i];

        if (!this.isAlredySelected(tag)) {
            this.selectTag(tag);
            this.generateGuiForTag(tag);
            this.setServerInput(this._selectedTags);
        }
    }

    //Re-render suggested tags
    this.clearAllSuggestedTags();
    this.showSuggestedTags();
};

Tags.prototype.removeTags = function (tags) {
    for (var i = 0, len = tags.length; i < len; i++) {
        var tag = tags[i];

        if (this.isAlredySelected(tag)) {
            this.deselectTag(tag);
            this.degenerateGuiForTag(tag);
            this.setServerInput(this._selectedTags);
        }
    }

    //Re-render suggested tags
    this.clearAllSuggestedTags();
    this.showSuggestedTags();
};

Tags.prototype.showSuggestedTags = function () {

    for (var i = 0, len = this.options.suggestedTags.length; i < len; i++) {
        var tag = this.options.suggestedTags[i];

        if (!this.isAlredySelected(tag)) {
            this.generateGuiForSuggestedTag(tag);
        }
    }

    var suggestedTagsThatAreNotSelected = this.suggestedTagsThatAreNotSelected();

    if (len == 0 || suggestedTagsThatAreNotSelected.length == 0) {
        this.hideSuggestHeader();
        this.hideSeparatorSuggest();
    } else {
        this.showSuggestHeader();

        if (this._selectedTags.length > 0) {
            this.showSeparatorSuggest();
        } else {
            this.hideSeparatorSuggest();
        }
    }
};

//View
Tags.prototype._setEvents = function () {
    var that = this;

    this.options.$addedTagsContainer.on('mouseover', 'button', function () {
        $(this).find('i').toggleClass('fa-check fa-times');
    });

    this.options.$addedTagsContainer.on('mouseout', 'button', function () {
        $(this).find('i').toggleClass('fa-times fa-check');
    });

    this.options.$addedTagsContainer.on('click', 'button', function () {
        that.removeTags([this.dataset.tag]);
    });

    this.options.$suggestedTagsContainer.on('click', 'button', function () {
        that.addTags([this.dataset.tag]);
    });

    this.options.$tagInput.keyup(function (e) {
        if(this.value.length > 24){
            that.showError("Max length is 24 characters.");
            return;
        }else{
            that.hideError();
        }

        if ([188, 13].indexOf(e.which) > -1) {
            that.addTags([this.value.replace(/,/g, '')]);
            this.value = '';
        } else if(this.value.length >= 3){
            that.showAutocomplete();
            that.getAutocompleteResults(that.generateAutocompletePossabilities.bind(that), this.value );
        }
    });

    this.options.$tagInput.focus(function(){
        if(this.value.length >= 3){
            that.showAutocomplete();
        }
    });

    this.options.$tagInput.click(function(e){
        e.stopPropagation();
    });


    this.options.$autocompleteContainer.on('mouseover', 'p', function () {
        $(this).addClass('alert-info');
    });

    this.options.$autocompleteContainer.on('mouseout', 'p', function () {
        $(this).removeClass('alert-info');
    });

    this.options.$autocompleteContainer.on('click', 'p', function (e) {
        that.options.$tagInput.val('').focus();
        that.addTags([this.dataset.value]);
        that.hideAutocomplete();

        e.stopPropagation();
    });

    $(window).click(function() {
        that.hideAutocomplete();
    });
};

Tags.prototype.generateGuiForTag = function (tag) {
    this.options.$addedTagsContainer.append("<button data-tag='" + tag + "' type='button' class='btn btn-light bg-transparent mr-5 mt-5'>" +
        "<i class='fa fa-check w-20' aria-hidden='true'></i>" + tag +
        "</button>");
};
Tags.prototype.degenerateGuiForTag = function (tag) {
    this.options.$addedTagsContainer.find('[data-tag="' + tag + '"]').remove();
};


Tags.prototype.generateGuiForSuggestedTag = function (tag) {
    this.options.$suggestedTagsContainer.append("<button type='button' data-tag='" + tag + "' class='btn btn-light bg-transparent mr-5 mt-5'>" +
        "<i class='fa fa-plus w-20' aria-hidden='true'></i>" + tag +
        "</button>");
};

Tags.prototype._generateSuggestedTagContainer = function () {
    this.options.$suggestedTagsContainer.append("<hr/>");
    this.options.$suggestedTagsContainer.append("<p class='m-5 fn-s-14'>Suggested tags:</p>");
};

Tags.prototype.generateAutocompletePossabilities = function(possibilities){
    this.options.$autocompleteContainer.empty();
    for(var i = 0, len = possibilities.length; i < len; i++){
        this.options.$autocompleteContainer.append("<p style='cursor: pointer' class='m-0 pl-10' data-value='" + possibilities[i] + "'>" + possibilities[i] + "</p>");
    }

    if(len == 0){
        this.options.$autocompleteContainer.text('There is no results.')
    }
};

Tags.prototype.showAutocomplete = function(){
    this.options.$autocompleteContainer.show();
};

Tags.prototype.hideAutocomplete = function(){
    this.options.$autocompleteContainer.hide();
};

Tags.prototype.setServerInput = function (tagsForServer) {
    this.options.$tagServerInput.val(tagsForServer.join(','));
};

Tags.prototype.clearAllSuggestedTags = function () {
    this.options.$suggestedTagsContainer.find("button").remove();
};

Tags.prototype.hideSuggestHeader = function () {
    this.options.$suggestedTagsContainer.find("p").hide();
};

Tags.prototype.showSuggestHeader = function () {
    this.options.$suggestedTagsContainer.find("p").show();
};

Tags.prototype.hideSeparatorSuggest = function () {
    this.options.$suggestedTagsContainer.find("hr").hide();
};

Tags.prototype.showSeparatorSuggest = function () {
    this.options.$suggestedTagsContainer.find("hr").show();
};

Tags.prototype.showError = function(error){
    var $errorElem = this.options.$tagInput.next('.text-danger').find('i');

    if($errorElem.length){
        $errorElem.text(error);
    }else{
        this.options.$tagInput.after('<p class="text-left text-danger fn-s-13  tags-message"><i>' + error + '</i></p>');
    }
};

Tags.prototype.hideError = function(){
    this.options.$tagInput.next('.text-danger').remove();
};

module.exports = Tags;