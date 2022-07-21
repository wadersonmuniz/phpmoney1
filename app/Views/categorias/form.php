<?php echo $this->extend('_common/layout') ?>
<?php echo $this->section('content') ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo anchor('', "Home") ?></li>
        <li class="breadcrumb-item"><?php echo anchor('categoria', "Categorias") ?></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $titulo ?></li>
    </ol>
</nav>

<h1>Categorias</h1>
<div class="card">
    <div class="card-header">
        <?php echo $titulo; ?>
    </div>
    <div class="card-body">
        <?php echo form_open('categoria/store') ?>
        <div class="form-group col-sm-6">
            <label for="descricao">Descrição</label>
            <input type="text" name="descricao" id="descricao" class="form-control" autofocus value="">
            <?php if (!empty($errors['descricao'])) : ?>
                <div class="alert alert-danger mt-2"><?php echo $errors['descricao'] ?></div>
            <?php endif; ?>
        </div>
        <div class="form-group col-sm-4">
            <label for="tipo">Tipo</label>
            <?php $options = [
                '' => 'Selecione',
                'd' => 'Despesa',
                'r' => 'Receita'
            ]; ?>
            <?php echo form_dropdown('tipo', $options, null, ['id' => 'tipo', 'class' => 'form-control']) ?>
            <?php if (!empty($errors['tipo'])) : ?>
                <div class="alert alert-danger mt-2"><?php echo $errors['tipo'] ?></div>
            <?php endif; ?>
        </div>
        <div class="form-group col-sm-12">
            <input type="submit" class="btn btn-primary" value="Salvar">
        </div>

        <?php echo form_close() ?>
    </div>
</div>

<?php echo $this->endSection('content') ?>