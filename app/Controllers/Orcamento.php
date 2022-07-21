<?php

namespace App\Controllers;

use App\Models\OrcamentoModel;

class Orcamento extends BaseController
{
    public function index()
    {
        echo view('orcamentos/index');
    }
}
