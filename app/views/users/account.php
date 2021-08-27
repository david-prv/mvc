<?php require APP_ROOT . '/views/includes/auth.php'; ?>

<head>
    <title><?php echo TITLE_PREFIX . $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/account_managing.css" type="text/css">
</head>

<body>
    <h2><?php echo $data['title'];?></h2>
    <?php require APP_ROOT . '/views/includes/navbar.php'; ?>
</body>