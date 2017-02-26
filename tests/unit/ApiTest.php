<?php
namespace Domaintools\Tests;

use stdClass;
use PHPUnit\Framework\TestCase;
use Domaintools\Api as DomaintoolsAPI;
use Domaintools\Web\Client;

final class ApiTest extends TestCase
{
    protected $api = null;
    protected $clientStub = null;

    public function setUp()
    {
        $this->clientStub = $this->createMock(Client::class);
        $this->api        = new DomaintoolsAPI('', '', $this->clientStub);
    }

    public function tearDown()
    {
        $this->api        = null;
        $this->clientStub = null;
    }

    public function testInstance()
    {
        $this->assertInstanceOf(DomaintoolsAPI::class, $this->api);
    }

    public function testProfile()
    {
        $this->clientStub->method('get')->willReturn(new stdClass);

        $actual = $this->api->profile('domaintools.com');

        $this->assertInstanceOf(stdClass::class, $actual);
    }

    public function testReputation()
    {
        $expected = new stdClass;
        $expected->risk_score = 0.0;

        $this->clientStub->method('get')->willReturn($expected);

        $actual = $this->api->reputation('domaintools.com');

        $this->assertEquals($expected->risk_score, $actual);
    }

    public function testSearch()
    {
        $expected = new stdClass;
        $expected->results = [];

        $this->clientStub->method('get')->willReturn($expected);

        $actual = $this->api->search('domaintools.com');

        $this->assertEquals($expected->results, $actual->results);
    }
}
