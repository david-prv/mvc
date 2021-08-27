<head>
    <title><?php echo TITLE_PREFIX . $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/store.css" type="text/css">
</head>

<body>
    <template id="preset-showcase-gamecard-content">
        <li class="showcase-gamecard-content">
            <div class="image-container">
                <picture>
                    <source srcset="<?php echo URL_ROOT; ?>/img/welcome.webp" type="image/webp">
                    <source srcset="<?php echo URL_ROOT; ?>/img/welcome.JPG" type="image/JPG">
                    <img src="<?php echo URL_ROOT; ?>/img/welcome.JPG" alt="Welcome">
                </picture>
            </div>
        </li>
    </template>

    <?php
        require APP_ROOT . '/views/includes/navbar.php';
    ?>

    <section id="download-page">
        <div class="showcase-container">
            <div class="image-container">
                <picture>
                    <source srcset="<?php echo URL_ROOT; ?>/img/welcome.webp" type="image/webp">
                    <source srcset="<?php echo URL_ROOT; ?>/img/welcome.JPG" type="image/JPG">
                    <img src="<?php echo URL_ROOT; ?>/img/welcome.JPG" alt="Welcome">
                </picture>
            </div>
            <div class="details">
                <h2>Title</h2>
                <button>Download</button>
            </div>
        </div>
    </section>

    <script src="<?php echo URL_ROOT; ?>/js/base.js"></script>
</body>