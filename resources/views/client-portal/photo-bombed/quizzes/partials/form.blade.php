@section('dashboard-main-content')
    <div class="col-xs-12 bg-white shadow wraper-background">
        {{ Form::open(['class' => 'fn-s-19', 'url' => $formUrl, 'id' => 'quiz-form']) }}
        <div class="row pt-30 pb-30">
            @if($saveError = $errors->first('save'))
                <div class="row">
                    <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                        <p class="text-left text-danger"><i>{{$saveError}}</i></p>
                    </div>
                </div>
            @endif

            <div class="col-xs-9">
                <div class="row">
                    @if($nameError = $errors->first('name'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$nameError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-4 text-right">
                        <b>{{ Form::label('name', 'Quiz Name*') }}</b>
                    </div>
                    <div class="col-xs-8 {{ $nameError ? 'has-error': '' }}">
                        {{ Form::text('name', $quiz->name, [ 'class' => 'form-control' , 'id' => 'name']) }}
                    </div>

                </div>

                <div class="row mt-15">
                    @if($tagsError = $errors->first('tags'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$tagsError}}</i></p>
                            </div>
                        </div>
                    @endif

                        <div class="col-xs-4 text-right">
                            <b>{{ Form::label('tags', 'Tags') }}</b>
                        </div>

                        <div class="col-xs-8 {{ $tagsError ? 'has-error': '' }}">
                            <div class="tag bg-white w-100p fn-c-mine-sec w-100-max bl-secondary" id="quiz-tags">
                                {{ Form::text('hasTags',null, ['class' => 'form-control', 'id' => 'boxes' ]) }}
                            </div>
                            <div class="col-xs-12 p-0">
                                <div id="autocompleteTags" class='form-control d-none autocomplate' style="" ></div>
                            </div>
                            <div id="added-tags">

                            </div>
                            <!-- Default tags-->
                            <div id="default-tags">

                            </div>
                            {{ Form::text('tags' , '',  [ 'class' => 'form-control invisible' , 'id' => 'tags'] ) }}
                        </div>
                </div>

                <div class="row">
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
                        {{ Form::text('description', $quiz->description, [ 'class' => 'form-control' , 'id' => 'description']) }}
                    </div>
                </div>

                <div class="row mt-10">
                    <div class="col-xs-4 text-right">
                        <b>{{Form::label('question_filter','Question filter')}}</b>
                    </div>
                    <div class="col-xs-8 fn-s-13">
                        <label class="mr-15">Multiple choice
                            {{Form::radio('question_type','Multiple choice', false, ['class'=>'mr-15 d-none','id'=>'question_type'])}}
                            <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                        </label>

                        <label class="mr-15">Multi-answer
                            {{Form::radio('question_type','Multi-answer', false, ['class'=>'mr-15 d-none','id'=>'question_type'])}}
                            <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                        </label>

                        <label class="mr-15">Slider
                            {{Form::radio('question_type','Slider', false, ['class'=>'mr-15 d-none','id'=>'question_type'])}}
                            <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                        </label>

                        <label class="mr-15">True/False
                            {{Form::radio('question_type','True/False', false, ['class'=>'mr-15 d-none','id'=>'question_type'])}}
                            <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                        </label>

                        <label class="mr-15">Image/Video
                            {{Form::radio('question_type','Image/Video', false, ['class'=>'mr-15 d-none','id'=>'question_type'])}}
                            <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                        </label>

                        <label class="mr-15">All
                            {{Form::radio('question_type','all', true , ['class'=>'mr-15 d-none','id'=>'question_type'])}}
                            <i class="fn-s-25 fa fa-fw fa-check-circle-o"></i>
                        </label>
                    </div>
                </div>

                <div class="row mt-10">
                    @if($randomizeQuestionsError = $errors->first('randomize_questions'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$randomizeQuestionsError}}</i></p>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-4 text-right">
                        <b>{{Form::label('are_questions_randomized','Randomize questions')}}</b>
                    </div>
                    <div class="col-xs-2">
                        <label class="">
                            {{Form::checkbox('are_questions_randomized', 0 , $quiz->are_questions_randomized, ['class'=>'mr-15 d-none','id'=>'are_questions_randomized'])}}
                            <i class="fn-s-25 fa fa-fw fa-square-o w-20"></i>
                        </label>
                    </div>
                </div>

                <div class="row mt-10">
                    @if($questionsError = $errors->first('questions'))
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-4 fn-s-13">
                                <p class="text-left text-danger"><i>{{$questionsError}}</i></p>
                            </div>
                        </div>
                    @endif
                    <div class="col-xs-4 text-right">
                        <b>{{Form::label(null,'Questions')}}</b>
                    </div>
                    <div class="col-xs-8 fn-s-15 pt-5">
                        <b>{{Form::label(null,'Each  Quiz  can  between  1  and  up  to  50  questions')}}</b>
                    </div>
                </div>
                <div class="row mt-10 ">
                    <div id='questions-wrapper'>
                        @foreach($selectedQuestions as $index => $question)
                            <div class="question clearfix mt-10">
                                <div class="col-xs-4 text-right pt-5 m-0-auto">
                                    <b>{{ Form::label('', $index +1 ) }}</b>
                                </div>
                                <div class="col-xs-7 fn-s-16">
                                    <div class="input-group">
                                        {{ Form::select('questions[]', $questions, $question->id, [ 'class' => 'form-control']) }}

                                        <span class="input-group-btn">
                                                 <a href="{{route('client-portal.photo-bombed.questions.create')}}" class="add {{$question->id ? 'd-none': ''}}">
                                                     <button type="button" class="btn pb-5 bl-none ">
                                                         <i class="fa fa-plus" aria-hidden="true"></i>
                                                     </button>
                                                 </a>

                                                 <button type="button" class="edit btn pb-5 bl-none {{$question->id ? '': 'd-none'}}">
                                                     <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                 </button>

                                                 <button type="button" class="delete btn pb-5 bl-none {{$index == 0 ? 'd-none': ''}}">
                                                     <i class="fa fa-trash" aria-hidden="true"></i>
                                                 </button>
                                            </span>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>
                    <div class="col-xs-8 col-xs-offset-4 input-group-btn mt-10">
                        <button type="button" id='add-question' class="btn pb-5">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection

@push('scripts')
     <script>

        function setInputs($questionsWrapper){
            var input = '{!! json_encode(old()) !!}';

            //check for inputs
            if(input.length > 2){
                input = JSON.parse(input);

                if(input.are_questions_randomized){
                    $('#are_questions_randomized').prop('checked',true)
                }

                //set questions
                if(input.questions.length && input.questions[0] !== ""){
                    for(var i=0; i<input.questions.length;i++){
                        if(i==0){
                            $questionsWrapper.find(">div:last").prop('selectedIndex',input.questions[i]);
                        }else{
                            var $questionBlueprint = $questionsWrapper.find(">div:last").clone();

                            var $label = $questionBlueprint.find('label');
                            $label.text( parseInt($label.text()) + 1);

                            $questionBlueprint.find('select').val('');

                            $questionBlueprint.find('.delete').removeClass('d-none');
                            $questionBlueprint.find('.edit').addClass('d-none');
                            $questionBlueprint.find('.add').removeClass('d-none');
                            $questionBlueprint.val('selectedIndex',input.questions[i]);
                            $questionsWrapper.append($questionBlueprint);
                        }
                    }
                }

                $('select option[value]').attr('selected','selected');

            }
        }

        $(document).ready(function(){
            $("#save, #update").click(function () {
                $('input[name^="boolean_answer_"]').prop('disabled', false);
                document.querySelector('#quiz-form').submit();
            });

            var $questionType = $('input[name="question_type"]');
            var selectedType = $questionType.filter(":checked").val();

            if(!selectedType){
                selectedType = 'multiple_choice';
                $('input[value="multiple_choice"]').attr("checked", "checked");
            }

            $('input[name="question_type"]').change(function(){
                var value = this.value;

                if(value == 'all'){
                    $('optgroup').show();
                }else{
                    $('optgroup').hide();
                    $('optgroup[label="' + value +'"]').show();
                }
            });


            var $questionsWrapper = $("#questions-wrapper");
            $('#add-question').click(function(){
                var $questionBlueprint = $questionsWrapper.find(">div:last").clone();

                var $label = $questionBlueprint.find('label');
                $label.text( parseInt($label.text()) + 1);

                $questionBlueprint.find('select').val('');

                $questionBlueprint.find('.delete').removeClass('d-none');
                $questionBlueprint.find('.edit').addClass('d-none');
                $questionBlueprint.find('.add').removeClass('d-none');

                $questionsWrapper.append($questionBlueprint);

            });

            $questionsWrapper.on('change', 'select[name="questions[]"]', function(){
                var $inputGroup = $(this).parents('.input-group');

                if(this.value == ''){
                    $inputGroup.find('.edit').addClass('d-none');
                    $inputGroup.find('.add').removeClass('d-none');
                }else{
                    $inputGroup.find('.edit').removeClass('d-none');
                    $inputGroup.find('.add').addClass('d-none');
                }
            });

            $questionsWrapper.on('click', '.delete', function(){
                $(this).parents('.question').remove();

                //update numbers
                $questionsWrapper.find('label').each(function(index){
                    this.textContent = index + 1;
                })
            });

            $questionsWrapper.on('click', '.edit', function(){
                var id = $(this).parents('.question').find('select[name="questions[]"]').val();

                var url = '{{ route('client-portal.photo-bombed.questions.edit', '_ID_' ) }}';
                url = url.replace('_ID_', id);

                window.open(url, '_blank');
            });

            //fill inputs
            setInputs($questionsWrapper);


            // Initialize tags
            var tags = new Tags({
                autocomplate: true,
                autocomplateUrl: "{{ route('client-portal.quizzes.tags') }}",
                csrfToken: '{{ csrf_token() }}',
                suggestedTags: {!! json_encode($suggestedTags) !!},

                $tagInput: $("input[name='hasTags']"),
                $tagServerInput: $("input[name='tags']"),
                $suggestedTagsContainer: $('#default-tags'),
                $addedTagsContainer: $('#added-tags'),
                $autocompleteContainer: $("#autocompleteTags"),
            });

            tags.addTags({!! json_encode($tags) !!})

        });
    </script>
@endpush

