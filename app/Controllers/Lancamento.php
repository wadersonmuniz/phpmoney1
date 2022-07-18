<?php

namespace App\Controllers;

class Lancamento extends BaseController
{
    public function index()
    {
        echo view('lancamentos/index');
    }
}
