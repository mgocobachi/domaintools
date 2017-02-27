<p align="center">
<a href="https://travis-ci.org/mgocobachi/domaintools"><img src="https://travis-ci.org/mgocobachi/domaintools.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/mgocobachi/domaintools"><img src="https://poser.pugx.org/mgocobachi/domaintools/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/mgocobachi/domaintools"><img src="https://poser.pugx.org/mgocobachi/domaintools/license.svg" alt="License"></a>
</p>

# DomainTools API

The DomainTools API provides direct access to the same data that drives the powerful research tools on DomainTools.com.

## What is DomainTools?

DomainTools helps security analysts turn threat data into threat intelligence. We take indicators from your network, including domains and IPs, and connect them with nearly every active domain on the Internet. Those connections inform risk assessments, help profile attackers, guide online fraud investigations, and map cyber activity to attacker infrastructure.

## Endpoints supported

* [Domain Profile](http://www.domaintools.com/resources/api-documentation/domain-profile/)
* [Domain Reputation](http://www.domaintools.com/resources/api-documentation/reputation/)
* [Domain Search](http://www.domaintools.com/resources/api-documentation/domain-search/)

## How to use

To use our package we defined a helper function called as 'domantools()', it
accept two parameters, the username and the API key. However, if you leave it
in blank you will hit the API as anonymous and limited calls.

```
<?php
$dt = domaintools('myusername', 'my_long_api_key');
```

## Examples

If you like to know the profile information of domaintools.com:
```
<?php
$profile = domaintools()->profile('domaintools.com');
```

If you like to know the reputation of domaintools.com (remember, more higher more risk).

```
<?php
$reputation = domaintools()->reputation('domaintools.com');
```

If you like to know the results of any term search

Note: it will return a collection, which is an object that could be acceded like
an array or you can use its built-in functions like: map(), filter() and more.

In this example we are filtering by those domains active only, and when
we get the results then, search and filter where char_count is greater than 15.

```
<?php
$search = domaintools()->search('domain tools', [
  'active_only'   => 'true',
]);

$results = collection($search->results)->filter(function ($result) {
    return $result->char_count > 15;
});
```

Other example could be applying it immediately.
```
<?php
$results = collection(domaintools()->search('domain tools', [
  'active_only' => 'true',
])->results)->filter(function ($result) {
    return $result->char_count > 15;
});
```


## More information

For more information please visit [domaintols.com](http://www.domaintools.com/company/contact/)
