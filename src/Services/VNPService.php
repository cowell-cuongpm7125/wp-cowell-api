<?php


namespace CoWell\Services;


class VNPService extends \WC_Payment_Gateway
{
    protected $orderId;
    protected $amount;
    protected $order;
    protected $vnp_TmnCode;
    protected $inputData;
    protected $vnp_Url;
    protected $hashSecret;

    public function getUrl()
    {
        $this->setInput();
        $this->validator();
        $this->getData();

        return $this->buildUrl();
    }

    private function buildUrl()
    {
        $query = "";
        $i = 0;
        $hashdata = "";

        foreach ($this->inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . $key . "=" . $value;
            } else {
                $hashdata .= $key . "=" . $value;
                $i = 1;
            }

            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $this->vnp_Url = $this->vnp_Url . "?" . $query;

        $vnpSecureHash = hash('sha256', $this->hashSecret . $hashdata);
        $this->vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;

        return $this->vnp_Url;
    }

    private function setInput()
    {
        global $json_api;

        $this->id = 'vnpay';

        $this->vnp_Url = $this->get_option('Url');
        $this->vnp_TmnCode = $this->get_option('terminal');
        $this->hashSecret = $this->get_option('secretkey');

        $this->orderId = $json_api->query->order_id;
        $this->order = new \WC_Order($this->orderId);
        $this->amount = number_format($this->order->order_total, 2, '.', '') * 100;
    }

    private function getData()
    {
        $vnp_Returnurl = home_url() . ("/api/vnp/vnpay_return");
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $vnp_OrderType = '1100';
        $vnp_OrderInfo = 'ORDER|' . $this->orderId;;

        $this->inputData = [
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $this->amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => rand(111111, 9999999),
            "vnp_Version" => "2.0.0",
        ];

        ksort($this->inputData);
    }

    private function validator()
    {
        if (!$this->orderId) {
            throw new \Exception('order_id is empty', 422);
        }

        if (!$this->order) {
            throw new \Exception('order not found', 422);
        }
    }
}
