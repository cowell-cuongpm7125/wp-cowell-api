<?php
//namespace CoWell\Controllers;

/*
Controller name:  VNP API
Controller description: VN pay
 */

class json_api_vnp_controller
{
    public function pay()
    {
        global $json_api;

        try {
            $service = new \CoWell\Services\VNPService();

            return ['code' => '00', 'message' => 'success', 'data' => $service->getUrl()];
        } catch (Exception $exception) {
            return $json_api->error($exception->getMessage(), $exception->getCode());
        }
    }

    public function vnpay_return()
    {
        global $json_api;

        try {
            $order_id = explode('|', $_REQUEST['vnp_OrderInfo'])[1];

            if ($order_id) {
                $order = new WC_Order($order_id);
                $order->set_status('processing', 'VNP payment');
                $order->add_order_note('VNP payment ');
                $order->save();

                return [
                    'status' => 200,
                    'code' => 00,
                    'message' => 'payment successfully',
                    json_decode($order, true)
                ];
            } else {
                throw new Exception('Order not found', 422);
            }
        } catch (Exception $exception) {
            error_log(json_encode($_REQUEST));
            error_log(json_encode($exception));
            return $json_api->error($exception->getMessage(), $exception->getCode());
        }
    }
}
