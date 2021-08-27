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

    <section id="account-login" class="account-managing">
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

                <form id="login-form" action="<?php echo URL_ROOT; ?>/users/login" method="POST">
                    <input type="text" placeholder="Username ... *" name="username" />
                    <input type="password" placeholder="Password ... *" name="password" autocomplete="off" />

                    <?php if (!empty($data["usernameError"]) || !empty($data["passwordError"])) {
                        echo '<div class="invalid-feedback"><p>' . $data["usernameError"] . $data["passwordError"] . '</p></div>';
                    } ?>

                    <button id="submit" type="submit" value="submit">Login</button>

                    <a href="<?php echo URL_ROOT; ?>/users/reset">I forgot my password</a>
                    <a href="<?php echo URL_ROOT; ?>/users/register">I do not have an account yet</a>
                </form>
            </div>
        </div>
    </section>

    <?php
        require APP_ROOT . '/views/includes/footer.php';
    ?>
</body>
