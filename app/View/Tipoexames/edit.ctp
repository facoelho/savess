<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false));
?>
<br>
<br>
<?php echo $this->Form->create('Tipoexame'); ?>
<fieldset>
    <?php
    echo $this->Form->input('descricao');
    ?>
</fieldset>

<?php echo $this->Form->end(__('Editar')); ?>

<script type="text/javascript">

</script>