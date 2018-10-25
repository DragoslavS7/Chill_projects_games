@section('dashboard-main-content')
    <div class="col-xs-12 bg-white shadow wraper-background">
        <div class="row pt-30 pb-30">


            <div class="col-xs-8">
                <div class="row">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('first_name', 'First Name') }} :</b>
                    </div>
                    <div class="col-xs-3">
                        {{ Form::label('first_name', $teamMember->first_name) }}
                    </div>

                    <div class="col-xs-2 text-right p-0">
                        <b>{{ Form::label('last_name', 'Last Name') }} :</b>
                    </div>
                    <div class="col-xs-3">
                        <b>{{ Form::label('last_name', $teamMember->last_name) }}</b>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('email', 'Email') }} :</b>
                    </div>
                    <div class="col-xs-8">
                        <b>{{ Form::label('email', $teamMember->email) }}</b>
                    </div>
                </div>

                @if($teamMember->role != 'uber_admin')
                    <div class="row mt-5">
                        <div class="col-xs-4 text-right mt-10 ">
                            <b>{{ Form::label('role', 'Role') }} :</b>
                        </div>

                        <div class="col-xs-8 mt-10">
                            <b>{{ Form::label('role', $teamMember->role) }} </b>
                        </div>
                    </div>
                @endif

                <div class="row mt-5">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('phone', 'Phone') }} :</b>
                    </div>
                    <div class="col-xs-8">
                        <b>{{ Form::label('phone', $teamMember->phone) }}</b>
                </div>

                <div class="row mt-5">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('department', 'Department') }} :</b>
                    </div>
                    <div class="col-xs-8">
                        <b>{{ Form::label('department', $teamMember->department) }}</b>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('location', 'location') }} :</b>
                    </div>
                    <div class="col-xs-8">
                        <b>{{ Form::label('location', $teamMember->location) }}</b>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('employee_id', 'Employee ID') }} :</b>
                    </div>
                    <div class="col-xs-8">
                        <b>{{ Form::label('employee_id', $teamMember->employee_id) }}</b>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection

