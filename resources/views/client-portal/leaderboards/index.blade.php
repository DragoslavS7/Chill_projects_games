@extends('layouts.dashboard')
@section('page-title', 'Leaderboard')
@section('dashboard-title', 'Leaderboard')

@section('dashboard-main-content')

    @if(Session::has('error'))

        <div class="alert alert-danger fade in">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            {{Session::get('error')}}
        </div>

    @endif

    <div class="col-xs-12 bg-white shadow wraper-background">
        @if(true)
            <div class="row pt-30">
                @can('create', new \App\Game())
                <div class="col-xs-2">
                    {{  Form::select('bulk_actions', [ '' => 'Bulk Actions',
                                                       'enable_all' => 'Enable',
                                                       'disable_all' => 'Disable',
                                                       'reset_all' => 'Reset',
                    ], null, ['class' => 'form-control','id'=>'bulk_actions']) }}
                </div>

                <div class="col-xs-1">
                    <button class="apply btn btn-light">Apply</button>
                </div>
                <div class="col-xs-offset-1 col-xs-1 text-right fn-s-19 pt-5">
                    <b>{{ Form::label('search', 'Search') }}</b>
                </div>
                <div class="col-xs-2">
                    <input type="text" id="search" class="form-control">
                </div>

                <div class="col-xs-2 text-right fn-s-19 pt-5">
                    <b>{{ Form::label('date_range', 'Date Range') }}</b>
                </div>
                <div class="col-xs-3">
                    <div class="form-control">
                        {{ Form::date('start_date', \Carbon\Carbon::createFromDate(null, 1, 1)), ['class' => 'form-control','id'=>'start_date'] }}
                        {{ Form::date('end_date', \Carbon\Carbon::createFromDate(null, 12, 31)), ['class' => 'form-control','id'=>'end_date'] }}
                    </div>
                </div>
                @endcan
            </div>
            <div class="pt-15 pb-30">
                <table class="table table-bordered" id="games-table">
                </table>
            </div>
        @else
            <div class="pt-10 pb-10 fn-s-19" >
                <p class="text-center p-0  bg-athens-gray">
                    No current games.
                    <a href="" class="fn-c-mine-shaft"> Add new?</a>
                </p>
            </div>
        @endif
    </div>
@endsection


@push('scripts')
    <script>
        var DATA_TABLE_COLUMNS =  [
            {
                title: 'Game Name',
                data: 'name',
                name: 'name',
                defaultContent: '',
            },
                @can('create', new \App\Game())
            {
                title: 'Status',
                data: 'is_active',
                name: 'is_active',
                width: '3%',
                render: function(data, type, row, meta){
                    return  '<div class="m-0-auto d-table">' +
                        '<label class="switch mt-5">' +
                        '<input data-id="' + row.id + '" type="checkbox"' + (row.is_active ? 'checked' : '') + '>' +
                        '<span class="slider round"></span>' +
                        '</label>' +
                        '</div>';
                }
            },
                @endcan
            {
                title: '<p class="text-center">View</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    return '<button title="View leader board" class="view btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.id + '">' +
                        '<i class="fa fa-eye" aria-hidden="true"></i>'+
                        '</button>';
                },

            },
                @can('create', new \App\Game())
            {
                title: '<p class="text-center">Reset</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    return '<button title="Reset" class="reset btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.id + '">' +
                        '<i class="fa fa-undo" aria-hidden="true"></i>'+
                        '</button>';
                },

            }
                @endcan
        ];

        $(document).ready(function () {
            var dataTable = new DataTablesMyArcadeChef('#games-table', {
                ajax: {
                    url:'{{ route('client-portal.leaderboard.data-tables-games') }}',
                    data: function(d){
                        $.extend(d, {start_date: $('input[name="start_date"]').val()});
                        $.extend(d, {end_date: $('input[name="end_date"]').val()});
                    }
                },
                columns: DATA_TABLE_COLUMNS,
                customSearchInputSelector: '#search'
            });

            // On click on view button go to game page
            $('table').on('click', '.view', function() {
                var id = this.dataset.id;
                var url = '{{ route('client-portal.leaderboards.game', '_ID_') }}';
                url = url.replace('_ID_', id);

                window.location = url;
            });

            // On click on reset button notify backend
            $('table').on('click', '.reset', function() {
                var id = this.dataset.id;
                var url = '{{ route('client-portal.leaderboards.reset', '_ID_' ) }}';
                url = url.replace('_ID_', id);
                var $row = $(this).parents('tr');

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    success:function(){
                        dataTable.dataTable.draw();
                    }
                });
            });

            // On change of status notify backend
            $('table').on('change', ':checkbox', function(){
                if($(this).hasClass('select-row')){
                    var isActive = $(this).is(':checked') ? 1 : 0;
                    var id = this.dataset.id;
                    var url = '{{ route('client-portal.games.update', '_ID_' ) }}';
                    url = url.replace('_ID_', id);

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'is_active': isActive
                        }
                    });
                }
            });

            //on apply aply bulk actions
            $('.apply').on('click',function(){
                var $rows = $('.select-row');
                var $rowsAssigned = [];

                $rows.each(function(){
                    if(this.checked){
                        $rowsAssigned.push($(this).parents('tr').find('.switch :checkbox'));
                    }
                });

                var selected = $('#bulk_actions :selected').text();

                switch (selected){
                    case 'Enable':
                        $rowsAssigned.forEach(function($row){
                            var id = parseInt($row.attr('data-id'));
                            var url = '{{ route('client-portal.games.update', '_ID_' ) }}';
                            url = url.replace('_ID_', id);

                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: {
                                    '_token': '{{ csrf_token() }}',
                                    'is_active': 1
                                }
                            });

                            $row.prop('checked',true);
                        });
                        break;
                    case 'Disable':
                        $rowsAssigned.forEach(function($row){
                            var id = parseInt($row.attr('data-id'));
                            var url = '{{ route('client-portal.games.update', '_ID_' ) }}';
                            url = url.replace('_ID_', id);

                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: {
                                    '_token': '{{ csrf_token() }}',
                                    'is_active': 0
                                }
                            });

                            $row.prop('checked',false);
                        });
                        break;
                    case 'Reset':
                        $rowsAssigned.forEach(function($row){
                            var id = parseInt($row.attr('data-id'));
                            var url = '{{ route('client-portal.leaderboards.reset', '_ID_' ) }}';
                            url = url.replace('_ID_', id);

                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                data: {
                                    '_token': '{{ csrf_token() }}',
                                    success:function(){
                                        dataTable.dataTable.draw();
                                    }
                                }
                            });

                        });
                        break;
                }
            });

            dateRangeFilter(dataTable);

            filtersSession(dataTable);
        });
    </script>
@endpush