<?php
/**
 * Class PasswordTemplate
 *
 * @author Ceytec <david@nani-games.net>
 */
class PasswordTemplate extends Template {

    /**
     * PasswordTemplate constructor.
     *
     * @param $password
     * @param $altMessage
     */
    public function __construct($password, $altMessage)
    {
        $this->setHeader("
            <head></head>
        ");
        $this->setBody("
            <h3>Your new temporary password:</h3>
            <h1>{$password}</h1>
            <br><br>
            <p>Important: Please make sure to change your password as soon as possible!</p>
        ");
        $this->setFooter("
            <footer></footer>
        ");
        $this->setAltMessage($altMessage);
    }
}