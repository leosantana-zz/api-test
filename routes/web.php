<?php
$router->get('balance', 'AccountController@balance');
$router->post('reset', 'AccountController@reset');
$router->post('event', 'AccountController@event');
