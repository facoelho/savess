<div id="toporight">
    <?php
    echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
    ?>
</div>
<?php
echo $this->Form->postLink($this->Html->image('botoes/excluir.png', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $especialista['Especialista']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?'));
?>
<br>
<br>
<p>
    <strong> Nome: </strong>
    <?php echo $especialista['Especialista']['nome'] . " " . $especialista['Especialista']['sobrenome']; ?>
    <br>
    <strong> Status: </strong>
    <?php if ($especialista['Especialista']['ativo'] == 'S') { ?>
        <?php echo 'Ativo' ?>
    <?php } else { ?>
        <?php echo 'Inativo' ?>
    <?php } ?>
    <br>
</p>