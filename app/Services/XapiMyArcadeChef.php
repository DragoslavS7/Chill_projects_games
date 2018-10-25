<?php

namespace App\Services;

use Xabbuh\XApi\Client\XApiClientBuilder;
use Http\Adapter\Guzzle6\Client;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Xabbuh\XApi\Model\Context;
use Xabbuh\XApi\Model\ContextActivities;
use Xabbuh\XApi\Model\StatementId;
use Xabbuh\XApi\Model\Definition;
use Xabbuh\XApi\Model\Extensions;
use Xabbuh\XApi\Model\Score;
use Xabbuh\XApi\Model\Statement;
use Xabbuh\XApi\Model\StatementsFilter;
use Xabbuh\XApi\Model\Verb;
use Xabbuh\XApi\Model\IRI;
use Xabbuh\XApi\Model\Agent;
use Xabbuh\XApi\Model\InverseFunctionalIdentifier;
use Xabbuh\XApi\Model\LanguageMap;
use Xabbuh\XApi\Model\Activity;
use Xabbuh\XApi\Model\Result;

class XapiMyArcadeChef
{

    private $builder;
    private $xAPIClient;
    private $statementsApiClient;
    private $stateApiClient;
    private $activityProfileApiClient;
    private $agentProfileApiClient;

    const ACTIONS = [
        'createGame' => [
            'url' => 'http://activitystrea.ms/schema/1.0/create',
            'languageMap' => [
                'en-US' => 'created game'
            ],
            'id' => 'http://activitystrea.ms/schema/1.0/game/',
            'type'=>'http://activitystrea.ms/schema/1.0/game'
        ],
        'startGame' => [
            'url' => 'http://activitystrea.ms/schema/1.0/start',
            'languageMap' => [
                'en-US' => 'started playing game:'
            ],
            'id' => 'http://activitystrea.ms/schema/1.0/game/',
            'type'=>'http://activitystrea.ms/schema/1.0/game'
        ],
        'createQuestion' => [
            'url' => 'http://activitystrea.ms/schema/1.0/create',
            'languageMap' => [
                'en-US' => 'created question'
            ],
            'id' => 'http://adlnet.gov/expapi/activities/question/',
            'type'=>'http://adlnet.gov/expapi/activities/question'
        ],
        'createAnswer' => [
            'url' => 'https://myarcadechef/actions/answers/create',
            'languageMap' => [
                'en-US' => 'created answer'
            ],
            'id' => 'https://myarcadechef/answers/',
            'type'=>'http://id.tincanapi.com/activitytype/solution'
        ],
        'createQuiz' => [
            'url' => 'http://activitystrea.ms/schema/1.0/create',
            'languageMap' => [
                'en-US' => 'created quiz'
            ],
            'id' => 'http://adlnet.gov/expapi/activities/assessment/',
            'type'=>'http://adlnet.gov/expapi/activities/assessment'
        ],
        'startQuiz' => [
            'url' => 'http://activitystrea.ms/schema/1.0/start',
            'languageMap' => [
                'en-US' => 'started playing quiz'
            ],
            'id' => 'http://adlnet.gov/expapi/activities/assessment/',
            'type'=>'http://adlnet.gov/expapi/activities/assessment'
        ],
        'createUser' => [
            'url' => 'http://activitystrea.ms/schema/1.0/create',
            'languageMap' => [
                'en-US' => 'created user'
            ],
            'id' => 'http://activitystrea.ms/schema/1.0/person/',
            'type'=>'http://activitystrea.ms/schema/1.0/person'
        ],
        'finishGame' => [
            'url' => 'http://adlnet.gov/expapi/verbs/completed',
            'languageMap' => [
                'en-US' => 'finished game'
            ],
            'id' => 'http://activitystrea.ms/schema/1.0/game/',
            'type'=>'http://activitystrea.ms/schema/1.0/game'
        ],
        'finishQuiz' =>[
            'url' => 'http://adlnet.gov/expapi/verbs/completed',
            'languageMap' => [
                'en-US' => 'finished quizz'
            ],
            'id' => 'http://adlnet.gov/expapi/activities/assessment/',
            'type'=>'http://adlnet.gov/expapi/activities/assessment'
        ],
        'game' =>[
            'url' => 'http://adlnet.gov/expapi/verbs/experienced',
            'languageMap' => [
                'en-US' => 'played game'
            ],
            'id' => 'http://activitystrea.ms/schema/1.0/game/',
            'type'=>'http://activitystrea.ms/schema/1.0/game'
        ],
        'quiz' =>[
            'url' => 'http://adlnet.gov/expapi/verbs/experienced',
            'languageMap' => [
                'en-US' => 'took quiz'
            ],
            'id' => 'http://adlnet.gov/expapi/activities/assessment/',
            'type'=>'http://adlnet.gov/expapi/activities/assessment'
        ],
        'answeredQuestion' =>[
            'url' => 'http://adlnet.gov/expapi/verbs/answered',
            'languageMap' => [
                'en-US' => 'answered question'
            ],
            'id' => 'http://adlnet.gov/expapi/activities/question/',
            'type'=>'http://adlnet.gov/expapi/activities/question'
        ]
    ];

