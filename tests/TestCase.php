<?php

namespace Dotswan\ModulesAutoDiscover\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function defineEnvironment($app): void
    {
        $app['config']->set('modules.paths.generator.config.path', 'Config');
        $app['config']->set('modules.paths.generator.lang.path', 'Lang');
    }
}
