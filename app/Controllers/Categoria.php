<?php

namespace App\Controllers;

class Categoria extends BaseController
{
    public function index()
    {
        echo view('categorias/index');
    }
}
