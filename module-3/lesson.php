<?php
/**
 * This file contains the actual lessons we'll be covering in Module 3.
 *
 * The goal of this module is to introduce you to the concepts of symmetric and asymmetric
 * encryption leveraging the Libsodium module shipped with PHP.
 */

namespace EAMann\Contacts\Lesson;

require_once '../config.php';

/**
 * Automatically encrypt the contents of a secret message sent to the server. Store the output
 * of the encryption in `secret.txt` for later retrieval.
 *
 * @param string $message
 */
function store_secret_message(string $message)
{
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $key = hex2bin(SECRET_KEY);
    $cipher = sodium_crypto_secretbox($message, $nonce, $key);

    file_put_contents('secret.txt', bin2hex($nonce . $cipher));
}

/**
 * Read the contents of a secret message and print them to screen.
 *
 * @return string
 */
function get_secret_message() : string
{
    $message = file_get_contents('secret.txt');

    $key = hex2bin(SECRET_KEY);

    $bits = hex2bin($message);
    $nonce = substr($bits, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $cipher = substr($bits, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

    $plaintext = sodium_crypto_secretbox_open($cipher, $nonce, $key);

    return $plaintext;
}