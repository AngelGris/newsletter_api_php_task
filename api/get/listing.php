<?php
/**
 * Validate input
 */
if (empty($input['email'])) {
    sendResponse(['response_code' => 2]);
}

if (isset($input['page'])) {
    if (!is_integer($input['page']) || $input['page'] <= 0) {
        sendResponse(['response_code' => 8]);
    }
} else {
    $input['page'] = 1;
}

if (isset($input['results'])) {
    if (!is_integer($input['results']) || $input['results'] <= 0) {
        sendResponse(['response_code' => 9]);
    }
} else {
    $input['results'] = 100;
}

$input = array_map([$db_link, 'real_escape_string'], $input);

$sql = <<< 'EOD'
SELECT
    `vouchers`.`code`,
    `offers`.`name`,
    `offers`.`discount`,
    `vouchers`.`expiration`
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
    `vouchers`.`used` IS NULL
    AND
    `vouchers`.`expiration` >= '%s'
LIMIT
    %d, %d
EOD;

$result = mysqli_query($db_link, sprintf($sql, $input['email'], date('Y-m-d'), $input['page'] - 1, $input['results']));

$vouchers = [];
if (mysqli_num_rows($result) == 0) {
    sendResponse(['response_code' => 7]);
} else {
    while ($voucher = mysqli_fetch_assoc($result)) {
        $vouchers[] = [
            'code'          => $voucher['code'],
            'offer'         => $voucher['name'],
            'discount'      => $voucher['discount'],
            'expiration'    => $voucher['expiration']
        ];
    }
}

sendResponse(['vouchers' => $vouchers]);
?>