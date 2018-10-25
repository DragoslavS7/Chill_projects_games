@section('dashboard-main-content')
    <div class="col-xs-12 bg-white shadow">
        <div class="row pt-30 pb-30">
            {{ Form::open(['class' => 'fn-s-19', 'url' => $formUrl, 'id' => 'client-portal-form', 'enctype' => 'multipart/form-data' ]) }}
            <div class="col-xs-7">
                <div class="row">
                    @if($companyNameError = $errors->first('company_name'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$companyNameError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('company_name', 'Company Name*') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $companyNameError ? 'has-error': '' }}">
                        {{ Form::text('company_name', $clientPortal->company_name, [ 'class' => 'form-control' , 'id' => 'company_name']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    @if($subDomainError = $errors->first('sub_domain'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$subDomainError}}</i></p>
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('sub_domain', 'Subdomain*') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $subDomainError ? 'has-error': '' }}">
                        {{ Form::text('sub_domain', $clientPortal->sub_domain, [ 'class' => 'form-control' , 'id' => 'sub_domain']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    @if($numberOfAdminsError = $errors->first('number_of_admins'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$numberOfAdminsError}}</i></p>
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('number_of_admins', 'Number of Admins*') }}</b>
                    </div>
                    <div class="col-xs-3 {{ $numberOfAdminsError ? 'has-error': '' }}">
                        {{ Form::text('number_of_admins', $clientPortal->number_of_admins, [ 'class' => 'form-control' , 'id' => 'number_of_admins']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="row">
                        @if($firstNameError = $errors->first('first_name'))
                            <div class="col-xs-3 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$firstNameError}}</i></p>
                            </div>
                        @endif

                        @if($lastNameError = $errors->first('last_name'))
                            <div class="col-xs-3 col-xs-offset-{{$firstNameError ? '2' : '9'}} fn-s-13">
                                <p class="text-left text-danger"><i>{{$lastNameError}}</i></p>
                            </div>
                        @endif
                    </div>

                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('first_name', 'First Name*') }}</b>
                    </div>
                    <div class="col-xs-3 {{ $firstNameError ? 'has-error': '' }}">
                        {{ Form::text('first_name', $defaultAdmin->first_name, [ 'class' => 'form-control' , 'id' => 'first_name']) }}
                    </div>

                    <div class="col-xs-2 text-right p-0">
                        <b>{{ Form::label('last_name', 'Last Name*') }}</b>
                    </div>
                    <div class="col-xs-3 {{ $lastNameError ? 'has-error': '' }}">
                        {{ Form::text('last_name', $defaultAdmin->last_name, [ 'class' => 'form-control' , 'id' => 'last_name']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    @if($emailError = $errors->first('email'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$emailError}}</i></p>
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('email', 'Email*') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $emailError ? 'has-error': '' }}">
                        {{ Form::email('email', $defaultAdmin->email, [ 'class' => 'form-control' , 'id' => 'email']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    @if($addressError = $errors->first('address'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$addressError}}</i></p>
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('address', 'Addresss') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $addressError ? 'has-error': '' }}">
                        {{ Form::text('address', $clientPortal->address, [ 'class' => 'form-control' , 'id' => 'address']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    @if($phoneError = $errors->first('phone'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$phoneError}}</i></p>
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('phone', 'Phone') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $phoneError ? 'has-error': '' }}">
                        {{ Form::text('phone', $defaultAdmin->phone, [ 'class' => 'form-control' , 'id' => 'phone']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    @if($faxError = $errors->first('fax'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$faxError}}</i></p>
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('fax', 'Fax') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $faxError ? 'has-error': '' }}">
                        {{ Form::text('fax', $clientPortal->fax, [ 'class' => 'form-control' , 'id' => 'fax']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    @if($websiteError = $errors->first('website'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$websiteError}}</i></p>
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('website', 'Website') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $websiteError ? 'has-error': '' }}">
                        {{ Form::text('website', $clientPortal->website, [ 'class' => 'form-control' , 'id' => 'website']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    @if($passwordError = $errors->first('password'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$passwordError}}</i></p>
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('password', 'Password*') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $passwordError ? 'has-error': '' }}">
                        {{ Form::password('password',  [ 'class' => 'form-control' , 'id' => 'password']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    @if($passwordConfirmationError = $errors->first('password_confirmation'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$passwordConfirmationError}}</i></p>
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('password_confirmation', 'Confirm Password*') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $passwordConfirmationError ? 'has-error': '' }}">
                        {{ Form::password('password_confirmation',  [ 'class' => 'form-control' , 'id' => 'password_confirmation']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    @if($assignTemplatesError = $errors->first('assign_templates'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$assignTemplatesError}}</i></p>
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('assign_templates', 'Assign Templates*') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::text('assign_templates', $assignedTemplates, [ 'class' => 'form-control d-none' , 'id' => 'assign_templates']) }}
                    </div>
                    @if(!$areThereAnyGameTemplates)
                        <div class="col-xs-8 fn-s-15 mt-5">
                            <p>
                                <b>NO CURRENT TEMPLATES.
                                    <u>
                                        <a href="{{route('admin-portal.game-templates.create')}}" class="fn-c-mine-shaft">
                                            ADD NEW?
                                        </a>
                                    </u>
                                </b>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-xs-4">
                <p class="text-center">
                    <b>
                        Client Logo
                    </b>
                    <br />
                    <small class="fn-s-13">(300x300)</small>
                </p>
                <div class="col-xs-12 mt-10">
                    <img id='logo-preview' class="img-responsive m-0-auto" src="{{$clientPortal->logo}}" alt="">
                </div>
                @if($logoError = $errors->first('logo'))
                    <div class="row">
                        <div class="col-xs-12 fn-s-13">
                            <p class="text-center text-danger"><i>{{$logoError}}</i></p>
                        </div>
                    </div>
                @endif
                <div class="col-xs-12 mt-10 {{ $logoError ? 'has-error' : '' }}">
                    <span class="btn btn-light btn-file m-0-auto d-table">
                        Upload Image {{ Form::file('logo', [ 'class' => 'form-control ' , 'id' => 'logo']) }}
                    </span>
                </div>
            </div>
            {{ Form::close() }}
        </div>
        @if($areThereAnyGameTemplates)
            <div class="row pt-30">
                <div class="col-xs-2">
                    {{  Form::select('bulk_actions', [ '' => 'Bulk Actions',
                                                       'assign_all' => 'Assign All',
                                                       'unassign_all' => 'Unassign All',
                    ], null, ['class' => 'form-control','id'=>'bulk_actions']) }}
                </div>

                <div class="col-xs-1">
                    <button class="btn btn-light" id="apply" type="button">Apply</button>
                </div>

                <div class="col-xs-offset-6 col-xs-1 text-right fn-s-19 pt-5">
                    <b>{{ Form::label('search', 'Search') }}</b>
                </div>
                <div class="col-xs-2">
                    <input type="text" id="search" class="form-control">
                </div>
            </div>

            <div class="row pt-15 pb-30">
                <div class="col-xs-12">
                    <table class="table table-bordered" id="game-templates-table"></table>
                </div>
            </div>
        @endif
    </div>
@endsection
@push('scripts')
    <script>
        var DATA_TABLE_COLUMNS = [
            {
                title: 'Template name',
                data: 'name',
                name: 'name',
                defaultContent: ''
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
                title: '<p class="text-center">Assign</p>',
                orderable: false,
                searchable: false,
                width: '3%',
                render: function (data, type, row, meta) {
                    var isAssigned = false;
                    var value = document.querySelector('#assign_templates').value || '[]';

                    var assignedTempaltesIds = JSON.parse(value);

                    if(assignedTempaltesIds && assignedTempaltesIds.indexOf(row.id) > -1){
                        isAssigned = true;
                    }

                    return '<div class="m-0-auto d-table">' +
                        '<label class="switch mt-5">' +
                        '<input data-id="' + row.id + '" type="checkbox" '+ (isAssigned ? 'checked' : '' ) +'>' +
                        '<span class="slider round"></span>' +
                        '</label>' +
                        '</div>';
                }
            }
        ];

        // Show preview when file is selected
        $(document).on('change', ':file', function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();

                var previewImageSelector = "#" + this.name + "-preview";

                reader.onload = function (e) {
                    document.querySelector(previewImageSelector).src = e.target.result;
                };

                reader.readAsDataURL(this.files[0]);
            }
        });

        $(document).ready(function () {
            var dataTable = new DataTablesMyArcadeChef('#game-templates-table', {
                ajax: '{{ route('admin-portal.game-templates.data-tables') }}',
                columns: DATA_TABLE_COLUMNS,
                customSearchInputSelector: '#search'
            });

            // On click on view button go to view page
            $('table').on('click', '.view', function() {
                var id = this.dataset.id;
                var url = '{{ route('admin-portal.game-templates.view', '_ID_') }}';
                url = url.replace('_ID_', id);

                window.location = url;
            });

            // Handle assign
            $('#game-templates-table').on('change', '.switch :checkbox', function(){
                var isAssigned = $(this).is(":checked");
                var id = parseInt(this.dataset.id);

                var $assignedTemplates = $('#assign_templates');
                var value = $assignedTemplates.val() || '[]';
                value = JSON.parse(value);

                if(isAssigned){
                    if(value.indexOf(id) == -1){
                        value.push(id);
                    }
                }else{
                    var index = value.indexOf(id);
                    if(index > -1){
                        value.splice(index, 1);
                    }
                }

                $assignedTemplates.val(JSON.stringify(value));
            })

            $("#save, #update").click(function(){
                document.querySelector('#client-portal-form').submit();
            });

            $(window).resize(function(){
                dataTable.dataTable.columns.adjust();
            });

            // On click on aplly button preform the bulk action
            $('#apply').on('click', function() {

                var $rows = $('.select-row');
                var $rowsAssigned = [];

                var $assignedTemplates = $('#assign_templates');
                var value = $assignedTemplates.val() || '[]';
                value = JSON.parse(value);

                $rows.each(function(){
                    if(this.checked){
                        $rowsAssigned.push($(this).parents('tr').find('.switch :checkbox'));
                    }
                });

                var selected = $('#bulk_actions :selected').text();

                switch (selected){
                    case 'Assign All':
                        $rowsAssigned.forEach(function($row){
                            var id = parseInt($row.attr('data-id'));

                            if(value.indexOf(id) == -1){
                                value.push(id);
                                $row.prop('checked',true);
                            }
                        });
                        $assignedTemplates.val(JSON.stringify(value));
                        break;
                    case 'Unassign All':
                        $rowsAssigned.forEach(function($row){
                            var id = parseInt($row.attr('data-id'));

                            var index = value.indexOf(id);
                            if(index > -1){
                                value.splice(index, 1);
                                $row.prop('checked',false);
                            }
                        });
                        $assignedTemplates.val(JSON.stringify(value));
                        break;
                }
            });
        });

    </script>
@endpush
