<?php
namespace Domaintools\Contracts\Web;

interface ClientInterface
{
    public function get(string $endpoint, array $params = []);
}
