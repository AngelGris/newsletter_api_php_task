<?php
    if (!empty($errors)) {
?>
    <div class="alert alert-danger">
        <h3>Plesae check the following errors</h3>
        <ul>
<?php
        foreach ($errors as $error) {
?>
            <li><?php echo($error); ?></li>
<?php
        }
?>
        </ul>
    </div>
<?php
    }
?>