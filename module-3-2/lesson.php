<?php
/**
 * This file contains the actual lessons we'll be covering in the second part of Module 3.
 */

namespace EAMann\Contacts\Lesson;

use EAMann\Contacts\Util\Contact;

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
 * Pull a list of all contacts from the database, automatically decrypting
 * the email field (and other sensitive fields) as you go.
 *
 * @return Contact[]
 */
function list_contacts(): array
{
    $contacts = [];

    $handle = new \SQLite3('contacts.db');

    $results = $handle->query('SELECT * FROM contacts');
    while ($row = $results->fetchArray()) {

        // This won't decrypt anything ... just pass out the name and email address
        $email = decrypt($row['email']);
        $contacts[] = new Contact($row['name'], $email);
    }

    $handle->close();

    return $contacts;
}

/**
 * Insert the new contact into the database, automatically encrypting sensitive
 * fields and setting up a hash index against which we can search.
 *
 * @param string $name
 * @param string $email
 */
function create_contact(string $name, string $email)
{
    $handle = new \SQLite3('contacts.db');

    $hashedEmail = hash_hmac('sha512', $email, HMAC_KEY);
    $encryptedEmail = encrypt($email);

    $handle->exec(sprintf("INSERT INTO contacts (name, email, email_hash) VALUES ('%s', '%s', '%s')", $name, $encryptedEmail, $hashedEmail));

    $handle->close();
}

/**
 * Find a specific contact based on a known email address.
 *
 * @param string $email
 *
 * @return Contact
 */
function find_contact(string $email) : Contact
{
    $handle = new \SQLite3('contacts.db');

    $emailHash = hash_hmac('sha512', $email, HMAC_KEY);

    $results = $handle->query(sprintf("SELECT * FROM contacts WHERE email_hash = '%s' LIMIT 1;", $emailHash));
    $row = $results->fetchArray();

    if (!empty($row)) {
        $decryptedEmail = decrypt($row['email']);

        $contact = new Contact($row['name'], $decryptedEmail);
    } else {
        return new Contact('', '');
    }


    $handle->close();

    return $contact;
}