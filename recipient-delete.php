<?php
    require_once('config.php');

    if (!empty($_GET['id'])) {
        mysqli_query($db_link, 'DELETE FROM `vouchers` WHERE `recipient_id` = ' . $_GET['id']);
        mysqli_query($db_link, 'DELETE FROM `recipients` WHERE `id` = ' . $_GET['id']);
    }

    header('Location: recipients.php');
?>