<?php

declare(strict_types=1);

namespace Tests;

use SheryanEu\LaravelPopo\PopoServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

/**
 * @internal
 */
class TestCase extends OrchestraTestCase
{
    /**
     * {@inheritDoc}
     */
    public function seed($class = 'DatabaseSeeder')
    {
    }

    /**
     * {@inheritDoc}
     */
    protected function getPackageProviders($app): array
    {
        return [
            PopoServiceProvider::class,
        ];
    }
}
