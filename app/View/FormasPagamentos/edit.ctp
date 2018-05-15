<br>
<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('FormasPagamento'); ?>
<fieldset>
    <?php
    echo $this->Form->input('descricao');
    echo $this->Form->input('ativo', array('id' => 'ativoID', 'type' => 'select', 'options' => $status, 'label' => 'Status'));
    ?>
</fieldset>

<?php echo $this->Form->end(__('SALVAR')); ?>