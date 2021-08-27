<?php
    declare(strict_types=1);
    require APP_ROOT . '/views/includes/head.php';
?>

Code required!<br>
<form id="confirm-code-form" action="<?php echo URL_ROOT; ?>/users/login" method="POST">
    <input type="text" placeholder="Authentication Code" name="code"/>
    <input type="submit" value="Submit here" name="submit"/>
</form>