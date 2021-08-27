<?php

/**
 * Validates Password and checks if password
 * is given or free
 *
 * @param $password
 * @return string
 */
function validatePassword($password) {
    $passwordValidation = "/^(.{0,7}|[^a-z]*|[^\d]*)$/i";
    if(empty($password)) {
        return "Password can not be empty";
    } elseif (strlen($password) < 6) {
        return "Password must be at least 8 characters";
    } elseif(preg_match($passwordValidation, $password)) {
        return "Password must have at least one numeric value";
    }
    return '';
}

/**
 * Validates Username and checks if username
 * is given or free
 *
 * @param $username
 * @param $userModel
 * @return string
 */
function validateUserName($username, $userModel) {
    $nameValidation = "/^[a-zA-Z0-9]*$/";
    if(empty($username)) {
        return "Username can not be empty";
    } elseif(!preg_match($nameValidation, $username)) {
        return "Username can only contain numbers and letters";
    } else {
        if($userModel->findUserByName($username)) {
            return "Username is already taken";
        }
    }
    return null;
}

/**
 * Validates Email and checks if Email
 * is given or free
 *
 * @param $email
 * @param $userModel
 * @return string
 */
function validateEmail($email, $userModel) {
    if(empty($email)) {
        return "Email can not be empty";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Illegal Email Format";
    } else {
        if($userModel->findUserByEmail($email)) {
            return "Email is already taken";
        }
    }
    return null;
}

/**
 * Matches passwords
 *
 * @param $password
 * @param $confirmPassword
 * @return string
 */
function validateConfirmPassword($password, $confirmPassword) {
    if(empty($confirmPassword)) {
        return "Please confirm your password";
    } else {
        if ($password !== $confirmPassword) {
            return "Passwords do not match";
        }
    }
    return null;
}

/**
 * Matches emails
 *
 * @param $mail
 * @param $confirmMail
 * @return string
 */
function validateConfirmEmail($mail, $confirmMail) {
    if(empty($confirmMail)) {
        return "Please confirm your mail";
    } else {
        if ($mail !== $confirmMail) {
            return "Emails do not match";
        }
    }
    return null;
}