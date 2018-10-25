<?php

namespace App;

use App\Traits\ModelValidation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class GameTemplate extends Authenticatable
{
    use ModelValidation, SoftDeletes;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'source',
        'source_url', 'description',
        'genre', 'template_icon',
        'screenshot', 'is_active',
        'video_url','demo_url','is_default'
    ];

    public $rules = [
        'name' => 'required|min:3',
        'template_icon' => 'image|mimes:jpeg,png,jpg,gif,svg',
        'screenshot' => 'image|mimes:jpeg,png,jpg,gif,svg',
        'source' => 'required|present',
    ];

    protected $attributeNames = [
        'screenshot' => 'Screen Shoot',
        'template_icon' => 'Template Icon',
    ];

    public function clientPortals(){
        return $this->belongsToMany('App\ClientPortal',
            'client_portals_game_templates',
        'game_template_id',
         'client_portal_id'
            );
    }

    public function instances(){
        return $this->belongsToMany('App\GameTemplate', 'client_portals_game_templates');
    }

    static function isNameUnique($gameTemplateName){
        return GameTemplate::where('name', $gameTemplateName)->count() == 0;
    }

    public function isPhotobombed(){
        return  starts_with($this->source, 'pb_');
    }
}
