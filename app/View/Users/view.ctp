<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
//echo $this->Form->postLink($this->Html->image('botoes/excluir.png', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $user['User']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?'));
?>
<br>
<br>
<p>
    <strong> Nome: </strong>
    <?php echo $user['User']['nome'] . " " . $user['User']['sobrenome']; ?>
    <br>
    <strong> E-mail: </strong>
    <?php echo $user['User']['username']; ?>
    <br>
    <strong> Holding: </strong>
    <?php echo $user['Holding']['nome']; ?>
    <br>
    <strong> Último acesso: </strong>
    <?php if (!empty($user['User']['ultimoacesso'])) { ?>
        <?php echo date('d/m/Y H:i:s', strtotime($user['User']['ultimoacesso'])); ?>
    <?php } else { ?>
        <?php echo ''; ?>
    <?php } ?>
    <br>
    <strong> Última modifiação: </strong>
    <?php echo date('d/m/Y', strtotime($user['User']['modified'])); ?>
    <br>
    <strong> Criação: </strong>
    <?php echo date('d/m/Y', strtotime($user['User']['created'])); ?>
</p>