<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use App\Models\LancamentoModel;
use App\Models\OrcamentoModel;

class Lancamento extends BaseController
{
    protected $categoriaModel;
    protected $lancamentoModel;
    protected $orcamentoModel;

    public function __construct(){
        $this->categoriaModel = new CategoriaModel();
        $this->lancamentoModel = new LancamentoModel();
        $this->orcamentoModel = new OrcamentoModel();
    }

    public function index($mes = null, $ano = null)
    {
        $post = $this->request->getPost();

        $ano = empty($post['ano']) ? (empty($ano) ? date("Y") : $ano) : $post['ano'];
        $mes = empty($post['mes']) ? (empty($mes) ? date("m") : $mes) : $post['mes'];

        $search = $this->request->getGet('search') ? : '';

        if(empty($search)){
            $this->categoriaModel->addMes($mes)->addAno($ano);
        }

        $categorias = $this->categoriaModel
        ->groupStart()
            ->addSearch($search, 'categorias.descricao', true)
            ->addSearch($search, 'lancamentos.descricao', true)
        ->groupEnd()
        ->addUserId($this->session->id_usuario)
        ->addOrder([
            'order' => [
                [
                    'campo' => 'tipo',
                    'sentido' => 'desc'
                ],
                [
                    'campo' => 'categorias.descricao',
                    'sentido' => 'asc'
                ]
            ]
        ])
        ->getComLancamento();

        $resultCategorias = [];
        $totalLancamentos = 0;
        //Agora para cada categoria busco seus respectivos lançamentos
        foreach($categorias as $categoria){
            
            if(empty($search)){
                $this->lancamentoModel->addMes($mes)->addAno($ano);
            }

            $lancamentos = $this->lancamentoModel
                ->groupStart()
                    ->addSearch($search, 'categorias.descricao', true)
                    ->addSearch($search, 'lancamentos.descricao', true)
                ->groupEnd()
                ->getByIdCategoria($categoria['id_categoria']);

            $valorOrcamento = $this->orcamentoModel
                ->addUserId($this->session->id_usuario)
                ->valorOrcamento($categoria['id_categoria']);

            if(empty($search)){
                $this->lancamentoModel->addMes($mes)->addAno($ano);
            }
            $totalPorCategoria = $this->lancamentoModel
                ->addUserId($this->session->id_usuario)
                ->addConsolidado(1)
                ->getIdCategoria($categoria['id_categoria'])
                ->getTotais();

            $resultCategorias[] = [
                'descricao' => $categoria['descricao_categoria'],
                'lancamentos' => $lancamentos,
                'totalPorCategoria' => $totalPorCategoria,
                'valorOrcamento' => $valorOrcamento,
                'orcamentoDisponivel' => (float)$valorOrcamento - (float)$totalPorCategoria
            ];
            $totalLancamentos += count($lancamentos);
        }

        $receitasTotalGeral = $this->lancamentoModel
            ->addConsolidado(1)
            ->addUserId($this->session->id_usuario)
            ->addTipo('r')
            ->getTotais();

        $despesasTotalGeral = $this->lancamentoModel
            ->addConsolidado(1)
            ->addUserId($this->session->id_usuario)
            ->addTipo('d')
            ->getTotais();
   
        $saldoTotalGeral = (float)$receitasTotalGeral - (float)$despesasTotalGeral;

        $receitasMesAtual = $despesasMesAtual = $saldoAnterior = 0;
        if(empty($search)){
            $receitasMesAtual = $this->lancamentoModel
                ->addUserId($this->session->id_usuario)
                ->addConsolidado(1)
                ->addMes($mes)
                ->addAno($ano)
                ->addTipo('r')
                ->getTotais();

            $despesasMesAtual = $this->lancamentoModel
                ->addUserId($this->session->id_usuario)
                ->addConsolidado(1)
                ->addMes($mes)
                ->addAno($ano)
                ->addTipo('d')
                ->getTotais();
            $dataReferencia = date('Y-m-t', strtotime("$ano-$mes-01"));         //t retorna o último dia do mês anterior
            $saldoAnterior = $this->lancamentoModel->getSaldoAnterior($dataReferencia);
        }
        

        $dados = [
            'mes' => !is_null($mes) ? $mes : (date("m")),
            'ano' => !is_null($ano) ? $ano : (date("Y")),
            'comboAnos' => comboAnos([
                'ano_inicial' => $this->lancamentoModel->addUserId($this->session->id_usuario)->getMenorAno()
            ]),
            'categorias' => $resultCategorias,
            'receitasTotalGeral' => $receitasTotalGeral,
            'despesasTotalGeral' => $despesasTotalGeral,
            'saldoTotalGeral' => $saldoTotalGeral,
            'receitasMesAtual' => $receitasMesAtual,
            'despesasMesAtual' => $despesasMesAtual,
            'saldoMesAtual' => (float)$receitasMesAtual - (float)$despesasMesAtual +$saldoAnterior,
            'saldoAnterior' => $saldoAnterior,
            'totalLancamentos' => $totalLancamentos,
            'search' => $search
        ];
        // echo '<pre>';
        // print_r($resultCategorias);
        // echo '</pre>';

        echo view('lancamentos/index', $dados);
    }

    /**
     * Carrega o formulário d novo lançamento
     *
     * @return void
     */
    public function create(){
        
        $data = [
            'titulo' => 'Novo lançamento',
            'dropDownCategorias' => $this->categoriaModel
            ->addUserId($this->session->id_usuario)
            ->addOrder([
                'campo' => 'descricao',
                'sentido' => 'asc'
            ])
            ->formDropDown([
                'opcaoNova' => true
            ])
        ];

        echo view('lancamentos/form', $data);
    }
}
