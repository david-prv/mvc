<?php
/**
 * Class Template
 *
 * @author Ceytec <david@nani-games.net>
 */
class Template {
    private $header;
    private $footer;
    private $body;
    private $altMessage;

    /**
     * Returns header
     *
     * @return string
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * Returns body
     *
    * @return string
    */
    public function getBody() {
        return $this->body;
    }

    /**
     * Returns footer
     *
     * @return string
     */
    public function getFooter() {
        return $this->footer;
    }

    /**
     * Returns alternate message
     *
     * @return string
     */
    public function getAltMessage() {
        return $this->altMessage;
    }

    /**
     * Composes html and returns it
     *
     * @return string
     */
    public function getHtml() {
        return '<html>' . $this->getHeader() . $this->getBody() . $this->getFooter() . '</html>';
    }

    /**
     * Sets a new alternate message
     *
     * @param $message
     * @return bool
     */
    public function setAltMessage($message) {
        $this->altMessage = $message;
        return true;
    }

    /**
     * Sets a new header
     *
     * @param $html
     * @return bool
     */
    public function setHeader($html) {
        $this->header = $html;
        return true;
    }

    /**
     * Sets a new body
     *
     * @param $html
     * @return bool
     */
    public function setBody($html) {
        $this->body = $html;
        return true;
    }

    /**
     * Sets a new footer
     *
     * @param $html
     * @return bool
     */
    public function setFooter($html) {
        $this->footer = $html;
        return true;
    }
}