    /**
     * XapiMyArcadeChef constructor.
     */

    public function __construct()
    {
        $this->builder = new XApiClientBuilder();

        $this->xAPIClient = $this->builder->setHttpClient(new Client())
            ->setRequestFactory(new GuzzleMessageFactory())
            ->setBaseUrl(env('LRS_BASE_URL'))
            ->setAuth(env('LRS_CLIENT_KEY'), env('LRS_CLIENT_SECRET'))
            ->build();

        $this->statementsApiClient = $this->xAPIClient->getStatementsApiClient();
        $this->stateApiClient = $this->xAPIClient->getStateApiClient();
        $this->activityProfileApiClient = $this->xAPIClient->getActivityProfileApiClient();
        $this->agentProfileApiClient = $this->xAPIClient->getAgentProfileApiClient();
    }


    private  function _sendCreate($actorMbox, $actorName, $verbAction, $definitionName, $definitionDescription, $definitionType, $activityId){
        $actor = new Agent(InverseFunctionalIdentifier::withMbox(IRI::fromString("mailto:$actorMbox")), $actorName);
        $verb = new Verb(IRI::fromString($verbAction['url']), LanguageMap::create($verbAction['languageMap']));

        $definition = new Definition(LanguageMap::create(['en-US' => $definitionName]),
                                     LanguageMap::create(['en-US' => $definitionDescription]),
                                     IRI::fromString($definitionType));

        $activity = new Activity(IRI::fromString($activityId), $definition);

        $insert_statement = new Statement(null, $actor, $verb, $activity);
        $result = $this->statementsApiClient->storeStatement($insert_statement);

        return $result;
    }

    private function _sendFinish($actorMbox,
                                 $actorName,
                                 $verbAction,
                                 $definitionName,
                                 $definitionDescription,
                                 $definitionType,
                                 $activityId,
                                 $score=[ 'scale' => null, 'raw' => null, 'min' => null, 'max' => null],
                                 $success=false,
                                 $completion=false,
                                 $response=null,
                                 $duration=null,
                                 $parentActivityId=null,
                                 $parentActivityDefinitionName=null,
                                 $parentActivityDefinitionDescription=null,
                                 $groupActivityId=null,
                                 $groupActivityDefinitionName=null,
                                 $groupActivityDefinitionDescription=null
){

        $actor = new Agent(InverseFunctionalIdentifier::withMbox(IRI::fromString("mailto:$actorMbox")), $actorName);
        $verb = new Verb(IRI::fromString($verbAction['url']), LanguageMap::create($verbAction['languageMap']));

        $definition = new Definition(
            LanguageMap::create(['en-US' => $definitionName]),
            LanguageMap::create(['en-US' => $definitionDescription]),
            IRI::fromString($definitionType)
        );

        $activity = new Activity( IRI::fromString($activityId), $definition);

        $gameScore = new Score($score['scale'], $score['raw'], $score['min'], $score['max']);

        $result =  new Result($gameScore, $success, $completion, $response, $duration);


        $contextActivities = new ContextActivities();
        $context = new Context();

        if($parentActivityId){
            $parentActivityDefinition = new Definition(
                LanguageMap::create(['en-US' => $parentActivityDefinitionName]),
                LanguageMap::create(['en-US' => $parentActivityDefinitionDescription])
            );
            $parentActivity = new Activity( IRI::fromString($parentActivityId), $parentActivityDefinition);
            $contextActivities = $contextActivities->withAddedParentActivity($parentActivity);

        }
        if($groupActivityId){
            $groupActivityDefinition = new Definition(
                LanguageMap::create(['en-US' => $groupActivityDefinitionName]),
                LanguageMap::create(['en-US' => $groupActivityDefinitionDescription])
            );
            $groupActivity = new Activity( IRI::fromString($groupActivityId), $groupActivityDefinition);
            $contextActivities = $contextActivities->withAddedGroupingActivity($groupActivity);
        }

        $context = $context->withContextActivities($contextActivities);

        $insert_statement = new Statement(null, $actor, $verb, $activity, $result,null,null,null,$context);

        $result = $this->statementsApiClient->storeStatement($insert_statement);
        return $result;
    }

