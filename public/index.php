<?php

require  __DIR__ . '/../vendor/autoload.php';

Flight::set('flight.views.path', __DIR__ . '/../templates/');

Flight::route('/', function(){
    Flight::render('index');
});

Flight::start();

