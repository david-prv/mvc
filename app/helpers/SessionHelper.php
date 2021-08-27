<?php

/**
 * Checks if user session is on
 *
 * @return bool
 */
function isLoggedIn() {
    if(isset($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if user identity is confirmed
 *
 * @return bool
 */
function hasConfirmedIdentity() {
    if(isset($_SESSION['identity'])) {
        return ($_SESSION['identity'] == User::USER_IDENTITY_CONFIRMED);
    } else {
        return false;
    }
}
