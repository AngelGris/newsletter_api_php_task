<?php
    require_once('includes/header.php');

    $offers = mysqli_query($db_link, 'SELECT * FROM `offers`');
?>
<a href="offer-edit.php" class="btn">Create Offer</a>
<?php
    if (mysqli_num_rows($offers) == 0) {
?>
<h2>No offers to show</h2>
<?php
    } else {
?>
<table>
    <thead>
        <tr>
            <td>ID</td>
            <td>Name</td>
            <td>Discount</td>
            <td>Options</td>
        </tr>
    </thead>
    <tbody>
<?php
        while ($row = mysqli_fetch_assoc($offers)) {
?>
        <tr>
            <td class="center"><?php echo($row['id']); ?></td>
            <td><?php echo($row['name']); ?></td>
            <td class="center"><?php echo($row['discount']); ?>%</td>
            <td class="center"><a href="offer-edit.php?id=<?php echo($row['id']); ?>" class="btn">Edit</a> <a href="offer-delete.php?id=<?php echo($row['id']); ?>" class="btn btn-delete">Delete</a></td>
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