    public function createGame($user, $game){
        $activityId = XapiMyArcadeChef::ACTIONS['createGame']['id'] . $game->id;
        $activityType = XapiMyArcadeChef::ACTIONS['createGame']['type'];

        $this->_sendCreate($user->email,
                          $user->full_name,
                          XapiMyArcadeChef::ACTIONS['createGame'],
                          $game->name,
                          $game->description,
                          $activityType,
                          $activityId
            );
    }

    public function startGame($user, $game){
        $activityId = XapiMyArcadeChef::ACTIONS['startGame']['id'] . $game->id;
        $activityType = XapiMyArcadeChef::ACTIONS['startGame']['type'];

        $this->_sendCreate($user->email,
            $user->full_name,
            XapiMyArcadeChef::ACTIONS['startGame'],
            $game->name,
            $game->description,
            $activityType,
            $activityId
        );
    }

    public function createQuestion($user, $question){
        $activityId = XapiMyArcadeChef::ACTIONS['createQuestion']['id'] . $question->id;
        $activityType = XapiMyArcadeChef::ACTIONS['createQuestion']['type'];

        $this->_sendCreate($user->email,
            $user->full_name,
            XapiMyArcadeChef::ACTIONS['createQuestion'],
            $question->name,
            $question->description,
            $activityType,
            $activityId
        );
    }

    /**
     * @param $user
     * @param $answer
     */
    public function createAnswer($user, $answer){
        $activityId = XapiMyArcadeChef::ACTIONS['createAnswer']['id'] . $answer->id;
        $answerValue = '';
        $activityType = XapiMyArcadeChef::ACTIONS['createAnswer']['type'];
        if($answer->type == 'slider'){
            $answerValue = $answer->correct_value;
        }
        else{
            $answerValue = $answer->answer;
        }

        $this->_sendCreate($user->email,
            $user->full_name,
            XapiMyArcadeChef::ACTIONS['createAnswer'],
            $answerValue,
            'is correct answer: ' . $answer->is_correct,
            $activityType,
            $activityId
        );
    }

    public function createQuiz($user, $quiz){
        $activityId = XapiMyArcadeChef::ACTIONS['createQuiz']['id'] . $quiz->id;
        $activityType = XapiMyArcadeChef::ACTIONS['createQuiz']['type'];

        $this->_sendCreate($user->email,
            $user->full_name,
            XapiMyArcadeChef::ACTIONS['createQuiz'],
            $quiz->name,
            $quiz->description,
            $activityType,
            $activityId
        );
    }

    public function startQuiz($user, $quiz){
        $activityId = XapiMyArcadeChef::ACTIONS['startQuiz']['id'] . $quiz->id;
        $activityType = XapiMyArcadeChef::ACTIONS['startQuiz']['type'];

        $this->_sendCreate($user->email,
            $user->full_name,
            XapiMyArcadeChef::ACTIONS['startQuiz'],
            $quiz->name,
            $quiz->description,
            $activityType,
            $activityId
        );
    }

