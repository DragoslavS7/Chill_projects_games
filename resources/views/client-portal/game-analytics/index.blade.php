@extends('layouts.dashboard')
@section('page-title', 'Analytics Per Game')
@section('dashboard-title', 'Analytics Per Game')

@section('dashboard-main-content')
    <div class="col-xs-12 bg-white shadow wraper-background">
        @if($areThereGames)
            <div class="row pt-30">
                <div class="col-xs-offset-4 col-xs-1 text-right fn-s-19 pt-5">
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
                        {{ Form::date('start_date',  \Carbon\Carbon::createFromDate(null, 1, 1)), ['class' => 'form-control','id'=>'start_date'] }}
                        {{ Form::date('end_date',  \Carbon\Carbon::createFromDate(null, 12, 31)), ['class' => 'form-control','id'=>'end_date'] }}
                    </div>
                </div>
            </div>
            <div class="pt-15 pb-30">
                <table class="table table-bordered" id="games-table">
                </table>
            </div>
        @else
            <div class="pt-10 pb-10 fn-s-19" >
                <p class="text-center p-0  bg-athens-gray">
                    No current analytics data.
                    <a href="" class="fn-c-mine-shaft"> Add new games?</a>
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
            {
                data: 'created_at',
                visible: false,
                searchable: true
            },
            {
                title: '<p class="text-center">View</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    return '<button title="View game analytics" class="view btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.id + '">' +
                        '<i class="fa fa-eye" aria-hidden="true"></i>'+
                        '</button>';
                },

            }
        ];

        $(document).ready(function () {
            var dataTable = new DataTablesMyArcadeChef('#games-table', {
                ajax: {
                    url:'{{ route('client-portal.games.data-tables.analytics') }}',
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
                var url = '{{ route('client-portal.game-analytics.game', '_ID_') }}';
                url = url.replace('_ID_', id);

                window.location = url;
            });

            dateRangeFilter(dataTable);

            filtersSession(dataTable);

        });
    </script>
@endpush