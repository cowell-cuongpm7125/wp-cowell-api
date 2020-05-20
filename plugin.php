<?php
/**
 * Plugin Name: WP Co-well API
 * Author: Co-well Developer
 * Description: API for WP(Change password, VNpay)
 * Version: 0.1
 */
require __DIR__ . '/vendor/autoload.php';

add_filter('json_api_controllers', function ($controllers) {
    $controllers[] = 'CoWellAPI';
    $controllers[] = 'VNP';

    return $controllers;
});


/**
 * Change password
 */
add_filter('json_api_cowellapi_controller_path', function ($default_path) {
    return  __DIR__. '/src/controllers/json_api_cowellapi_controller.php';
});
/**
 * VNPay
 */
add_filter('json_api_vnp_controller_path', function ($default_path) {
    return  __DIR__. '/src/controllers/json_api_vnp_controller.php';
});
