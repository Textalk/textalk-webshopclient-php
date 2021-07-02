<?php

namespace Textalk\WebshopClient;

use PHPUnit\Framework\TestCase;

class ApiClassTest extends TestCase
{
    public function testInstatiate()
    {
        $class = new ApiClass('Foo');

        // No classname validation is done before first call.
        $this->assertInstanceOf('Textalk\WebshopClient\ApiClass', $class);
    }

    public function testContextCalls()
    {
        $context      = new ApiClass('Context');
        $context_data = $context->get(true);

        $this->assertIsArray($context_data);
        $this->assertArrayHasKey('language', $context_data);
    }

    public function testGivenConnection()
    {
        $connection   = new Connection();
        $context      = new ApiClass('Context', $connection);
        $context_data = $context->get(true);

        $this->assertIsArray($context_data);
        $this->assertArrayHasKey('language', $context_data);
    }

    public function testConnectionGetMethod()
    {
        $connection = new Connection();
        $class = $connection->getApiClass('Foo');

        // No classname validation is done before first call.
        $this->assertInstanceOf('Textalk\WebshopClient\ApiClass', $class);
    }

    public function testMagicMethod()
    {
        $connection = new Connection();
        $class = $connection->Foo;

        // No classname validation is done before first call.
        $this->assertInstanceOf('Textalk\WebshopClient\ApiClass', $class);
    }
}
