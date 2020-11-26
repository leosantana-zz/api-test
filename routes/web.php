<?php

$router->post('/reset', 'AccountController@reset');
$router->post('/event', 'AccountController@event');
$router->get('/balance', 'AccountController@balance');

