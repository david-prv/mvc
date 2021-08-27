<?php
 /**
  * Libraries
  */
 require_once 'libraries/Core.php';
 require_once 'libraries/Controller.php';
 require_once 'libraries/Database.php';
 require_once 'libraries/Template.php';
 require_once 'libraries/PHPMailer/Exception.php';
 require_once 'libraries/PHPMailer/PHPMailer.php';
 require_once 'libraries/PHPMailer/SMTP.php';

 /**
  * Helpers
  */
 require_once 'helpers/SessionHelper.php';
 require_once 'helpers/ValidationHelper.php';
 require_once 'helpers/MailHelper.php';
 require_once 'helpers/PasswordHelper.php';

 /**
  * Templates
  */
 require_once 'templates/ConfirmTemplate.php';
 require_once 'templates/PasswordTemplate.php';

 /**
  * Composer Autoloader
  */
 require_once 'vendor/autoload.php';

 /**
  * Configuration
  */
 require_once 'config/config.php';

 /**
  * Default head
  */
 require_once 'views/includes/head.php';

 /**
  * Initiate Core
  */
 $init = new Core();