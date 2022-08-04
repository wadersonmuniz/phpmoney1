<?php namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model {

    /**
     * Vincula o id_usuario logado no campo da tabela 
     *
     * @param [type] $data
     * @return void
     */
    protected function vinculaIdUsuario($data) {
        $data['data']['usuarios_id'] = session()->id_usuario;

        return $data;
    }

    /**
     * Gera uma chave randômica e vincula ao campo chave da tabela
     *
     * @param [type] $data
     * @return void
     */
    protected function geraChave($data) {
        $data['data']['chave'] = md5(uniqid(rand(), true));

        return $data;
    }

    /**
     * Faz a conversão do valor brasileiro para americano
     *
     * @param [type] $data
     * @return void
     */
    protected function corrigeValor($data) {
        if (!isset($data['data']['valor'])) {
            return $data;
        }

        $data['data']['valor'] = str_replace('.', '', $data['data']['valor']);
        $data['data']['valor'] = str_replace(',', '.', $data['data']['valor']);
        return $data;
    }

    /**
     * converte a data para formato americano 
     *
     * @param [type] $data
     * @return void
     */
    protected function converterData($data) {
        return $data;
    }

    /**
     * Verifica se o registro sendo excluído pertence ao seu dono ou a algum membro de sua família
     *
     * @param [type] $data
     * @return void
     */
    protected function checaPropriedade($data) {
        return $data;
    }

    ##############################################################
    ///////////////////// MÉTODOS PÚBLICOS ///////////////////////
    ##############################################################

    /**
     * Injeta o campo categorias_id na query
     *
     * @param [type] $id_categoria
     * @return object
     */
    public function getIdCategoria($id_categoria = null): object {
        if(!is_null($id_categoria)){
            $this->where('categorias_id', $id_categoria);
        }
        return $this;
    }

    /**
     * Injeta o campo mês na query
     *
     * @param [type] $mes
     * @return object
     */
    public function addMes($mes = null): object {
        if(!is_null($mes)){
            $this->where("MONTH(data)", $mes);
        }
        return $this;
    }

     /**
     * Injeta o campo ano na query
     *
     * @param [type] $ano
     * @return object
     */
    public function addAno($ano  = null): object {
        if(!is_null($ano)){
            $this->where("YEAR(data)", $ano);
        }
        return $this;
    }

    /**
     * Retorna os registros baseados na informação de consolidação.
     * É preciso que a tabela 'lancamento' exixsta na query para usar este método.
     * 1 para Sim, 2 para Não
     *
     * @param integer|null $value
     * @return object
     */
    public function addConsolidado(int $value = null): object{
        if(!is_null($value)){
            $this->where('lancamentos.consolidado', $value); //(tabela.campo)
        }
        return $this;
    }

    /**
     * Insere o campo tipo na tabela de busca (tabela-categorias)
     *
     * @param [type] $tipo
     * @return void
     */
    public function addTipo($tipo = null) {

        if (!is_null($tipo)) {
            $this->where('tipo', $tipo);
            if($this->table != 'categorias'){
                $this->join('categorias', "categorias.id = {$this->table}.categorias_id AND {$this->table}.usuarios_id = categorias.usuarios_id");
            }
            
        }
        return $this;
    }


    /**
     * Injeja abusca por chave dentro da query
     *
     * @param string|null $chave
     * @return mixed
     */
    public function getByChave(string $chave = null) {
        if (!is_null($chave)) {
            return $this->find($chave);
        }
    }

    /**
     * Retorna todos os registros
     *
     * @return array
     */
    public function getAll(): array { 
        return $this->findAll();
    }

    /**
     * Injeta o campo ordem na query
     *
     * @param array|null $order
     * @return object
     */
    public function addOrder(array $order = null): object {
        if (!is_null($order)) {
            if (key_exists('order', $order)) {
                foreach($order['order'] as $ordem) {
                    $this->orderBy($ordem['campo'], $ordem['sentido']);
                }
            } else {
                $this->orderBy($order['campo'], $order['sentido']);
            }
        } 
        return $this;
    }

    /**
     * Injeta o campo id_usuario na query
     *
     * @param integer|null $id_usuario
     * @return object
     */
    public function addUserId(int $id_usuario = null): object {
        if (!is_null($id_usuario)) {
            $this->where("{$this->table}.usuarios_id", $id_usuario);
        }
        return $this;
    }

    /**
     * Injeta a busca por like na query
     * Se o parâmetro or for true, faz a busca por orLike
     *
     * @param string|null $search
     * @param string|null $campo
     * @param [type] $or
     * @return object
     */
    public function addSearch(string $search = null, string $campo = null, $or = null): object {
        if (!is_null($search) && !is_null($campo)) {
            if(!is_null($or)){
                $this->orlike($campo, $search);
            } else {
                $this->like($campo, $search);
            }     
        }
        return $this;
    }
}