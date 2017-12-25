<?php
    require_once('includes/header.php');

    $recipient = NULL;

    /**
     * Load recipient information if $_GET['id']
     */
    if (!empty($_GET['id'])) {
        $sql = 'SELECT * FROM `recipients` WHERE `id` = ' . $_GET['id'];
        $recipient = mysqli_fetch_assoc(mysqli_query($db_link, $sql));
    }

    if ($recipient) {
        $title = 'Edit Recipient';
    } else {
        $title = 'Create Recipient';
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

        if ($_POST['email'] == '') {
            $errors[] = 'E-mail is mandatory';
        } elseif (preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $_POST['email']) == 0) {
            $errors[] = 'Invalid e-mail address';
        }

        if (empty($errors)) {
            /**
             * If ID is empty create new recipient
             * If ID not empty update given ID
             */
            if (empty($_POST['id'])) {
                $sql = 'INSERT INTO `recipients` (`name`, `email`) VALUES (\'' . mysqli_real_escape_string($db_link, $_POST['name']) . '\', \'' . mysqli_real_escape_string($db_link, $_POST['email']) . '\');';
            } else {
                $sql = 'UPDATE `recipients` SET `name` = \'' . mysqli_real_escape_string($db_link, $_POST['name']) . '\', `email` = \'' . mysqli_real_escape_string($db_link, $_POST['email']) . '\' WHERE `id` = ' . $_POST['id'] . ';';
            }

            mysqli_query($db_link, $sql);

            /**
             * If no error on SQL query, create vouchers and go back to recipients listing
             * If error stay in this page and show error
             */
            $error_no = mysqli_errno($db_link);
            if ($error_no == 0) {
                header('Location: recipients.php');
                exit;
            } elseif ($error_no == 1062) {
                $errors[] = 'E-mail address is already registered';
            } else {
                $errors[] = mysqli_errno($db_link) . ' - ' . mysqli_error($db_link);
            }
        }

        /**
         * Put posted data in the $recipient array for inputs' value
         */
        $recipient['name'] = $_POST['name'];
        $recipient['email'] = $_POST['email'];
    }
?>
<h2><?php echo($title); ?></h2>
<form method="post">
    <input type="hidden" name="id" value="<?php echo($recipient ? $recipient['id'] : 0) ?>" />
<?php
    /**
     * Display errors if any
     */
    include('includes/errors.php');
?>
    <table>
        <tbody>
<?php
    if (!empty($recipient['id'])) {
?>
            <tr>
                <td>ID</td>
                <td><?php echo($recipient['id']); ?></td>
            </tr>
<?php
    }
?>
            <tr>
                <td>Name</td>
                <td><input type="text" id="txt_name" name="name" value="<?php echo(empty($recipient['name']) ? '' : $recipient['name']); ?>" required /></td>
            </tr>
            <tr>
                <td>E-mail</td>
                <td><input type="email" id="txt_email" name="email" value="<?php echo(empty($recipient['email']) ? '' : $recipient['email']); ?>" required /></td>
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
    $('form').submit(function(e) {
        var error = true;
        var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

        if ($('#txt_name').val().trim() == '') {
            alert('Recipient name is mandatory');
            $('#txt_name').focus();
        } else if ($('#txt_email').val().trim() == '') {
            alert('Recipient e-mail is mandatory');
            $('#txt_email').focus();
        } else if (!re.test($('#txt_email').val())) {
            alert('Invalid e-mail address');
            $('#txt_email').select();
        } else {
            error = false;
        }

        if (error) {
            e.preventDefault();
        }
    });

    $('input[type=reset]').click(function() {
        document.location = '/recipients.php';
    })
});
</script>
<?php
    require_once('includes/footer.php');
?>