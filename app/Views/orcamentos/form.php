<?php echo $this->extend('_common/layout') ?>
<?php echo $this->section('content') ?>

<script>
    $(document).ready(function() {
        $('#valor').mask('00.000.000.000.000,00', {
            reverse: true
        })
    })

    // Chama o modal Noca Categoria
    function modalNovaCategoria(valor) {
        // console.log(valor)
        if (valor == 'n') {
            $('#modalNovaCategoria').modal('show')
            $('#modalNovaCategoria').on('shown.bs.modal', function(e) {
                $('#descricao_nova_categoria').focus();
            })
        }
    }

    function salvaNovaCategoria() {
        var descricao = $('#descricao_nova_categoria')
        var tipo = $('#tipo_nova_categoria')

        if (descricao.val() == '' || tipo.val() == '') {
            // alert('Preencha todos os campos antes de continuar.')
            // descricao.focus()
            // return false
        }

        $.post(base_url + '/ajax/categoria/store', {
            descricao: descricao.val(),
            tipo: tipo.val()
        }, function(data) {
            // console.log(data)
            if (data.error ==  true) {
                if ('descricao' in data.message) {
                    $('#erro_descricao').css({
                        'margin-top': '5px',
                        'color': 'red'
                    }).html(data.message['descricao'])
                } else {
                    $('erro_descricao').html('').hide()
                }
                if ('tipo' in data.message) {
                    $('#erro_tipo').css({
                        'margin-top': '5px',
                        'color': 'red'
                    }).html(data.message['tipo'])
                } else {
                    $('erro_tipo').html('').hide()
                }
            } else {
                $('#erro_descricao').hide()
                $('#erro_tipo').hide()
                $('#modalNovaCategoria').modal('hide')
                carregaCategoriasDropdown(data.id)
            }
        }, 'json')
    }

    function carregaCategoriasDropdown(id) {
        $('#spinnerLoading').show()
        $selectCategorias = $('#categorias_id')
        $selectCategorias.empty();
        $.get(base_url + '/ajax/categoria/get', {},
        function(data) {
            console.log(data);
        }, 'json')
    }


</script>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo anchor('', "Home") ?></li>
        <li class="breadcrumb-item"><?php echo anchor('orcamento', "Orçamentos") ?></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $titulo ?></li>
    </ol>
</nav>

<h1>Orçamentos</h1>
<div class="card">
    <div class="card-header">
        <?php echo $titulo ?>
    </div>
    <div class="card-body">
        <?php echo form_open('orcamento/store') ?>

            <div class="form-group col-sm-6">
                <label for="descricao">Descrição</label>
                <input type="text" name="descricao" id="descricao" value="<?php echo !empty($orcamento['descricao']) ? $orcamento['descricao'] : set_value('descricao') ?>" class="form-control" autofocus>
                <?php if (!empty($errors['descricao'])) : ?>
                    <div class="alert alert-danger mt-2"><?php echo $errors['descricao'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group col-sm-3">
                <label for="categorias_id">Categorias<span id="spinnerLoading" style="display: none;" class="ml-2 spinner-border spinner-border-sm"></span></label> 
                <?php echo form_dropdown('categorias_id', $formDropDown, !empty($orcamento['categorias_id']) ? $orcamento['categorias_id'] : set_value('categorias_id'), ['class' => 'form-control', 'id' => 'categorias_id', 'onChange' => 'modalNovaCategoria(this.value)']) ?>
            </div>

            <div class="form-group col-sm-3">
                <label for="valor">Valor</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">R$</div>
                    </div>
                    <input type="text" name="valor" id="valor" value="<?php echo !empty($orcamento['valor']) ? $orcamento['valor'] : set_value('valor') ?>" class="form-control" placeholder="0,00">
                    <?php if (!empty($errors['valor'])) : ?>
                        <div class="alert alert-danger mt-2"><?php echo $errors['valor'] ?></div>
                    <?php endif; ?>
                </div> 
            </div>

            <div class="form-group col-sm-12">
                <label for="notificar">Notificar por e-mail?
                    <div>
                        <small>Marque se desejar receber um e-mail quando os lançamentos atingirem 80% deste valor.</small>
                    </div>
                </label>
                <div class="row col-md-2">
                    <?php echo form_dropdown('notificar_por_email', [2 => 'Não', 1 => 'Sim'], !empty($orcamento['notificar_por_email']) ? $orcamento['notificar_por_email'] : set_value('notificar_por_email'), ['id' => 'notificar_por_email', 'class' => 'form-control']); ?>
                </div>
            </div>

            <input type="hidden" name="chave" value="<?php echo !empty($orcamento['chave']) ? $orcamento['chave'] : set_value('chave') ?>">

            <div class="form-group col-sm-12">
                <input type="submit" class="btn btn-primary" value="Salvar">
            </div>

        <?php echo form_close(); ?>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalNovaCategoria" tabindex="-1" role="dialog" aria-labelledby="modalNovaCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="descricao_nova_categoria">Categoria</label>
                    <input type="text" name="descricao_nova_categoria" id="descricao_nova_categoria" required class="form-control">
                    <div id="erro_descricao"></div>
                </div>
                <div class="form-group">
                    <label for="tipo_nova_categoria">Tipo</label>
                    <?php echo form_dropdown('tipo_nova_categoria', ['' => 'Selecione', 'd' => 'Despesa', 'r' => 'Receita'], null, "id='tipo_nova_categoria' class='form-control' required") ?>
                    <div id="erro_tipo"></div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary" onclick="salvaNovaCategoria()">Salvar</button>
            </div>
        </div>
    </div>
</div>
<?php echo $this->endSection('content') ?>