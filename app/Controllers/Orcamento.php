<?php

namespace App\Controllers;

class Orcamento extends BaseController
{
    public function index()
    {
        echo view('orcamentos/index');
    }
}
