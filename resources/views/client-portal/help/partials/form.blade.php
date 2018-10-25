@section('dashboard-main-content')
    <div class="col-xs-12 bg-white shadow wraper-background">
        {{ Form::open(['class' => 'fn-s-19', 'id' => 'email-form']) }}
        <div class="row pt-30 pb-30">
            @if($saveError = $errors->first('save'))
                <div class="row">
                    <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                        <p class="text-left text-danger"><i>{{$saveError}}</i></p>
                    </div>
                </div>
            @endif

            <div class="col-xs-6">

                <div class="row mt-5">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('company_name', 'Company Name') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::text('company_name', '', [ 'class' => 'form-control' , 'id' => 'company_name']) }}
                    </div>
                </div>

                <div class="row mt-70">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('name', 'Name') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::text('name', '', [ 'class' => 'form-control' , 'id' => 'name']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('email', 'Email') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::email('email', '', [ 'class' => 'form-control' , 'id' => 'email']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('phone', 'Phone') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::text('phone', '', [ 'class' => 'form-control' , 'id' => 'phone']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('reason', 'Reason') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::select('reason', ['reason_1'=>'Reason 1','reason_2'=>'Reason 2','reason_3'=>'Reason 3'], null,[ 'class' => 'form-control' , 'placeholder'=>'Select reason...','id'=> 'reason']) }}
                    </div>
                </div>

            </div>

            <div class="col-xs-6">

                <div class="row mt-5">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('subject', 'Subject') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::text('subject', '', [ 'class' => 'form-control' , 'id' => 'subject']) }}
                    </div>
                </div>

                <div class="row mt-40">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('message', 'Message') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::textarea('message', '', [ 'class' => 'form-control' , 'id' => 'message', 'rows'=>'10']) }}
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

        });
    </script>
@endpush

