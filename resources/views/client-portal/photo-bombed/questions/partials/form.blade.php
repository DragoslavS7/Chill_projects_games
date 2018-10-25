@section('dashboard-main-content')
    <div class="col-xs-12 bg-white shadow wraper-background">
        {{ Form::open(['class' => 'fn-s-19', 'url' => $formUrl, 'id' => 'question-form']) }}
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
                    @if($nameError = $errors->first('name'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$nameError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('name', 'Question Title (ID)*') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $nameError ? 'has-error': '' }}">
                        {{ Form::text('name', $question->name, [ 'class' => 'form-control' , 'id' => 'name']) }}
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
                        {{ Form::text('description', $question->description, [ 'class' => 'form-control' , 'id' => 'description']) }}
                    </div>
                </div>

                <div class="row mt-10">
                    @if($questionTypeError = $errors->first('question_type'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$questionTypeError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('question_type', 'Question Type') }}</b>
                    </div>
                    <div class="col-xs-8 fn-s-16 {{ $questionTypeError ? 'has-error': '' }}">
                        <label class="mr-15">Multiple choice
                            {{ Form::radio('question_type', 'multiple_choice', 'multiple_choice' == $question->question_type, [ 'class' => 'mr-15 d-none' , 'id' => 'question_type']) }}
                            <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                        </label>

                        <label class="mr-15">Multi-answer
                            {{ Form::radio('question_type', 'multi_answer', 'multi_answer' == $question->question_type, [ 'class' => 'mr-15 d-none' , 'id' => 'question_type']) }}
                            <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                        </label>

                        <label class="mr-15">Slider
                            {{ Form::radio('question_type', 'slider', 'slider' == $question->question_type, [ 'class' => 'mr-15 d-none' , 'id' => 'question_type']) }}
                            <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                        </label>

                        <label class="mr-15">True/False
                            {{ Form::radio('question_type', 'boolean', 'boolean' == $question->question_type, [ 'class' => 'mr-15 d-none' , 'id' => 'question_type']) }}
                            <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                        </label>
                    </div>
                </div>

                <div class="row mt-5">
                    @if($imageVideoError = $errors->first('question_url'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$imageVideoError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('description', 'Image/Video URL') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $imageVideoError ? 'has-error': '' }}">
                        <div class="input-group">

                            {{ Form::text('question_url', $question->question_video ? $question->question_video : $question->question_image, [ 'class' => 'form-control h-43' , 'id' => 'question_url']) }}
                            <span class="input-group-addon bg-white">
                                <a href="{{$question->question_video ? $question->question_video : $question->question_image}}" type='button' class="edit btn p-0 bg-transparent d-block" id="video-url" target="_blank">
                                    <i class="fa fa-youtube" aria-hidden="true"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row mt-10">
                    @if($orderTypeError = $errors->first('order_type'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$orderTypeError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('order_type', 'Order Type') }}</b>
                    </div>
                    <div class="col-xs-8 fn-s-16 {{ $orderTypeError ? 'has-error': '' }}">
                        <label class="mr-15">Numerical
                            {{ Form::radio('order_type', 'numerical', 'numerical' == $question->order_type, [ 'class' => 'mr-15 d-none' , 'id' => 'question_type']) }}
                            <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                        </label>

                        <label class="mr-15">Alphabetical
                            {{ Form::radio('order_type', 'alphabetical', 'alphabetical' == $question->order_type, [ 'class' => 'mr-15 d-none' , 'id' => 'question_type']) }}
                            <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                        </label>

                        <label class="mr-15">None
                            {{ Form::radio('order_type', 'none', 'none' == $question->order_type, [ 'class' => 'mr-15 d-none' , 'id' => 'question_type']) }}
                            <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                        </label>
                    </div>
                </div>

                <div class="row mt-40">
                    <div class="col-xs-12 text-right">
                        <div class="col-xs-4 text-right">
                            {{ Form::label('is_puzzle', 'Is puzzle') }}
                        </div>
                        <div class="col-xs-8 text-left">
                            <label class="">
                            {{ Form::checkbox('is_puzzle','1',$question->is_puzzle,['class' => 'd-none' , 'id' => 'is_puzzle'])}}
                                <i class="fn-s-25 fa fa-fw fa-square-o w-20"></i>
                            </label>
                        </div>
                    </div>

                </div>

                <div class="row mt-40">
                    @if($correctAnswersError = $errors->first('correct_answer'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$correctAnswersError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-12 text-right">
                        <div class="col-xs-4 text-right">
                            <b>{{ Form::label('answers', 'Answers') }}</b>
                        </div>
                        <div class="col-xs-8 text-right">
                            <b>{{ Form::label('answers', 'Correct') }}</b>
                        </div>
                    </div>
                </div>

                <div class="text-answers">
                    <div class="row mt-10">
                        @if($answer1Error = $errors->first('answer_1'))
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                    <p class="text-left text-danger"><i>{{$answer1Error}}</i></p>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-4 text-right pt-5">
                            <b>{{ Form::label('answer_1', '1') }}</b>
                        </div>
                        <div class="col-xs-8 fn-s-16 {{ $answer1Error ? 'has-error': '' }}">
                            {{ Form::text('answer_1', $answers[0]->answer, [ 'class' => 'form-control' , 'id' => 'answer_1', 'placeholder' => '(max 80 Characters)']) }}
                            <label class="input-right-label">
                                {{ Form::checkbox('correct_answer[]', '1', in_array($answers[0]->id, $correctAnswersIds), [ 'class' => 'd-none' , 'id' => 'answer_1']) }}
                                <i class="fn-s-25 fa fa-fw fa-empty"></i>
                            </label>
                        </div>
                    </div>

                    <div class="row mt-10">
                        @if($answer2Error = $errors->first('answer_2'))
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                    <p class="text-left text-danger"><i>{{$answer2Error}}</i></p>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-4 text-right pt-5">
                            <b>{{ Form::label('answer_2', '2') }}</b>
                        </div>
                        <div class="col-xs-8 fn-s-16 {{ $answer1Error ? 'has-error': '' }}">
                            {{ Form::text('answer_2', $answers[1]->answer, [ 'class' => 'form-control' , 'id' => 'answer_2', 'placeholder' => '(max 80 Characters)']) }}
                            <label class="input-right-label">
                                {{ Form::checkbox('correct_answer[]', '2', in_array($answers[1]->id, $correctAnswersIds), [ 'class' => 'd-none' , 'id' => 'answer_2']) }}
                                <i class="fn-s-25 fa fa-fw fa-empty"></i>
                            </label>
                        </div>
                    </div>

                    <div class="row mt-10">
                        @if($answer3Error = $errors->first('answer_3'))
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                    <p class="text-left text-danger"><i>{{$answer3Error}}</i></p>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-4 text-right pt-5">
                            <b>{{ Form::label('answer_3', '3') }}</b>
                        </div>
                        <div class="col-xs-8 fn-s-16 {{ $answer1Error ? 'has-error': '' }}">
                            {{ Form::text('answer_3', $answers[2]->answer, [ 'class' => 'form-control' , 'id' => 'answer_3', 'placeholder' => '(max 80 Characters)']) }}
                            <label class="input-right-label">
                                {{ Form::checkbox('correct_answer[]', '3',  in_array($answers[2]->id, $correctAnswersIds), [ 'class' => 'd-none' , 'id' => 'answer_3']) }}
                                <i class="fn-s-25 fa fa-fw fa-empty"></i>
                            </label>
                        </div>
                    </div>

                    <div class="row mt-10">
                        @if($answer4Error = $errors->first('answer_4'))
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                    <p class="text-left text-danger"><i>{{$answer4Error}}</i></p>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-4 text-right pt-5">
                            <b>{{ Form::label('answer_4', '4') }}</b>
                        </div>
                        <div class="col-xs-8 fn-s-16 {{ $answer1Error ? 'has-error': '' }}">
                            {{ Form::text('answer_4', $answers[3]->answer, [ 'class' => 'form-control' , 'id' => 'answer_4', 'placeholder' => '(max 80 Characters)']) }}
                            <label class="input-right-label">
                                {{ Form::checkbox('correct_answer[]', '4', in_array($answers[3]->id, $correctAnswersIds), [ 'class' => 'd-none' , 'id' => 'answer_4']) }}
                                <i class="fn-s-25 fa fa-fw fa-empty"></i>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="slider-answer">
                    <div class="row mt-10">
                        @if($minValueError = $errors->first('min'))
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                    <p class="text-left text-danger"><i>{{$minValueError}}</i></p>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-4 text-right pt-5">
                            <b>{{ Form::label('min', '1') }}</b>
                        </div>
                        <div class="col-xs-8 fn-s-16 {{ $minValueError ? 'has-error': '' }}">
                            {{ Form::text('min', $answers[0]->min, [ 'class' => 'form-control' , 'id' => 'min', 'placeholder' => 'Min Value']) }}
                            <label class="input-right-label">
                                {{ Form::checkbox('', '', in_array($answers[3]->id, $correctAnswersIds), [ 'class' => 'd-none' , 'disabled']) }}
                                <i class="fn-s-25 fa fa-fw fa-empty"></i>
                            </label>
                        </div>
                    </div>

                    <div class="row mt-10">
                        @if($correctError = $errors->first('correct_value'))
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                    <p class="text-left text-danger"><i>{{$correctError}}</i></p>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-4 text-right pt-5">
                            <b>{{ Form::label('correct_value', '2') }}</b>
                        </div>
                        <div class="col-xs-8 fn-s-16 {{ $correctError ? 'has-error': '' }}">
                            {{ Form::text('correct_value', $answers[0]->correct_value, [ 'class' => 'form-control' , 'id' => 'correct_value', 'placeholder' => 'Correct Value']) }}
                            <label class="input-right-label">
                                {{ Form::checkbox('', '', true, [ 'class' => 'd-none' , 'disabled', 'checked' => 'checked']) }}
                                <i class="fn-s-25 fa fa-fw fa-empty"></i>
                            </label>
                        </div>
                    </div>

                    <div class="row mt-10">
                        @if($maxError = $errors->first('max'))
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                    <p class="text-left text-danger"><i>{{$maxError}}</i></p>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-4 text-right pt-5">
                            <b>{{ Form::label('max', '3') }}</b>
                        </div>
                        <div class="col-xs-8 fn-s-16 {{ $maxError ? 'has-error': '' }}">
                            {{ Form::text('max', $answers[0]->max, [ 'class' => 'form-control' , 'id' => 'max', 'placeholder' => 'Max']) }}
                            <label class="input-right-label">
                                {{ Form::checkbox('', '', false, [ 'class' => 'd-none' , 'disabled']) }}
                                <i class="fn-s-25 fa fa-fw fa-empty"></i>
                            </label>
                        </div>
                    </div>

                    <div class="row mt-10">
                        @if($startError = $errors->first('start'))
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                    <p class="text-left text-danger"><i>{{$startError}}</i></p>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-4 text-right pt-5">
                            <b>{{ Form::label('start', '4') }}</b>
                        </div>
                        <div class="col-xs-8 fn-s-16 {{ $startError ? 'has-error': '' }}">
                            {{ Form::text('start', $answers[0]->max, [ 'class' => 'form-control' , 'id' => 'start', 'placeholder' => 'Start']) }}
                            <label class="input-right-label">
                                {{ Form::checkbox('', '', false, [ 'class' => 'd-none' , 'disabled']) }}
                                <i class="fn-s-25 fa fa-fw fa-empty"></i>
                            </label>
                        </div>
                    </div>

                    <div class="row mt-10">
                        @if($incrementError = $errors->first('increment'))
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                    <p class="text-left text-danger"><i>{{$incrementError}}</i></p>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-4 text-right pt-5">
                            <b>{{ Form::label('increment', '5') }}</b>
                        </div>
                        <div class="col-xs-8 fn-s-16 {{ $incrementError ? 'has-error': '' }}">
                            {{ Form::text('increment', $answers[0]->max, [ 'class' => 'form-control' , 'id' => 'increment', 'placeholder' => 'Increment']) }}
                            <label class="input-right-label">
                                {{ Form::checkbox('', '', false, [ 'class' => 'd-none' , 'disabled']) }}
                                <i class="fn-s-25 fa fa-fw fa-empty"></i>
                            </label>
                        </div>
                    </div>

                </div>

                <div class="boolean-answer">
                    <div class="row mt-10">
                        @if($booleanAnswer1Error = $errors->first('boolean_answer_1'))
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                    <p class="text-left text-danger"><i>{{$booleanAnswer1Error}}</i></p>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-4 text-right pt-5">
                            <b>{{ Form::label('boolean_answer_1', '1') }}</b>
                        </div>
                        <div class="col-xs-8 fn-s-16 {{ $booleanAnswer1Error ? 'has-error': '' }}">
                            {{ Form::text('boolean_answer_1', 'True', [ 'class' => 'form-control reset-disabled' , 'id' => 'boolean_answer', 'disabled']) }}
                            <label class="input-right-label">
                                {{ Form::radio('boolean_correct', "True", $answers[0]->answer == 'True', [ 'class' => 'd-none' , 'id' => 'boolean_correct']) }}
                                <i class="fn-s-25 fa fa-fw fa-empty"></i>
                            </label>
                        </div>
                    </div>


                    <div class="row mt-10">
                        @if($booleanAnswer2Error = $errors->first('boolean_answer_2'))
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                    <p class="text-left text-danger"><i>{{$booleanAnswer2Error}}</i></p>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-4 text-right pt-5">
                            <b>{{ Form::label('boolean_answer_2', '2') }}</b>
                        </div>
                        <div class="col-xs-8 fn-s-16 {{ $booleanAnswer2Error ? 'has-error': '' }}">
                            {{ Form::text('boolean_answer_2', 'False', [ 'class' => 'form-control reset-disabled' , 'id' => 'boolean_answer_2', 'disabled']) }}
                            <label class="input-right-label">
                                {{ Form::radio('boolean_correct', "False", $answers[0]->answer == 'False', [ 'class' => 'd-none' , 'id' => 'boolean_correct']) }}
                                <i class="fn-s-25 fa fa-fw fa-empty"></i>
                            </label>
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-xs-4">
                <div class="row mt-5">
                    @if($feedbackError = $errors->first('is_feedback_display_available'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$feedbackError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-5">
                        <b>{{ Form::label('is_feedback_display_available', 'Feedback - Display?') }}</b>
                    </div>
                    <div class="col-xs-2 {{ $feedbackError ? 'has-error': '' }}">
                        <label class="">
                            {{ Form::checkbox('is_feedback_display_available', 1, $question->is_feedback_display_available, [ 'class' => 'd-none' , 'id' => 'is_feedback_display_available']) }}
                            <i class="fn-s-25 fa fa-fw fa-square-o w-20"></i>
                        </label>
                    </div>
                </div>
            </div>


            <div class="col-xs-4">
                <div class="row mt-5">
                    <div class="col-xs-12">
                        <b>{{ Form::label('correct_feedback', 'Correct') }}</b>
                        <b class="fn-s-13">( max 80 characters )</b>
                    </div>

                    @if($correctFeedbackError = $errors->first('correct_feedback'))
                        <div class="row">
                            <div class="col-xs-12 fn-s-13">
                                <p class="text-left text-danger"><i>{{$correctFeedbackError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-12 mt-10 {{ $correctFeedbackError ? 'has-error': '' }}">
                        {{ Form::textarea('correct_feedback', $question->correct_feedback, [ 'class' => 'form-control' , 'id' => 'correct_feedback']) }}
                    </div>
                </div>
            </div>


            <div class="col-xs-4">
                <div class="row mt-40">
                    <div class="col-xs-12">
                        <b>{{ Form::label('incorrect_feedback', 'Incorrect') }}</b>
                        <b class="fn-s-13">( max 80 characters )</b>
                    </div>

                    @if($incorrectFeedbackError = $errors->first('incorrect_feedback'))
                        <div class="row">
                            <div class="col-xs-12 fn-s-13">
                                <p class="text-left text-danger"><i>{{$incorrectFeedbackError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-12 mt-10 {{ $incorrectFeedbackError ? 'has-error': '' }}">
                        {{ Form::textarea('incorrect_feedback', $question->incorrect_feedback, [ 'class' => 'form-control' , 'id' => 'incorrect_feedback']) }}
                    </div>
                </div>
            </div>

        </div>
        {{ Form::close() }}
    </div>
@endsection

@push('scripts')
    <script>
        function setAnswers(type) {
            switch (type) {
                case 'multiple_choice':
                    $('input[name="correct_answer[]"]').attr('type', 'radio');
                    $('.text-answers').show();
                    $('.slider-answer, .boolean-answer').hide();
                    break;
                case 'multi_answer':
                    $('input[name="correct_answer[]"]').attr('type', 'checkbox');
                    $('.text-answers').show();
                    $('.slider-answer, .boolean-answer').hide();
                    break;

                case 'slider':
                    $('.slider-answer').show();
                    $('.text-answers, .boolean-answer').hide();
                    break;
                case 'boolean':
                    $(' .boolean-answer').show();
                    $('input[name="correct_answer[]"]').attr('type', 'radio');
                    $('.text-answers, .slider-answer').hide();
                    break;
            }

        }


        $(document).ready(function(){
            $("#save, #update").click(function () {
                $('input[name^="boolean_answer_"]').prop('disabled', false);
                document.querySelector('#question-form').submit();
            });

            var $questionType = $('input[name="question_type"]');
            var selectedType = $questionType.filter(":checked").val();

            if(!selectedType){
                selectedType = 'multiple_choice';
                $('input[value="multiple_choice"]').attr("checked", "checked");
            }

            setAnswers(selectedType);

            $questionType.change(function(){
                setAnswers(this.value);
            });

            // On click on checkbox show notify
            var confirmModal = new ConfirmDialog({
                modal_title:'Question as a puzzle?',
                modal_dialog_message:'Do you want this photobomb question as a puzzle?'
            });

            function cancelShow () {
                var prevState = !$(this).is(':checked');
                $(this).prop('checked', prevState);

            }

            $('#is_puzzle').on('click', confirmModal.show(null,cancelShow));
        });
    </script>
@endpush

