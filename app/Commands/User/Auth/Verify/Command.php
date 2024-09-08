<?php

namespace App\Commands\User\Auth\Verify;

use Illuminate\Foundation\Http\FormRequest;

class Command
{
  public $id;

  public function __construct($id)
  {
    $this->id = $id;
  }
}
