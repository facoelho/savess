<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Estado'); ?>
<fieldset>
    <?php
    echo $this->Form->input('nome', array('label' => 'Nome'));
    echo $this->Form->input('pais_id', array('id' => 'paise', 'type' => 'select', 'options' => $paises, 'label' => 'País', 'empty' => ''));
    echo $this->Form->input('holding_id', array('type' => 'hidden', 'value' => $holding_id));
    ?>
</fieldset>
<?php echo $this->Form->end(__('SALVAR')); ?>