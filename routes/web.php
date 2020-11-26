<?php
$router->get('/', function(){
    return 'Take Home assignment';
});
$router->get('balance', 'AccountController@balance');
$router->post('reset', 'AccountController@reset');
$router->post('event', 'AccountController@event');
