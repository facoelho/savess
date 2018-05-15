<div id="toporight">
    <?php
    echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
    ?>
</div>
<div id="esquerda">
    <b><u><?php echo 'INFORMAÇÕES GERAIS'; ?></u></b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link($this->Html->image("botoes/editar.gif", array("alt" => "Editar", "title" => "Editar")), array('action' => 'edit', $paciente['Paciente']['id']), array('escape' => false)); ?>
    <br>
    <br>
    <p>
        <strong> Nome: </strong>
        <?php echo $paciente['Paciente']['nome'] . " " . $paciente['Paciente']['sobrenome']; ?>
        <br>
        <strong> Status: </strong>
        <?php if ($paciente['Paciente']['ativo'] == 'S') { ?>
            <?php echo 'Ativo'; ?>
        <?php } else { ?>
            <?php echo 'Inativo'; ?>
        <?php } ?>
        <br>
        <strong> Valor desconto: </strong>
        <?php echo $paciente['Paciente']['valordesconto']; ?>
        <br>
        <strong> CPF: </strong>
        <?php
        echo substr($paciente['Paciente']['cpf'], 0, 3) . "." .
        substr($paciente['Paciente']['cpf'], 3, 3) . "." .
        substr($paciente['Paciente']['cpf'], 6, 3) . "-" .
        substr($paciente['Paciente']['cpf'], 9, 2);
        ?>
        <br>
        <strong> RG: </strong>
        <?php
        echo substr($paciente['Paciente']['rg'], 0, 2) . "." .
        substr($paciente['Paciente']['rg'], 2, 3) . "." .
        substr($paciente['Paciente']['rg'], 5, 2) . "-" .
        substr($paciente['Paciente']['rg'], 7, 3);
        ?>
        <br>
        <?php
        if ($paciente['Paciente']['sexo'] == 1) {
            ?>
            <strong> Sexo: </strong> Masculino
            <?php
        } else {
            ?>
            <strong> Sexo: </strong> Feminino
            <?php
        }
        ?>
        <br>
        <strong> Data nascimento: </strong>
        <?php //echo date('d/m/Y', strtotime($paciente['Paciente']['dtnascimento'])); ?>
        <?php echo $paciente['Paciente']['dtnascimento']; ?>
        <br>
        <strong> Telefone: </strong>
        <?php echo '(' . $paciente['Paciente']['dddfone'] . ') ' . $paciente['Paciente']['fone']; ?>
        <br>
        <strong> Celular: </strong>
        <?php echo '(' . $paciente['Paciente']['dddcelular'] . ') ' . $paciente['Paciente']['celular']; ?>
        <br>
        <strong> E-mail: </strong>
        <?php echo $paciente['Paciente']['email']; ?>
        <br>
        <?php if (empty($paciente['Endereco'])) { ?>
            <strong> Observação: </strong>
            <?php echo $paciente['Paciente']['obs']; ?>
            <br>
            <?php
            echo "Este cliente não tem endereço cadastrado.";
        } else {
            ?>
            <strong> Cidade: </strong>
            <?php echo $paciente['Endereco'][0]['Cidade']['nome']; ?>
            <br>
            <strong> Rua: </strong>
            <?php echo $paciente['Endereco'][0]['rua']; ?>
            <br>
            <strong> Número: </strong>
            <?php echo $paciente['Endereco'][0]['numero']; ?>
            <br>
            <strong> Complemento: </strong>
            <?php echo $paciente['Endereco'][0]['complemento']; ?>
            <br>
            <strong> Bairro: </strong>
            <?php echo $paciente['Endereco'][0]['bairro']; ?>
            <br>
            <strong> Cep: </strong>
            <?php echo $paciente['Endereco'][0]['cep']; ?>
            <br>
            <strong> Observação: </strong>
            <?php echo $paciente['Paciente']['obs']; ?>
            <?php
        }
        ?>
        <br>
        <br>
</div>
<div id="direita">
    <b><u><?php echo 'ÚLTIMAS CONSÚLTAS'; ?></u></b>
    <br>
    <br>
    <?php $cont = count($evolucaos); ?>
    <?php if (!empty($evolucaos)) { ?>
        <?php foreach ($evolucaos as $item) : ?>
            <strong> <?php echo $cont; ?> - Consulta: <?php echo date('d/m/Y H:i', strtotime($item['Event']['start'])); ?></strong>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link($this->Html->image("botoes/editar.gif", array("alt" => "Editar", "title" => "Editar")), array('controller' => 'Evolucaos', 'action' => 'edit', $item['Event']['id'], $paciente['Paciente']['id']), array('escape' => false)); ?>
            <br><br>
            <p1><?php echo $item['Evolucao']['obs']; ?></p1>
            <br><br>
            <?php $cont = $cont - 1; ?>
        <?php endforeach; ?>
    <?php } ?>
</div>
</p>