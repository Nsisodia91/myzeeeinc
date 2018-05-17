<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload
use Zinc\Zinc;
use Zinc\Orders;
// "max_price": 2300,
$zinc = new Orders();
echo "<pre>";
$array = array (
  'retailer' => 'amazon',
  'products' =>
  array (
    0 =>
    array (
      'product_id' => 'B0016NHH56',
      'quantity' => 1,
    ),
  ),
  'shipping_address' =>
  array (
    'first_name' => 'Tim',
    'last_name' => 'Beaver',
    'address_line1' => '77 Massachusetts Avenue',
    'address_line2' => '',
    'zip_code' => '02139',
    'city' => 'Cambridge',
    'state' => 'MA',
    'country' => 'US',
    'phone_number' => '5551230101',
  ),
  'is_gift' => true,
  'gift_message' => 'Here is your package, Tim! Enjoy!',
  'shipping' =>
  array (
    'order_by' => 'price',
    'max_days' => 5,
    'max_price' => 1000,
  ),
  'payment_method' =>
  array (
    'name_on_card' => 'Ben Bitdiddle',
    'number' => '5555555555554444',
    'security_code' => '123',
    'expiration_month' => 1,
    'expiration_year' => 2020,
    'use_gift' => false,
  ),
  'billing_address' =>
  array (
    'first_name' => 'William',
    'last_name' => 'Rogers',
    'address_line1' => '84 Massachusetts Ave',
    'address_line2' => '',
    'zip_code' => '02139',
    'city' => 'Cambridge',
    'state' => 'MA',
    'country' => 'US',
    'phone_number' => '5551234567',
  ),
  'retailer_credentials' =>
  array (
    'email' => 'test3242@mailinator.com',
    'password' => 'Test@1234',
    'totp_2fa_key' => '698003',
  ),
  'webhooks' =>
  array (
    'request_succeeded' => 'https://www.DEMO.com/pesapal_notifcation.php?type=success',
    'request_failed' => 'https://www.DEMO.com/pesapal_notifcation.php?type=failed',
    'tracking_obtained' => 'https://www.DEMO.com/pesapal_notifcation.php?type=tracking',
  ),
  'client_notes' =>
  array (
    'our_internal_order_id' => 'abc123',
    'any_other_field' =>
    array (
      0 => 'any value',
    ),
  ),
);

// print_r($zinc->createOrder($array));exit;
// print_r($zinc->retrieveOrder(['request_id' => 'c3e1ad00257bba469887fa4279d439d0asdga7787']));exit;
// print_r($zinc->retrieveOrder(['request_id' => '26f1b9abb4213c269d88837400b4be81']));exit;
// print_r($zinc->retrieveOrder(['request_id' => '907196ca6a6f974f190e5afc3c45cf4b']));exit;
// print_r($zinc->retrieveOrder(['request_id' => '2335995b1576696fd8bc599d427792f5']));exit;
// print_r($zinc->retrieveOrder(['request_id' => '022c9e5631fea352e1b0a21900241f92']));exit;
// print_r($zinc->retrieveOrder(['request_id' => 'c739f82650c841e5423a4bd2737b17b5']));exit;
print_r($zinc->retrieveOrder(['request_id' => 'c3e1ad00257bba469887fa4279d439d0']));exit;
// print_r($zinc->retrieveOrder(['request_id' => '15c6c3d2e7c89c2aa75fa562cd9b59b2']));exit;
// print_r($zinc->retrieveOrder(['request_id' => '48470c88911184f11996074b762410ca']));exit;
// print_r($zinc->retrieveOrder(['request_id' => 'dc5441fd260f1c3efe2ba9319981a415']));exit;
// print_r($zinc->abortOrder(['request_id' => 'ec1f8a4f0549380723692cedf0548c60']));exit;

