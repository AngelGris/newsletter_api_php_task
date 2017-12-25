<?php
/**
 * Van Hack PHP test API
 *
 * Responde codes
 * 0        = Success
 * 1        = Invalid API call
 * 2        = Recipient email is required
 * 3        = Voucher code is required
 * 4        = Voucher not found
 * 5        = Voucher expired
 * 6        = Voucher already used
 * 7        = No valid vouchers found for given e-mail
 * 8        = Page value must be an integer greater than 0
 * 9        = Results value must be an integer greater than 0
 */

require_once('config.php');

/*
 * Get the HTTP method, path and body of the request
 */
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET' && !empty($_GET)) {
    $input = $_GET;
} else {
    $input = json_decode(file_get_contents('php://input'),true);
}

$file = strtolower($method . '/' . $input['entity'] . '.php');

if (is_file($file)) {
    require_once($file);
}

sendResponse(['response_code' => 1, 'method' => $method, 'entity' => $input['entity']]);