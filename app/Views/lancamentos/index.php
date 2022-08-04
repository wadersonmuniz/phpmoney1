<?php echo $this->extend('_common/layout') ?>
<?php echo $this->section('content') ?>

<script>
    function confirma() {
        if (!confirm("Deseja excluir o registro?")) {
            return false;
        }
        return true;
    }

    $(function(){
        $('#ano').change(function(){
            $('#formAno').submit()
        })
    })
</script>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo anchor('', "Home") ?></li>
        <li class="breadcrumb-item active" aria-current="pag">Lançamentos</li>
    </ol>
</nav>

<div class="row no-gutters d-flex justify-content-center justify-content-sm-between">
    <div class="my-0">
        <h1>Lançamentos</h1>
    </div>
    <div class="text-center pt-3">
        <span class="text-success">Receitas Geral: R$ <?php echo number_format($receitasTotalGeral, 2, ',', '.') ?></span> - 
        <span class="text-danger">Despesas Geral: R$ <?php echo number_format($despesasTotalGeral, 2, ',', '.') ?></span> = 
        <?php if($saldoTotalGeral > 0): ?>
            <span class="text-success font-weight-bold">Saldo Geral: R$ <?php echo number_format($saldoTotalGeral, 2, ',', '.') ?></span>
        <?php else: ?>
            <span class="text-danger font-weight-bold">Saldo Geral: R$ <?php echo number_format($saldoTotalGeral, 2, ',', '.') ?></span>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row d-flex justify-content-center justify-content-sm-start">
            <div class="text-center">Últimos lançamentos - <?php echo $totalLancamentos ?> - registros encontrados - <strong class="text-uppercase"><?php echo date("m/Y") ?></strong></div>
            <div class="d-none d-sm-block">&nbsp; - &nbsp;</div>
            <div class="text-center"><?php echo anchor('lancamento', 'Selecionar hoje', ['title' => 'Retorna para a data atual']) ?></div>
        </div>
    </div>
    <div class="card-body">
        <div class="row no-gutters d-flex justify-content-center justify-content-sm-between">
            <div class="my-3">
                <?php echo anchor('lancamento/create', 'Novo Lançamento', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php echo form_open('lancamento', ['class' => 'form-inlie', 'method' => 'GET']) ?>
                <div class="form-group d-flex justify-content-center my-3">
                    <input type="search" name="search" autocomplete="off" placeholder="Busca..." class="form-control" value="<?php echo !empty($search) ? $search : '' ?>">
                    <input type="submit" value="OK" class="ml-2 btn btn-primary">
                </div>
            </form>
        </div>
        <div class="row no-gutters d-flex justify-content-center">
            <?php echo form_open('lancamento', ['id' => 'formAno']) ?>
                <?php echo form_dropdown('ano', $comboAnos, $ano, ['id' => 'ano', 'class' => 'form-control mb-2']) ?>
                <input type="hidden" name="mes" value="<?php echo $mes; ?>">
            </form>
        </div>
        <div class="row no-gutters d-flex justify-content-center mb-3">
            <?php $meses = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]; ?>
            <?php //$mes = 1; ?>
            <?php //$ano = 2022; ?>
            <?php foreach($meses as $mes_loop): ?>
                <?php $classe = $mes == $mes_loop ? 'bg-warning' : ''; ?>
                <a href="<?php echo base_url("lancamento/index/{$mes_loop}/{$ano}") ?>" class="nav-link <?php echo $classe; ?>"><span class="text-uppercase small"><?php echo nomeMes($mes_loop) ?></span></a>
            <?php endforeach; ?>
        </div>
        <div class="table-responsive">
            <table class="table table-stripped table-hover">
                <thead>
                    <tr class="bg-dark text-white">
                        <th>Descrição</th>
                        <th>Data</th>
                        <th>Tipo</th>
                        <th>Consolidado?</th>
                        <th>Notificar</th>
                        <th>Valor</th>
                        <th class="text-center">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($categorias) > 0): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            
                            <?php $classe = $categoria['totalPorCategoria'] > $categoria['valorOrcamento'] ? 'text-danger' : 'text-info' ?>
                            <?php if(!is_null($categoria['valorOrcamento'])): ?>
                                <tr>
                                    <td colspan="7" class="bg-light text-uppercase font-weight-bold"><?php echo $categoria['descricao'] ?><span class="<?php echo $classe; ?> ml-3 py-2"><?php echo !is_null($categoria['valorOrcamento']) ? " - Orçamento: " . number_format($categoria['valorOrcamento'], 2, ',', '.') : '' ?></span><small> Disponível: R$ <?php echo number_format($categoria['orcamentoDisponivel'], 2, ',', '.'); ?></small></td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="bg-light text-uppercase font-weight-bold"><?php echo $categoria['descricao'] ?></td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php foreach ($categoria['lancamentos'] as $lancamento): ?>
                                <?php $classe = $lancamento['tipo'] === 'd' ? 'text-danger' : 'text-success'; ?>
                                <tr class="<?php echo $classe ?>">
                                    <td class="pl-5"><?php echo $lancamento['descricao'] ?></td>
                                    <td><?php echo toDataBr($lancamento['data']) ?></td>
                                    <td><?php echo $lancamento['tipo_formatado'] ?></td>
                                    <td><?php echo $lancamento['consolidado_formatado'] ?></td>
                                    <td><?php echo $lancamento['notificar_por_email_formatado'] ?></td>
                                    <td>R$ <?php echo number_format($lancamento['valor'], 2, ',', '.') ?></td>
                                    <td class="text-center">
                                        <?php echo anchor("lancamento/{$lancamento['chave']}/edit", 'Editar', ['class' => "btn btn-success btn-sm "]) ?>
                                        -
                                        <?php echo anchor("lancamento/{$lancamento['chave']}/delete/", 'Excluir', ['onclick' => "return confirma()", 'class' => "btn btn-danger btn-sm "]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="5" class="text-right">Subtotal:</td>
                                <td colspan="2" class="text-uppercase font-weight-bold">R$ <?php echo number_format($categoria['totalPorCategoria'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Nenhum lançamento encontrado.</td>
                        </tr>
                    <?php endif; ?>
                    
                    <?php if(empty($search)): ?>
                    <tr>
                        <td colspan="7" class="bg-light font-weight-bold text-uppercase">Totalizador</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Saldo do mês anterior:</td>
                        <td colspan="3"><strong>R$ <?php echo number_format($saldoAnterior, 2, ',', '.') ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-success text-right">Receitas neste mês (A):</td>
                        <td colspan="3" class="text-success">R$ <?php echo number_format($receitasMesAtual, 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-danger text-right">Despesas neste mês (B):</td>
                        <td colspan="3" class="text-danger">R$ <?php echo number_format($despesasMesAtual, 2, ',', '.') ?></td>
                    <tr>
                        <td colspan="3" class="text-right">Saldo neste mês (A) - (B):</td>
                        <?php if($saldoMesAtual > 0): ?>
                            <td colspan="3" class="text-success"><strong>R$ <?php echo number_format($saldoMesAtual, 2, ',', '.') ?></strong></td>
                        <?php else: ?>
                            <td colspan="3" class="text-danger"><strong>R$ <?php echo number_format($saldoMesAtual, 2, ',', '.') ?> 
                        <?php endif; ?>  
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php echo $this->endSection('content') ?>