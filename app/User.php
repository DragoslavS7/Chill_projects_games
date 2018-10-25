<?php

namespace App;

use App\Traits\ModelValidation;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use ModelValidation;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name',
        'email', 'password',
        'role', 'client_portal_id',
        'phone','department',
        'location','employee_id','supervisor','is_active',
        'verification_token','is_verified','is_saved_to_lrs'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $rules = [
        'first_name' => 'required|max:255',
        'last_name' => 'required|max:255',
        'password' => 'required|min:6|confirmed',
        'role' => 'required',
        'client_portal_id' => 'required_if:role,admin|required_if:role,player'
    ];

    const LOGIN_RULES = [
        'email' => 'required|email|max:255',
        'password' => 'required|min:6'
    ];

    const ROLES = [
        'uberAdmin' => 'uber_admin',
        'admin' => 'admin',
        'viewer' => 'viewer',
        'player' => 'player'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->rules['email'] = "required|email|max:255|uniqueEmailClientPortalPair:{$this->client_portal_id}";

    }

    public function getUpdateRules(){
        return [
            'first_name' => 'sometimes|required|max:255',
            'last_name' => 'sometimes|required|max:255',
            'password' => 'min:6|confirmed',
            'role' => 'required',
            'client_portal_id' => 'required_if:role,player|required_if:role,viewer',
        ];
    }

    public function getClientPortalRules(){
        return [
            'first_name' => 'sometimes|required|max:255',
            'last_name' => 'sometimes|required|max:255',
            'email' => "sometimes|required|email|max:255|unique:users,email,{$this->id}",
            'password' => 'min:6|confirmed'
        ];
    }

    public function isUberAdmin(){
        return $this->hasRole(User::ROLES['uberAdmin']);
    }

    public function isUberAdminOrAdmin(){
        return $this->hasRole([
            User::ROLES['uberAdmin'],
            User::ROLES['admin']
        ]) ;
    }

    public function isAdmin(){
        return $this->hasRole([
            User::ROLES['admin']
        ]) ;
    }

    public function hasRole($role){
        $res = false;
        if(is_array($role)){
            $res = in_array($this->role, $role);
        }else{
            $res = $this->role == $role;
        }
        return $res;
    }

    public function getFullNameAttribute(){
        return $this->first_name . " " . $this->last_name;
    }

    public function passwordToken(){
        return $this->hasOne('App\PasswordReset','email','email');
    }

    public function clientPortal(){
        return $this->belongsTo('\App\ClientPortal', 'client_portal_id');
    }

    public function gameSessions(){
        return $this->hasMany('App\GameSessions','user_id','id');
    }

    public function quizSessions(){
        return $this->hasMany('App\QuizSessions');
    }

    public function questionSessions(){
        return $this->hasMany('App\QuestionSessions');
    }

    public function games(){
        return $this->hasMany('App\Game','author_id','id');
    }

    public function quizzes(){
        return $this->hasMany('App\Quiz','author_id','id');
    }

    public function locationTags(){
        return $this->belongsToMany('App\ClientPortalLocationTags', 'user_location_tags', 'user_id', 'client_portal_location_tag_id');
    }

    public function departmentTags(){
        return $this->belongsToMany('App\ClientPortalDepartmentTags', 'user_department_tags', 'user_id', 'client_portal_department_tag_id');
    }

    public function getUsers($clientPortalId){
        $clientPortal = \App\ClientPortal::find($clientPortalId);

        $error = [];

        $numUsers = 0;
        $numUsers = $clientPortal->users()->count();

        return $numUsers;
    }

    public function getAdminAnalytics($clientPortalId){
        $clientPortal = \App\ClientPortal::find($clientPortalId);

        $defaullUser = [
            'user_id'=>'0',
            'name'=>'',
            'email'=>'',
            'game_created'=>0,
            'games_deployed'=>'0',
            'quizzes_created'=>0,
        ];

        $user = $clientPortal->users()
            ->join('games','users.id','=','games.author_id')
            ->groupBy('games.author_id')
            ->selectRaw('users.id as user_id, 
                        concat(first_name," ",last_name) as name,
                        email,
                        count(games.id) as game_created,
                        sum( if(games.is_active=1,1,0))as games_deployed,
                        (select count(*) from quizzes where users.id = quizzes.author_id) as quizzes_created
                        ');

        if($user->get()->count()){
            return $user->get();
        }else{
            return $defaullUser;
        }
    }
}
