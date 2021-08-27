<head>
    <title><?php echo TITLE_PREFIX . $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/account_managing.min.css" type="text/css">
</head>

<body>
    <?php
        require APP_ROOT . '/views/includes/navbar.php';

        if(isLoggedIn()) {
            header('Location: ' . URL_ROOT . '/users/account');
        }
    ?>

    <section id="account-register" class="account-managing">
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
                <form id="register-form" action="<?php echo URL_ROOT; ?>/users/register" method="POST">
                    <input type="text" placeholder="Username ... *" name="username">
                    <?php if (!empty($data["usernameError"])) {
                       echo '<div class="invalid-feedback"><p>' . $data["usernameError"] . '</p></div>';
                    } ?>
                    <input type="email" placeholder="Email ... *" name="email" autocomplete="off" />
                    <?php if (!empty($data["emailError"])) {
                       echo '<div class="invalid-feedback"><p>' . $data["emailError"] . '</p></div>';
                    } ?>
                    <input type="password" placeholder="Password ... *" name="password" autocomplete="new-password" />
                    <?php if (!empty($data["passwordError"])) {
                        echo '<div class="invalid-feedback"><p>' . $data["passwordError"] . '</p></div>';
                    } ?>
                    <input type="password" placeholder="Confirm Password ... *" name="confirmPassword" autocomplete="new-password" />
                    <?php if (!empty($data["confirmPasswordError"])) {
                        echo '<div class="invalid-feedback"><p>' . $data["confirmPasswordError"] . '</p></div>';
                    } ?>
                    <input placeholder="Answer captcha ... *" name="captcha" size="16" autocomplete="off" />
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
                    <button id="submit" type="submit" value="submit">Register</button>
                    <a href="<?php echo URL_ROOT; ?>/users/login">I already have an account</a>
                </form>
            </div>
        </div>
    </section>

    <?php
        require APP_ROOT . '/views/includes/footer.php';
    ?>

    <script src="<?php echo URL_ROOT; ?>/public/js/register.js"></script>

    <svg version="1.1" viewBox="0 0 1000 1000" style="display:none;visibility:hidden;"
         xmlns="http://www.w3.org/2000/svg">
        <defs>
            <path id="arrow-form-slider"
                  d="m 0,426.07749 365,-0.001 V 207.87403 L 874.94785,500.00001 365,792.12598 V 573.92255 H 0 Z" />
        </defs>
    </svg>
</body>