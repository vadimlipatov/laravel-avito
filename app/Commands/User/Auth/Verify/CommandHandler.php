<?php

namespace App\Commands\User\Auth\Verify;

use App\Entity\User;
use App\Mail\Auth\VerifyMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Events\Dispatcher as DispatcherInterface;
use Illuminate\Contracts\Mail\Mailer as MailerInterface;

class CommandHandler
{
  private $mailer;
  private $dispatcher;

  public function __construct(MailerInterface $mailer, DispatcherInterface $dispatcher)
  {
    $this->mailer = $mailer;
    $this->dispatcher = $dispatcher;
  }

  public function __invoke(User $user)
  {
    $user->verify();
  }
}
