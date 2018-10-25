@extends('layouts.dashboard')
@section('page-title', $game->name.' Analytics')
@section('dashboard-title', $game->name.' Analytics')


@section('dashboard-actions')
    <div class="col-xs-8">
        {{ Form::select('game',$games, $game->id,[ 'class' => 'form-control' ,'id'=> 'game','data-id'=>$game->id]) }}
    </div>
    <a href="">
        <button title="Download report" class="btn btn-default pull-right">Download Report</button>
    </a>
@endsection

@section('dashboard-main-content')

    <div class="col-xs-12 bg-white shadow wraper-background">
        @if($areThereAnyAnalytics)
        <div class="row">
            <ul class="nav nav-tabs">
                <li role="presentation" class="ml-33 active">
                    <a href="#list-of-users" aria-controls="list-of-users" role="tab" data-toggle="tab" class="fn-c-white bg-silver">List of users</a>
                </li>
                <li role="presentation" class="ml-33">
                    <a href="#list-of-questions" aria-controls="list-of-questions" role="tab" data-toggle="tab" class="fn-c-white  bg-silver">List of questions</a>
                </li>
            </ul>
        </div>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="list-of-users">
                <div class="row pt-30">
                    <div class="col-xs-offset-4 col-xs-1 text-right fn-s-19 pt-5">
                        <b>{{ Form::label('search', 'Search') }}</b>
                    </div>
                    <div class="col-xs-2">
                        <input type="text" id="search-users" class="form-control">
                    </div>

                    <div class="col-xs-2 text-right fn-s-19 pt-5">
                        <b>{{ Form::label('date_range', 'Date Range') }}</b>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-control">
                            {{ Form::date('start_date', \Carbon\Carbon::createFromDate(null, 1, 1)), ['class' => 'form-control','id'=>'game_start_date'] }}
                            {{ Form::date('end_date', \Carbon\Carbon::createFromDate(null, 12, 31)), ['class' => 'form-control','id'=>'game_end_date'] }}
                        </div>
                    </div>
                </div>
                <div class="pt-15 pb-30">
                    <table class="table table-bordered" id="users-table">
                    </table>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane" id="list-of-questions">
                <div class="row pt-30">
                    <div class="col-xs-offset-4 col-xs-1 text-right fn-s-19 pt-5">
                        <b>{{ Form::label('search', 'Search') }}</b>
                    </div>
                    <div class="col-xs-2">
                        <input type="text" id="search-questions" class="form-control">
                    </div>

                    <div class="col-xs-2 text-right fn-s-19 pt-5">
                        <b>{{ Form::label('date_range', 'Date Range') }}</b>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-control">
                            {{ Form::date('start_date', \Carbon\Carbon::createFromDate(null, 1, 1)), ['class' => 'form-control','id'=>'questions_start_date'] }}
                            {{ Form::date('end_date', \Carbon\Carbon::createFromDate(null, 12, 31)), ['class' => 'form-control','id'=>'questions_end_date'] }}
                        </div>
                    </div>
                </div>
                <div class="pt-15 pb-30">
                    <table class="table table-bordered" id="questions-table">
                    </table>
                </div>
            </div>
        </div>

        @else
            <div class="pt-10 pb-10 fn-s-19" >
                <p class="text-center p-0  bg-athens-gray">
                    No current analytics data.
                    <a href="{{ route('client-portal.games.index') }}" class="fn-c-mine-shaft"> Send new invites?</a>
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
                data: 'game_sessions.[0].number_of_sessions',
                name: 'game_sessions.[0].number_of_sessions',
                searchable: false,
                defaultContent:'',
            },
            {
                title: 'Points',
                data: 'game_sessions.[0].sum_points',
                name: 'game_sessions.[0].sum_points',
                searchable: false,
                defaultContent:'',
            },
            {
                title: 'Avg. Game Time',
                data: 'game_sessions.[0].avgdiff',
                name: 'game_sessions.[0].avgdiff',
                searchable: false,
                defaultContent:'',
            },
            {
                title: 'Correct Answers',
                data: 'game_sessions.[0].sum_correct_answers',
                name: 'game_sessions.[0].sum_correct_answers',
                searchable: false,
                defaultContent:'',
            },
            {
                title: 'Incorrect Answers',
                data: 'game_sessions.[0].sum_incorrect_answers',
                name: 'game_sessions.[0].sum_incorrect_answers',
                searchable: false,
                defaultContent:'',
            },
            {
                title: 'Total Questions',
                data: 'game_sessions.[0].sum_total_questions',
                name: 'game_sessions.[0].sum_total_questions',
                searchable: false,
                defaultContent:'',
            },
        ];

        var DATA_TABLE_COLUMNS_QUESTIONS =  [
            {
                title: 'Question Title',
                data: 'name',
                name: 'name',
                defaultContent: '',
            },
            {
                title: 'Avg. Game Time',
                data: 'question_sessions.[0].avgdiff',
                name: 'question_sessions.[0].avgdiff',
                searchable: false,
                defaultContent:'',
            },
            {
                title: 'Correct Answers',
                data: 'question_sessions.[0].sum_correct_answers',
                name: 'question_sessions.[0].sum_correct_answers',
                searchable: false,
                defaultContent:'',
            },
            {
                title: 'Incorrect Answers',
                data: 'question_sessions.[0].sum_incorrect_answers',
                name: 'question_sessions.[0].sum_incorrect_answers',
                searchable: false,
                defaultContent:'',
            },
            {
                title: 'Total',
                data: 'question_sessions.[0].total_questions',
                name: 'question_sessions.[0].total_questions',
                searchable: false,
                defaultContent:'',
            },
        ];

        $(document).ready(function () {
            var id = $('#game').attr('data-id');
            var urlUsers = '{{ route('client-portal.game-analytics.data-tables-users', '_ID_') }}';
            var urlQuestions = '{{ route('client-portal.game-analytics.data-tables-questions', '_ID_') }}';
            urlUsers = urlUsers.replace('_ID_', id);
            urlQuestions = urlQuestions.replace('_ID_', id);

            var dataTableUsers = new DataTablesMyArcadeChef('#users-table', {
                ajax: {
                    url: urlUsers,
                    data: function (d) {
                        $.extend(d, {start_date: $('input[name="start_date"]').val()});
                        $.extend(d, {end_date: $('input[name="end_date"]').val()});
                    }
                },
                columns: DATA_TABLE_COLUMNS_USERS,
                customSearchInputSelector: '#search-users'
            });

            var dataTableQuestions = new DataTablesMyArcadeChef('#questions-table', {
                ajax: {
                    url: urlQuestions,
                    data: function (d) {
                        $.extend(d, {start_date: $('input[name="start_date"]').val()});
                        $.extend(d, {end_date: $('input[name="end_date"]').val()});
                    }
                },
                columns: DATA_TABLE_COLUMNS_QUESTIONS,
                customSearchInputSelector: '#search-questions'
            });

            $('#game').change(function(){
                var id = this.value;
                var url = '{{ route('client-portal.game-analytics.game', '_ID_') }}';
                url = url.replace('_ID_', id);

                window.location = url;
            });

            $('#questions-table').css('width','100%');

            $('input[name="start_date"], input[name="end_date"]').change( function() {
                dataTableUsers.dataTable.draw();
            } );

            $('input[name="start_date"], input[name="end_date"]').change( function() {
                dataTableQuestions.dataTable.draw();
            } );
        });
    </script>
@endpush