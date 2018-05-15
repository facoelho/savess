<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Paise'); ?>
<fieldset>
    <?php
    echo $this->Form->input('nome', array('label' => 'Nome', 'type' => 'text'));
    ?>
</fieldset>
<?php echo $this->Form->end(__('Editar')); ?>
