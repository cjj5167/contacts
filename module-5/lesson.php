<?php
/**
 * This file contains the actual lessons we covered in the second part of Module 3 that
 * need to be updated as part of Module 5.
 */

namespace EAMann\Contacts\Lesson;

use EAMann\Contacts\Util\Contact;

/**
 * Pull a list of all contacts from the database, automatically decrypting
 * the email field (and other sensitive fields) as you go.
 *
 * @return Contact[]
 */
function list_contacts(): array
{
    $contacts = [];

    $handle = new \PDO('sqlite:contacts.db');

    $statement = $handle->prepare('SELECT * FROM contacts');

    $statement->execute();
    while ($row = $statement->fetch()) {
        // This won't decrypt anything ... just pass out the name and email address
        $contacts[] = new Contact($row['name'], $row['email']);
    }

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
    $handle = new \PDO('sqlite:contacts.db');

    $statement = $handle->prepare('INSERT INTO contacts (name, email) VALUES (:name, :email)');

    $s = $statement->execute([':name' => $name, ':email' => $email]);

    if (!$s) {
        error_log($statement->errorCode());
    }
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
    $handle = new \PDO('sqlite:contacts.db');

    $statement = $handle->prepare('SELECT * FROM contacts WHERE email = :email LIMIT 1');
    $statement->execute([':email' => $email]);

    $row = $statement->fetch();

    $contact = new Contact($row['name'], $row['email']);

    return $contact;
}