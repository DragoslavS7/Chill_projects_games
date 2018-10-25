<?php

namespace App\Http\Controllers\ClientPortal;


use App\Answer;
use App\Http\Controllers\Controller;
use App\Services\XapiMyArcadeChef;
use Illuminate\Http\Request;
use App\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class QuestionsController extends Controller
{

    function __construct(){
        $this->xApiMyArcadeChef = new XapiMyArcadeChef();
    }

    function index(Request $request){
        $areThereAnyQuestions = $request->clientPortal->questions()->count() > 0;

        return view('client-portal.questions.index', ['user' => \Auth::user(),
                                                           'areThereAnyQuestions' => $areThereAnyQuestions
            ]);
    }

    function create(){
        $question = new Question();
        $formUrl = route('client-portal.questions.store');
        $answers = [new Answer(), new Answer(), new Answer(), new Answer()];
        $correctAnswersIds = [];

        return view('client-portal.questions.create', ['user' => \Auth::user(),
                                                            'question' => $question,
                                                            'formUrl' => $formUrl,
                                                            'answers' => $answers,
                                                            'correctAnswersIds' => $correctAnswersIds
        ]);
    }

    function edit($id, Request $request){
        $id = (int)$id;
        $question = $request->clientPortal->questions->where('id',$id)->first();

        if(!$question){
            return redirect()->route('client-portal.questions.index')->with('error','This question does not exist on this client portal.');
        }

        $formUrl = route('client-portal.questions.update', $id);
        $answers = $question->answers;

        //populate empty answers
        while(sizeof($answers) < 4 ){
            $answers[] = new Answer();
        }

        $correctAnswersIds = $question->correctAnswers()->lists('id')->toArray();

        return view('client-portal.questions.edit', [  'user' => \Auth::user(),
                                                            'question' => $question,
                                                            'formUrl' => $formUrl,
                                                            'answers' => $answers,
                                                            'correctAnswersIds' => $correctAnswersIds
                                                        ]);
    }

    function save($input, $request, $question){
        if ($question->validate($input,$question->rules)) {

            DB::beginTransaction();
            try{

                if(array_key_exists('is_feedback_display_available', $input) && $input['is_feedback_display_available']){
                    $input['is_feedback_display_available'] = true;
                }else{
                    $input['is_feedback_display_available'] = false;
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
            return redirect()->route('client-portal.questions.index')->with('success','Question successfully created.');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }

    function bulkStore(Request $request){
        $csvPath = $request->file('csv')->getRealPath();



        $csv = array_map('str_getcsv', file($csvPath));

        if(count($csv) == 0){
            return redirect()->back()
                ->with('error', 'Incorrect file type or file is empty.');
        }

        $diff =  array_diff($csv[0], [
            '#',
            'name',
            'description',
            'question_type',
            'order_type',
            'answer_1',
            'answer_2',
            'answer_3',
            'answer_4',
            'correct_answer',
            'min',
            'correct_value',
            'max',
            'start',
            'increment',
            'boolean_answer_1',
            'boolean_answer_2',
            'boolean_correct',
            'is_feedback_display_available',
            'correct_feedback',
            'incorrect_feedback'
        ]);

        if(count($diff) > 0){
            $diffError = implode(",",$diff);
            return redirect()->back()
                ->with('error', "Invalid csv file, the data did not coincide. Your data is: '.$diffError.'");
        }

        $errors = [];
        $added = 0;
        array_walk($csv, function (&$input, $index) use ($csv, &$errors, &$added, $request) {
            if (trim(implode('', $input)) !== '') {
                $input = array_combine($csv[0], $input);

                if ($index > 0) {
                    $question = Question::where('name', $input['name'])->first();

                    if (!$question) {
                        $question = new Question();
                    }
                    $input['correct_answer'] = array_map('trim', explode(',', $input['correct_answer']));

                    $result = $this->save($input, $request, $question);

                    if (!$result['success']) {
                        $errors[$index] = $result['errors'];
                    } else {
                        $added++;
                    }
                }
            }
        });

        return redirect()->back()
            ->with('success-csv-upload', "Successfully added $added questions!")
            ->with('fail-csv-upload', $errors);
    }

    function update($id, Request $request){
        $id = (int)$id;
        $question = $request->clientPortal->questions->where('id',$id)->first();

        if(!$question){
            return redirect()->route('client-portal.questions.index')->with('error','This question does not exist on this client portal.');
        }

        $result = $this->save( $request->all(), $request, $question);

        if ($result['success']) {
            return redirect()->route('client-portal.questions.index')->with('success','Question successfully updated.');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }

    function duplicate($id, Request $request){
        $id = (int)$id;
        $question = $request->clientPortal->questions->where('id',$id)->first();

        if(!$question){
            return redirect()->route('client-portal.questions.index')->with('error','This question does not exist on this client portal.');
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

            return redirect()->route('client-portal.questions.index');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }


    function dataTables(Request $request){
        $questionsQueryBuilder = $request->clientPortal->questions()->where('question_video','=','')->with('quizzes');

        return \DataTables::of($questionsQueryBuilder)->make(true);
    }

    function destroy($id,Request $request){
        $id = (int)$id;
        $question = $request->clientPortal->questions->where('id',$id)->first();

        if(!$question){
            return redirect()->route('client-portal.questions.index')->with('error','This question does not exist on to this client portal.');
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
            $question = $request->clientPortal->questions->where('id',$id)->first();

            if($question){
                // soft-delete all answers for question
                $question->answers()->delete();

                // soft-delete question
                $question->delete();
            }
        }
    }
}