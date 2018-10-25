@section('dashboard-main-content')
    <div class="col-xs-12 bg-white shadow">
        <div class="row pt-30 pb-30 fn-s-19">
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
                        <b>{{ Form::label('company_name', 'Company Name:') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $companyNameError ? 'has-error': '' }}">
                        {{ Form::label('company_name', $clientPortal->company_name) }}
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
                        <b>{{ Form::label('sub_domain', 'Subdomain:') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $subDomainError ? 'has-error': '' }}">
                        {{ Form::label('sub_domain', $clientPortal->sub_domain) }}
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
                        <b>{{ Form::label('number_of_admins', 'Number of Admins:') }}</b>
                    </div>
                    <div class="col-xs-3 {{ $numberOfAdminsError ? 'has-error': '' }}">
                        {{ Form::label('number_of_admins', $clientPortal->number_of_admins) }}
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="row">
                        @if($firstNameError = $errors->first('first_name'))
                            <div class="col-xs-3 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$firstNameError}}</i></p>
                            </div>
                        @endif

                    </div>

                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('first_name', 'First Name:') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $firstNameError ? 'has-error': '' }}">
                        {{ Form::label('first_name', $defaultAdmin->first_name) }}
                    </div>

                </div>

                <div class="row mt-5">
                    <div class="row">
                        @if($lastNameError = $errors->first('last_name'))
                            <div class="col-xs-3 col-xs-offset-{{$firstNameError ? '2' : '9'}} fn-s-13">
                                <p class="text-left text-danger"><i>{{$lastNameError}}</i></p>
                            </div>
                        @endif
                    </div>

                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('last_name', 'Last Name:') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $lastNameError ? 'has-error': '' }}">
                        {{ Form::label('last_name', $defaultAdmin->last_name) }}
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
                        <b>{{ Form::label('email', 'Email:') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $emailError ? 'has-error': '' }}">
                        {{ Form::label('email', $defaultAdmin->email) }}
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
                        {{ Form::label('address', $clientPortal->address) }}
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
                        {{ Form::label('phone', $defaultAdmin->phone) }}
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
                        {{ Form::label('fax', $clientPortal->fax) }}
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
                        {{ Form::label('website', $clientPortal->website) }}
                    </div>
                </div>

                <div class="row mt-5">
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
            </div>
        </div>
        @if($areThereAnyGameTemplates)
            <div class="row pt-30">
                <div class="col-xs-2 fn-s-19">
                    <b>{{ Form::label('assign_templates', 'Assigned Templates') }}</b>
                </div>

                <div class="col-xs-offset-7 col-xs-1 text-right fn-s-19 pt-5">
                    <b>{{ Form::label('search', 'Search') }}</b>
                </div>
                <div class="col-xs-2">
                    <input type="text" id="search" class="form-control">
                </div>
            </div>

            <div class="row pt-15 pb-30">
                <div class="col-xs-12">
                    <table class="table table-bordered w-100p" id="game-templates-table"></table>
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
                    return '<button title="View game template" class="view btn p-0 bg-transparent m-0-auto d-block" data-id="' + row.game_template_id + '">'+
                        '<i class="fa fa-eye" aria-hidden="true"></i>'+
                        '</button>';
                },

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
            var id ='{{ $clientPortal->id }}';
            var dataTableUrl = '{{ route('admin-portal.client-portals.data-tables-templates', '_ID_') }}';
            dataTableUrl = dataTableUrl.replace('_ID_', id);

            var dataTable = new DataTablesMyArcadeChef('#game-templates-table', {
                ajax: dataTableUrl,
                columns: DATA_TABLE_COLUMNS,
                addSelectColumn: false,
                customSearchInputSelector: '#search'
            });


            // On click on view button go to view page
            $('#game-templates-table').on('click', '.view', function() {
                var id = this.dataset.id;
                var url = '{{ route('admin-portal.game-templates.view', '_ID_') }}';
                url = url.replace('_ID_', id);

                window.location = url;
            });

            $(window).resize(function(){
                $('#game-templates-table').css('width','100%');
            });
        });

    </script>
@endpush
