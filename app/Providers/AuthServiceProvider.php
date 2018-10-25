<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy'
    ];

    public function __construct(){
        if( isUberAdminPortal() ){
            $this->policies += [];
        }else{
            // Client portal
            $this->policies += [
                'App\Game' => 'App\Policies\ClientPortal\GamePolicy',
                'App\GameTemplate' => 'App\Policies\ClientPortal\GameTemplatePolicy',
                'App\Quiz' => 'App\Policies\ClientPortal\QuizPolicy',
                'App\Question' => 'App\Policies\ClientPortal\QuestionPolicy',
                'App\User' => 'App\Policies\ClientPortal\User'
            ];
        }
    }

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

    }
}