    public function createUser($user, $newUser){
        $activityId = XapiMyArcadeChef::ACTIONS['createUser']['id'] . $newUser->id;
        $activityType = XapiMyArcadeChef::ACTIONS['createUser']['type'];
        $siteUrl = env('APP_URL');

        $this->_sendCreate($user->email,
            $user->full_name,
            XapiMyArcadeChef::ACTIONS['createUser'],
            $newUser->full_name,
            "$newUser->role - $newUser->email, is created for {$newUser->clientPortal->company_name} company on {$newUser->clientPortal->sub_domain}.$siteUrl",
            $activityType,
            $activityId
        );
    }

    public function finishGame($user, $game, $points, $didTheUserCompletedTheGame, $duration){
        $points = (float) $points;

        $activityId = XapiMyArcadeChef::ACTIONS['finishGame']['id'] . $game->id;
        $activityType = XapiMyArcadeChef::ACTIONS['finishGame']['type'];

        $score = [
            'scale' => (float) $game->max_score == 0 ? null : $points / (float) $game->max_score,
            'raw' => $points,
            'min' => 0,
            'max' => $game->max_score
            ];

        $this->_sendFinish($user->email,
            $user->full_name,
            XapiMyArcadeChef::ACTIONS['finishGame'],
            $game->name,
            $game->description,
            $activityType,
            $activityId,
            $score,
            $points >= (float) $game->score_to_win,
            $didTheUserCompletedTheGame,
            null,
            $duration
        );
    }

    public function finishQuiz($user, $quiz, $game, $points, $didTheUserCompleteTheQuiz, $duration){

        $points = (float) $points;

        $activityId = XapiMyArcadeChef::ACTIONS['finishQuiz']['id'] . $quiz->id;
        $parentActivityId = XapiMyArcadeChef::ACTIONS['game']['id'] . $game->id;
        $activityType = XapiMyArcadeChef::ACTIONS['finishQuiz']['type'];

        $score = [
            'scale' => (float) $quiz->max_score == 0 ? null : $points / (float) $quiz->max_score,
            'raw' => $points,
            'min' => 0,
            'max' => $quiz->max_score
        ];

        $this->_sendFinish($user->email,
            $user->full_name,
            XapiMyArcadeChef::ACTIONS['finishQuiz'],
            $quiz->name,
            $quiz->description,
            $activityType,
            $activityId,
            $score,
            $points >= (float) $quiz->score_to_win,
            $didTheUserCompleteTheQuiz,
            null,
            $duration,
            $parentActivityId,
            $game->name,
            $game->description
        );
    }

    public function answerQuestion($user, $question, $answer, $quiz, $game, $didTheUserAnswerQuestion, $duration){

        $activityId = XapiMyArcadeChef::ACTIONS['answeredQuestion']['id'] . $question->id;
        $parentActivityId = XapiMyArcadeChef::ACTIONS['quiz']['id'] . $quiz->id;
        $groupActivityId = XapiMyArcadeChef::ACTIONS['game']['id'] . $game->id;
        $activityType = XapiMyArcadeChef::ACTIONS['answeredQuestion']['type'];

        $correct = false;
        if($answer->is_correct){
            if($answer->is_correct ==  1){
                $correct = true;
            }
            else if($answer->is_correct ==  0){
                $correct = false;
            }
        }

        $this->_sendFinish($user->email,
            $user->full_name,
            XapiMyArcadeChef::ACTIONS['answeredQuestion'],
            $question->name,
            $question->description,
            $activityType,
            $activityId,
            null,
            $correct,
            $didTheUserAnswerQuestion,
            $answer->answer,
            $duration,
            $parentActivityId,
            $quiz->name,
            $quiz->description,
            $groupActivityId,
            $game->name,
            $game->description
        );
    }

    public function getGames(){
        $verb = new Verb(IRI::fromString(XapiMyArcadeChef::ACTIONS['finishGame']['url']), LanguageMap::create(XapiMyArcadeChef::ACTIONS['finishGame']['languageMap']));
        $filter = new StatementsFilter();
        $filter->byVerb($verb);

        return $this->statementsApiClient->getStatements($filter);
    }
}