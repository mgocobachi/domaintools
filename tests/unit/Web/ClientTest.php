<?php
namespace Domaintools\Tests\Web;

use stdClass;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpStub;
use Domaintools\Web\Client;

final class ClientTest extends TestCase
{
    /**
     * Setup the initialization of the data before each test
     */
    public function setUp(): void
    {
        $this->httpStub = $this->createMock(HttpStub::class);
        $this->client   = new Client($this->httpStub);

        $this->initDefaults();
    }

    /**
     * Destroy the data initialized after each test
     */
    public function tearDown(): void
    {
        $this->client   = null;
        $this->httpStub = null;
    }

    /**
     * @cover \Domaintools\Web\Client::get()
     */
    public function testGet(): void
    {
        $actual = $this->client->get('/', []);

        $this->assertInstanceOf(stdClass::class, $actual);
    }

    /**
     * @cover \Domaintools\Web\Client::generateEndpoint()
     */
    public function testGenerateEndpoint(): void
    {
        $expected = 'query=domaintools';

        $actual = $this->client->generateEndpoint($expected);

        $this->assertContains($expected, $actual);
    }

    /**
     * Define the default methods for the stubs
     *
     * @return void
     */
    protected function initDefaults(): void
    {
        $this->httpStub->method('request')->willReturn($this->mockResponse());
    }

    /**
     * Creates a response mock object
     *
     * @return object
     */
    protected function mockResponse()
    {
        return new class {
            public function getBody()
            {
                return new class {
                    public function getContents()
                    {
                        return json_encode(new class {
                            public $response;

                            public function __construct()
                            {
                                $this->response = new stdClass;
                            }
                        });
                    }
                };
            }
        };
    }
}
