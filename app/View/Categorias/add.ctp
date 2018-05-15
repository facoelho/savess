<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false));
?>
<br>
<br>
<?php echo $this->Form->create('Categoria'); ?>
<fieldset>
    <?php
    echo $this->Form->input('descricao');
    echo $this->Form->input('categoria_pai_id', array('id' => 'categoriaID', 'type' => 'select', 'options' => $categorias_pai, 'label' => 'Categoria pai', 'empty' => '-- Selecione a categoria pai --'));
    echo $this->Form->input('Tipoexame.Tipoexame', array('title' => 'CTRL + Click (para selecionar mais de um)', 'label' => 'Escolha os tipos de exame', 'type' => 'select', 'options' => $tipoexames, 'multiple' => true));
    echo $this->Form->input('tipo', array('id' => 'tipoID', 'type' => 'select', 'options' => $tipo, 'label' => 'Tipo de lançamento'));
    echo $this->Form->input('ativo', array('id' => 'ativoID', 'type' => 'select', 'options' => $ativo, 'label' => 'Ativo'));
    echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa_id));
    ?>
</fieldset>
<?php echo $this->Form->end(__('SALVAR')); ?>