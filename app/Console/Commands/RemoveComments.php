<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class RemoveComments extends Command
{
    // Название команды
    protected $signature = 'remove:comments {file}';

    // Описание команды
    protected $description = 'Удалить все комментарии из PHP файла';

    public function handle()
    {
        $file = $this->argument('file');

        // Проверяем, существует ли файл
        if (!File::exists($file)) {
            $this->error("Файл не найден: {$file}");
            return;
        }

        // Читаем содержимое файла
        $content = File::get($file);

        // Удаляем однострочные (//) и многострочные (/* ... */) комментарии
        $contentWithoutComments = preg_replace('!/\*.*?\*/!s', '', $content); // Многострочные комментарии
        $contentWithoutComments = preg_replace('/\/\/.*?(\r?\n)/', '$1', $contentWithoutComments); // Однострочные комментарии

        // Записываем модифицированное содержимое обратно в файл
        File::put($file, $contentWithoutComments);

        $this->info('Комментарии были успешно удалены!');
    }
}
