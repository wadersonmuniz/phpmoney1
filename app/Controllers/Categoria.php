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
            // echo 'Registro salvo comsucesso.';
            return redirect()->to('/mensagem/sucesso')->with('mensagem', [
                'mensagem' => 'Registro salvo com sucesso',
                'link' => [
                    'to' => 'categoria',
                    'texto' => 'Voltar para ategorias'
                ]
            ]);
        } else {
            echo view('categorias/form', [
               'titulo' => !empty($post['chave']) ? 'Editar categoria' : 'Nova categoria',
               'errors' => $this->categoriaModel->errors()
            ]);
        }
    }

    /**
     * Chama o form de edição com os campos populado
     *
     * @param [type] $chave
     * @return void
     */
    public function edit($chave) {
        
        $categoria = $this->categoriaModel->addUserId($this->session->id_usuario)->getByChave($chave);

        if (!is_null($categoria)) {
            $data = [
                'titulo' => 'Editar categoria',
                'categoria' => $categoria
            ];
            echo view('categorias/form', $data);
        } else {
            return redirect()->to('/mensagem/erro')->with('mensagem', [
                'mensagem' => 'Categoria não encontrada.',
                'link' => [
                    'to' => 'categoria',
                    'texto' => 'Voltar para categorias'
                ]
            ]);
        }
    }

    public function delete($chave = null) {
        if ($this->categoriaModel->addUserId($this->session->id_usuario)->delete($chave)) {
            return redirect()->to('/mensagem/sucesso')->with('mensagem', [
                'mensagem' => 'Categoria excluida com sucesso.',
                'link' => [
                    'to' => 'categoria',
                    'texto' => 'Voltar para ategorias'
                ]
            ]);
        } else {
            return redirect()->to('/mensagem/erro')->with('mensagem', [
                'mensagem' => 'Erro ao excluir a categoria.',
                'link' => [
                    'to' => 'categoria',
                    'texto' => 'Voltar para ategorias'
                ]
            ]);
        }
    }
}

