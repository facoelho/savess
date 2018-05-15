<br>
<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Albun'); ?>
<fieldset>
    <?php
    echo $this->Form->input('titulo');
    echo $this->Form->input('paciente_id', array('id' => 'pacienteID', 'type' => 'select', 'options' => $pacientes, 'label' => 'Cliente', 'empty' => '-- Selecione o cliente --'));
    echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa_id));
    ?>
</fieldset>
<?php echo $this->Form->end(__('SALVAR')); ?>

