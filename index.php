<?php
    require_once('includes/header.php');

    /**
     * Get totl number of Recipients, Offers and Vouchers from the DB
     */
    list($total_recipients) = mysqli_fetch_row(mysqli_query($db_link, 'SELECT COUNT(*) FROM `recipients`;'));
    list($total_offers) = mysqli_fetch_row(mysqli_query($db_link, 'SELECT COUNT(*) FROM `offers`;'));
    list($total_vouchers) = mysqli_fetch_row(mysqli_query($db_link, 'SELECT COUNT(*) FROM `vouchers`;'));

    /**
     * Get total number of used vouchers for the last 24 hours, 48 hours and 7 days from the DB
     */
    list($vouchers_24_hours) = mysqli_fetch_row(mysqli_query($db_link, 'SELECT COUNT(*) FROM `vouchers` WHERE `used` > ' . (time() - 86400)));
    list($vouchers_48_hours) = mysqli_fetch_row(mysqli_query($db_link, 'SELECT COUNT(*) FROM `vouchers` WHERE `used` > ' . (time() - 172800)));
    list($vouchers_7_days) = mysqli_fetch_row(mysqli_query($db_link, 'SELECT COUNT(*) FROM `vouchers` WHERE `used` > ' . (time() - 604800)));
?>
<table>
    <thead>
        <tr>
            <td style="width:33%">Total Recipients</td>
            <td style="width:33%">Total Offers</td>
            <td style="width:33%">Total Vouchers</td>
        </tr>
    </thead>
    <tbody class="center">
        <tr>
            <td><?php echo($total_recipients); ?></td>
            <td><?php echo($total_offers); ?></td>
            <td><?php echo($total_vouchers); ?></td>
        </tr>
    </tbody>
</table>
<h2>Vouchers used</h2>
<table>
    <thead>
        <tr>
            <td style="width:33%">Last 24 hours</td>
            <td style="width:33%">Last 48 hours</td>
            <td style="width:33%">Last 7 days</td>
        </tr>
    </thead>
    <body class="center">
        <tr>
            <td><?php echo($vouchers_24_hours); ?></td>
            <td><?php echo($vouchers_48_hours); ?></td>
            <td><?php echo($vouchers_7_days); ?></td>
        </tr>
    </body>
</table>
<?php
    require_once('includes/footer.php');
?>