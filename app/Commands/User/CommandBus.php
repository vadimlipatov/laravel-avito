<?php

namespace App\Commands\User;

class CommandBus
{
  public function handle($command)
  {
    $class = get_class($command) . 'Handler';

    $handler = app()->make($class);

    $handler($command);
  }
}
