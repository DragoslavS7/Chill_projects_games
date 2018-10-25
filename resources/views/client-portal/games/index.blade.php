@extends('layouts.dashboard')
@section('page-title', 'Manage Games')
@section('dashboard-title', 'Manage Games')


@section('dashboard-actions')
    @can('create', new \App\Game())
    <a href="{{ route('client-portal.games.templates') }}">
        <button title="New Game" class="btn btn-default pull-right">New Game</button>
    </a>
    @endcan
@endsection


@section('dashboard-main-content')

    @foreach($errors->all() as $error)
        <div class="col-xs-12">
            <div class="alert alert-danger alert-dismissible fade in">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p class="text-left text-danger"><i>{{$error}}</i></p>
            </div>
        </div>
    @endforeach

    @if(Session::has('success'))
        <div class="row p-20">
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ Session::get('success')}}
            </div>
        </div>
    @endif

    <div class="col-xs-12 bg-white shadow wraper-background">
        @if($areThereAnyGames)
            <div class="row pt-30">
                @can('create', new \App\Game())
                <div class="col-xs-2">
                    {{  Form::select('bulk_actions', [ '' => 'Bulk Actions',
                                                       'invite' => 'Invite to play',
                                                       'delete_all' => 'Delete All',
                    ], null, ['class' => 'form-control', 'id'=>'bulk_actions']) }}
                </div>

                <div class="col-xs-1">
                    <button id="apply" class="apply btn btn-light">Apply</button>
                </div>

                <div class="col-xs-offset-6 col-xs-1 text-right fn-s-19 pt-5">
                    <b>{{ Form::label('search', 'Search') }}</b>
                </div>
                <div class="col-xs-2">
                    <input type="text" id="search" class="form-control">
                </div>
                @endcan
            </div>
            <div class="pt-15 pb-30">
                <table class="table table-bordered" id="games-table">
                </table>
            </div>
        @else
            <div class="pt-10 pb-10 fn-s-19" >
                <p class="text-center p-0  bg-athens-gray">No Games Yet</p>
            </div>
        @endif
    </div>

    <div id="select-users" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select Users</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 pt-15">
                            <table class="table table-bordered w-100p" id="users-table">
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <form id='invite-to-play' action="{{route('client-portal.games.bulk-invite')}}" method="post">
                        {{Form::token()}}
                        <input type="hidden" name="game_ids">
                        <input type="hidden" name="user_ids">
                        <button type="submit" class="btn btn-default pull-right">Invite</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection


