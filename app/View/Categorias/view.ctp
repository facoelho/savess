<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false));
?>
<br>
<br>
<p>
    <strong> Descrição: </strong>
    <?php echo $categoria['Categoria']['descricao']; ?>
    <br>
    <strong> Tipo exame: </strong>
    <?php echo $categoria['Tipoexame']['descricao']; ?>
    <br>
    <strong> Ativo: </strong>
    <?php if ($categoria['Categoria']['ativo'] == 'S') { ?>
        <?php echo 'SIM'; ?>
    <?php } else { ?>
        <?php echo 'NÃO'; ?>
    <?php } ?>
    <br>
</p>