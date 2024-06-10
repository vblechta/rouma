<?php

require 'inc/medoo.php';

// Using Medoo namespace.
use Medoo\Medoo;

// Connect the database.
$database = new Medoo([
    'type' => 'mysql',
    'host' => getenv("DB_HOST"),
    'database' => getenv("DB_NAME"),
    'username' => getenv("DB_USER"),
    'password' => getenv("DB_PASSWORD")
]);

?>