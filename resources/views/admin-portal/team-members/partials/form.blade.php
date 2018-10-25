@section('dashboard-main-content')
    <div class="col-xs-12 bg-white shadow wraper-background">
        {{ Form::open(['class' => 'fn-s-19', 'url' => $formUrl, 'id' => 'team-members-form']) }}
        <div class="row pt-30 pb-30">
            @if($saveError = $errors->first('save'))
                <div class="row">
                    <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                        <p class="text-left text-danger"><i>{{$saveError}}</i></p>
                    </div>
                </div>
            @endif

            <div class="col-xs-8">
                <div class="row">
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

                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('first_name', 'First Name*') }}</b>
                    </div>
                    <div class="col-xs-3 {{ $firstNameError ? 'has-error': '' }}">
                        {{ Form::text('first_name', $teamMember->first_name, [ 'class' => 'form-control' , 'id' => 'first_name']) }}
                    </div>

                    <div class="col-xs-2 text-right p-0">
                        <b>{{ Form::label('last_name', 'Last Name*') }}</b>
                    </div>
                    <div class="col-xs-3 {{ $lastNameError ? 'has-error': '' }}">
                        {{ Form::text('last_name', $teamMember->last_name, [ 'class' => 'form-control' , 'id' => 'last_name']) }}
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
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('email', 'Email*') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $emailError ? 'has-error': '' }}">
                        {{ Form::email('email', $teamMember->email, [ 'class' => 'form-control' , 'id' => 'email']) }}
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
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('password', 'Password*') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $passwordError ? 'has-error': '' }}">
                        {{ Form::password('password',  [ 'class' => 'form-control' , 'id' => 'password', 'placeholder' => $teamMember->password ? '*******' : '']) }}
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
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('password_confirmation', 'Confirm Password*') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $passwordConfirmationError ? 'has-error': '' }}">
                        {{ Form::password('password_confirmation',  [ 'class' => 'form-control' , 'id' => 'password_confirmation', 'placeholder' => $teamMember->password ? '*******' : '' ]) }}
                    </div>
                </div>

                <div class="row mt-5">
                    @if($roleError = $errors->first('role'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$roleError}}</i></p>
                            </div>
                        </div>
                    @endif
                    @if($teamMember->role != 'uber_admin')
                        <div class="col-xs-4 text-right mt-10">
                            <b>{{ Form::label('role', 'Role*') }}</b>
                        </div>

                        <div class="col-xs-8 mt-10 {{ $roleError ? 'has-error': '' }}">
                            {{ Form::select('role', ['uber_admin'=>'Uber Admin','admin'=>'Client Portal Admin'], $teamMember->role,[ 'class' => 'form-control' , 'placeholder'=>'Select Role ...','id'=> 'role']) }}
                        </div>
                    @endif
                </div>

                <div class="row mt-5">
                    @if($clientPortalError = $errors->first('client_portal_id'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$clientPortalError}}</i></p>
                            </div>
                        </div>
                    @endif
                    @if($teamMember->role != 'uber_admin')
                        <div class="col-xs-4 text-right" id="client_portal_label">
                            {{ Form::label('client_portal_id', 'Select Client Portal') }}
                        </div>
                        <div class="col-xs-8 {{ $clientPortalError ? 'has-error': '' }}">
                            {{ Form::select('client_portal_id', $clientPortals, $teamMember->client_portal_id, [ 'class' => 'form-control' , 'id' => 'client_portal_id']) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){

            $("#save, #update").click(function () {
                $('input[name^="boolean_answer_"]').prop('disabled', false);
                document.querySelector('#team-members-form').submit();
            });

            $('#role').change(function(){
                if(this.value === 'uber_admin'){
                    $('#client_portal_id').hide();
                    $('#client_portal_label').hide();
                }else{
                    $('#client_portal_id').show();
                    $('#client_portal_label').show();
                }
            });
        });
    </script>
@endpush

