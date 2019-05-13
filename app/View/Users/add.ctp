<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('User'); ?>
<fieldset>
    <?php
    echo $this->Form->input('nome');
    echo $this->Form->input('sobrenome');
    echo $this->Form->input('username', array('label' => 'E-mail'));
    echo $this->Form->input('password');
    if ($adminmaster == 1) {
        echo $this->Form->input('holding_id', array('type' => 'select', 'options' => $holdings, 'label' => 'Holding', 'empty' => ''));
        //echo $this->Form->input('adminmaster', array ('type' => 'select','options' => $opcoes, 'label' => 'Administrador master?', 'empty' => ''));
        echo $this->Form->input('adminmaster', array('value' => 2, 'type' => 'hidden'));
        echo $this->Form->input('adminholding', array('type' => 'select', 'options' => $opcoes, 'label' => 'Administrador da holding?', 'empty' => ''));
    } else {
        echo $this->Form->input('holding_id', array('value' => $holding_id, 'type' => 'hidden'));
        echo $this->Form->input('adminmaster', array('value' => 2, 'type' => 'hidden'));
        echo $this->Form->input('adminholding', array('type' => 'select', 'options' => $opcoes, 'label' => 'Administrador da holding?', 'empty' => ''));
    }

//    echo $this->Form->input('username', array('type' => 'hidden'));
    ?>
</fieldset>
<?php echo $this->Form->end(__('Adicionar')); ?>