<?php
    require_once('includes/header.php');

    $offer = NULL;

    /**
     * Load offer information if $_GET['id']
     */
    if (!empty($_GET['id'])) {
        $sql = 'SELECT `offers`.*, `vouchers`.`expiration` FROM `offers` LEFT JOIN `vouchers` ON (`vouchers`.`offer_id` = `offers`.`id`) WHERE `offers`.`id` = ' . $_GET['id'] . ' LIMIT 1;';
        $offer = mysqli_fetch_assoc(mysqli_query($db_link, $sql));
    }

    if ($offer) {
        $title = 'Edit Offer';
    } else {
        $title = 'Create Offer';
    }

    /**
     * If $_POST is present process it
     */
    if (!empty($_POST)) {
        $errors = [];

        /**
         * Check $_POST fields
         */
        if ($_POST['name'] == '') {
            $errors[] = 'Name is mandatory';
        }

        $_POST['discount'] = str_replace(',', '.', $_POST['discount']);
        if ($_POST['discount'] == '') {
            $errors[] = 'Discount is mandatory';
        } elseif (preg_match('/^\d+\.?\d*$/', $_POST['discount']) == 0) {
            $errors[] = 'Invalid discount format';
        } elseif (is_numeric($_POST['discount']) && ($_POST['discount'] <= 0 || $_POST['discount'] > 100)) {
            $errors[] = 'Discount must be a float between 1 and 100';
        }

        if (empty($_POST['expiration'])) {
            $errors[] = 'Expiration date is mandatory';
        } else if (preg_match('/^\d+\/\d+\/\d+$/', $_POST['expiration']) == 0) {
            $errors[] = 'Invalid date format';
        }

        if (empty($errors)) {
            /**
             * If ID is empty create new offer
             * If ID not empty update given ID
             */
            if (empty($_POST['id'])) {
                $sql = 'INSERT INTO `offers` (`name`, `discount`) VALUES (\'' . mysqli_real_escape_string($db_link, $_POST['name']) . '\', ' . str_replace(',', '.', $_POST['discount']) . ');';
            } else {
                $sql = 'UPDATE `offers` SET `name` = \'' . mysqli_real_escape_string($db_link, $_POST['name']) . '\', `discount` = ' . str_replace(',', '.', $_POST['discount']) . ' WHERE `id` = ' . $_POST['id'] . ';';
            }

            mysqli_query($db_link, $sql);

            /**
             * If no error on SQL query generate vouchers and go back to offers listing
             * If error stay in this page and show error
             */
            $error_no = mysqli_errno($db_link);
            if ($error_no == 0) {
                if (empty($_POST['id'])) {
                    /**
                     * Generated vouchers for recipients
                     */
                    $offer_id = mysqli_insert_id($db_link);
                    $recipients = mysqli_query($db_link, 'SELECT * FROM `recipients`;');
                    while ($recipient = mysqli_fetch_assoc($recipients)) {
                        mysqli_query($db_link, 'INSERT INTO `vouchers` (`code`, `recipient_id`, `offer_id`, `expiration`) VALUES (\'' . genVoucherCode($offer_id, $recipient['id']) . '\', ' . $recipient['id'] . ', ' . $offer_id . ', \'' . date('Y-m-d', strtotime($_POST['expiration'])) . '\');');

                        /**
                         * Send voucher to recipient's email
                         */
                        // Code to send mail to $recipient['email'] here using your email sender plugin
                    }
                } else {
                    mysqli_query($db_link, 'UPDATE `vouchers` SET `expiration` = \'' . date('Y-m-d', strtotime($_POST['expiration'])) . '\' WHERE `offer_id` = ' . $_POST['id']);
                }
                header('Location: offers.php');
                exit;
            } else {
                $errors[] = mysqli_errno($db_link) . ' - ' . mysqli_error($db_link);
            }
        }

        /**
         * Put posted data in the $offer array for inputs' value
         */
        $offer['name'] = $_POST['name'];
        $offer['discount'] = $_POST['discount'];
    }
?>
<h2><?php echo($title); ?></h2>
<form method="post">
    <input type="hidden" name="id" value="<?php echo($offer ? $offer['id'] : 0) ?>" />
<?php
    /**
     * Display errors if any
     */
    include('includes/errors.php');
?>
    <table>
        <tbody>
<?php
    if (!empty($offer['id'])) {
?>
            <tr>
                <td>ID</td>
                <td><?php echo($offer['id']); ?></td>
            </tr>
<?php
    }
?>
            <tr>
                <td>Name</td>
                <td><input type="text" id="txt_name" name="name" value="<?php echo(empty($offer['name']) ? '' : $offer['name']); ?>" required /></td>
            </tr>
            <tr>
                <td>Discount</td>
                <td><input type="text" id="txt_discount" name="discount" value="<?php echo(empty($offer['discount']) ? '' : $offer['discount']); ?>" required /></td>
            </tr>
            <tr>
                <td>Expiration</td>
                <td><input type="text" id="txt-expiration" name="expiration" value="<?php echo(empty($offer['expiration']) ? date('m/d/Y') : date('m/d/Y', strtotime($offer['expiration']))); ?>"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" value="Save"> <input type="reset" value="Cancel"></td>
            </tr>
        </tbody>
    </table>
</form>
<?php
    require_once('includes/footer-js.php');
?>
<script type="text/javascript">
$(function () {
    $('#txt-expiration').datepicker({
        'startDate': '0d'
    });

    $('form').submit(function(e) {
        var error = true;
        var re = /^\d+\.?\d*$/;

        if ($('#txt_name').val().trim() == '') {
            alert('Offer name is mandatory');
            $('#txt_name').focus();
        } else if ($('#txt_discount').val().trim() == '') {
            alert('Offer discount is mandatory');
            $('#txt_discount').focus();
        } else if (!re.test($('#txt_discount').val().replace(',', '.'))) {
            alert('Invalid discount format');
            $('#txt_discount').select();
        } else {
            error = false;
        }

        if (error) {
            e.preventDefault();
        }
    });

    $('input[type=reset]').click(function() {
        document.location = '/offers.php';
    })
});
</script>
<?php
    require_once('includes/footer.php');
?>