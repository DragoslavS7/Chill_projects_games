<?php

namespace App\Http\Controllers\ClientPortal;


use App\Answer;
use App\Http\Controllers\Controller;
use App\Services\XapiMyArcadeChef;
use Illuminate\Http\Request;
use App\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class PhotoBombedQuestionsController extends Controller
{

    function __construct(){
        $this->xApiMyArcadeChef = new XapiMyArcadeChef();
    }

    function index(Request $request){
        $areThereAnyQuestions = $request->clientPortal->questions()->where('question_image','!=','')->count() > 0;

        return view('client-portal.photo-bombed.questions.index', ['user' => \Auth::user(),
                                                           'areThereAnyQuestions' => $areThereAnyQuestions
            ]);
    }

    function create(){
        $question = new Question();
        $formUrl = route('client-portal.photo-bombed.questions.store');
        $answers = [new Answer(), new Answer(), new Answer(), new Answer()];
        $correctAnswersIds = [];

        return view('client-portal.photo-bombed.questions.create', ['user' => \Auth::user(),
                                                            'question' => $question,
                                                            'formUrl' => $formUrl,
                                                            'answers' => $answers,
                                                            'correctAnswersIds' => $correctAnswersIds
        ]);
    }

    function edit($id, Request $request){
        $id = (int)$id;
        $question = $request->clientPortal->questions()->where('question_image','!=','')->where('id',$id)->first();

        if(!$question){
            return redirect()->route('client-portal.photo-bombed.questions.index')->with('error','This question does not exist on this client portal.');
        }

        $formUrl = route('client-portal.photo-bombed.questions.update', $id);
        $answers = $question->answers;

        //populate empty answers
        while(sizeof($answers) < 4 ){
            $answers[] = new Answer();
        }

        $correctAnswersIds = $question->correctAnswers()->lists('id')->toArray();

        return view('client-portal.photo-bombed.questions.edit', [  'user' => \Auth::user(),
                                                            'question' => $question,
                                                            'formUrl' => $formUrl,
                                                            'answers' => $answers,
                                                            'correctAnswersIds' => $correctAnswersIds
                                                        ]);
    }

    function save($input, $request, $question){
        $rules = $question->rules;
        $rules['question_url'] = 'required';

        if ($question->validate($input,$rules)) {

            DB::beginTransaction();
            try{

                if(array_key_exists('is_feedback_display_available', $input) && $input['is_feedback_display_available']){
                    $input['is_feedback_display_available'] = true;
                }else{
                    $input['is_feedback_display_available'] = false;
                }

                if(array_key_exists('is_puzzle', $input) && $input['is_puzzle']){
                    $input['is_puzzle'] = true;
                }else{
                    $input['is_puzzle'] = false;
                }

                if(array_key_exists('question_url',$input)){
                    if (preg_match('/\b(youtube)\b/i', $input['question_url'], $matches)) {
                        //format youtube url to get video thumbnail link if not already formated
                        if (strpos($input['question_url'], 'embed') === false) {
                            $urlArr =[];
                            parse_str( parse_url( $input['question_url'], PHP_URL_QUERY ), $urlArr );

                            $youtubeVideoId = $urlArr['v'];

                            $input['question_image'] = 'http://img.youtube.com/vi/'.$youtubeVideoId.'/0.jpg';
                            $input['question_video'] = 'http://www.youtube.com/embed/'.$youtubeVideoId;
                        }
                    }
                    else if(preg_match('/\b(vimeo)\b/i', $input['question_url'], $matches)){
                        //format vimeo url to get video thumbnail link if not already formated
                        if (strpos($input['question_url'], 'player.vimeo') === false){
                            $urlArr = explode('/',$input['question_url']);
                            $vimeoVideoId = end($urlArr);
                            $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$vimeoVideoId.php"));

                            $input['question_image'] = $hash[0]['thumbnail_large'];
                            $input['question_video'] = 'https://player.vimeo.com/video/'. $vimeoVideoId;
                        }
                    }
                    else if(preg_match('/\b(imgur)\b/i', $input['question_url'], $matches)) {
                        $input['question_image'] = $input['question_url'];
                        $input['question_video'] = '';
                    }
                    else{
                        throw new \Exception('Link provided must be a Youtube, Vimeo or a Imgur link .', 1);
                    }
                }

                $input['client_portal_id'] = $request->clientPortal->id;
                $input['is_saved_to_lrs'] = true;
                $question->fill($input);
                $question->save();

                //check if not status change action
                if(!array_key_exists('is_enabled',$input)){
                    //remove all answers for question and re-add them
                    $question->answers()->forceDelete();
                }


                $user = \Auth::user();

                switch ($question->question_type) {
                    case "multi_answer":
                        if(count($input['correct_answer'])<2){
                            throw new \Exception('Multiple answers questions must have at least two correct answers.', 1);
                        }

                    case "multiple_choice":
                        $conditions = ["answer_1", "answer_2", "answer_3", "answer_4"];

                        $answers = $request->only($conditions);
                        if(!count($answers)<1){
                            $answers = array_intersect_key($input,array_flip($conditions));
                        }

                        foreach ($answers as $name => $answer) {
                            if ($answer) {
                                $isCorrect = in_array(explode('_', $name)[1], $input['correct_answer']);

                                $dbAnswer = Answer::create([
                                    "type" => "text",
                                    "answer" => $answer,
                                    "question_id" => $question->id,
                                    "is_correct" => $isCorrect
                                ]);

                                $isAnswerSavedToLRS = true;

                                try {
                                    $this->xApiMyArcadeChef->createAnswer($user, $dbAnswer);
                                }catch (\Exception $e) {
                                    //mark this session as not saved in lrs and continue
                                    $isAnswerSavedToLRS = false;
                                    \Log::error($e);
                                }
                                $dbAnswer->is_saved_to_lrs = $isAnswerSavedToLRS;
                            }

                        }
                        break;

                    case "boolean":
                        $conditions = ["boolean_answer_1", "boolean_answer_2"];

                        $answers = $request->only($conditions);
                        if(!count($answers)<1){
                            $answers = array_intersect_key($input,array_flip($conditions));
                        }
                        foreach ($answers as $name => $answer) {
                            if ($answer) {
                                $isCorrect = $answer == $input['boolean_correct'];

                                $dbAnswer = Answer::create([
                                    "type" => "boolean",
                                    "answer" => $answer,
                                    "question_id" => $question->id,
                                    "is_correct" => $isCorrect
                                ]);

                                $isAnswerSavedToLRS = true;

                                try {
                                    $this->xApiMyArcadeChef->createAnswer($user, $dbAnswer);
                                }catch (\Exception $e) {
                                    //mark this session as not saved in lrs and continue
                                    $isAnswerSavedToLRS = false;
                                    \Log::error($e);
                                }
                                $dbAnswer->is_saved_to_lrs = $isAnswerSavedToLRS;

                            }

                        }

                        break;

                    case "slider":
                        $dbAnswer = Answer::create([
                            "type" => "slider",
                            "question_id" => $question->id,
                            "is_correct" => 1,
                            "min" => $input["min"],
                            "correct_value" => $input["correct_value"],
                            "max" => $input["max"],
                            "start" =>  $input["start"],
                            "increment" => $input["increment"]
                        ]);

                        $isAnswerSavedToLRS = true;

                        try {
                            $this->xApiMyArcadeChef->createAnswer($user, $dbAnswer);
                        }catch (\Exception $e) {
                            //mark this session as not saved in lrs and continue
                            $isAnswerSavedToLRS = false;
                            \Log::error($e);
                        }
                        $dbAnswer->is_saved_to_lrs = $isAnswerSavedToLRS;

                        break;
                }

                $isSavedToLRS = true;
                try {
                    $this->xApiMyArcadeChef->createQuestion($user, $question);
                }catch (\Exception $e) {
                    //mark this session as not saved in lrs and continue
                    $isSavedToLRS = false;
                    \Log::error($e);

                    $question->is_saved_to_lrs = $isSavedToLRS;
                    $question->save();
                }

                DB::commit();
            }catch (\Exception $e){
                DB::rollback();

                $errors = new MessageBag();
                $errors->add('save', $e->getMessage());

                return ['success' => false, 'errors' => $errors];
            }

            return ['success' => true];
        }else{
            return ['success' => false, 'errors' => $question->errors()];
        }
    }

    function store(Request $request){
        $question = new Question();

        $result = $this->save( $request->all(), $request, $question);

        if ($result['success']) {
            return redirect()->route('client-portal.photo-bombed.questions.index')->with('success','Question successfully created.');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }

    function update($id, Request $request){
        $id = (int)$id;
        $question = $request->clientPortal->questions()->where('question_image','!=','')->where('id',$id)->first();

        if(!$question){
            return redirect()->route('client-portal.photo-bombed.questions.index')->with('error','This question does not exist on this client portal.');
        }

        $result = $this->save( $request->all(), $request, $question);

        if ($result['success']) {
            return redirect()->route('client-portal.photo-bombed.questions.index')->with('success','Question successfully updated.');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }

    function duplicate($id, Request $request){
        $id = (int)$id;
        $question = $request->clientPortal->questions()->where('question_image','!=','')->where('id',$id)->first();

        if(!$question){
            return redirect()->route('client-portal.photo-bombed.questions.index')->with('error','This question does not exist on this client portal.');
        }

        $questionDuplicate = $question->replicate();
        $questionDuplicate->name = $questionDuplicate->name.' copy';

        $result = $questionDuplicate->save();

        if ($result) {
            $answers = $question->answers->lists('id')->toArray();

            foreach ($answers as $answerId){
                $answerOriginal = Answer::findorFail($answerId);
                $answerDuplicate = $answerOriginal->replicate();
                $answerDuplicate->question_id = $questionDuplicate->id;

                $answerResult = $answerDuplicate->save();

                if(!$answerResult){
                    return back()
                        ->with('error','Not all answers could be duplicated please check the new duplicate question.')
                        ->withInput();
                }
            }

            return redirect()->route('client-portal.photo-bombed.questions.index');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }


    function dataTables(Request $request){
        $questionsQueryBuilder = $request->clientPortal->questions()
                                                        ->where('question_image','!=','')
                                                        ->with('quizzes');

        return \DataTables::of($questionsQueryBuilder)->make(true);
    }

    function destroy($id,Request $request){
        $id = (int)$id;
        $question = $request->clientPortal->questions()->where('question_image','!=','')->where('id',$id)->first();

        if(!$question){
            return redirect()->route('client-portal.photo-bombed.questions.index')->with('error','This question does not exist on this client portal.');
        }

        // soft-delete all answers for question
        $question->answers()->delete();

        // soft-delete question
        $question->delete();
    }

    function destroyBulk(Request $request){
        $ids = $request->ids;

        foreach ($ids as $id){
            $id = (int)$id;
            $question = $request->clientPortal->questions()->where('question_image','!=','')->where('id',$id)->first();

            if($question){
                // soft-delete all answers for question
                $question->answers()->delete();

                // soft-delete question
                $question->delete();
            }
        }
    }
}