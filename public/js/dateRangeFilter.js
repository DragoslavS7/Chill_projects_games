function dateRangeFilter(dataTable) {
    // Date range filter
    var minDateFilter = '';
    var maxDateFilter = '';

    $.fn.dataTableExt.afnFiltering.push(
        function(oSettings, aData, iDataIndex) {
            if (typeof aData._date === 'undefined') {
                aData._date = new Date(aData[0]).getTime();
            }
            if (minDateFilter && !isNaN(minDateFilter)) {
                if (aData._date < minDateFilter) {
                    return false;
                }
            }

            if (maxDateFilter && !isNaN(maxDateFilter)) {
                if (aData._date > maxDateFilter) {
                    return false;
                }
            }

            return true;
        }
    );

    $("[name='start_date']").change(function() {
        if ($(this).val() !== null && $(this).val() !== '') {
            minDateFilter = new Date($(this).val()).getTime();
        }
        if ($(this).val() === null || $(this).val() === '') {
            var dateOnFirstDayOfCurrentYear = new Date(new Date().getFullYear(), 0, 1);

            var day = ("0" + dateOnFirstDayOfCurrentYear.getDate()).slice(-2);
            var month = ("0" + (dateOnFirstDayOfCurrentYear.getMonth() + 1)).slice(-2);

            this.value =  dateOnFirstDayOfCurrentYear.getFullYear()+"-"+(month)+"-"+(day) ;
        }
        dataTable.dataTable.draw();
    });

    $("[name='end_date']").change(function() {
        if ($(this).val() !== null && $(this).val() !== '') {
            maxDateFilter = new Date($(this).val()).getTime();
        }
        if ($(this).val() === null || $(this).val() === '') {
            var dateOnLastDayOfCurrentYear = new Date(new Date().getFullYear(), 11, 31);

            var day = ("0" + dateOnLastDayOfCurrentYear.getDate()).slice(-2);
            var month = ("0" + (dateOnLastDayOfCurrentYear.getMonth() + 1)).slice(-2);

            this.value =  dateOnLastDayOfCurrentYear.getFullYear()+"-"+(month)+"-"+(day) ;
        }
        dataTable.dataTable.draw();
    });
}
