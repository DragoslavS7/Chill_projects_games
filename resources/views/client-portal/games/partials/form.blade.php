@section('dashboard-main-content')

    <div class="col-xs-12 bg-white shadow wraper-background">
        {{ Form::open(['class' => 'fn-s-19', 'url' => $formUrl, 'id' => 'game-form', 'enctype' => 'multipart/form-data']) }}
        <div class="row pt-30 pb-30" data-url="{{ $formUrl }}" id="form-wrapper">
            @if($saveError = $errors->first('save'))
                <div class="row">
                    <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                        <p class="text-left text-danger"><i>{{$saveError}}</i></p>
                    </div>
                </div>
            @endif

            <div class="col-xs-8">
                <div class="row mb-8 d-none">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('game_template_id', 'Game Template ID') }}</b>
                    </div>
                    <div class="col-xs-8">
                        {{ Form::text('game_template_id', $game->game_template_id, [ 'class' => 'form-control' , 'id' => 'game_template_id']) }}
                    </div>
                </div>
                <div class="row">
                    @if($nameError = $errors->first('name'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$nameError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('name', 'Game Title*') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $nameError ? 'has-error': '' }}">
                        {{ Form::text('name', $game->name, [ 'class' => 'form-control' , 'id' => 'name', 'pattern'=>'[a-zA-Z]{1,}']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    @if($descriptionError = $errors->first('description'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$descriptionError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('description', 'Description') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $descriptionError ? 'has-error': '' }}">
                        {{ Form::text('description', $game->description, [ 'class' => 'form-control' , 'id' => 'description']) }}
                    </div>
                </div>

                <div class="row mt-5">
                    @if($urlError = $errors->first('url'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$urlError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('url', 'URL') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $urlError ? 'has-error': '' }}">
                        <div class="input-group">
                            <span class="input-group-addon">{{request()->getSchemeAndHttpHost()}}</span>

                            {{ Form::text('url', $game->url, [ 'class' => 'form-control h-43' , 'id' => 'url', 'readonly']) }}

                            <span class="input-group-addon bg-white">
                                <button type='button' class="edit btn p-0 bg-transparent d-block" id="edit-url">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row mt-10">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('player_login', 'Player Login') }}</b>
                    </div>

                    <label class="mr-2 fn-s-16">Collect Additional Player Data
                        {{ Form::radio('player_login', 'required_data', count($game->required_additional_player_data), [ 'class' => 'mr-15 d-none' , 'id' => 'required_additional_player_data']) }}
                        <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                    </label>

                    <label class="mr-15 fn-s-16">Allow Anonymous Players
                        {{ Form::radio('player_login', 'allow_anonymous_players', $game->allow_anonymous_players, [ 'class' => 'mr-15 d-none' , 'id' => 'allow_anonymous_players']) }}
                        <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                    </label>
                </div>

                <div class="row mt-10 additional-data">
                    <div class="col-xs-offset-5 col-xs-4">
                        <label class="mr-15 fn-s-16 w-100p">Last Name
                            {{ Form::checkbox('required_additional_player_data[]', 'last_name', in_array('last_name', $game->required_additional_player_data), [ 'class' => 'mr-15 d-none']) }}
                            <i class="fn-s-25 fa fa-fw fa-square-o w-20"></i>
                        </label>

                        <label class="mr-15 fn-s-16 w-100p">Phone Number
                            {{ Form::checkbox('required_additional_player_data[]', 'phone_number', in_array('phone_number', $game->required_additional_player_data), [ 'class' => 'mr-15 d-none']) }}
                            <i class="fn-s-25 fa fa-fw fa-square-o w-20"></i>
                        </label>

                        <label class="mr-15 fn-s-16 w-100p">Department
                            {{ Form::checkbox('required_additional_player_data[]', 'department', in_array('department', $game->required_additional_player_data), [ 'class' => 'mr-15 d-none']) }}
                            <i class="fn-s-25 fa fa-fw fa-square-o w-20"></i>
                        </label>

                        <label class="mr-15 fn-s-16 w-100p">Locations
                            {{ Form::checkbox('required_additional_player_data[]', 'locations', in_array('locations', $game->required_additional_player_data), [ 'class' => 'mr-15 d-none']) }}
                            <i class="fn-s-25 fa fa-fw fa-square-o w-20"></i>
                        </label>

                        <label class="mr-15 fn-s-16 w-100p">Employee ID
                            {{ Form::checkbox('required_additional_player_data[]', 'employee_id', in_array('employee_id', $game->required_additional_player_data), [ 'class' => 'mr-15 d-none']) }}
                            <i class="fn-s-25 fa fa-fw fa-square-o w-20"></i>
                        </label>

                        <label class="mr-15 fn-s-16 w-100p">Supervisor
                            {{ Form::checkbox('required_additional_player_data[]', 'supervisor', in_array('supervisor', $game->required_additional_player_data), [ 'class' => 'mr-15 d-none']) }}
                            <i class="fn-s-25 fa fa-fw fa-square-o w-20"></i>
                        </label>
                    </div>

                </div>

                <div class="row mt-10">
                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('score_type', 'Score type') }}</b>
                    </div>

                    <div class="col-xs-4">
                        <label class="mr-15 fn-s-16">Points
                            {{ Form::radio('score_type', 'points', 'points' == $game->score_type, [ 'class' => 'mr-15 d-none' , 'id' => 'points']) }}
                            <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                        </label>

                        <label class="mr-15 fn-s-16">Percentage
                            {{ Form::radio('score_type', 'percentage', 'percentage' == $game->score_type, [ 'class' => 'mr-15 d-none' , 'id' => 'percentage']) }}
                            <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                        </label>
                    </div>

                    <div class="col-xs-4 fn-s-16">
                        <b>{{ Form::label('score_to_win', 'Score To Win') }}</b>

                        <label class="mr-15 w-45">
                            {{ Form::text('score_to_win', $game->score_to_win, [ 'class' => 'form-control' , 'id' => 'score_to_win']) }}
                        </label>

                        <b>{{ Form::label('max_score', 'Max Score') }}</b>

                        <label class="mr-15 w-45">
                            {{ Form::text('max_score', $game->max_score, [ 'class' => 'form-control' , 'id' => 'max_score']) }}
                        </label>
                    </div>
                </div>

                <div class="row mt-10">
                    <div class="col-xs-4 text-right">
                        <b>{{Form::label('is_leadboard_visible','Display Leadboard')}}</b>
                    </div>
                    <div class="col-xs-2">
                        <label class="">
                            {{Form::checkbox('is_leadboard_visible', 0 , $game->is_leadboard_visible, ['class'=>'mr-15 d-none','id'=>'is_leadboard_visible'])}}
                            <i class="fn-s-25 fa fa-fw fa-square-o w-20"></i>
                        </label>
                    </div>
                </div>

                <div class="row mt-10">
                    @if($companyInfoError = $errors->first('company_info'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$companyInfoError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('company_info', 'Company Info') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $companyInfoError ? 'has-error': '' }}">
                        {{ Form::text('company_info', $game->company_info, [ 'class' => 'form-control' , 'id' => 'company_info']) }}
                    </div>
                </div>
                <div class="row">
                    @if($quizError = $errors->first('select_quiz'))
                        <div class="row">
                            <div class="col-xs-12 fn-s-13">
                                <p class="text-center text-danger"><i>{{$quizError}}</i></p>
                            </div>
                        </div>
                    @endif
                    @foreach($selectedQuizzes as $index => $selectedQuiz)
                        <div class="col-xs-4 text-right mt-10">
                            <b>{{ Form::label('select_quiz_' . ($index + 1), 'Select Quiz '. ($index + 1)) }}</b>
                        </div>

                        <div class="col-xs-8 mt-10">
                            {{ Form::select('select_quiz[]', $quizzes, $selectedQuiz->id,[ 'class' => 'form-control' , 'placeholder'=>'','id'=> 'select_quiz_' . ($index + 1)]) }}
                        </div>

                    @endforeach
                </div>
                <div class="row mt-10">

                    @if($randomizeQuizzesError = $errors->first('randomize_quizzes'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$randomizeQuizzesError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-4 text-right">
                        <b>{{Form::label('are_quizzes_randomized','Randomize quizzes')}}</b>
                    </div>
                    <div class="col-xs-2">
                        <label class="">
                            {{Form::checkbox('are_quizzes_randomized', 1 , $game->are_quizzes_randomized, ['class'=>'mr-15 d-none','id'=>'are_quizzes_randomized'])}}
                            <i class="fn-s-25 fa fa-fw fa-square-o w-20"></i>
                        </label>
                    </div>
                </div>

                <div class="row mt-10">
                    @if($quizzIntroTextError = $errors->first('quizz_intro_text'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$quizzIntroTextError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('quiz_intro_text', 'Quiz Intro Text') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $quizzIntroTextError ? 'has-error': '' }}">
                        {{ Form::text('quiz_intro_text', $game->quiz_intro_text, [ 'class' => 'form-control' , 'id' => 'quiz_intro_text']) }}
                    </div>
                </div>
            </div>

            <div class="col-xs-4">
                <div class="row mb-8">
                    <p class="text-center">
                        <b>
                            Game Icon
                        </b>
                        <br />
                        <small class="fn-s-13">(300x300)</small>
                    </p>
                    <div class="col-xs-12 mt-10">
                        <img id='game_icon-preview' class="img-responsive m-0-auto" src="{{$game->game_icon}}" alt="">
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
                            Upload Image {{ Form::file('game_icon', [ 'class' => 'form-control' , 'id' => 'game_icon']) }}
                        </span>
                    </div>
                </div>


                <div class="row mb-8 mt-40">
                    <p class="text-center">
                        <b>
                            Splash Page Image
                        </b>
                        <br />
                        <small class="fn-s-13">(300x168)</small>
                    </p>
                    <div class="col-xs-12 mt-10">
                        <img id='splash_page_image-preview' class="img-responsive m-0-auto" src="{{$game->splash_page_image}}" alt="">
                    </div>
                    @if($splashImageError = $errors->first('splash_page_image'))
                        <div class="row">
                            <div class="col-xs-12 fn-s-13">
                                <p class="text-center text-danger"><i>{{$splashImageError}}</i></p>
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-12 mt-10 {{ $splashImageError ? 'has-error' : '' }}">
                        <span class="btn btn-light btn-file m-0-auto d-table">
                            Upload Image {{ Form::file('splash_page_image', [ 'class' => 'form-control' , 'id' => 'splash_page_image']) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}

    </div>
@endsection

@push('scripts')
    <script>

        function setInputs(){
            var input = '{!! json_encode(old()) !!}';

            //check for inputs
            if(input.length > 2){
                input = JSON.parse(input);

                if(input.is_leadboard_visible){
                    $('#is_leadboard_visible').prop('checked',true);
                }

                if(input.are_quizzes_randomized){
                    $('#are_quizzes_randomized').prop('checked',true);
                }
                //set quizzes
                if(input.select_quiz[0] !== ""){
                    $('#select_quiz_1').val(input.select_quiz[0]);
                    $('#select_quiz_2').val(input.select_quiz[1]);
                    $('#select_quiz_3').val(input.select_quiz[2]);
                    $('#select_quiz_4').val(input.select_quiz[3]);
                }
            }
        }

        function setPlayerLogin(type){
            switch(type){
                case 'required_data':
                    $('.additional-data').show();
                    break;
                case 'allow_anonymous_players':
                    $('.additional-data').hide();
                    break;
            }
        }

        $(document).ready(function(){
            $("#save, #update").click(function () {
                document.querySelector('#game-form').submit();
            });

            $("#edit-url").click(function(){
                $("#url").removeAttr("readonly");
            });

            //Autogenerate the game url based on game name
            $("#name").change(function(){
                var gameName = $("#name").val();

                gameName = gameName.toLowerCase();

                //Replace all empty space with -
                gameName = gameName.replace(/\s+/g, '-');

                //Remove all special chars
                gameName = gameName.replace(/[^0-9a-z-]/g, '');

                $("#url").val("/" + gameName);
            });

            var $scoreType = $('input[name="score_type"]');
            var selectedType = $scoreType.filter(":checked").val();

            if(!selectedType){
                selectedType = 'points';
                $('input[value="points"]').attr("checked", "checked");
            }

            var $playerLogin = $('input[name="player_login"]');
            var selectedPlayerLogin = $playerLogin.filter(":checked").val();

            if(!selectedPlayerLogin)
            {
                $('#allow_anonymous_players').attr("checked", "checked");
                $('.additional-data').hide();
            }

            setPlayerLogin(selectedPlayerLogin);

            $playerLogin.change(function(){
                setPlayerLogin(this.value);
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

            //set inputs
            setInputs();
        });
    </script>
@endpush

