<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class RegisterUberAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:register-uber-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new uber-admin user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userData = [];
        $userData['first_name'] = $this->ask('Please enter uber-admin first name');
        $userData['last_name'] = $this->ask('Please enter uber-admin last name');
        $userData['email'] = $this->ask('Please enter uber-admin email');
        $userData['password'] = $this->ask('Please enter uber-admin password');
        $userData['password_confirmation'] = $this->ask('Please confirm the password');
        $userData['role'] = 'uber_admin';
        $userData['is_verified'] = 1;
        $userData['verification_token'] = hash_hmac('sha256', str_random(40), config('app.key'));

        $user = new User();

        if ($user->validate($userData)) {
            $userData['password'] = bcrypt($userData['password']);
            $user->fill($userData);
            $user->save();

            $this->info("uber-admin {$userData['first_name']} is created.");

        } else {
            $this->error("Provided data is not valid:");
            $this->warn(json_encode($user->errors(), JSON_PRETTY_PRINT));
        }
    }
}
