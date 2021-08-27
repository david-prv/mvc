<?php
/**
 * If user isn't logged in --> redirect to login page
 */
if(!isLoggedIn()) {
    header('Location: ' .  URL_ROOT . '/users/login');
    exit();
}