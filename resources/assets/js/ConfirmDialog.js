var defaultOptions = {
    modal_title:'Confirmation',
    modal_dialog_message:'Are you sure?'
};


function ConfirmDialog(options) {
    this.options = Object.assign({}, defaultOptions, options);
    this._init();
}


ConfirmDialog.prototype._init = function () {
    var $confirmationDialog = $('<div/>',{
        id:'confirmation-dialog',
        'class':'modal fade',
        'role':'dialog'
    });


    this._$dialog = $confirmationDialog.append(
        '<div class="modal-dialog" role="document">'+
            '<div class="modal-content">'+
                '<div class="modal-header">'+
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                    '<h4 class="modal-title">'+this.options.modal_title+'</h4>'+
                '</div>'+
                '<div class="modal-body text-center">'+
                    '<p>'+this.options.modal_dialog_message+'</p>'+
                '</div>'+
                '<div class="modal-footer">'+
                    '<button id="confirm" type="button" class="btn btn-primary">Confirm changes</button>'+
                    '<button id="cancel" type="button" class="btn btn-default">Cancel</button>'+
                '</div>'+
            '</div><!-- /.modal-content -->'+
        '</div><!-- /.modal-dialog -->'
    );

    $('body').append(this._$dialog);

};


ConfirmDialog.prototype.show = function (confirmCallback, cancelCallback) {
    var classContext = this;

    return function(event){
        classContext._$dialog.modal('show');
        var eventContext = this;
        var isConfirmed = undefined;

        classContext._$dialog.find("#confirm").off().click(function(){
            isConfirmed = true;
            classContext._$dialog.modal('hide');
            if(confirmCallback){
                confirmCallback.call(eventContext, event);
            }
        });

        classContext._$dialog.find("#cancel").off().click(function(){
            classContext._$dialog.modal('hide');
            isConfirmed = false;
            if(cancelCallback){
                cancelCallback.call(eventContext, event);
            }
        });

        classContext._$dialog.off('hidden.bs.modal').on('hidden.bs.modal', function () {
            // If dialog is closed without using confirm or cancel button, cancel callback is called
            if(isConfirmed === undefined && cancelCallback){
                cancelCallback.call(eventContext, event);
            }
        });

    };

};

module.exports = ConfirmDialog;