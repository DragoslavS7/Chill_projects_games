@extends('layouts.dashboard')
@section('page-title', 'Manage Templates')
@section('dashboard-title', 'Manage Templates')

@section('dashboard-actions')
    <a href="{{ route('admin-portal.game-templates.create') }}">
        <button title="Add new game Template" class="btn btn-default pull-right">Add New</button>
    </a>
@endsection

@section('dashboard-main-content')

    @if(Session::has('success'))
        <div class="row p-20">
            <div class="alert alert-success">
                {{ Session::get('success')}}
            </div>
        </div>
    @endif

    <div class="col-xs-12 bg-white shadow">
        @if($areThereAnyGameTemplates)
            <div class="row pt-30 pb-30">
                <div class="col-xs-12">
                    <div class="col-xs-2 p-0">
                        {{  Form::select('bulk_actions', [ '' => 'Bulk Actions',
                                                           'Archive All' => 'Archive All',
                        ], null, ['class' => 'form-control','id'=>'bulk_actions']) }}
                    </div>

                    <div class="col-xs-1">
                        <button class="btn btn-light" id="apply">Apply</button>
                    </div>

                    <div class="col-xs-offset-6 col-xs-1 text-right fn-s-19 pt-5">
                        <b>{{ Form::label('search', 'Search') }}</b>
                    </div>
                    <div class="col-xs-2 pr-5">
                        <input type="text" id="search" class="form-control">
                    </div>

                </div>

                <div class="col-xs-12">
                    <table class="table table-bordered" id="templates-table">
                    </table>
                </div>
            </div>
    </div>
    </div>
        @else
        <div class="pt-10 pb-10 fn-s-19" >
            <p class="text-center p-0  bg-athens-gray">No Game Templates Yet</p>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        var DATA_TABLE_COLUMNS = [
            {
                title: 'Name',
                data: 'name',
                name: 'name',
                defaultContent: ''
            },
            {
                title: 'Source',
                data: 'source',
                name: 'source'
            },
            {
                title: 'Description',
                data: 'description',
                name: 'description'
            },
            {
                title: 'Genre',
                data: 'genre',
                name: 'genre'
            },
            {
                title: 'Instances',
                render: function(data, type, row, meta){
                    return row.instances.length;
                }
            },
            {
                title: 'Status',
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
            {
                title: '<p class="text-center">View</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    return '<button title="View game template" class="view btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.id + '">'+
                        '<i class="fa fa-eye" aria-hidden="true"></i>'+
                        '</button>';
                },

            },
            {
                title: '<p class="text-center">Edit</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    return '<button title="Edit game template" class="edit btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.id + '">' +
                                '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>' +
                            '</button>';
                },

            },
            {
                title: '<p class="text-center">Archive</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    return '<button title="Archive game template" class="archive btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.id + '">' +
                                '<i class="fa fa-archive" aria-hidden="true"></i>' +
                           '</button>';
                },
            }
        ];


        $(document).ready(function () {
            var dataTable = new DataTablesMyArcadeChef('#templates-table', {
                ajax: '{{ route('admin-portal.game-templates.data-tables') }}',
                columns: DATA_TABLE_COLUMNS,
                customSearchInputSelector: '#search'
            });


            // On change of status notify backend
            $('table').on('change', ':checkbox', function(){
                var isActive = $(this).is(':checked') ? 1 : 0;
                var id = this.dataset.id;
                var url = '{{ route('admin-portal.game-templates.update', '_ID_' ) }}';
                url = url.replace('_ID_', id);

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
                modal_title:'Delete game template',
                modal_dialog_message:'Are you sure you want to delete this game template?'
            });

            function archiveTemplate() {
                var id = this.dataset.id;
                var url = '{{ route('admin-portal.game-templates.delete', '_ID_' ) }}';
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

            // On click on archive button notify backend and remove row in table
            $('table').on('click', '.archive', confirmModal.show(archiveTemplate));

            // On click on view button go to view page
            $('table').on('click', '.view', function() {
                var id = this.dataset.id;
                var url = '{{ route('admin-portal.game-templates.view', '_ID_' ) }}';
                url = url.replace('_ID_', id);

                window.location = url;
            });
            // On click on edit button go to edit page
            $('table').on('click', '.edit', function() {
                var id = this.dataset.id;
                var url = '{{ route('admin-portal.game-templates.edit', '_ID_' ) }}';
                url = url.replace('_ID_', id);

                window.location = url;
            });

            // On click on aplly button notify backend of the bulk action
            $('#apply').on('click', function() {

                var ids = [];
                var rows = $('.select-row');
                var rowsDelete = [];
                var url = '';

                rows.each(function(){
                    if(this.checked){
                        var $row = $(this).parents('tr');
                        var id  = $row.find('button.archive')[0].dataset.id;
                        ids.push(id);
                        rowsDelete.push($(this).parents('tr'));
                    }
                });

                var selected = $('#bulk_actions :selected').text();

                switch (selected){
                    case 'Archive All':
                        url = '{{ route('admin-portal.game-templates.bulk-delete') }}';

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
        });

    </script>
@endpush