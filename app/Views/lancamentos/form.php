<?php echo $this->extend('_common/layout') ?>
<?php echo $this->section('content') ?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/datepicker/css/datepicker.css') ?>">

<script type="text/javascript" src="<?php echo base_url('assets/jquery.mask/jquery.mask.js') ?>"></script>

<script type="text/javascript" src="<?php echo base_url('assets/datepicker/js/bootstrap-datepicker.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datepicker/js/locales/bootstrap-datepicker.pt-BR.js') ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/novaCategoria.js') ?>"></script>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
        <li class="breadcrumb-item" aria-current="page"><?php echo anchor("lancamento", 'Lançamentos') ?></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $titulo; ?></li>
    </ol>
</nav>

<h1>Lançamentos</h1>

<div class="card">
    <div class="card-header">
        <?php echo $titulo ?>
    </div>
    <div class="card-body">
        <?php echo form_open('lancamento/store') ?>
            <div class="form-group col-sm-4">
                <label for="categorias_id">Categorias</label>
                <?php echo form_dropdown('categoria_id', $dropDownCategorias, !empty($lancamento['categorias_id']) ? $lancamento['categorias_id'] : set_value('categorias_id'), ['class' => 'form-control', 'id' => 'categorias_id', 'onchange' => 'modalNovaCategoria(this.value)']) ?>
                <?php if (!empty($errors['categorias_id'])) : ?>
                    <div class="alert alert-danger mt-2"><?php echo $errors['categorias_id'] ?></div>
                <?php endif; ?>
            </div>
            <div class="col-sm-4">
            <label for="valor">Valor</label>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">R$</div>
                </div>
                <input type="text" name="valor" id="valor" class="form-control" placeholder="0,00" value="<?php echo !empty($lancamento['valor']) ? $lancamento['valor'] : set_value('valor') ?>">
            </div>
            <?php if (!empty($errors['valor'])) : ?>
                <div class="alert alert-danger mt-2"><?php echo $errors['valor'] ?></div>
            <?php endif; ?>
        </div>
        <div class="form-group col-sm-2">
            <label for="data">Data</label>
            <input type="text" name="data" id="data" class="form-control" value="<?php echo !empty($lancamento['data']) ? toDataBR($lancamento['data']) : (isset($dia) && isset($mes) && isset($ano) ? "{$dia}/{$mes}/$ano" : set_value('data', date('d/m/Y'))) ?>" required>
            <?php if (!empty($errors['data'])) : ?>
                <div class="alert alert-danger mt-2"><?php echo $errors['data'] ?></div>
            <?php endif; ?>
        </div>
        <div class="form-group col-sm-6">
            <label for="descricao">Descrição</label>
            <input type="text" name="descricao" id="descricao" class="form-control" value="<?php echo !empty($lancamento['descricao']) ? $lancamento['descricao'] : set_value('descricao') ?>">
            <?php if (!empty($errors['descricao'])) : ?>
                <div class="alert alert-danger mt-2"><?php echo $errors['descricao'] ?></div>
            <?php endif; ?>
        </div>
        <div class="form-group col-sm-8">
            <label class="mb-0">Consolidado?</label>
            <p class="mb-2">
                <small>
                    Indica se o lançamento entrará nos cálculos de saldo.<br />
                    Se o lançamento for de uma data futura, este valor será registrado automaticamente como "Não".
                </small>
            </p>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="sim_consolidado" name="consolidado" class="custom-control-input" value="1" <?php echo !empty($lancamento['consolidado']) && (int)$lancamento['consolidado'] === 1 ? 'checked' : set_radio('consolidado', 1, true) ?> />
                <label class="custom-control-label text-default" for="sim_consolidado">Sim</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="nao_consolidado" name="consolidado" class="custom-control-input" value="2" <?php echo !empty($lancamento['consolidado']) && (int)$lancamento['consolidado'] === 2 ? 'checked' : set_radio('consolidado', 2) ?> />
                <label class="custom-control-label text-default" for="nao_consolidado">Não</label>
            </div>
        </div>
        <div class="form-group col-md-12">
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php echo $this->include('_common/components/modalNovaCategoria') ?>

<?php echo $this->endSection('content') ?>