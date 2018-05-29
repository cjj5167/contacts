<?php
/**
 * This file contains the actual lessons we'll be covering in Module 6.
 *
 * The goal of this module is to introduce you to the concepts of message signatures
 * leveraging the Libsodium module shipped with PHP.
 */

namespace EAMann\Contacts\Lesson;

use function Sodium\crypto_sign_detached;
use function Sodium\crypto_sign_verify_detached;

/**
 * Sign the text stored in a message with a specific (Hex-encoded) _private_ key.
 *
 * @param string $filename
 * @param string $private_key
 *
 * @return string
 */
function sign_message(string $filename, string $private_key) : string
{
    $message = file_get_contents($filename);
    $key = hex2bin($private_key);

    $signature = sodium_crypto_sign_detached($message, $key);

    file_put_contents("$filename.sig", bin2hex($signature));

    return $signature;
}

/**
 * Read the contents of a signed message and signature and validate against a Hex-encoded public key.
 *
 * @param string $filename
 * @param string $public_key
 *
 * @return bool
 */
function get_signed_message(string $filename, string $public_key) : bool
{
    $message = file_get_contents($filename);
    $signature = hex2bin(file_get_contents("$filename.sig"));
    $key = hex2bin($public_key);

    return sodium_crypto_sign_verify_detached($signature, $message, $key);
}