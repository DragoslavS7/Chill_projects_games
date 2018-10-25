<?php

namespace App;

use App\Traits\ModelValidation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Game extends Authenticatable
{
    use ModelValidation, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_portal_id', 'name', 'description', 'url', 'allow_anonymous_players',
        'required_additional_player_data', 'score_type', 'score_to_win', 'max_score',
        'is_leadboard_visible', 'company_info', 'are_quizzes_randomized', 'quiz_intro_text',
        'game_icon','splash_page_image','is_active','game_template_id','is_saved_to_lrs',
        'author_id','updated_by_id'
    ];

    public $rules = [
        'name'=> 'required|min:3|max:100',
        'select_quiz' => 'required|array|between:1,4',
        'game_icon' => 'image|mimes:jpeg,png,jpg,gif,svg'
    ];

    public function getUpdateRules(){
        return [
            'is_active' => 'sometimes|required',
        ];
    }

    function quizzes(){
        return $this->belongsToMany('App\Quiz', 'games_quizzes');
    }

    function getRequiredAdditionalPlayerDataAttribute($value){
        $result = [];

        if($value){
            $result = json_decode($value);
        }

        return $result;
    }

    function setRequiredAdditionalPlayerDataAttribute($value){
        $result = null;

        if($value){
            $result = json_encode($value);
        }

        $this->attributes['required_additional_player_data'] = $result;
    }

    function template(){
        return $this->belongsTo('App\GameTemplate', 'game_template_id')->withTrashed();
    }

    function clientPortal(){
        return $this->belongsTo('App\ClientPortal', 'client_portal_id');
    }

    public function gameSessions(){
        return $this->hasMany('App\GameSessions','game_id','id');
    }

    public function getGameAvgTime($clientPortalId){
        //set default values
        $gameDefault = [
            [
                'client_portal_id'=>$clientPortalId,
                'average_game_duration_in_seconds'=>0
            ]
        ];

        $game = Game::where('client_portal_id',$clientPortalId)
            ->join('game_sessions', 'games.id', '=','game_sessions.game_id')
            ->selectRaw('client_portal_id, avg(timestampdiff(SECOND, game_start_time, game_end_time)) as average_game_duration_in_seconds')->groupBy('client_portal_id');

        if($game->get()->count()>0){
            return $game->get();
        }else{
            return $gameDefault;
        }

    }

    public function getGameAvgScore($clientPortalId){
        $clientPortal = ClientPortal::find($clientPortalId);

        $error = [];

        if(!$clientPortal){
            $error['error'] = "Client portal with id `$clientPortalId` does not exists.";
            return response($error, 404);
        }

        //set default values
        $gameDefault = [
            [
                'client_portal_id'=>$clientPortalId,
                'average_game_score'=>0
            ]
        ];

        $game = Game::where('client_portal_id',$clientPortalId)
            ->join('game_sessions', 'games.id', '=','game_sessions.game_id')->where('status','finished')
            ->selectRaw('client_portal_id, avg(points) as average_game_score')->groupBy('client_portal_id');

        if($game->get()->count()>0){
            return $game->get();
        }else{
            return $gameDefault;
        }
    }

    static function getGameAnalytics($clientPortalId){
        //set default values
        $gamesDefault = [
            'client_portal_id'=>$clientPortalId,
            'game_analytics'=>[
                'description'=>'',
                'games_started'=>'0',
                'games_completed'=>'0',
                'games_deployed'=>'0',
            ]
        ];

        $games = Game::where('client_portal_id', $clientPortalId)
            ->join('game_sessions', 'games.id', '=','game_sessions.game_id')
            ->selectRaw('description,
                SUM(if(status="started",1,0)) as games_started,
                SUM(if(status="finished",1,0)) as games_completed,
                SUM(1) as games_deployed
             ')
            ->groupBy('description');

        if($games->get()->count()>0){
            $res = [
                'client_portal_id' => $clientPortalId,
                'game_analytics' => $games->get()
            ];

            return $res;
        }else{
            return $gamesDefault;
        }
    }
}
