<?php

declare(strict_types=1);

namespace SheryanEu\LaravelPopo;

use Illuminate\Support\ServiceProvider;
use SheryanEu\LaravelPopo\Commands\MakePopoCommand;

class PopoServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            MakePopoCommand::class,
        ]);
    }
}