function filtersSession(dataTable) {
    var path = window.location.pathname;
    var pageLengthSession = sessionStorage.getItem('page_length');
    var searchTermElement = $('#search');
    var searchTermSession = sessionStorage.getItem('search_term');
    var startDateElement = $("[name='start_date']");
    var endDateElement = $("[name='end_date']");
    var startDateSession = sessionStorage.getItem('start_date');
    var endDateSession = sessionStorage.getItem('end_date');

    if (sessionStorage.getItem('path') === path) {
        if ((pageLengthSession !== null && pageLengthSession !== '') && (searchTermSession === null || searchTermSession === '')) {
            dataTable.dataTable.page.len(pageLengthSession).draw();
        }
        if ((searchTermSession !== null && searchTermSession !== '') && (pageLengthSession === null && pageLengthSession === '')) {
            searchTermElement.val(searchTermSession);
            dataTable.dataTable.search(searchTermSession).draw();
        }
        if ((searchTermSession !== null && searchTermSession !== '') && (pageLengthSession !== null && pageLengthSession !== '')) {
            searchTermElement.val(searchTermSession);
            dataTable.dataTable.page.len(pageLengthSession).search(searchTermSession).draw();
        }
        if ((pageLengthSession === null || pageLengthSession === '') && (searchTermSession === null || searchTermSession === '')) {
            dataTable.dataTable.draw();
        }
        if (startDateSession !== '' && startDateSession !== null) {
            startDateElement.val(startDateSession);
            minDateFilter = new Date(startDateSession).getTime();
            dataTable.dataTable.draw();
        }
        if (endDateSession !== '' && endDateSession !== null) {
            endDateElement.val(endDateSession);
            maxDateFilter = new Date(endDateSession).getTime();
            dataTable.dataTable.draw();
        }
    }

    if (sessionStorage.getItem('path') !== path) {
        sessionStorage.removeItem("search_term");
        sessionStorage.removeItem("page_length");
        sessionStorage.removeItem("start_date");
        sessionStorage.removeItem("end_date");
        sessionStorage.removeItem("path");
        sessionStorage.setItem('path', path);
    }

    searchTermElement.bind('input', function() {
        sessionStorage.setItem('search_term', $(this).val());
    });

    startDateElement.change(function() {
        if ($(this).val() !== null || $(this).val() !== '') {
            sessionStorage.setItem('start_date', $(this).val());
        }
    });

    endDateElement.change(function() {
        if ($(this).val() !== null || $(this).val() !== '') {
            sessionStorage.setItem('end_date', $(this).val());
        }
    });

    dataTable.dataTable.on('length.dt', function(e, settings, len) {
        sessionStorage.setItem('page_length', len);
    });
}
