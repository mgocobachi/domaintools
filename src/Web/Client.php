<?php
namespace Domaintools\Web;

use stdClass;
use Domaintools\Contracts\Web\ClientInterface as WebClientInterface;
use GuzzleHttp\ClientInterface;

class Client implements WebClientInterface
{
    /**
     * The default host of the Domaintools API
     *
     * @var string
     */
    protected const HOST = 'api.domaintools.com';

    /**
     * The Http client (GuzzleHttp)
     *
     * GuzzleHttp\Client
     */
    protected $http = null;

    /**
     * Constructor
     */
    public function __construct(ClientInterface $http)
    {
        $this->http = $http;
    }

    /**
     * Returns the response from the API using the GET HTTP method
     *
     * @return stdClass
     */
    public function get(string $endpoint, array $params = []): stdClass
    {
        return json_decode(
            $this->http->request(
                'GET',
                $this->generateEndpoint($endpoint),
                $this->defaultOptions()
            )->getBody()->getContents()
        )->response;
    }

    /**
     * Generate the endpoint API using the uri
     *
     * @param string $uri The resource to hit
     *
     * @return string
     */
    public function generateEndpoint(string $uri): string
    {
        return 'https://' . static::HOST . $uri;
    }

    /**
     * Define the common options for all the API calls
     *
     * @return array
     */
    public function defaultOptions(): array
    {
        return [
            'allow_redirects' => false,
            'protocols'       => ['https'],
        ];
    }
}
