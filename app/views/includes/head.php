<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if(isLoggedIn()) { echo "<script src=\"" . URL_ROOT . "/public/js/inactivity.js\"></script>
                                   <script type='text/javascript'>startHandler(" . INACTIVITY_TIME . ", '" . URL_ROOT . "')</script>"; }?>
</head>
