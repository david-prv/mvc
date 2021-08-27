<?php
if(isLoggedIn()) {
    header('Location: ' . URL_ROOT . '/');
}
?>

<head>
    <title><?php echo TITLE_PREFIX . $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/account_managing.min.css" type="text/css">
</head>

<body>
    <?php
        require APP_ROOT . '/views/includes/navbar.php';
    ?>

    <section id="account-reset-pass" class="account-managing">
        <div class="account-form-container">
            <div class="user-profile-background">
                <div class="image-container user-profile-picture">
                    <picture>
                        <source srcset="<?php echo URL_ROOT; ?>/public/img/welcome.webp" type="image/webp">
                        <source srcset="<?php echo URL_ROOT; ?>/public/img/welcome.JPG" type="image/JPG">
                        <img src="<?php echo URL_ROOT; ?>/public/img/welcome.JPG" alt="Welcome">
                    </picture>
                </div>
            </div><div id="form-container">
                <form id="reset-pass-form" action="<?php echo URL_ROOT; ?>/users/reset" method="POST">
                    <input type="email" placeholder="Email ... *" name="email" autocomplete="off" />

                    <?php if (!empty($data["notFoundError"])) {
                        echo '<div class="invalid-feedback"><p>' . $data["notFoundError"] . '</p></div>';
                    } ?>

                    <input class="captcha-input" placeholder="Answer captcha ... *" name="captcha" size="16" autocomplete="off" />
                    <div class="captcha-container">
                        <input style="display:none;" name="captchaId" value="<?php echo $_SESSION['id']; ?>" hidden />
                        <img id="captchaimg" src="http://www.EasyCaptchas.com/<?php echo $_SESSION['id']; ?>.captcha?" />
                        <a class="captcha-refresh-button" href="http://www.easycaptchas.com/" onclick="document.getElementById('captchaimg').src+='rf=1';return false;">R</a><br />
                        <!-- Free Captcha provided courtesy of www.EasyCaptchas.com (please do not remove this link) -->
                        <noscript><a href="http://www.easycaptchas.com/" onclick="return false;" style="display:none">Captcha</a></noscript>
                    </div>

                    <?php if (!empty($data["captchaError"])) {
                        echo '<div class="invalid-feedback"><p>' . $data["captchaError"] . '</p></div>';
                    } ?>

                    <button id="submit" type="submit" value="submit">Reset password</button>
                    <a href="<?php echo URL_ROOT; ?>/users/login">I want to sign in instead</a>
                </form>
            </div>
        </div>
    </section>

    <?php
        require APP_ROOT . '/views/includes/footer.php';
    ?>
</body>
