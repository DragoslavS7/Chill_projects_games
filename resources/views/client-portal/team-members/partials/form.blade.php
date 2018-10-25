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
                    <div class="col-xs-4 text-right password">
                        <b>{{ Form::label('password', 'Password*') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $passwordError ? 'has-error': '' }} password">
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
                    <div class="col-xs-4 text-right password_confirmation">
                        <b>{{ Form::label('password_confirmation', 'Confirm Password*') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $passwordConfirmationError ? 'has-error': '' }} password_confirmation">
                        {{ Form::password('password_confirmation',  [ 'class' => 'form-control' , 'id' => 'password_confirmation', 'placeholder' => $teamMember->password ? '*******' : '' ]) }}
                    </div>
                </div>

                @if($teamMember->role != 'uber_admin')
                    @if($roleError = $errors->first('role'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$roleError}}</i></p>
                            </div>
                        </div>
                    @endif
                    <div class="row mt-5">
                        <div class="col-xs-4 text-right mt-10 ">
                            <b>{{ Form::label('role', 'Role*') }}</b>
                        </div>

                        <div class="col-xs-8 mt-10  {{ $roleError ? 'has-error': '' }}">
                            {{ Form::select('role', ['viewer'=>'Viewer','player'=>'Player'], $teamMember->role,[ 'class' => 'form-control' , 'placeholder'=>'Select Role ...','id'=> 'role']) }}
                        </div>
                    </div>
                @endif

                <div class="row mt-5">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('phone', 'Phone') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::text('phone', $teamMember->phone, [ 'class' => 'form-control' , 'id' => 'phone']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('department', 'Department') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::text('department', $teamMember->department, [ 'class' => 'form-control' , 'id' => 'department']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('location', 'Location') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::text('location', $teamMember->location, [ 'class' => 'form-control' , 'id' => 'location']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('employee_id', 'Employee ID') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::text('employee_id', $teamMember->employee_id, [ 'class' => 'form-control' , 'id' => 'employee_id']) }}
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection

@push('scripts')
    <script>

        function setFields(role){
            switch(role){
                case 'viewer':
                    $('.password').show();
                    $('.password_confirmation').show();
                    break;
                case 'player':
                    $('.password').hide();
                    $('#password').val('');
                    $('.password_confirmation').hide();
                    $('#password_confirmation').val('');
                    break;
            }
        }

        $(document).ready(function(){

            $("#save, #update").click(function () {
                $('input[name^="boolean_answer_"]').prop('disabled', false);
                document.querySelector('#team-members-form').submit();
            });

            $role = $('#role').val();

            setFields($role);

            $('#role').change(function(){
                setFields(this.value);
            });
        });
    </script>
@endpush

