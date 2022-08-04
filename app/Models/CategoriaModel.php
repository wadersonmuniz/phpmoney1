<?php namespace App\Models;

class CategoriaModel extends BaseModel {
    protected $table = 'categorias';
    protected $primaryKey = 'chave';

    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $useTimestamps = true;

    protected $beforeInsert = ['vinculaIdUsuario', 'geraChave'];
    protected $beforeUpdate = ['checaPropriedade'];

    // protected $allowCallbacks = false;

    protected $allowedFields = [
        'usuarios_id',
        'chave',
        'tipo',
        'descricao'
    ];

    protected $validationRules = [
        'descricao' => [
            'label' => 'Descrição',
            'rules' => 'required'
        ],
        'tipo' => [
            'label' => 'Tipo',
            'rules' => 'required'
        ]
    ];

    /**
     * Gera uma array pronta para ser populada na função dropdown,
     * se for passado o parametro opcaoNova, insere a opção Nova Categoria
     *
     * @param array|null $param
     * @return void
     */
    public function formDropDown(array $params = null) {

        $this->select('id, descricao, tipo');

        if (!is_null($params) && isset($params['tipo'])) {
            $this->where(['tipo' => $params['tipo']]);
        }

        if (!is_null($params) && isset($params['id'])) {
            $this->where(['id' => $params['id']]);
        }

        $categoriasArrays = $this->findAll();

        $optionCategorias = array_column($categoriasArrays, 'descricao', 'id');

        // dd($optionCategorias);

        $optionSelecione = [
            '' => 'Selecione...'
        ];

        $selectConteudo = $optionSelecione + $optionCategorias;

        $novaCategoria = [];

        if (!is_null($params) && isset($params['opcaoNova'])) {
            if ((bool)$params['opcaoNova'] === true) {
                $novaCategoria = [
                    '---' => [
                        'n' => 'Nova categoria...'
                    ]
                ];
            }
        }

        return $selectConteudo + $novaCategoria;
     }

     /**
      * Retorna todas as categorias com lançamentos
      *
      * @return void
      */
     public function getComLancamento(){
        $this->select(
            "
            tipo,
            categorias.usuarios_id,
            categorias.descricao as descricao_categoria,
            categorias.id as id_categoria,
            lancamentos.descricao as descricao_lancamento,
            lancamentos.id as id_lancamento
            "
        );
        $this->join('lancamentos', 'lancamentos.categorias_id = categorias.id');
        $this->groupBy('descricao_categoria');
        return $this->findAll();
     }
}