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
    // protected $beforeUpdate = ['checaPropriedade'];

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

}