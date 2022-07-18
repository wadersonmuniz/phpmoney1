<?php

namespace App\Controllers;

class Usuarios extends BaseController
{
    public function index()
    {
        echo view('usuarios/index');
    }
}
