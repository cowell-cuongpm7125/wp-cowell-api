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

    public function forgot_password()
    {
        $login = $_REQUEST['email'];

        $userdata = get_user_by('email', $login);

        if (empty($userdata)) {
            $userdata = get_user_by('login', $login);
        }

        if (empty($userdata)) {
            $json = array('code' => '101', 'msg' => 'User not found');
            echo json_encode($json);
            exit;
        }

        $user = new WP_User(intval($userdata->ID));
        $reset_key = get_password_reset_key($user);
        $wc_emails = WC()->mailer()->get_emails();
        $wc_emails['WC_Email_Customer_Reset_Password']->trigger($user->user_login, $reset_key);

        return $reset_key;

        $json = array('code' => '200', 'msg' => 'Password reset link has been sent to your registered email');
        echo json_encode($json);
        exit;
    }
}
