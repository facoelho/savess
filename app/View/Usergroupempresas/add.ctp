<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false));
?>
<br>
<br>
<?php echo $this->Form->create('Usergroupempresa'); ?>
<fieldset>
    <?php
    echo $this->Form->input('user_id', array ('id' => 'userID', 'type' => 'select','options' => $usuarios, 'label' => 'Usuário', 'empty' => ''));
    echo $this->Form->input('empresa_id', array ('id' => 'empresaID', 'type' => 'select','options' => $empresas, 'label' => 'Empresa', 'empty' => ''));
    echo $this->Form->input('group_id', array ('id' => 'groupID', 'type' => 'select', 'options' => $grupos, 'label' => 'Grupo', 'empty' => ''));
    echo $this->Form->input('empresaboot', array ('id' => 'empresaBoot', 'type' => 'select', 'options' => $opcoes, 'label' => 'Empresa Boot', 'empty' => ''));
    ?>
</fieldset>
<?php echo $this->Form->end(__('Adicionar')); ?>
