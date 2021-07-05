<?php

namespace Textalk\WebshopClient;

use PHPUnit\Framework\TestCase;

class ApiInstanceTest extends TestCase
{
    public function testInstantiationOfFoo()
    {
        $instance = new ApiInstance('Foo', 'bar');

        // No classname validation is done before first call.
        $this->assertInstanceOf('Textalk\WebshopClient\ApiInstance', $instance);
    }

    public function testGetArticleData()
    {
        $connection = new Connection(array('webshop' => 22222));
        $article    = new ApiInstance('Article', 12565609, $connection);

        // Get all data
        $article_data = $article->get(array('name' => 'sv', 'articlegroup' => true));
        $this->assertArrayHasKey('name', $article_data);
    }

    public function testConnectionGetMethod()
    {
        $connection = new Connection();
        $class = $connection->getApiInstance('Foo', 'bar');

        // No classname validation is done before first call.
        $this->assertInstanceOf('Textalk\WebshopClient\ApiInstance', $class);
    }

    public function testMagicMethod()
    {
        $connection = new Connection();
        $class = $connection->Foo('bar');

        // No classname validation is done before first call.
        $this->assertInstanceOf('Textalk\WebshopClient\ApiInstance', $class);
    }
}
