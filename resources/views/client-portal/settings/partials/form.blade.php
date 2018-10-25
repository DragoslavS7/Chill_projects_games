@section('dashboard-main-content')
    <div class="col-xs-12 bg-white shadow wraper-background">
        {{ Form::open(['class' => 'fn-s-19', 'url' => $formUrl, 'id' => 'settings-form', 'enctype' => 'multipart/form-data']) }}
        <div class="row pt-30 pb-30">
            @if($saveError = $errors->first('save'))
                <div class="row">
                    <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                        <p class="text-left text-danger"><i>{{$saveError}}</i></p>
                    </div>
                </div>
            @endif

            <div class="col-xs-7">
                <div class="row mt-5 mb-25">
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('company_name', 'Company Name') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::text('company_name', $client->company_name, [ 'class' => 'form-control' , 'id' => 'company_name']) }}
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
                        <b>{{ Form::label('first_name', 'First Name') }}</b>
                    </div>
                    <div class="col-xs-3 text-right {{ $firstNameError ? 'has-error': '' }}">
                        {{ Form::text('first_name', $deafultAdmin->first_name, [ 'class' => 'form-control' , 'id' => 'first_name']) }}
                    </div>

                    <div class="col-xs-2 text-right p-0">
                        <b>{{ Form::label('last_name', 'Last Name') }}</b>
                    </div>
                    <div class="col-xs-3 {{ $lastNameError ? 'has-error': '' }}">
                        {{ Form::text('last_name', $deafultAdmin->last_name, [ 'class' => 'form-control' , 'id' => 'last_name']) }}
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
                        <b>{{ Form::label('email', 'Email') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $emailError ? 'has-error': '' }}">
                        {{ Form::email('email', $deafultAdmin->email, [ 'class' => 'form-control' , 'id' => 'email']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('address', 'Address') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::text('address', $client->address, [ 'class' => 'form-control' , 'id' => 'address']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('phone', 'Phone') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::text('phone', $deafultAdmin->phone, [ 'class' => 'form-control' , 'id' => 'phone']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('fax', 'Fax') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::text('fax', $client->fax, [ 'class' => 'form-control' , 'id' => 'fax']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('website', 'Website') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::text('website', $client->website, [ 'class' => 'form-control' , 'id' => 'website']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    @if($locationTagsError = $errors->first('location_tags'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$locationTagsError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-3 text-right w-20p">
                        <b>{{ Form::label('addTagsLocation', 'Location Tags') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $locationTagsError ? 'has-error': '' }}">
                        {{ Form::text('addTagsLocation', '', ['class' => 'form-control']) }}
                    </div>

                    <div class="col-xs-3 w-20p"></div>
                    <div id="location-added-tags" class="col-xs-8">

                    </div>
                    {{ Form::text('location_tags' , '',  [ 'class' => 'form-control d-none' , 'id' => 'tags'] ) }}

                </div>


                <div class="row mt-5">
                    @if($departmentTagsError = $errors->first('department_tags'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$departmentTagsError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-3 text-right w-20p fn-s-17 pt-5">
                        <b>{{ Form::label('addTagsDepartment', 'Department Tags') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $departmentTagsError ? 'has-error': '' }}">
                        {{ Form::text('addTagsDepartment', '', ['class' => 'form-control']) }}
                    </div>

                    <div class="col-xs-3 w-20p"></div>
                    <div id="department-added-tags" class="col-xs-8">

                    </div>
                    {{ Form::text('department_tags' , '',  [ 'class' => 'form-control d-none' , 'id' => 'tags'] ) }}

                </div>

                <div class="row mt-10">
                    <div class="col-xs-3 text-right w-20p">
                        <b>{{Form::label('show_index','Show index')}}</b>
                    </div>
                    <div class="col-xs-2">
                        <label class="">
                            {{Form::checkbox('show_index', 0 , $client->show_index, ['class'=>'mr-15 d-none','id'=>'show_index'])}}
                            <i class="fn-s-25 fa fa-fw fa-square-o w-20"></i>
                        </label>
                    </div>
                </div>
                @if(!$user->isUberAdmin())
                    <div class="row mt-10">
                        <div class="col-xs-3 text-right w-20p">
                            <b>{{Form::label('is_costumer_service_available','Costumer service available')}}</b>
                        </div>
                        <div class="col-xs-2">
                            <label class="">
                                {{Form::checkbox('is_costumer_service_available', 0 , $client->is_costumer_service_available, ['class'=>'mr-15 d-none','id'=>'is_costumer_service_available'])}}
                                <i class="fn-s-25 fa fa-fw fa-square-o w-20"></i>
                            </label>
                        </div>
                    </div>
                @endif

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
                    <img id='logo-preview' class="img-responsive m-0-auto" src="{{$client->logo}}" alt="">
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
                Upload Image {{ Form::file('logo', [ 'class' => 'form-control' , 'id' => 'logo']) }}
            </span>
                </div>
            </div>

            <div class="col-xs-12 mt-70">
                <div class="row">
                    <div class="col-xs-2 text-right w-11p">
                        <b class="d-block">{{ Form::label('change_colors', 'Change colors') }}</b>
                        <button id="default-colors" type='button' class="btn btn-light bg-transparent mt-17">Default colors</button>
                    </div>
                    <div class="col-xs-10">
                        <div class="row">
                            <div class="col-xs-2 w-14p">
                                <label for="" class="d-block text-center">
                                    <b>Primary</b>
                                </label>
                            </div>
                            <div class="col-xs-2 w-14p">
                                <label for="" class="d-block text-center">
                                    <b>Secondary</b>
                                </label>
                            </div>
                            <div class="col-xs-2 w-14p">
                                <label for="" class="d-block text-center">
                                    <b>Menu fonts</b>
                                </label>
                            </div>
                            <div class="col-xs-2 w-14p">
                                <label for="" class="d-block text-center">
                                    <b>Menu fonts 2</b>
                                </label>
                            </div>
                            <div class="col-xs-2 w-14p">
                                <label for="" class="d-block text-center">
                                    <b>Buttons</b>
                                </label>
                            </div>
                            <div class="col-xs-2 w-14p">
                                <label for="" class="d-block text-center">
                                    <b>Buttons font</b>
                                </label>
                            </div>
                            <div class="col-xs-2 w-14p">
                                <label for="" class="d-block text-center">
                                    <b>Buttons rollover</b>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-2 w-14p">
                                {{ Form::input('color', 'menu_background', $styles != null ? $styles['menu_background']:'#202A3C', ['class' => 'w-75 h-75 d-block m-0-auto p-0 m-0 bg-transparent b-none', 'id'=>'menu_background'] )}}
                            </div>
                            <div class="col-xs-2 w-14p">
                                {{ Form::input('color', 'wraper_background', $styles != null ? $styles['wraper_background']:'#ffffff', ['class' => 'w-75 h-75 d-block m-0-auto p-0 m-0 bg-transparent b-none', 'id'=>'wraper_background'] )}}
                            </div>
                            <div class="col-xs-2 w-14p">
                                {{ Form::input('color', 'menu_top_level_font', $styles != null ? $styles['menu_top_level_font']:'#FFFFFF', ['class' => 'w-75 h-75 d-block m-0-auto p-0 m-0 bg-transparent b-none', 'id'=>'menu_top_level_font'] )}}
                            </div>
                            <div class="col-xs-2 w-14p">
                                {{ Form::input('color', 'menu_low_level_font', $styles != null ? $styles['menu_low_level_font']:'#808080', ['class' => 'w-75 h-75 d-block m-0-auto p-0 m-0 bg-transparent b-none', 'id'=>'menu_low_level_font'] )}}
                            </div>
                            <div class="col-xs-2 w-14p">
                                {{ Form::input('color', 'colored_button_background', $styles != null ? $styles['colored_button_background']:'#2CA189', ['class' => 'w-75 h-75 d-block m-0-auto p-0 m-0 bg-transparent b-none', 'id'=>'color_button_background'] )}}
                            </div>
                            <div class="col-xs-2 w-14p">
                                {{ Form::input('color', 'colored_button_font', $styles != null ? $styles['colored_button_font']:'#FFFFFF', ['class' => 'w-75 h-75 d-block m-0-auto p-0 m-0 bg-transparent b-none', 'id'=>'colored_button_font'] )}}
                            </div>
                            <div class="col-xs-2 w-14p">
                                {{ Form::input('color', 'colored_button_rollover', $styles != null && isset($styles['colored_button_rollover']) ? $styles['colored_button_rollover']:'#2EB29C', ['class' => 'w-75 h-75 d-block m-0-auto p-0 m-0 bg-transparent b-none', 'id'=>'colored_button_rollover'] )}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $("#save").click(function () {
                $('input[name^="boolean_answer_"]').prop('disabled', false);
                document.querySelector('#settings-form').submit();
            });

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

            $('#default-colors').click(function(){
                document.querySelector('input[name="menu_background"]').value = '#202A3C';
                document.querySelector('input[name="wraper_background"]').value = '#ffffff';
                document.querySelector('input[name="menu_top_level_font"]').value = '#ffffff';
                document.querySelector('input[name="menu_low_level_font"]').value = '#808080';
                document.querySelector('input[name="colored_button_background"]').value = '#2CA189';
                document.querySelector('input[name="colored_button_font"]').value = '#ffffff';
                document.querySelector('input[name="colored_button_rollover"]').value = '#2EB29C';
            });


            // Initialize location tags
            var locationTags = new Tags({
                $tagInput: $("input[name='addTagsLocation']"),
                $tagServerInput: $("input[name='location_tags']"),
                $addedTagsContainer: $('#location-added-tags')
            });

            locationTags.addTags({!! json_encode($locationTags) !!})


            // Initialize department tags
            var departmentTags = new Tags({
                $tagInput: $("input[name='addTagsDepartment']"),
                $tagServerInput: $("input[name='department_tags']"),
                $addedTagsContainer: $('#department-added-tags')
            });

            departmentTags.addTags({!! json_encode($departmentTags) !!})


        });
    </script>
@endpush

