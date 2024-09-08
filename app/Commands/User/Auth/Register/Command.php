<?php

namespace App\Commands\User\Auth\Register;

use Illuminate\Foundation\Http\FormRequest;

class Command
{
  public $name;
  public $email;
  public $password;

  public static function fromRequest(FormRequest $request): self
  {
    return new self(
      $request['name'],
      $request['email'],
      $request['password']
    );
  }
}
