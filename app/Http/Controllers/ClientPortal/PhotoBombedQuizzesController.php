<?php

namespace App\Http\Controllers\ClientPortal;


use App\Http\Controllers\Controller;
use App\Question;
use App\Quiz;
use App\QuizzesQuestion;
use App\QuizzesTags;
use App\Services\XapiMyArcadeChef;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Mockery\Exception;

class PhotoBombedQuizzesController extends Controller
{
    function __construct(){
        $this->xApiMyArcadeChef = new XapiMyArcadeChef();
    }

    function index(Request $request){
        $areThereAnyQuizzes = $request->clientPortal->quizzes()->where('is_photobombed',1)->count() > 0;

        return view('client-portal.photo-bombed.quizzes.index', ['user' => \Auth::user(),
            'areThereAnyQuizzes' => $areThereAnyQuizzes
        ]);
    }

    function create(Request $request){
        $quiz = new Quiz();
        $formUrl = route('client-portal.photo-bombed.quizzes.store');

        $questions = ['' => 'Select Question'] + $this->getGroupedQuestions($request->clientPortal->questions()->where('question_image','!=','')->get());

        $selectedQuestions  = [new Question()];

        $tags = [];

        $suggestedTags = [
            'Menu',
            'Recipe',
            'Product Knowledge',
            'New Hire',
            'Policy',
            'Food Safety',
            'Service Standards',
            'Hospitality',
            'SOP'
        ];

        return view('client-portal.photo-bombed.quizzes.create', ['user' => \Auth::user(),
            'quiz' => $quiz,
            'formUrl' => $formUrl,
            'questions' => $questions,
            'selectedQuestions' => $selectedQuestions,
            'tags'=>$tags,
            'suggestedTags'=>$suggestedTags
        ]);
    }

    function getGroupedQuestions($questions){

        $groupedQuestions = [];
        foreach ($questions as $question) {

            if(!array_key_exists($question->formatted_question_type, $groupedQuestions)) {
                $groupedQuestions[$question->formatted_question_type] = [];
            }

            $groupedQuestions[$question->formatted_question_type][$question->id] = $question->name;
        }

        return $groupedQuestions;
    }

    function edit($id, Request $request){
        $id = (int)$id;
        $quiz = $request->clientPortal->quizzes->where('is_photobombed',1)->where('id',$id)->first();

        if(!$quiz){
            return redirect()->route('client-portal.photo-bombed.quizzes.index')->with('error','This quiz does not exist on this client portal.');
        }

        $formUrl = route('client-portal.photo-bombed.quizzes.update', $id);

        $questions = ['' => 'Select Question'] + $this->getGroupedQuestions($request->clientPortal->questions()->where('question_image','!=','')->get());

        $selectedQuestions  = $quiz->questions;

        if(count($selectedQuestions)===0){
            $selectedQuestions  = [new Question()];
        }

        $tags = DB::table('quizzes_tags')->where('quiz_id',$quiz->id)->pluck('tag');

        $suggestedTags = [
            'Menu',
            'Recipe',
            'Product Knowledge',
            'New Hire',
            'Policy',
            'Food Safety',
            'Service Standards',
            'Hospitality',
            'SOP'
        ];

        return view('client-portal.photo-bombed.quizzes.edit', [  'user' => \Auth::user(),
            'quiz'=>$quiz,
            'formUrl' => $formUrl,
            'questions' => $questions,
            'selectedQuestions' => $selectedQuestions,
            'tags'=>$tags,
            'suggestedTags' => $suggestedTags
        ]);
    }


