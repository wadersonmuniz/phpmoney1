<?php

namespace App\Controllers;

use App\Models\CategoriaModel;

class Categoria extends BaseController
{
    protected $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');

        //$registros_count = $categoriaModel->findAll();    //Usar para obter o total de registros

        $categorias = $this->categoriaModel
            ->addUserId($this->session->id_usuario)
            ->addSearch($search)
            ->addOrder([
                'order' => [
                    [
                        'campo' => 'tipo',
                        'sentido' => 'desc'
                    ],
                    [
                        'campo' => 'descricao',
                        'sentido' => 'asc'
                    ]
                ]
            ])
            ->paginate(5);

        // dd($categoria); 
        $data = [
            //'registros_count' => $registros_count,
            'categorias' => $categorias,
            'pager' => $this->categoriaModel->pager,
            'search' => $search
        ];
        echo view('categorias/index', $data);
    }

    /**
     * Chama o form de criação
     *
     * @return void
     */
    public function create() {
        $data = [
            'titulo' => 'Nova ategoria'
        ];

        echo view('categorias/form', $data);
    }
    
    /**
     * Salva os dados vindos do form
     *
     * @return void
     */
    public function store() {
        $post = $this->request->getPost();
        // dd($post);
        if ($this->categoriaModel->save($post)) {
            echo 'Registro salvo comsucesso.';
        } else {
            echo view('categorias/form', [
               'titulo' => 'Nova categoria',
               'errors' => $this->categoriaModel->errors()
            ]);
        }
    }
}