@push('scripts')
    <script>
        var DATA_TABLE_COLUMNS =  [
            {
                title: 'Name',
                data: 'name',
                name: 'name',
                defaultContent: '',
            },
            {
                title: 'Template',
                defaultContent: '',
                render:function(data, type, row, meta){
                    return row.template.name;
                }
            },
                @can('create', new \App\Game())
            {
                title: 'Quizzes',
                width: '3%',
                defaultContent:'',
                render: function(data, type, row, meta){
                    return ''+(row.quizzes.length > 0 ? 'Yes':'No')+'';
                }
            },
                @endcan
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
                    return '<button title="View game" class="view btn p-0 bg-transparent m-0-auto d-block" data-url="'+row.url+'">'+
                        '<i class="fa fa-eye" aria-hidden="true"></i>'+
                        '</button>';
                },

            },
                @can('edit', new \App\Game())
            {
                title: '<p class="text-center">Edit</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    return '<button title="Edit game" class="edit btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.id + ' ">' +
                        '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>' +
                        '</button>';
                },

            },
                @endcan
                @can('destroy', new \App\Game())
            {
                title: '<p class="text-center">Delete</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    return '<button title="Delete game" type="button" class="delete btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.id + '">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                        '</button>';
                },
            }
                @endcan
        ];

        $(document).ready(function () {
            var gamesDataTable = new DataTablesMyArcadeChef('#games-table', {
                ajax: '{{ route('client-portal.games.data-tables') }}',
                columns: DATA_TABLE_COLUMNS,
                customSearchInputSelector: '#search'
            });

            var usersDataTable = new DataTablesMyArcadeChef('#users-table', {
                ajax: '{{ route('client-portal.team-members.data-tables') }}',
                sDom: '<t><"row"<"col-xs-4 pt-5"l> <"col-xs-3"i> <"col-xs-5"p>>',
                columns: [
                    {
                        title:'First Name',
                        data:'first_name',
                        name:'first_name',
                        defaultContent:'',
                    },
                    {
                        title: 'Last Name',
                        data: 'last_name',
                        name:'last_name',
                        defaultContent:''
                    },
                    {
                        title: 'Email',
                        data: 'email',
                        name:'email',
                        defaultContent:''
                    },
                    {
                        title: 'Role',
                        data: 'role',
                        name:'role',
                        defaultContent:''
                    }]
            });

            // On click on view button go to view page
            $('table').on('click', '.view', function() {
                var gameUrl = this.dataset.url;
                var url = '{{ route('client-portal.players.game', '_URL_') }}';
                url = url.replace('/_URL_', gameUrl);

                window.open(url, '_blank');
                // window.location = url;
            });

            // On click on edit button go to edit page
            $('table').on('click', '.edit', function() {
                var id = this.dataset.id;
                var url = '{{ route('client-portal.games.edit', '_ID_') }}';
                url = url.replace('_ID_', parseInt(id));

                window.location = url;
            });

            // On chaning the game of status notify the backend
            $('table').on('change', ':checkbox', function(){
                var isActive = $(this).is(':checked') ? 1 : 0;
                var id = this.dataset.id;
                var url = '{{ route('client-portal.games.update', '_ID_' ) }}';
                url = url.replace('_ID_', parseInt(id));
console.log('fasdf');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'is_active': isActive
                    }
                });

            });

            var confirmModal = new ConfirmDialog({
                modal_title:'Delete game',
                modal_dialog_message:'Are you sure you want to delete this game?'
            });

            function deleteGame() {
                var id = this.dataset.id;
                var url = '{{ route('client-portal.games.delete', '_ID_' ) }}';
                url = url.replace('_ID_', parseInt(id));
                var $row = $(this).parents('tr');

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function () {
                        gamesDataTable.dataTable
                            .row($row)
                            .remove()
                            .draw();
                    }
                });
            }
            // On click on delete button notify backend and remove row in table
            $('table').on('click', '.delete', confirmModal.show(deleteGame));

            // adjust columns when modal is shown
            $('#select-users').on('shown.bs.modal', function (e) {
                usersDataTable.dataTable.columns.adjust();
            });

            // On click on aplly button notify backend of the bulk action
            $('.apply').on('click', function() {

                var ids = [];
                var rows = $('#games-table .select-row:checked');
                var rowsDelete = [];
                var url = '';

                rows.each(function(){
                    var $row = $(this).parents('tr');
                    var id  = parseInt(this.dataset.id);
                    ids.push(id);
                    rowsDelete.push($row);
                });

                var selected = $('#bulk_actions :selected').text();

                switch (selected){
                    case 'Delete All':
                        url = '{{ route('client-portal.games.bulk-delete') }}';

                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                ids:ids
                            },
                            success: function () {
                                rowsDelete.forEach(function($row){
                                    gamesDataTable.dataTable
                                        .row($row)
                                        .remove();
                                });
                                gamesDataTable.dataTable.draw();
                            }
                        });
                        break;
                    case 'Invite to play':
                        $('#select-users').modal('show');
                        $('#invite-to-play input[name="game_ids"]').val(JSON.stringify(ids));
                        break;

                    default:
                        break;
                }
            });

            $('#invite-to-play').submit(function(e){
                e.preventDefault();
                var ids = [];
                var rows = $('#users-table .select-row:checked');

                rows.each(function(){
                   ids.push( parseInt(this.dataset.id));
                });

                $('#invite-to-play input[name="user_ids"]').val(JSON.stringify(ids));

                this.submit();
            });

            filtersSession(gamesDataTable);

        });
    </script>
@endpush