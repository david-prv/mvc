<?php

/**
 * Generation of random temporary password
 *
 * @credits https://stackoverflow.com/a/6101969/15439717
 */
function generateTempPassword() {
        $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $password = array();
        $charLen = strlen($charset) - 1;
        for ($i = 0; $i < 10; $i++) {
            $randomChar = rand(0, $charLen);
            $password[] = $charset[$randomChar];
        }
        $password = implode($password);
        return $password;
    }
