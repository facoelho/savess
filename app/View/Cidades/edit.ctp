<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Cidade'); ?>
<fieldset>
    <?php
    echo $this->Form->input('nome');
    ?>
</fieldset>
<?php echo $this->Form->end(__('SALVAR')); ?>
