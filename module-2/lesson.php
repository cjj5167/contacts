<?php
/**
 * This file contains the actual lessons we'll be covering in Module 2.
 *
 * The goal of this module is to introduce you to the concept of credentials management
 * when dealing with external systems that also require authentication.
 */

namespace EAMann\Contacts\Lesson;

use EAMann\Contacts\Util\API;
use EAMann\Contacts\Util\Brewery;

require_once '../config.php';

/**
 * Retrieve a list of breweries from our remote API, based on a certain geographic
 * location.
 *
 * @param string $zipcode
 *
 * @return array
 */
function get_breweries(string $zipcode) : array
{
    $api = new API(API_KEY_ID, API_SECRET);

    return $api->getBreweries($zipcode);
}

/**
 * The main dashboard will present data retrieved from our (mock) APU system.
 *
 * @return string
 */
function show_dashboard($zipcode): string
{
    $breweries = get_breweries($zipcode);

    $list = '<ul>';

    foreach($breweries as $brewery) {
        /** @var Brewery $brewery */
        $list .= '<li>' . $brewery->name . ' | ' . $brewery->address . '</li>';
    }

    $list .= '</ul>';

    return $list;
}