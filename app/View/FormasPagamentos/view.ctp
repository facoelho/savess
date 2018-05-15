<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<p>
    <strong> Nome: </strong>
    <?php echo $formapagamento['FormasPagamento']['descricao']; ?>
    <br>
    <strong> Status: </strong>
    <?php if ($formapagamento['FormasPagamento']['ativo'] == 'S') { ?>
        <?php echo 'Ativo' ?>
    <?php } else { ?>
        <?php echo 'Inativo' ?>
    <?php } ?>
    <br>
</p>