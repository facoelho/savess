<div id="toporight">
    <?php
    echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
    ?>
</div>
<br>
<br>
<?php echo $this->Form->create('Evolucao'); ?>
<fieldset>
    <?php
    echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa_id));
    echo $this->Form->input('event_id', array('type' => 'hidden', 'value' => $event_id));
    echo $this->Form->input('paciente_id', array('type' => 'hidden', 'value' => $paciente_id));
    if (!empty($evolucao['Evolucao'][0]['obs'])) {
        echo $this->Form->input('obs', array('label' => 'Evolução', 'type' => 'textarea', 'style' => 'height: 380px;', 'value' => $evolucao['Evolucao'][0]['obs']));
    } else {
        echo $this->Form->input('obs', array('label' => 'Evolução', 'type' => 'textarea', 'style' => 'height: 380px;'));
    }
    ?>
</fieldset>
<?php echo $this->Form->end(__('SALVAR')); ?>
