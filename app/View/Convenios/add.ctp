<br>
<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Convenio'); ?>
<fieldset>
    <?php
    echo $this->Form->input('descricao');
    echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa_id));
    echo $this->Form->input('ativo', array('id' => 'ativoID', 'type' => 'select', 'options' => $ativo, 'label' => 'Ativo'));
    ?>
</fieldset>
<?php echo $this->Form->end(__('SALVAR')); ?>