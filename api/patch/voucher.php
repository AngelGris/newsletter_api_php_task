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
    `vouchers`.`id`,
    `vouchers`.`code`,
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
    /**
     * Check if voucher is expired
     */
    if (strtotime($voucher['expiration']) < time()) {
        sendResponse(['response_code' => 5]);
    }

    /**
     * Check if the voucher was used
     */
    if (!is_null($voucher['used'])) {
        sendResponse(['response_code' => 6]);
    }

    /**
     * Update voucher used date
     */
    $used = date('Y-m-d H:i:s');
    mysqli_query($db_link, 'UPDATE `vouchers` SET `used` = \'' . $used . '\' WHERE `id` = ' . $voucher['id'] . ' LIMIT 1;');

    sendResponse([
        'code'          => $voucher['code'],
        'discount'      => $voucher['discount'],
        'used'          => $used
    ]);
} else {
    sendResponse(['response_code' => 4]);
}
?>