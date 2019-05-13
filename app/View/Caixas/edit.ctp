<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Caixa'); ?>
<fieldset>
    <?php
    echo $this->Form->input('dtcaixa', array('id' => 'dtcaixa', 'class' => 'data', 'type' => 'text', 'label' => 'Data do caixa', 'readonly'));
//    echo $this->Form->input('saldo', array('id' => 'saldo', 'readonly'));
    echo $this->Form->input('status', array('id' => 'statusID', 'type' => 'select', 'options' => $status, 'label' => 'Status'));
    echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa_id));
    ?>
</fieldset>

<?php echo $this->Form->end(__('SALVAR')); ?>
<script type="text/javascript">
    jQuery(document).ready(function() {

    });
</script>