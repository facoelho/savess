<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Group'); ?>
<fieldset>
    <?php
    echo $this->Form->input('name');
    echo $this->Form->input('Menu.Menu',array('title' => 'CTRL + Click (para selecionar mais de um)', 'label'=>'Escolha os menus', 'type'=>'select', 'multiple'=>true));
    echo $this->Form->input('holding_id', array('type' => 'hidden', 'value' => $holding_id));
    ?>
</fieldset>
<?php echo $this->Form->end(__('Adicionar')); ?>
