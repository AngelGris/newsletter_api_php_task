<?php
function genVoucherCode($offer_id, $recipient_id) {
    global $db_link;

    /**
     * Here the code checks if the code is unique in the DB,
     * but that'd represent a performance issue as the vouchers table grows
     */
    do {
        $code = substr(md5(uniqid($offer_id . '#' . $recipient_id)), 0, 10);
        $result = mysqli_query($db_link, 'SELECT `id` FROM `vouchers` WHERE `code` = \'' . $code . '\' LIMIT 1;');
    } while(mysqli_num_rows($result) > 0);

    return $code;
}
?>