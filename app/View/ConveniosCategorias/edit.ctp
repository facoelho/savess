<br>
<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('ConveniosCategoria'); ?>
<fieldset>
    <?php
    echo $this->Form->input('descricao', array('label' => 'Nome', 'type' => 'text'));
    echo $this->Form->input('valor');
    echo $this->Form->input('ativo', array('id' => 'ativoID', 'type' => 'select', 'options' => $ativo, 'label' => 'Ativo'));
    ?>
</fieldset>
<?php echo $this->Form->end(__('SALVAR')); ?>
