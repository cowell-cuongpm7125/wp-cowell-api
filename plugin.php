<?php
/**
 * Plugin Name: WP Co-well API
 * Author: Co-well Developer
 * Description: API for WP: change password
 */

add_filter('json_api_controllers', function ($controllers) {
    $controllers[] = 'CoWellAPI';
    return $controllers;
});

add_filter('json_api_cowellapi_controller_path', function ($default_path) {
    return  __DIR__. '/src/controllers/json_api_cowellapi_controller.php';
});
