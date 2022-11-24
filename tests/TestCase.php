<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * This method use ReflectionMethod and reports information about a method
     * You can access to protected and private methods in the object given.
     *
     * @throws \ReflectionException
     */
    protected function invokeMethod($object, $methodName, array $parameters = []): mixed
    {
        return (new \ReflectionMethod($object, $methodName))->invokeArgs($object, $parameters);
    }
}
