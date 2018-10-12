<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false));
?>
<br>
<br>
<p>
    <strong> Usuário: </strong>
    <?php echo $usergroupempresa['User']['nome']; ?>
    <br>
    <strong> Empresa: </strong>
    <?php echo $usergroupempresa['Empresa']['nomefantasia']; ?>
    <br>
    <strong> Grupo: </strong>
    <?php echo $usergroupempresa['Group']['name']; ?>
    <br>
    <strong> Empresa boot: </strong>
    <?php if ($usergroupempresa['Usergroupempresa']['empresaboot'] == "1") {
        echo "SIM";
    } else {
        echo "NAO";
    }; ?>
    <br>


