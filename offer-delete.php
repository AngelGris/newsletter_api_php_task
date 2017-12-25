<?php
    require_once('config.php');

    if (!empty($_GET['id'])) {
        mysqli_query($db_link, 'DELETE FROM `vouchers` WHERE `offer_id` = ' . $_GET['id']);
        mysqli_query($db_link, 'DELETE FROM `offers` WHERE `id` = ' . $_GET['id']);
    }

    header('Location: offers.php');
?>