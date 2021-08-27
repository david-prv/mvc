<?php
/**
 * Class Pages Controller
 *
 * @author Ceytec <david@nani-games.net>
 * @author Nolan <nolan@nani-games.net>
 */
class Pages extends Controller {

    /**
     * Store Page
     */
    public function store() {
        $data = [
            'title' => 'Store'
        ];

        $this->view('pages/store', $data);
    }

    /**
     * Fallback
     */
    public function index() {

        $data = [
            'title' => 'Home Page'
        ];

        $this->view('pages/index', $data);
    }
}