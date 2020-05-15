<?php
namespace CoWell\Services;

class ChangePasswordService
{
    protected $password;
    protected $new_password;
    protected $password_confirmation;
    protected $user_id;
    protected $user;

    public function work()
    {
        $this->setInput();
        $this->validate();
        wp_set_password($this->new_password, $this->user_id);
    }

    private function setInput()
    {
        global $json_api;

        $this->password = $json_api->query->password;
        $this->new_password = $json_api->query->new_password;
        $this->password_confirmation = $json_api->query->password_confirmation;
        $this->setUser();
    }

    private function setUser()
    {
        global $json_api;

        if ($json_api->query->token) {
            $this->user_id = wp_validate_auth_cookie($json_api->query->token, 'logged_in');
        } else {
            $this->user_id = get_current_user_id();
        }

        $this->user = get_user_by('id', $this->user_id);
    }

    protected function validate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new \Exception('Method Not Allowed. Method must be POST', 405);
        }

        if (!$this->password) {
            throw new \Exception('password must not empty', 422);
        }

        if (!$this->new_password) {
            throw new \Exception('new password must not empty', 422);
        }

        if (!$this->password_confirmation) {
            throw new \Exception('password confirmation must not empty', 422);
        }

        if ($this->password === $this->new_password) {
            throw new \Exception('new password must different old password', 422);
        }

        if ($this->password_confirmation !== $this->new_password) {
            throw new \Exception('new password must same password confirmation', 422);
        }

        if (!$this->user) {
            throw new \Exception('Please login', 403);
        }

        $hash = $this->user->data->user_pass;

        if (!wp_check_password($this->password, $hash)) {
            throw new \Exception('Password incorrect', 422);
        }
    }
}