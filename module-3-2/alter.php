<?php

$handle = new \SQLite3('contacts.db');

$handle->exec("ALTER TABLE contacts ADD COLUMN email_hash text;");