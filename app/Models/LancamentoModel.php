<?php namespace App\Models;

use DateTime;

class LancamentoModel extends BaseModel {
    protected $table = 'lancamentos';
    protected $primaryKey = 'chave';

    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $useTimestamps = true;

    protected $beforeInsert = ['vinculaIdUsuario', 'geraChave', 'corrigeValor', 'converterData'];
    protected $beforeUpdate = ['converterData', 'corrigeValor', 'checaPropriedade'];

    // protected $allowCallbacks = false;

    protected $allowedFields = [
        'usuarios_id',
        'chave',
        'categorias_id',
        'descricao',
        'valor',
        'data',
        'notificar_por_email',
        'consolidado'
    ];

    protected $validationRules = [
        'descricao' => [
            'label' => 'Descrição',
            'rules' => 'required'
        ],
        'categorias_id' => [
            'label' => 'Categoria',
            'rules' => 'required'
        ],
        'valor' => [
            'label' => 'Valor',
            'rules' => 'required'
        ],
        'data' => [
            'label' => 'Data ',
            'rules' => 'required'
        ]
    ];

    /**
     * Retorna todos os lançamentos vinculados a uma categoria
     *
     * @param [type] $id_categoria
     * @return void
     */
    public function getByIdCategoria($id_categoria){
        $this->select("
            lancamentos.id as id_lancamento,
            lancamentos.created_at,
            lancamentos.usuarios_id,
            categorias.tipo,
            if(tipo = 'r', 'Receita', 'Despesa') as tipo_formatado,
            lancamentos.descricao,
            lancamentos.data,
            lancamentos.categorias_id,
            notificar_por_email,
            if(notificar_por_email = 1, 'Sim', 'Não') as notificar_por_email_formatado,
            lancamentos.valor,
            lancamentos.chave,
            consolidado,
            if(consolidado = 1, 'Sim', 'Não') as consolidado_formatado
        ");
        $this->where('categorias_id', $id_categoria);
        $this->join('categorias', 'categorias.id = lancamentos.categorias_id');
        return $this->findAll();
    }

    /**
     * Retorna as somas dos lançamentos
     *
     * @return float
     */
    public function getTotais(): float {
        $this->selectSum('valor');
        $result = $this->first();

        return !is_null($result['valor']) ? $result['valor'] : 0.00;
    }

    /**
     * Retorna o ano do lançamento mais antigo
     * se não encontrar nada retorna o ano atual
     *
     * @return void
     */
    public function getMenorAno(){
        $result = $this
        ->select('MIN(YEAR(data)) as menor_ano')
        ->first();

        return !is_null($result['menor_ano']) ? $result['menor_ano'] : date('Y');
    }

    /**
     * Calcula o saldo do mês anterios usando o parâmentro como data de referencia,
     * começando pelo lançamento mais antigo
     *
     * @param string|null $data
     * @return float
     */
    public function getSaldoAnterior(string $data = null):float{
        $dataReferencia = new DateTime($data);

        $id_usuario = session()->id_usuario;

        $dataAnterior = $dataReferencia->modify('last day of last month')->format('Y-m-d');
        $dataInicial = $this->addUserId($id_usuario)->getMenorAno(). "-01-01";

        $this->selectSum('valor');
        $this->where("data BETWEEN '{$dataInicial}' AND '{$dataAnterior}'");
        $this->where('tipo', 'd');
        $this->join('categorias', 'categorias.id = lancamentos.categorias_id');
        $this->where('consolidado', 1);
        $this->addUserId($id_usuario);
        $totalDespesas = (float) $this->first()['valor'];

        $this->selectSum('valor');
        $this->where("data BETWEEN '{$dataInicial}' AND '{$dataAnterior}'");
        $this->where('tipo', 'r');
        $this->join('categorias', 'categorias.id = lancamentos.categorias_id');
        $this->where('consolidado', 1);
        $this->addUserId($id_usuario);
        $totalReceitasas = (float) $this->first()['valor'];

        $saldo = $totalReceitasas - $totalDespesas;

        return !is_null($saldo) ? $saldo : 0.00;
    
    }
}