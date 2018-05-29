<?php
/**
 * These utility functions help for rendering our UI to make password management
 * a bit easier. You're not expected to implement anything here.
 */

namespace EAMann\Contacts\Util;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

function show_search(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
{
    if ($request->getMethod() === 'POST') {
        $body = $request->getParsedBody();
        $zip = $body['zipcode'];
    } else {
        $zip = '';
    }

    $body = get_search_form($zip);
    $response->getBody()->write($body);

    return $response;
}

function get_search_form(string $zipcode): string
{
    $body = <<<BODY
<html>
<head>
    <title>Module 2 | Credentials Management</title>
    <style type="text/css">
        #search-form { width: 250px; margin: auto; }
        form > div { margin-top: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div id="search-form">
        <form method="post" action="">
            <div>
                <label for="zipcode">Zipcode: </label>
                <input type="text" name="zipcode" value="ZIPCODE" />
            </div>
            <div>
                <input type="submit" value="Find a Brewery!" />
            </div>       
        </form>
    </div>
</body>
</html>
BODY;

    return str_replace('ZIPCODE', $zipcode, $body);
}

class API
{
    public function __construct($apiKey, $apiSecret) {}

    public function getBreweries($zipcode): array
    {
        return [
            new Brewery('php[tek]', 'here'),
            new Brewery('php[world]', 'Washington, DC')
        ];
    }
}

class Brewery
{
    public $name;

    public $address;

    public function __construct($name, $address)
    {
        $this->name = $name;
        $this->address = $address;
    }
}