<?php
/**
 * This file contains the actual lessons we'll be covering in Module 3.
 *
 * The goal of this module is to introduce you to the concepts of symmetric and asymmetric
 * encryption leveraging the Libsodium module shipped with PHP.
 */

namespace EAMann\Contacts\Lesson;

require_once '../config.php';

function encrypt(string $message): string
{
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $key = hex2bin(SECRET_KEY);
    $cipher = sodium_crypto_secretbox($message, $nonce, $key);

    return bin2hex($nonce . $cipher);
}

function decrypt(string $encrypted): string
{
    $key = hex2bin(SECRET_KEY);

    $bits = hex2bin($encrypted);
    $nonce = substr($bits, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $cipher = substr($bits, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

    return sodium_crypto_secretbox_open($cipher, $nonce, $key);
}

/**
 * Automatically encrypt the contents of a secret message sent to the server. Store the output
 * of the encryption in `secret.txt` for later retrieval.
 *
 * @param string $message
 */
function store_secret_message(string $message)
{
    $_SESSION['secret_message'] = $message;
}

/**
 * Read the contents of a secret message and print them to screen.
 *
 * @return string
 */
function get_secret_message() : string
{
    $message = $_SESSION['secret_message'];

    return $message;
}

class EncryptedSessionHandler extends \SessionHandler
{
    public function read($id)
    {
        $data = parent::read($id);

        if (!$data) {
            return "";
        } else {
            return decrypt($data);
        }
    }

    public function write($id, $data)
    {
        $data = encrypt($data);

        return parent::write($id, $data);
    }
}