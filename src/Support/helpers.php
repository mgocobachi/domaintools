<?php
if (!function_exists('domaintools')) {
    /**
     * Create an instance of \Domaintools\Api class
     *
     * @return \Domaintools\Api
     */
    function domaintools(string $username = '', string $key = ''): \Domaintools\Api
    {
        return new \Domaintools\Api(
            $username,
            $key,
            new \Domaintools\Web\Client(new \GuzzleHttp\Client)
        );
    }
}
