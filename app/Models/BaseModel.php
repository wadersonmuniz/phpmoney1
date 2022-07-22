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
            $this->where('usuarios_id', $id_usuario);
        }
        return $this;
    }

    /**
     * Injeta o campo search na query
     *
     * @param [type] $search
     * @return object
     */
    public function addSearch(string $search = null): object {
        if (!is_null($search)) {
            $this->like('descricao', $search);
        }
        return $this;
    }
}