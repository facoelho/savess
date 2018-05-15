<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Parametro'); ?>
<fieldset>
    <?php
    echo $this->Form->input('descricao', array('id' => 'descricao', 'type' => 'textarea', 'label' => 'Descrição'));
    echo $this->Form->input('conteudo', array('label' => 'Conteúdo'));
    ?>
</fieldset>
<?php echo $this->Form->end(__('Editar')); ?>
