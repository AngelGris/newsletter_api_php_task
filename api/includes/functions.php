<?php
function sendResponse($data) {
    /**
     * Response messages
     */
    $messages = [
        0       => 'Success',
        1       => 'Invalid API call',
        2       => 'Recipient email is required',
        3       => 'Voucher code is required',
        4       => 'Voucher not found',
        5       => 'Voucher expired',
        6       => 'Voucher already used',
        7       => 'No valid vouchers found for given e-mail',
        8       => 'Page value must be an integer greater than 0',
        9       => 'Results value must be an integer greater than 0',
    ];

    $output = [];
    if (empty($data['response_code'])) {
        $output['response_code'] = 0;
    } else {
        $output['response_code'] = $data['response_code'];
    }

    $output['response_message'] = $messages[$output['response_code']];

    array_walk_recursive($data, function(&$item, $key){
        if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
        }
    });

    exit(json_encode(array_merge($output, $data)));
}
?>