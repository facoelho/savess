<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Agenda'); ?>
<fieldset>
    <?php
    echo $this->Form->input('datahora', array('id' => 'datahora', 'class' => 'data', 'type' => 'text', 'label' => 'Data e hora do atendimento', 'value' => $data, 'readonly'));
    echo $this->Form->input('especialista_id', array('id' => 'especialistaID', 'type' => 'select', 'options' => $especialistas, 'label' => 'Especialista'));
    echo $this->Form->input('paciente_id', array('id' => 'pacienteID', 'type' => 'select', 'options' => $pacientes, 'label' => 'Paciente', 'empty' => 'Selecione o paciente'));
    echo $this->Form->input('tiposervico_id', array('id' => 'tiposervicoID', 'type' => 'select', 'options' => $tiposervico, 'label' => 'Tipos de serviço', 'empty' => 'Selecione o tipo de serviço'));
    echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa_id));
    echo $this->Form->input('observacao', array('id' => 'obs', 'type' => 'textarea', 'label' => 'Observação'));
    ?>
</fieldset>

<?php echo $this->Form->end(__('Adicionar')); ?>

<script type="text/javascript">
    document.getElementById('pacienteID').focus();
</script>