<div id="toporight">
    <?php
    echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
    ?>
</div>
<br>
<br>
<?php echo $this->Form->create('Albun'); ?>
<fieldset>
    <?php
    echo $this->Form->input('titulo');
    echo $this->Form->input('paciente_id', array('id' => 'pacienteID', 'type' => 'select', 'options' => $pacientes, 'label' => 'Cliente', 'empty' => '-- Selecione o cliente --'));
    ?>
</fieldset>
<?php echo $this->Form->end(__('SALVAR')); ?>