    function save($request,$quiz){
        $input = $request->except('_token');
        $user = \Auth::user();

        if ($quiz->validate($input)) {
            DB::beginTransaction();
            try{
                $input['client_portal_id'] = $request->clientPortal->id;
                $quiz->is_photobombed= true;

                if($quiz->author_id){
                    $input['updated_by_id'] = $user->id;
                }
                else{
                    $input['author_id'] = $user->id;
                }

                $input['is_saved_to_lrs'] = true;
                $quiz->fill($input);
                $quiz->save();

                if(array_key_exists('are_questions_randomized', $input)){
                    $input['are_questions_randomized'] = true;
                }else{
                    $input['are_questions_randomized'] = false;
                }

                if(array_key_exists('tags', $input)){
                    $tags = explode(',',$input['tags']);
                    //set tags
                    foreach ($tags as $tag){
                        if ($tag && QuizzesTags::isUnique( $quiz->id, $tag)){
                            QuizzesTags::create([
                                'quiz_id'=>$quiz->id,
                                'tag'=>$tag
                            ]);
                        }
                    }

                    //unlink tags
                    $linkedTags =$quiz->tags->lists('tag','id')->toArray();

                    foreach ($linkedTags as $tag) {
                        if(!in_array($tag,$tags)){
                            QuizzesTags::where(['quiz_id'=>$quiz->id])->where('tag',$tag)->delete();
                        }
                    }
                }

                $questions = [];

                if(isset($input['questions'])){
                    $questions = preg_grep("/^.+$/", $input['questions']);

                    if(count($questions) == 0 && (!isset($input['is_enabled']) && count($input) > 1) ){
                        throw new Exception('You need to select at least one question', 1);
                    }

                    // link questions
                    foreach ($questions as $questionId) {
                        if (QuizzesQuestion::isUnique( $questionId, $quiz->id )) {
                            QuizzesQuestion::create([
                                "question_id" => $questionId,
                                "quiz_id" => $quiz->id
                            ]);
                        }
                    }

                    //unlink questions
                    $unLinkQuestionsId = array_diff($quiz->questions->lists('id')->toArray(), $questions);

                    foreach ($unLinkQuestionsId as $questionId) {
                        QuizzesQuestion::where(['question_id' => $questionId])
                            ->where(['quiz_id' => $quiz->id])
                            ->delete();
                    }
                }

                $user = \Auth::user();
                $isSavedToLRS = true;
                try {
                    $this->xApiMyArcadeChef->createQuiz($user, $quiz);
                }catch (\Exception $e) {
                    //mark this session as not saved in lrs and continue
                    $isSavedToLRS = false;
                    \Log::error($e);

                    $quiz->is_saved_to_lrs = $isSavedToLRS;
                    $quiz->save();
                }

                DB::commit();
            }catch (\Exception $e){
                DB::rollback();

                $errors = new MessageBag();

                if($e->getCode() == 1){
                    $errors->add('questions', $e->getMessage());
                }else {
                    $errors->add('save', $e->getMessage());
                }

                return ['success' => false, 'errors' => $errors];
            }

            return ['success' => true];
        }else{
            return ['success' => false, 'errors' => $quiz->errors()];
        }
    }

    function store(Request $request){
        $quiz = new Quiz();

        $result = $this->save($request,$quiz);

        if ($result['success']) {
            return redirect()->route('client-portal.photo-bombed.quizzes.index')->with('success','Quiz successfully created.');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }

    function update($id, Request $request){
        $id = (int)$id;
        $quiz = $request->clientPortal->quizzes->where('is_photobombed',1)->where('id',$id)->first();

        if(!$quiz){
            return redirect()->route('client-portal.photo-bombed.quizzes.index')->with('error','This quiz does not exist on this client portal.');
        }

        $result = $this->save($request,$quiz);

        if($request->ajax()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->route('client-portal.photo-bombed.quizzes.index')->with('success','Quiz successfully updated.');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }

    function duplicate($id, Request $request){
        $id = (int)$id;
        $quiz = $request->clientPortal->quizzes->where('is_photobombed',1)->where('id',$id)->first();

        if(!$quiz){
            return redirect()->route('client-portal.photo-bombed.quizzes.index')->with('error','This quiz does not exist on this client portal.');
        }

        $quizCopy = $quiz->replicate();

        $quizCopy->name = $quizCopy->name.' copy';

        $result = $quizCopy->save();

        if ($result) {
            $questions = $quiz->questions->lists('id')->toArray();

            //link questions
            foreach ($questions as $questionId){
                if (QuizzesQuestion::isUnique( $questionId, $quizCopy->id )){
                    QuizzesQuestion::create([
                        "question_id" => $questionId,
                        "quiz_id" => $quizCopy->id
                    ]);
                }
            }

            //link tags
            $linkedTags =$quiz->tags->lists('tag','id')->toArray();

            //set tags
            foreach ($linkedTags as $tag){
                if ($tag && QuizzesTags::isUnique( $quizCopy->id, $tag)){
                    QuizzesTags::create([
                        'quiz_id'=>$quizCopy->id,
                        'tag'=>$tag
                    ]);
                }
            }

            return redirect()->route('client-portal.photo-bombed.quizzes.index');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }

    function dataTables(Request $request){
        return \DataTables::of($request->clientPortal->quizzes()->where('is_photobombed',1)->with('games'))->make(true);
    }

    function tags(Request $request){
        $search = $request->get('search');
        $search = '%'.$search.'%';
        return QuizzesTags::where('tag','like',$search)->groupBy('tag')->lists('tag')->toJson();
    }

    function destroy($id, Request $request){
        $id = (int)$id;
        $quiz = $request->clientPortal->quizzes->where('is_photobombed',1)->where('id',$id)->first();

        if(!$quiz){
            return redirect()->route('client-portal.photo-bombed.quizzes.index')->with('error','This quiz does not exist on this client portal.');
        }

        // soft-delete question
        $quiz->delete();
    }

    function destroyBulk(Request $request){
        $ids = $request->ids;

        foreach ($ids as $id){
            $id = (int)$id;
            $quiz = $request->clientPortal->where('is_photobombed',1)->quizzes->where('id',$id)->first();

            if($quiz){
                // soft-delete question
                $quiz->delete();
            }
        }
    }

}