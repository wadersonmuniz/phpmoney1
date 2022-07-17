<?php

namespace App\Controllers;

class Lancamento extends BaseController {
    /**
     * Carrega a view lançamentos
     *
     * @return void
     */
    public function index() {
        echo view('lancamentos/index');
    }
}