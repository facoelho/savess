<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
echo $this->Form->postLink($this->Html->image('botoes/excluir.png', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $holding['Holding']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?'));
?>
<br>
<br>
<p>
    <strong> Holding: </strong>
    <?php echo $holding['Holding']['nome']; ?>
    <br>
    <strong> Validade da holding: </strong>
    <?php echo date('d/m/Y', strtotime($holding['Holding']['validade'])); ?>
    <br>
    <strong> Última modifiação: </strong>
    <?php echo date('d/m/Y', strtotime($holding['Holding']['modified'])); ?>
    <br>
    <strong> Criação: </strong>
    <?php echo date('d/m/Y', strtotime($holding['Holding']['created'])); ?>
    <br>
    <strong> Menus: </strong>
    <br>
    <?php $i = 0; ?>
    <?php foreach ($holding['Menu'] as $menu): ?>

        &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $menu['nome']; ?> <br>

        <?php $i++; ?>
    <?php endforeach; ?>
</p>

