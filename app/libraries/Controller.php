<?php
/**
 * Class Main Controller
 *
 * @author Ceytec <david@nani-games.net>
 */
class Controller {

    /**
     * Load a model
     *
     * @param $model
     * @return mixed
     */
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    /**
     * Load a view
     *
     * @param $view
     * @param array $data
     */
    public function view($view, $data = []) {
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            die("Critical! View does not exists!");
        }
    }

}