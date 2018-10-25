<?php

namespace App;

use App\Traits\ModelValidation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ClientPortal extends Authenticatable
{
    use ModelValidation, SoftDeletes;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'default_admin_id',
        'company_name', 'sub_domain',
        'number_of_admins',
        'address',
        'fax', 'website',
        'logo', 'custom_style',
        'is_costumer_service_available',
        'is_enabled','show_index'
    ];

    public $rules = [
        'company_name' => 'min:3',
        'logo' => 'image|mimes:jpeg,png,jpg,gif,svg',
        'sub_domain' => 'min:3',
    ];

    const CREATION_RULES = [
        'company_name' => 'required|min:3',
        'sub_domain' => 'required|min:3|unique:client_portals',
        'first_name' => 'required|max:255',
        'last_name' => 'required|max:255',
        'email' => 'required|email|max:255|unique:users',
        'number_of_admins' => 'required|numeric|min:1',
        'assign_templates' => 'required|min:3',
        'logo' => 'image|mimes:jpeg,png,jpg,gif,svg',
    ];

    public $validationMessages = [
        'assign_templates.min' => 'You will need to assign at least one template.',
    ];

    protected $attributeNames = [];

    public function getUpdateRules(){
        return [
            'company_name' => 'sometimes|required|min:3',
            'sub_domain' => "sometimes|required|min:3|unique:client_portals,sub_domain,{$this->id}",
            'first_name' => 'sometimes|required|max:255',
            'last_name' => 'sometimes|required|max:255',
            'email' => "sometimes|required|email|max:255|unique:users,email,{$this->id}",
            'number_of_admins' => 'sometimes|required|numeric|min:1',
            'assign_templates' => 'sometimes|required|min:3',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ];
    }

    public function gameTemplates(){
        return $this->belongsToMany('App\GameTemplate',
                                     'client_portals_game_templates',
                                 'client_portal_id',
                                  'game_template_id');
    }

    public function users(){
        return $this->hasMany('App\User');
    }

    public function defaultAdmin() {
        return $this->belongsTo('App\User');
    }

    public function questions(){
        return $this->hasMany('App\Question');
    }

    public function quizzes(){
        return $this->hasMany('App\Quiz');
    }

    public function games(){
        return $this->hasMany('App\Game');
    }

    public function locationTags(){
        return $this->hasMany('App\ClientPortalLocationTags','client_portal_id','id');
    }

    public function departmentTags(){
        return $this->hasMany('App\ClientPortalDepartmentTags','client_portal_id','id');
    }

    function getCustomStyleAttribute($value){
        $result = [];

        if($value){
            $result = json_decode($value);
        }

        return $result;
    }

    function setCustomStyleAttribute($value){
        $result = null;

        if($value){
            $result = json_encode($value);
        }

        $this->attributes['custom_style'] = $result;
    }

    function baseUrl(){
        $protocol = 'http://';

        if (\Request::secure()) {
            $protocol = 'https://';
        }

        return $protocol . $this->sub_domain . ".". env('APP_URL');
    }
}
