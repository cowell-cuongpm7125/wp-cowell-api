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
        $message =  "Giao dịch thành công";
        $success = true;

        try {
            $this->validate($_REQUEST['vnp_ResponseCode']);
            $order_id = explode('|', $_REQUEST['vnp_OrderInfo'])[1];

            if ($order_id) {
                $order = new WC_Order($order_id);
                $order->set_status('processing', 'VNP payment');
                $order->add_order_note('VNP payment ');
                $order->save();


            } else {
                throw new Exception('Order không tìm thấy', 422);
            }
        } catch (Exception $exception) {
            error_log(json_encode($_REQUEST));
            error_log(json_encode($exception));
            $message = $exception->getMessage();
            $success = false;
//            return $json_api->error($exception->getMessage(), $exception->getCode());
        }

        require (__DIR__ . '/../../views/result.php');
        die();
    }

    public function validate($vnp_ResponseCode)
    {
        $errors = [
            'init' => [
                '01' => 'Giao dịch đã tồn tại',
                '02' => 'Merchant không hợp lệ (kiểm tra lại vnp_TmnCode)',
                '03' => 'Dữ liệu gửi sang không đúng định dạng',
                '04' => 'Khởi tạo GD không thành công do Website đang bị tạm khóa',
                '08' => 'Giao dịch không thành công do: Hệ thống Ngân hàng đang bảo trì. Xin quý khách tạm thời không thực hiện giao dịch bằng thẻ/tài khoản của Ngân hàng này.',
                '05' => 'Giao dịch không thành công do: Quý khách nhập sai mật khẩu thanh toán quá số lần quy định. Xin quý khách vui lòng thực hiện lại giao dịch',
                '06' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Xin quý khách vui lòng thực hiện lại giao dịch.',
                '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường). Đối với giao dịch này cần merchant xác nhận thông qua merchant admin: Từ chối/Đồng ý giao dịch',
            ],
            'result_url' => [
                '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.',
                '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
                '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
                '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.',
                '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch.',
                '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
                '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.',
                '75' => 'Ngân hàng thanh toán đang bảo trì',
                '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)'
            ],
            'inp_vnpay' => [
                '00' => 'Ghi nhận giao dịch thành công',
                '01' => 'Không tìm thấy mã đơn hàng',
                '02' => 'Yêu cầu đã được xử lý trước đó',
                '03' => 'Địa chỉ IP không được phép truy cập (tùy chọn)',
                '97' => 'Sai chữ ký (checksum không khớp)',
                '99' => 'Lỗi hệ thống',
            ],
            'search_transaction' => [
                '91' => 'Không tìm thấy giao dịch yêu cầu hoàn trả',
                '02' => 'Merchant không hợp lệ (kiểm tra lại vnp_TmnCode)',
                '03' => 'Dữ liệu gửi sang không đúng định dạng',
                '08' => 'Hệ thống đang bảo trì',
                '93' => 'Số tiền hoàn trả không hợp lệ. Số tiền hoàn trả phải nhỏ hơn hoặc bằng số tiền thanh toán.',
                '94' => 'Giao dịch đã được gửi yêu cầu hoàn tiền trước đó. Yêu cầu này VNPAY đang xử lý',
                '95' => 'Giao dịch này không thành công bên VNPAY. VNPAY từ chối xử lý yêu cầu.',
                '97' => 'Chữ ký không hợp lệ',
                '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)'
            ],
        ];

        if ($vnp_ResponseCode != '00') {
            throw new Exception($errors['result_url'][$vnp_ResponseCode], 422);
        }
    }
}
