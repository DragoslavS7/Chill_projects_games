var defaultOptions = {
    processing: true,
    serverSide: true,
    ajax: '',
    order: [[1, "asc"]],
    sDom: '<t><"row"<"col-xs-4 pt-5"l> <"col-xs-4"i> <"col-xs-4"p>>',
    pagingType: 'full_numbers',
    columns: [],
    addSelectColumn: true
};

var defaultSelectColumn = {
    title: '<input type="checkbox" class="select-all m-0-auto d-block"/>',
    name: 'checkbox',
    orderable: false,
    searchable: false,
    render: function (data, type, row) {
        return '<input data-id="' + row.id + '" type="checkbox" class="select-row  m-0-auto d-block"/>';
    },
    width: '3%'
};


function DataTablesMyArcadeChef(selector, options) {
    this.options = Object.assign({}, defaultOptions, options);
    this._$table = $(selector);

    if (this.options.addSelectColumn) {
        this.options.columns.unshift(defaultSelectColumn);
    }

    this._initTable();
    this._setEvents();
}


DataTablesMyArcadeChef.prototype._initTable = function () {
    this.dataTable =  this._$table.DataTable(this.options);
};


DataTablesMyArcadeChef.prototype._setEvents = function () {
    var that = this;

    // Connect custom input search to data table
    if(this.options.customSearchInputSelector) {
        $(this.options.customSearchInputSelector).keyup(function () {
            that.dataTable.search($(this).val()).draw();
        });
    }

    // Connect events for selecting row
    if (this.options.addSelectColumn) {

        this._$table.on('click', '.select-row', function () {

            // Select row
            $(this).parents('tr').toggleClass('info');

            // Check if all checkbox are selected
            var areAllSelected = true;

            that._$table.find('.select-row').each(function () {
                if (!$(this).is(':checked')) {
                    areAllSelected = false;
                    return false;
                }
            });

            if (areAllSelected) {
                that._$table.find('.select-all').prop('checked', true);
            } else {
                that._$table.find('.select-all').prop('checked', false);
            }

        });

        this._$table.on('click', '.select-all', function () {
            var isChecked = $(this).is(':checked');

            if (isChecked) {
                that._$table.find('tr:gt(0)').addClass('info');
                that._$table.find('.select-row').prop('checked', true);
            } else {
                that._$table.find('tr:gt(0)').removeClass('info');
                that._$table.find('.select-row').prop('checked', false);
            }
        });
    }
};

module.exports = DataTablesMyArcadeChef;