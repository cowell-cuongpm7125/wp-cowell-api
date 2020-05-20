# WP Co-well API
This package makes it easy to build project

## Postcardware
You're free to use this package (it's MIT-licensed), but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our plugin(s) you are using.
- Author: Fight Light Diamond <i.am.m.cuong@gmail.com>
- MIT: 2e566161fd6039c38070de2ac4e4eadd8024a820

## Requires
- Wordpress
- Plugin [JSON API](https://github.com/PI-Media/json-api)
- Plugin [JSON API](https://github.com/PI-Media/json-api)
- Plugin [API JSON USER](https://vi.wordpress.org/plugins/json-api-user/)
- Plugin [Woocommerce](https://woocommerce.com/my-dashboard/)
- Plugin [VNPay for Woocommerce](https://sandbox.vnpayment.vn/apis/docs/open/woocommerce/)

## Usage
### Change password
Plugin for change password. Information API below:
- API: {domain}/api/cowellapi/change_password/
- Method: POST
- body:
  + password
  + new_password
  + password_confimation
  + cookie

`cookie` be return after login

Message successfully
```$xslt
{"status":"ok","message":"Change password successfully"}
```

### VNPay
Payment for order
- API: {domain}/api/vnp/pay?order_id={order_id}
- Method: GET
- params:
  + order_id
