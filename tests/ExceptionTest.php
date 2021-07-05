<?php

namespace Textalk\WebshopClient;

use PHPUnit\Framework\TestCase;
use Textalk\WebshopClient\Mock\ConnectionMock;
use Textalk\WebshopClient\Mock\RequestMock;
use Tivoka\Client;

class ExceptionTest extends TestCase
{
    public function testUnspecifiedExceptionCode()
    {
        $request = new RequestMock('Test.testMethod');
        $request->error = 424242;
        try {
            throw Exception::factory(new ConnectionMock(), $request);
        } catch (Exception $e) {
            $this->assertInstanceOf('Textalk\WebshopClient\Exception', $e);
        }

        $this->assertNotNull($e, 'A Textalk\WebshopClient\Exception should be thrown.');
    }

    public function testGetMessageWithData()
    {
        try {
            $connection = new Connection(array('webshop' => 22222));

            // Make an invalid set call.
            $connection->Context->set(array('webshop' => 'Bad shop'), true);
        } catch (Exception $e) {
            $string = $e->getMessage();
            $data   = $e->getData();

            $this->assertRegExp('/^Context.set: Invalid params/', $string);
            $this->assertRegExp('/Error data:/', $string);
            $this->assertRegExp('/On request:/', $string);
        }

        $this->assertNotNull($e, 'A Textalk\WebshopClient\Exception should be thrown.');
    }
}
