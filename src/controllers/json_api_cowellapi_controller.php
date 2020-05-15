<?php
/*
Controller name:  WP Co-well API
Controller description: Basic change password
 */
//namespace CoWell\Controllers;


class json_api_cowellapi_controller
{
    private $service;

    public function __construct()
    {
        $this->service = new \CoWell\Services\ChangePasswordService();
    }

    public function change_password()
    {
        global $json_api;

        try {
            $this->service->work();

            return [
                'status' => 200,
                'message' => 'Change password successfully'
            ];
        } catch (Exception $exception) {
            $json_api->error($exception->getMessage(), $exception->getCode());
        }
    }
}