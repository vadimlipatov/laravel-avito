<?php

namespace App\Console\Commands\User;

use App\Entity\User;
use Illuminate\Console\Command;
use App\UseCases\Auth\RegisterService;

class RoleCommand extends Command
{
    protected $signature = 'user:role {email} {role}';

    protected $description = 'Set role for user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->argument('role');

        if (!$user = User::where('email', $email)->first()) {
            $this->error("Undefined user with email: {$email}.");
            return;
        }

        try {
            $user->changeRole($role);
        } catch (\DomainException $e) {
            $this->error("Error: {$e->getMessage()}.");
            return;
        }

        $this->info('Role is successfully changed.');
        return true;
    }
}
