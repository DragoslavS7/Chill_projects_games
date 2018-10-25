@extends('layouts.dashboard')
@section('page-title', 'Team Members')
@section('dashboard-title', 'Team Members')


@section('dashboard-actions')
    <a href="{{ route('admin-portal.team-members.create') }}">
        <button title="New Member" class="btn btn-default pull-right">New Member</button>
    </a>
    <button id='upload-csv' title="Upload CSV" class="btn btn-default pull-right">Upload CSV</button>
    {{ Form::open(['id' => 'upload-csv-form', 'route' => 'admin-portal.team-members.bulk-store', 'enctype' => 'multipart/form-data']) }}
        <input name='csv' id='file-csv' type="file" class="hidden">
    {{ Form::close() }}
@endsection

@section('dashboard-main-content')
    @if(Session::has('success-csv-upload'))

        <div class="alert alert-success fade in">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            {{ Session::get('success-csv-upload') }}
        </div>

    @endif

    @if(Session::has('error'))

        <div class="alert alert-danger fade in  overflow-wrap">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            {{Session::get('error')}}
        </div>

    @endif

    @if(Session::has('fail-csv-upload'))

        @foreach(Session::get('fail-csv-upload') as $rowIndex => $errors)
            <div class="alert alert-danger fade in  overflow-wrap">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                In row {{$rowIndex}} you have errors {{json_encode($errors, JSON_PRETTY_PRINT)}}
            </div>
        @endforeach

    @endif

    @if(Session::has('success'))
        <div class="row p-20">
            <div class="alert alert-success">
                {{ Session::get('success')}}
            </div>
        </div>
    @endif

    <div id="message_success" class="alert alert-success fade in" style="display: none">
    </div>

    <div id="message_error" class="alert alert-danger fade in" style="display: none">
    </div>

    <div class="col-xs-12 bg-white shadow wraper-background">
        @if($areThereAnyTeamMembers)
            <div class="row pt-30">
                <div class="col-xs-2">
                    {{  Form::select('bulk_actions', [ '' => 'Bulk Actions',
                                                       'delete_all' => 'Delete All',
                    ], null, ['class' => 'form-control','id'=>'bulk_actions']) }}
                </div>

                <div class="col-xs-1">
                    <button class="btn btn-light" id="apply">Apply</button>
                </div>

                <div class="col-xs-offset-6 col-xs-1 text-right fn-s-19 pt-5">
                    <b>{{ Form::label('search', 'Search') }}</b>
                </div>
                <div class="col-xs-2">
                    <input type="text" id="search" class="form-control">
                </div>

            </div>
            <div class="pt-15 pb-30">
                <table class="table table-bordered" id="user-table">
                </table>
            </div>
        @else
            <div class="pt-10 pb-10 fn-s-19" >
                <p class="text-center p-0  bg-athens-gray">No Users Yet</p>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>

        var DATA_TABLE_COLUMNS = [
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
                title: 'Portal',
                defaultContent:'',
                render:function(data, type, row, meta){
                    if(row.client_portal){
                        return row.client_portal.company_name;
                    }
                    else{
                        return '';
                    }
                }
            },
            {
                title: 'Role',
                data: 'role',
                name:'role',
                defaultContent:''
            },
            {
                title: 'Status',
                data: 'is_active',
                name: 'is_active',
                width: '3%',
                render: function(data, type, row, meta){
                    return  '<div class="m-0-auto d-table">' +
                        '<label class="switch mt-5">' +
                        '<input data-id="' + row.id + '" type="checkbox"' + ( parseInt(row.is_active) ? 'checked' : '') + '>' +
                        '<span class="slider round"></span>' +
                        '</label>' +
                        '</div>';
                }
            },
            {
                title: '<p class="text-center">Verify</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    if(row.is_verified == 1){
                        return '<p style="text-align: center;">Verified</p>';
                    }else{
                        return '<button title="Resend verify email" class="verify btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.id + '">' +
                            '<i class="fa fa-envelope" aria-hidden="true"></i>' +
                            '</button>';
                    }
                },

            },
            {
                title: '<p class="text-center">Edit</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    return '<button title="Edit team member" class="edit btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.id + '">' +
                        '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>' +
                        '</button>';
                },

            },
            {
                title: '<p class="text-center">Delete</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    return '<button title="Delete team member" class="delete btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.id + '">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                        '</button>';
                },
            }
        ];

        $(document).ready(function () {

            var dataTable = new DataTablesMyArcadeChef('#user-table', {
                ajax: '{{ route('admin-portal.team-members.data-tables') }}',
                columns: DATA_TABLE_COLUMNS,
                customSearchInputSelector: '#search'
            });

            // On click on edit button go to edit page
            $('table').on('click', '.edit', function() {
                var id = this.dataset.id;
                var url = '{{ route('admin-portal.team-members.edit', '_ID_' ) }}';
                url = url.replace('_ID_', id);

                window.location = url;
            });

            // Check on verify
            $('table').on('click', '.verify', function() {
                var id = this.dataset.id;
                var url = '{{ route('admin-portal.team-members.resend-verify', '_ID_' ) }}';
                url = url.replace('_ID_', id);

                window.location = url;
            });

            // On change of status notify backend
            $('table').on('change', ':checkbox', function(){
                var isActive = $(this).is(':checked') ? 1 : 0;
                var id = this.dataset.id;
                var url = '{{ route('admin-portal.team-members.update', '_ID_' ) }}';
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
                modal_title:'Delete team member',
                modal_dialog_message:'Are you sure you want to delete this team member?'
            });

            function deleteTeamMember() {
                var id = this.dataset.id;
                var url = '{{ route('admin-portal.team-members.delete', '_ID_' ) }}';
                url = url.replace('_ID_', id);
                var $row = $(this).parents('tr');

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(resp) {
                        console.log(resp.success);
                        $("#message_success").removeAttr('style').html(resp.success).fadeOut(5000);

                        dataTable.dataTable
                            .row($row)
                            .remove()
                            .draw();
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON.error);
                        $("#message_error").removeAttr('style').html(xhr.responseJSON.error).fadeOut(5000);
                        dataTable.fnDraw();
                    }
                });
            }

            // On click on delete button notify backend and remove row in table
            $('table').on('click', '.delete', confirmModal.show(deleteTeamMember));

            // Upload csv
            $('#upload-csv').click(function(){
                $(this).prop('disabled', true).html('<i class="fa fa-refresh fa-spin fa-fw"></i>');
                $('#file-csv').click();
            });

            document.body.onfocus = function() {
                if ($('#file-csv').val().length == '') {
                    $('#upload-csv').prop('disabled', false).text('Upload CSV');
                }else{
                    document.querySelector('#upload-csv-form').submit();
                }
            };

            // On click on aplly button notify backend of the bulk action
            $('#apply').on('click', function() {

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
                        url = '{{ route('admin-portal.team-members.bulk-delete') }}';
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                ids:ids
                            },
                            success: function (resp) {
                                $("#message_success").removeAttr('style').html(resp.success).fadeOut(5000);
                                dataTable.dataTable.draw();
                            },
                            error: function(xhr) {
                                console.log(xhr.responseJSON.error);
                                $("#message_error").removeAttr('style').html(xhr.responseJSON.error).fadeOut(5000);
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