<?php
/**
 * Class ConfirmTemplate
 *
 * @author Ceytec <david@nani-games.net>
 */
class ConfirmTemplate extends Template {

    /**
     * ConfirmTemplate constructor.
     *
     * @param $headline
     * @param $subheading
     * @param $message
     * @param $acceptUrl
     * @param $cancelUrl
     * @param $altMessage
     */
    public function __construct($headline, $subheading, $message, $acceptUrl, $cancelUrl, $altMessage)
    {
        $this->setHeader("
            <head></head>
         ");
        $this->setBody("
            <body>
                <center>
                    <h2>{$headline}</h2>
                    <h3>{$subheading}</h3>
                    <br/><br/>
                    <p>{$message}</p>
                    <br/>
                    <a href='{$acceptUrl}'>Yes, I confirm</a>
                    <br/>
                    <a href='{$cancelUrl}'>It wasn't me, please cancel this task</a>
                </center>
            </body>
        ");
        $this->setFooter("
            <footer></footer>
        ");
        $this->setAltMessage($altMessage);
    }
}