<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand as LaravelServeCommand;

class ServeCommand extends LaravelServeCommand
{
    protected function serverCommand()
    {
        $command = parent::serverCommand();

        // PHP must receive these limits before it parses incoming uploads.
        array_splice($command, 1, 0, [
            '-d', 'upload_max_filesize=10M',
            '-d', 'post_max_size=16M',
        ]);

        return $command;
    }
}
