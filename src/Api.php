<?php
namespace Domaintools;

use stdClass;
use Domaintools\Contracts\Web\ClientInterface;

class Api
{
    protected const ENDPOINTS = [
        'PROFILE'       => '/v1',
        'REPUTATION'    => '/v1/reputation',
        'SEARCH'        => '/v2/domain-search',
    ];

    /**
     * HTTP calls
     *
     * @var \Domaintools\Http\Client
     */
    protected $http = null;

    /**
     * The user name of the API
     *
     * @var string
     */
    protected $username = '';

    /**
     * The key for the API
     *
     * @var string
     */
    protected $key = '';

    /**
     * Constructor
     */
    public function __construct(
        string $username,
        string $key,
        ClientInterface $http
    ) {
        $this->username = $username;
        $this->key      = $key;
        $this->http     = $http;
    }

    /**
     * Gets the current timestamp
     *
     * @return string
     */
    public function timestamp(): string
    {
        return gmdate('Y-m-d\TH:i:s\Z');
    }

    /**
     * HMAC sign for the API call
     *
     * @return string
     */
    public function sign(string $timestamp, string $uri): string
    {
        return hash_hmac(
            'sha256', $this->username . $timestamp . $uri, $this->key
        );
    }

    /**
     * Gets the array with the username, timestamp and signature
     *
     * @return array
     */
    public function signature(string $uri): array
    {
        $timestamp = $this->timestamp();
        $signature = $this->sign($timestamp, $uri);

        return [
            'api_username' => $this->username,
            'timestamp'    => $timestamp,
            'signature'    => $signature,
        ];
    }

    /**
     * The Domain Profile API provides basic domain name registration
     * details and a preview of additional data available from DomainTools
     * membership and report products
     *
     * http://www.domaintools.com/resources/api-documentation/domain-profile/
     *
     * @param string $name The domain name
     *
     * @return \stdClass
     */
    public function profile(string $name): stdClass
    {
        $uri = static::ENDPOINTS['PROFILE'] . '/' . urlencode($name);

        return $this->http->get($uri, $this->signature($uri));
    }

    /**
     * Domain reputation
     *
     * http://www.domaintools.com/resources/api-documentation/reputation/
     *
     * @param string $name The domain name
     *
     * @return float The risk score (more higher, more risky)
     */
    public function reputation(string $name): float
    {
        $uri = $this->resource(static::ENDPOINTS['REPUTATION'], [
            'domain' => $name
        ]);

        $signature = $this->signature($uri);

        return $this->http->get($uri, $signature)->risk_score;
    }

    /**
     * The Domain Search API searches for domain names
     * that match your specific search string
     *
     * http://www.domaintools.com/resources/api-documentation/domain-search/
     *
     * @param string $name   The domain name
     * @param array  $params The options for the API
     *
     * @return \stdClass
     */
    public function search(string $name, array $params = []): stdClass
    {
        $params['query'] = $name;

        $uri = $this->resource(static::ENDPOINTS['SEARCH'], $params);

        $params = array_merge($this->signature($uri), $params);

        return $this->http->get($uri, $params);
    }

    /**
     * Generate the URI
     *
     * @return string
     */
    public function resource(string $uri, array $params): string
    {
        return $uri . '/?' . http_build_query($params);
    }
}
