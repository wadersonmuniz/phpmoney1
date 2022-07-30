<?php namespace App\Models;

class OrcamentoModel extends BaseModel {
    protected $table = 'orcamentos';
    protected $primaryKey = 'chave';

    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $useTimestamps = true;

    protected $beforeInsert = ['corrigeValor', 'vinculaIdUsuario', 'geraChave'];
    protected $beforeUpdate = ['corrigeValor'];

    // protected $allowCallbacks = false;

    protected $allowedFields = [
        'usuarios_id',
        'categorias_id',
        'chave',
        'valor',
        'descricao',
        'notificar_por_email'
    ];

    protected $validationRules = [
        'descricao' => [
            'label' => 'Categorias',
            'rules' => 'required'
        ],
        'categorias_id' => [
            'label' => 'Tipo',
            'rules' => 'required|numeric'
        ],
        'valor' => [
            'label' => 'Valor',
            'rules' => 'required'
        ]
    ];

    /**
     * Retorna todos os orçamentos já com ascategorias vinculadas
     *
     * @return void
     */
    //concatenação tabela orçamento --> categoria
    public function getAllWithCategorias() {
        $this->select(
            "orcamentos.chave as chave_orcamento,
            orcamentos.descricao as descricao_orcamento,
            categorias.chave as chave_categoria,
            categorias.descricao as descricao_categoria,
            valor,
            notificar_por_email"
        );
        
        $this->join('categorias', 'categorias.id = orcamentos.categorias_id');
        return $this->findAll();
    }


}   