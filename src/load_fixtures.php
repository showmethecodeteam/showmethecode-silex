<?php

require_once __DIR__.'/../vendor/autoload.php';

// Connection
ORM::configure('sqlite:'.__DIR__.'/../cache/database.db');
Model::$auto_prefix_models = "\\SMTC\\Silex\\Model\\";
$db = ORM::get_db();

// Drop tables
$db->exec("
    DROP TABLE IF EXISTS user;
    DROP TABLE IF EXISTS address;"
);

// Create tables
$db->exec("
    CREATE TABLE IF NOT EXISTS user (
        id INTEGER PRIMARY KEY,
        firstName TEXT,
        lastName TEXT
    );
    CREATE TABLE IF NOT EXISTS address (
        id INTEGER PRIMARY KEY,
        user_id integer,
        street TEXT,
        Foreign Key (user_id) references user(id)
    );"
);

// Create fixtures
$faker = Faker\Factory::create();

for ($i = 0; $i < 100; $i++) {
    $user = Model::factory('User')->create();
    $user->firstName = $faker->firstName;
    $user->lastName = $faker->lastName;

    $user->save();

    for ($j = 0; $j < 3; $j++) {
        $address = Model::factory('Address')->create();
        $address->street = $faker->address;
        $address->user_id = $user->id;

        $address->save();
    }
}