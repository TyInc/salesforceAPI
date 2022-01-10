<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->group(['prefix'=>'api/v1'], function() use($router){

    $router->get('/shippingrate', 'ShippingRateController@info');
    $router->post('/shippingrate', 'ShippingRateController@index');

    $router->get('/ordersubmission', 'OrderSubmissionController@info');
    $router->post('/ordersubmission', 'OrderSubmissionController@index');

    $router->get('/orderstatus', 'OrderStatusController@info');
    $router->get('/orderstatus/id/{order_number}', 'OrderStatusController@index');

    $router->get('/refund/', 'OrderRefundController@info');
    $router->post('/refund', 'OrderRefundController@index');
});
