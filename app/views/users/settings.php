<head>
    <title><?php echo TITLE_PREFIX . $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/account_managing.css" type="text/css">
</head>

<body>
    <?php
        require APP_ROOT . '/views/includes/head.php';
        require APP_ROOT . '/views/includes/navbar.php';
        require APP_ROOT . '/views/includes/auth.php';
    ?>

    <center>
    <!--- Full width --->
    <div class="container-login">
        <!--- 80% View --->
        <div class="wrapper-login">
            <h2><?php echo $data['title']; ?></h2>
            <form id="change-profile-picture-form" action="<?php echo URL_ROOT; ?>/users/settings" method="POST">
                <h3>Change profile picture:</h3>
                <select name="profile_picture">
                    <?php
                        foreach($data['options_profile_pictures'] as $item):
                    ?>
                        <option class="pic-option" value="<?php echo $item->picture_id; ?>" <?php
                            if($item->picture_id == ($data['saved_profile_picture'])->picture_id) echo "selected";
                        ?>>
                            <?php echo $item->picture_path; ?>
                        </option>
                    <?php
                        endforeach;
                    ?>
                </select>
                <button id="submit" type="submit" value="submit">Save profile picture</button>
            </form>
            <form id="change-profile-wallpaper-form" action="<?php echo URL_ROOT; ?>/users/settings" method="POST">
                <h3>Change wallpaper:</h3>
                <select name="profile_wallpaper">
                    <?php
                        foreach($data['options_profile_wallpapers'] as $item):
                    ?>
                        <option class="pic-option" value="<?php echo $item->wallpaper_id; ?>"
                    <?php
                        if($item->wallpaper_id == ($data['saved_profile_wallpaper'])->wallpaper_id) echo "selected";
                    ?>>
                    <?php echo $item->wallpaper_path; ?>
                        </option>
                    <?php
                        endforeach;
                    ?>
                </select>
                <button id="submit" type="submit" value="submit">Save profile wallpaper</button>
            </form>
            <form id="switch-two-auth-form" action="<?php echo URL_ROOT; ?>/users/settings" method="POST">
                <h3>Activate/Deactivate 2-Factor-Authentication:</h3>
                <select name="two_auth">
                    <option value="active" <?php if($data['saved_two_auth'] == TWO_AUTH_ACTIVE) echo "selected";?>>Active</option>
                    <option value="inactive" <?php if($data['saved_two_auth'] == TWO_AUTH_INACTIVE) echo "selected";?>>Inactive</option>
                </select>
                <button id="submit" type="submit" value="submit">Save 2FA setting</button>
            </form>

            <span class="invalid-feedback">
                <?php echo $data['saveSettingsError']; ?>
            </span>
            <span class="confirmation-feedback">
                <?php echo $data['saveSettingsConfirmation']; ?>
            </span>
            <?php if($data['is_validated'] !== true): ?>
                <form id="send-new-confirmation-email-form" action="<?php echo URL_ROOT; ?>/tasks/create/<?php echo Task::TASK_CONFIRM_IDENTITY; ?>" method="POST">
                    <h3>New confirmation mail:</h3>
                    <button id="submit" type="submit" value="submit">Resend confirmation mail</button>
                </form>
            <?php endif; ?>
            <form id="change-username-form" action="<?php echo URL_ROOT; ?>/tasks/create/<?php echo Task::TASK_CONFIRM_USERNAME; ?>" method="POST">
                <h3>Change username:</h3>
                <p>Current username: <strong><?php echo $_SESSION['user_name']; ?></strong></p>
                <input name="newName" type="text" placeholder="Your new username"/>
                <button id="submit" type="submit" value="submit">Save new username</button>
            </form>
            <form id="change-password-form" action="<?php echo URL_ROOT; ?>/tasks/create/<?php echo Task::TASK_CONFIRM_PASSWORD; ?>" method="POST">
                <h3>Change password:</h3>
                <input name="oldPassword" type="password" placeholder="Old password"/>
                <input name="newPassword" type="password" placeholder="New password"/>
                <input name="confirmPassword" type="password" placeholder="Confirm password"/>
                <button id="submit" type="submit" value="submit">Save new password</button>
            </form>
            <form id="change-email-form" action="<?php echo URL_ROOT; ?>/tasks/create/<?php echo Task::TASK_CONFIRM_EMAIL; ?>" method="POST">
                <h3>Change email:</h3>
                <p>Current email: <strong><?php echo $_SESSION['user_email']; ?></strong></p>
                <input name="newMail" type="email" placeholder="Your new email address"/>
                <input name="confirmMail" type="email" placeholder="Confirm email address"/>
                <button id="submit" type="submit" value="submit">Save new email</button>
            </form>
        </div>
    </div>
    </center>
</body>
