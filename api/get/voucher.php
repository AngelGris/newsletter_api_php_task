<?php
/**
 * Validate input
 */
if (empty($input['email'])) {
    sendResponse(['response_code' => 2]);
}

if (empty($input['code'])) {
    sendResponse(['response_code' => 3]);
}

$input = array_map([$db_link, 'real_escape_string'], $input);

$sql = <<< 'EOD'
SELECT
    `vouchers`.`code`,
    `offers`.`name`,
    `offers`.`discount`,
    `vouchers`.`expiration`,
    `vouchers`.`used`
FROM
    `vouchers`
INNER JOIN
    `recipients`
    ON
    (`recipients`.`id` = `vouchers`.`recipient_id`)
INNER JOIN
    `offers`
    ON
    (`offers`.`id` = `vouchers`.`offer_id`)
WHERE
    `recipients`.`email` = '%s'
    AND
    `vouchers`.`code` = '%s'
LIMIT 1;
EOD;

$result = mysqli_query($db_link, sprintf($sql, $input['email'], $input['code']));

if ($voucher = mysqli_fetch_assoc($result)) {
    sendResponse([
        'code'          => $voucher['code'],
        'offer'         => $voucher['name'],
        'discount'      => $voucher['discount'],
        'expiration'    => $voucher['expiration'],
        'used'          => $voucher['used']
    ]);
} else {
    sendResponse(['response_code' => 4]);
}
?>