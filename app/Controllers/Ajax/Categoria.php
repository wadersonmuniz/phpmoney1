<?php namespace App\Controllers\Ajax;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;

class Categoria extends BaseController {

    protected $categoriaModel;

    public function __construct() {
        $this->categoriaModel = new CategoriaModel();
    }

    /**
     * Salva a categoria via ajax no BD
     *
     * @return void
     */
    public function store() {
        $result = [];
        
        if ($this->request->isAJAX()) {
            $post = $this->request->getPost();
            if ($this->categoriaModel->save($post)) {
                $result = [
                    'error' => false,
                    'code' => 201, //retorna succeso e criado
                    'message' => 'created',
                    'id' => $this->categoriaModel->getInsertID()  //recupera o id do item criado
                ];
            } else {
                $result = [
                    'error' => true,
                    'message' => $this->categoriaModel->errors()
                ];
            }
        } else {
            $result = [
                'error' => true,
                'code' => 400,
                'message' => '[ERRO] - Somente requisicoes AJAX sao permitidas',
            ];
        }
        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    public function get() {
        if ($this->request->isAJAX()) {
            $result = [];

            $tipo = $this->request->getPost('tipo');
            if (!is_null($tipo)) {
                $this->categoriaModel->addTipo($tipo);
            }

            $result = $this->categoriaModel
            ->addUserId($this->sessiion->id_usuario)
            ->addOrder()
            ->getAll([
                'campo' => 'descricao',
                'sentido' => 'asc'
            ])
            ->getAll();
        } else {
            $result = [
                'error' => true,
                'code' => 400,
                'message' => '[ERRO] - Somente requisicoes AJAX sao permitidas',
            ];
        }

        echo json_encode($result, JSON_PRETTY_PRINT);
    }
}

