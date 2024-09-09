<?php

namespace App\Console\Commands\User;

use App\Entity\User;
use Illuminate\Console\Command;
use App\UseCases\Auth\RegisterService;

class VerifyCommand extends Command
{
    protected $signature = 'user:verify {email}';

    protected $description = 'Подтвердить пользователя по электронной почте';

    private $service;

    public function __construct(RegisterService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle()
    {
        $email = $this->argument('email');

        if (!$user = User::where('email', $email)->first()) {
            $this->error("Undefined user with email: {$email}.");
            return;
        }

        try {
            $this->service->verify($user->id);
        } catch (\DomainException $e) {
            $this->error("Error: {$e->getMessage()}.");
            return;
        }

        $this->info("Success: {$email}.");
        return true;
    }
}
