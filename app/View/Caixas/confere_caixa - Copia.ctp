<?php $this->layout = 'naoLogado'; ?>
<br><br>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo 'Descrição'; ?></th>
        <th><?php echo 'Tipo'; ?></th>
        <th><?php echo 'Valor'; ?></th>
        <th><?php echo 'Saldo'; ?></th>
        <th><?php echo 'Usuário'; ?></th>
        <th><?php echo 'Dt lançamento'; ?></th>
    </tr>
    <?php foreach ($lancamentos as $item): ?>
        <tr>
            <td><?php echo $item['Lancamento']['descricao']; ?>&nbsp;</td>
            <?php if ($item['Lancamento']['tipo'] == 'E') { ?>
                <td><strong><font color="blue"><?php echo 'ENTRADA'; ?>&nbsp;</font></strong></td>
            <?php } elseif ($item['Lancamento']['tipo'] == 'S') { ?>
                <td><strong><font color="red"><?php echo 'SAÍDA'; ?>&nbsp;</font></strong></td>
            <?php } elseif ($item['Lancamento']['tipo'] == 'I') { ?>
                <td><strong><font color="green"><?php echo 'INICIALIZAÇÃO'; ?>&nbsp;</font></strong></td>
            <?php } elseif ($item['Lancamento']['tipo'] == 'G') { ?>
                <td><strong><font color="red"><?php echo 'SANGRIA'; ?>&nbsp;</font></strong></td>
            <?php } ?>
            <td><?php echo number_format($item['Lancamento']['valor'], 2, ",", ""); ?>&nbsp;</td>
            <?php if ($item['Lancamento']['saldo'] >= 0) { ?>
                <td><b><?php echo number_format($item['Lancamento']['saldo'], 2, ",", ""); ?>&nbsp;</b></td>
            <?php } else { ?>
                <td><strong><font color="red"><?php echo number_format($item['Lancamento']['saldo'], 2, ",", ""); ?>&nbsp;</font></strong></td>
            <?php } ?>
            <td><?php echo $item['User']['id'] . ' - ' . $item['User']['nome'] . ' ' . $item['User']['sobrenome']; ?>&nbsp;</td>
            <td><?php echo date('d/m/Y H:i', strtotime($item['Lancamento']['created'])); ?>&nbsp;</td>
        </tr>
    <?php endforeach; ?>
</table>
