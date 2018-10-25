@extends('layouts.dashboard')
@section('page-title', $game->name. ' Leaderboard')
@section('dashboard-title', $game->name. ' Leaderboard')

@section('dashboard-actions')
    <div class="col-xs-offset-4 col-xs-8">
        {{ Form::select('game',$games, $game->id,[ 'class' => 'form-control', 'id'=> 'game','data-id'=>$game->id]) }}
    </div>
@endsection

@section('dashboard-main-content')

    <div class="col-xs-12 bg-white shadow wraper-background">
        @if(true)

            <div class="row pt-30">
                <div class="col-xs-1 text-right fn-s-19 pt-5">
                    <b>{{ Form::label('search', 'Search') }}</b>
                </div>
                <div class="col-xs-2">
                    <input type="text" id="search-users" class="form-control">
                </div>

                <div class="col-xs-offset-4 col-xs-2 text-right fn-s-19 pt-5">
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
                <table class="table table-bordered" id="users-table">
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
        var DATA_TABLE_COLUMNS_USERS =  [
            {
                title: 'First Name',
                data: 'first_name',
                name: 'first_name',
                defaultContent: '',
            },
            {
                title: 'Last Name',
                data: 'last_name',
                name: 'last_name',
                defaultContent: '',
            },
            {
                title: 'Email',
                data: 'email',
                name: 'email',
                defaultContent: ''
            },
            {
                title: '# of Games Played',
                data: 'games_played',
                name: 'games_played',
                defaultContent:'',
            },
            {
                title: 'Points',
                data: 'points',
                name: 'points',
                defaultContent:'',
            },
            {
                title: 'Avg. Game Time',
                data: 'average_game_time',
                name: 'average_game_time',
                defaultContent:'',
            },
            {
                title: 'Correct Answers',
                data: 'correct',
                name: 'correct',
                defaultContent:'',
            },
            {
                title: 'Incorrect Answers',
                data: 'incorrect',
                name: 'incorrect',
                defaultContent:'',
            }
        ];

        $(document).ready(function () {
            var id = $('#game').attr('data-id');
            var urlUsers = '{{ route('client-portal.leaderboard.data-tables-users', '_ID_') }}';
            urlUsers = urlUsers.replace('_ID_', id);

            var dataTableUsers = new DataTablesMyArcadeChef('#users-table', {
                ajax: {
                    url: urlUsers,
                    data: function (d) {
                        $.extend(d, {start_date: $('input[name="start_date"]').val()});
                        $.extend(d, {end_date: $('input[name="end_date"]').val()});
                    }
                },
                columns: DATA_TABLE_COLUMNS_USERS,
                customSearchInputSelector: '#search-users',
            });

            $('input[name="start_date"], input[name="end_date"]').change( function() {
                dataTableUsers.dataTable.draw();

            } );

            $('#game').change(function(){
                var id = this.value;
                var url = '{{ route('client-portal.leaderboards.game', '_ID_') }}';
                url = url.replace('_ID_', id);

                window.location = url;
            });
        });
    </script>
@endpush