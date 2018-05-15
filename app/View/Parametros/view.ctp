<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
//echo $this->Form->postLink($this->Html->image('botoes/excluir.png', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $tipodespesa['Tipodespesa']['cod']), array('escape' => false), __('Você realmete deseja apagar esse item?'));
?>
<br>
<br>
<p>
    <strong> Id: </strong>
    <?php echo $parametro['Parametro']['id']; ?>
    <br>
    <strong> Parâmetro: </strong>
    <?php echo $parametro['Parametro']['parametro']; ?>
    <br>
    <strong> Descrição: </strong>
    <?php echo $parametro['Parametro']['descricao']; ?>
    <br>
    <strong> Usuário: </strong>
    <?php echo $parametro['User']['nome'] . ' ' . $parametro['User']['sobrenome']; ?>
    <br>
    <strong> Criado: </strong>
    <?php echo $parametro['Parametro']['created']; ?>
    <br>
    <?php if ($parametro['Parametro']['user_alt_id']) { ?>
        <strong> Alteração: </strong>
        <?php echo $user_alteracao['User']['nome'] . ' ' . $user_alteracao['User']['sobrenome'] . ' - ' . date('d/m/Y H:i:s', strtotime($user_alteracao['Parametro']['modified'])); ?>
    <?php } ?>
</p>