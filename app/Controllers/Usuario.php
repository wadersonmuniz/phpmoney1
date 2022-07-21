<?php

namespace App\Controllers;

class Usuario extends BaseController
{
    public function index()
    {
        echo view('usuarios/index');
    }
}
