<br>
<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('ConveniosCategoria'); ?>
<fieldset>
    <?php
    echo $this->Form->input('convenio_id', array('id' => 'convenioID', 'type' => 'select', 'options' => $convenios, 'label' => 'Convênios'));
    echo $this->Form->input('descricao');
    echo $this->Form->input('valor');
    ?>
</fieldset>
<?php echo $this->Form->end(__('SALVAR')); ?>

<script type="text/javascript">
    jQuery(document).ready(function() {
        document.getElementById('convenioID').focus();
    });
</script>