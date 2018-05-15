<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
echo $this->Form->postLink($this->Html->image('botoes/excluir.png', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $group['Group']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?'));
?>
<br>
<br>
<p>
    <strong> Grupo: </strong>
    <?php echo $group['Group']['name']; ?>
    <br>
    <strong> Última modifiação: </strong>
    <?php echo date('d/m/Y', strtotime($group['Group']['modified'])); ?>
    <br>
    <strong> Criação: </strong>
    <?php echo date('d/m/Y', strtotime($group['Group']['created'])); ?>
    <br>
    <?php $i = 0; ?>

    <strong> Menus: </strong> <br>

    <?php foreach ($group['Menu'] as $menu): ?>

        &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $menu['nome']; ?> <br>

        <?php $i++; ?>
    <?php endforeach; ?>
    <?php unset($group); ?>
</p>

