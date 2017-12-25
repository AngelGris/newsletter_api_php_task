<?php
    require_once('includes/header.php');

    $recipients = mysqli_query($db_link, 'SELECT * FROM `recipients`');
?>
<a href="recipient-edit.php" class="btn">Create Recipient</a>
<?php
    if (mysqli_num_rows($recipients) == 0) {
?>
<h2>No recipients to show</h2>
<?php
    } else {
?>
<table>
    <thead>
        <tr>
            <td>ID</td>
            <td>Name</td>
            <td>E-mail</td>
            <td>Options</td>
        </tr>
    </thead>
    <tbody>
<?php
        while ($row = mysqli_fetch_assoc($recipients)) {
?>
        <tr>
            <td class="right"><?php echo($row['id']); ?></td>
            <td><?php echo($row['name']); ?></td>
            <td><?php echo($row['email']); ?></td>
            <td><a href="recipient-edit.php?id=<?php echo($row['id']); ?>" class="btn">Edit</a> <a href="recipient-delete.php?id=<?php echo($row['id']); ?>" class="btn btn-delete">Delete</a></td>
        </tr>
<?php
        }
?>
    </tbody>
</table>
<?php
    }

    require_once('includes/footer-js.php');
    require_once('includes/footer.php');
?>