<?php
    declare(strict_types=1);
    require APP_ROOT . '/views/includes/head.php';
    require APP_ROOT . '/views/includes/auth.php';

    $qr_code_link = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($_SESSION['user_name'], TWO_AUTH_SECRET, 'Nani-Games Homepage');
?>

<!--- Show qr-code here --->
<body>
    <center>
        <img src="<?php echo $qr_code_link?>"></img>
        <p>Download <strong>Google Authenticator</strong> and connect this website with<br>your account. If you're done enter your code below:</p>
        <form id="add-two-auth-form" action="<?php echo URL_ROOT; ?>/users/settings" method="POST">
            <input type="text" placeholder="Authentication Code" name="code"/>
            <input value="Submit here" type="submit" name="submit" />
        </form>

    </center>
</body>
