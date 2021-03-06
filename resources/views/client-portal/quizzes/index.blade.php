@extends('layouts.dashboard')
@section('page-title', 'Master Quiz List')
@section('dashboard-title', 'Master Quiz List')


@section('dashboard-actions')
    @can('create', new \App\Quiz())
    <a href="{{ route('client-portal.quizzes.create') }}">
        <button class="btn btn-default pull-right">New Quiz</button>
    </a>
    @endcan
@endsection

@section('dashboard-main-content')

    @if(Session::has('success'))
        <div class="row p-20">
            <div class="alert alert-success">
                {{ Session::get('success')}}
            </div>
        </div>
    @endif

    @if(Session::has('error'))

        <div class="alert alert-danger fade in">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            {{Session::get('error')}}
        </div>

    @endif

    <div class="col-xs-12 bg-white shadow wraper-background">
        @if($areThereAnyQuizzes)
            <div class="row pt-30">
                @can('create', new \App\Quiz())
                <div class="col-xs-2">
                    {{  Form::select('bulk_actions', [ '' => 'Bulk Actions',
                                                       'delete_all' => 'Delete All',
                    ], null, ['class' => 'form-control', 'id'=>'bulk_actions']) }}
                </div>

                <div class="col-xs-1">
                    <button class="apply btn btn-light">Apply</button>
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
                <table class="table table-bordered" id="quiz-table">
                </table>
            </div>
        @else
            <div class="pt-10 pb-10 fn-s-19" >
                <p class="text-center p-0  bg-athens-gray">No Quizzes Yet</p>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        var DATA_TABLE_COLUMNS = [
            {
                title:'Quiz Name',
                data:'name',
                name:'name',
                defaultContent:'',
            },
            {
                title: 'Total assigned#',
                defaultContent: '',
                render: function(data, type, row, meta){
                    return  row.games.length;
                }
            },
            {
                title: 'Assigned to Game',
                defaultContent: '',
                render: function(data, type, row, meta){
                    var url = '{{ route('client-portal.games.edit', '_ID_' ) }}';

                    return  row.games.reduce(function(html, game){
                        return html + "<a class='mr-5 ml-5 fn-c-mine-shaft' href='"+ url.replace('_ID_', game.id) +"'>" + game.name + "</a>"+"<i class=\"fa fa-angle-double-right\" aria-hidden=\"true\"></i>"
                    }, '');
                }
            },
                @can('create', new \App\Quiz())
            {
                title: 'Status',
                data: 'is_enabled',
                name: 'is_enabled',
                width: '3%',
                render: function(data, type, row, meta){
                    return  '<div class="m-0-auto d-table">' +
                        '<label class="switch mt-5">' +
                        '<input data-id="' + row.id + '" type="checkbox"' + (row.is_enabled ? 'checked' : '') + '>' +
                        '<span class="slider round"></span>' +
                        '</label>' +
                        '</div>';
                }
            },
                @endcan
                @can('edit', new \App\Quiz())
            {
                title: '<p class="text-center">Edit</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    return '<button title="Edit quiz" class="edit btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.id + '">' +
                        '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>' +
                        '</button>';
                }
            },
                @endcan
                @can('create', new \App\Quiz())
            {
                title: '<p class="text-center">Duplicate</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    return '<button title="Duplicate quiz" class="duplicate btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.id + '">' +
                        '<i class="fa fa-files-o" aria-hidden="true"></i>' +
                        '</button>';
                },
            },
                @endcan
                @can('destroy', new \App\Quiz())
            {
                title: '<p class="text-center">Delete</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    return '<button title="Delete quiz" class="delete btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.id + '">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                        '</button>';
                },
            }
                @endcan
        ];

        $(document).ready(function () {
            var dataTable = new DataTablesMyArcadeChef('#quiz-table', {
                ajax: '{{ route('client-portal.quizzes.data-tables') }}',
                columns: DATA_TABLE_COLUMNS,
                customSearchInputSelector: '#search'
            });

            // On click on edit button go to edit page
            $('table').on('click', '.duplicate', function() {
                var id = this.dataset.id;
                var url = '{{ route('client-portal.quizzes.duplicate', '_ID_' ) }}';
                url = url.replace('_ID_', id);

                window.location = url;
            });

            // On click on edit button go to edit page
            $('table').on('click', '.edit', function() {
                var id = this.dataset.id;
                var url = '{{ route('client-portal.quizzes.edit', '_ID_' ) }}';
                url = url.replace('_ID_', id);

                window.location = url;
            });

            // On change of status notify backend
            $('table').on('change', ':checkbox', function(){
                var isActive = $(this).is(':checked') ? 1 : 0;
                var id = this.dataset.id;
                var url = '{{ route('client-portal.quizzes.update', '_ID_' ) }}';
                url = url.replace('_ID_', parseInt(id));

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'is_enabled': isActive
                    }
                });
            });

            var confirmModal = new ConfirmDialog({
                modal_title:'Delete quiz',
                modal_dialog_message:'Are you sure you want to delete this quiz?'
            });

            function deleteQuiz() {
                var id = this.dataset.id;
                var url = '{{ route('client-portal.quizzes.delete', '_ID_' ) }}';
                url = url.replace('_ID_', id);
                var $row = $(this).parents('tr');

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function () {
                        dataTable.dataTable
                            .row($row)
                            .remove()
                            .draw();
                    }
                });
            }

            // On click on delete button notify backend and remove row in table
            $('table').on('click', '.delete', confirmModal.show(deleteQuiz)  );

            // On click on aplly button notify backend of the bulk action
            $('.apply').on('click', function() {

                var ids = [];
                var rows = $('.select-row');
                var rowsDelete = [];
                var url = '';

                rows.each(function(){
                    if(this.checked){
                        var $row = $(this).parents('tr');
                        var id  = $row.find('button.edit')[0].dataset.id;
                        ids.push(id);
                        rowsDelete.push($(this).parents('tr'));
                    }
                });

                var selected = $('#bulk_actions :selected').text();
                switch (selected){
                    case 'Delete All':
                        url = '{{ route('client-portal.quizzes.bulk-delete') }}';

                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                ids:ids
                            },
                            success: function () {
                                rowsDelete.forEach(function($row){
                                    dataTable.dataTable
                                        .row($row)
                                        .remove();
                                });
                                dataTable.dataTable.draw();
                            }
                        });
                        break;
                    default:
                        break;
                }
            });

            filtersSession(dataTable);

        });
    </script>
@endpush