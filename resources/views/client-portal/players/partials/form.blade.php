@extends('layouts.auth')
@section('page-title', 'Additional data')
@section('auth-content')
    <div class="col-xs-12 bg-white">
        <div class="row">
            <div class="col-xs-12">
                @if($client->logo)
                    <img src="{{ $client->logo }}" class="h-100 mt-10 mb-10 img-responsive m-0-auto">
                @else
                    <p class="client-logo-placeholder">CLIENT LOGO</p>
                @endif
            </div>
        </div>
        {{ Form::open(['class' => 'fn-s-19', 'url' => $formUrl, 'id' => 'player-form']) }}
        <div class="row pt-30 pb-30" data-url="{{ $formUrl }}" id="form-wrapper">
            @if($saveError = $errors->first())
                <div class="row">
                    <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                        <p class="text-left text-danger"><i>{{$saveError}}</i></p>
                    </div>
                </div>
            @endif

            <div class="col-xs-12">
                <div class="row mt-10 d-none">
                    <div class="col-xs-3 text-right">
                        <b>{{ Form::label('game_slug', 'Game Slug') }}</b>
                    </div>
                    <div class="col-xs-3">
                        {{ Form::text('game_slug', $gameSlug, [ 'class' => 'form-control' , 'id' => 'game_slug']) }}
                    </div>
                    <div class="col-xs-3 text-right">
                        <b>{{ Form::label('source_path', 'Source Path') }}</b>
                    </div>
                    <div class="col-xs-3">
                        {{ Form::text('source_path', $sourcePath, [ 'class' => 'form-control' , 'id' => 'source_path']) }}
                    </div>
                </div>

                <div class="row mt-10">
                    <div class="col-xs-12 col-md-3 text-right player-form">
                        <b>{{ Form::label('first_name', 'First Name*') }}</b>
                    </div>
                    <div class="col-xs-12 col-md-9">
                        {{ Form::text('first_name', $user->first_name, [ 'class' => 'form-control' , 'id' => 'first_name']) }}
                    </div>
                </div>

                @if(in_array('last_name',$aditionalFields))
                <div class="row mt-10">
                   <div class="col-xs-12 col-md-3 text-right player-form">
                        <b>{{ Form::label('last_name', 'Last Name') }}</b>
                    </div>
                    <div class="col-xs-12 col-md-9">
                        {{ Form::text('last_name', $user->last_name, [ 'class' => 'form-control' , 'id' => 'last_name']) }}
                    </div>
                </div>
                @endif

                <div class="row mt-10">
                    <div class="col-xs-12 col-md-3 text-right player-form">
                        <b>{{ Form::label('email', 'Email*') }}</b>
                    </div>
                    <div class="col-xs-12 col-md-9">
                        {{ Form::email('email', $user->email, [ 'class' => 'form-control' , 'id' => 'email',$user->email != "" ? "readonly":""]) }}
                    </div>
                </div>

                @if(in_array('phone_number',$aditionalFields))
                    <div class="row mt-10">
                        <div class="col-xs-12 col-md-3 text-right player-form">
                            <b>{{ Form::label('phone_number', 'Phone') }}</b>
                        </div>
                        <div class="col-xs-12 col-md-9">
                            {{ Form::text('phone_number', $user->phone_number, [ 'class' => 'form-control' , 'id' => 'phone_number']) }}
                        </div>
                    </div>
                @endif
                <hr>
                @if(in_array('department',$aditionalFields))
                    <div class="row mt-5">
                        <div class="col-xs-12 col-md-3 text-right player-form">
                            <b>{{ Form::label('addTagsDepartment', 'Department Tags') }}</b>
                        </div>
                        <div class="col-xs-12 col-md-9">
                            <div id="department-added-tags"></div>
                            <div id="suggested-department-tags"></div>
                        </div>

                        {{ Form::text('department_tags' , '',  [ 'class' => 'form-control d-none' , 'id' => 'tags'] ) }}

                    </div>
                @endif
                <hr>
                @if(in_array('locations',$aditionalFields))
                    <div class="row mt-5">
                        <div class="col-xs-12 col-md-3 text-right player-form">
                            <b>{{ Form::label('addTagsLocation', 'Location Tags') }}</b>
                        </div>
                        <div class="col-xs-12 col-md-9">
                            <div id="location-added-tags"></div>
                            <div id="suggested-location-tags"></div>
                        </div>

                        {{ Form::text('location_tags' , '',  [ 'class' => 'form-control d-none' , 'id' => 'tags'] ) }}

                    </div>
                @endif
                <hr>
                @if(in_array('employee_id',$aditionalFields))
                <div class="row mt-10">
                    <div class="col-xs-12 col-md-3 text-right player-form">
                        <b>{{ Form::label('employee_id', 'Employee ID') }}</b>
                    </div>
                    <div class="col-xs-12 col-md-9">
                        {{ Form::text('employee_id', $user->employee_id, [ 'class' => 'form-control' , 'id' => 'employee_id']) }}
                    </div>
                </div>
                @endif

                @if(in_array('supervisor',$aditionalFields))
                <div class="row mt-10">
                    <div class="col-xs-12 col-md-3 text-right player-form">
                        <b>{{ Form::label('supervisor', 'Supervisor') }}</b>
                    </div>
                    <div class="col-xs-12 col-md-9">
                        {{ Form::text('supervisor', $user->employee_id, [ 'class' => 'form-control' , 'id' => 'supervisor']) }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="row mb-25 mr-10">
            <button type="submit" class="btn btn-default pull-right w-100">Play</button>
        </div>
        {{ Form::close() }}
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $("#play").click(function () {
                document.querySelector('#player-form').submit();
            });

            // Initialize location tags
            var locationTags = new Tags({
                suggestedTags: {!! json_encode($suggestedLocationTags) !!},
                $tagServerInput: $("input[name='location_tags']"),
                $addedTagsContainer: $('#location-added-tags'),
                $suggestedTagsContainer: $('#suggested-location-tags')
            });

            locationTags.addTags({!! $locationTags !!})

            // Initialize department tags
            var departmentTags = new Tags({
                suggestedTags: {!! json_encode($suggestedDepartmentTags) !!},
                $tagServerInput: $("input[name='department_tags']"),
                $addedTagsContainer: $('#department-added-tags'),
                $suggestedTagsContainer: $('#suggested-department-tags')
            });
            departmentTags.addTags({!! $departmentTags !!})
        });
    </script>
@endpush

