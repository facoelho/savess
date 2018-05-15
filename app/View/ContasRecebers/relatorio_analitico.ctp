<?php $this->layout = 'naoLogado'; ?>
<br><br>
<?php
$event_id = '';
$defice = 0;
$total_defice = 0;
$total = 0;
$total_final = 0;
?>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo 'Data'; ?></th>
        <th><?php echo 'Forma de consulta'; ?></th>
        <th><?php echo 'Especialista'; ?></th>
        <th><?php echo 'Paciente'; ?></th>
        <th><?php echo 'Forma pagamento'; ?></th>
        <th><?php echo '(R$) Consulta'; ?></th>
        <th><?php echo '(R$) Pago'; ?></th>
        <th><?php echo '(R$) Défice'; ?></th>
    </tr>
    <?php //debug($result); ?>
    <?php foreach ($result as $item): ?>
        <?php if ($item['contas_recebers']['event_id'] <> $event_id) { ?>
            <?php $cont = $this->requestAction('/ContasRecebers/busca_numero_movimentacoes', array('pass' => array($item['contas_recebers']['id']))); ?>
            <?php if ($total > 0) { ?>
                <?php $total_defice = $total_defice + $defice; ?>
                <tr>
                    <td><?php echo ''; ?></td>
                    <td><?php echo ''; ?></td>
                    <td><?php echo ''; ?></td>
                    <td><?php echo ''; ?></td>
                    <td><?php echo ''; ?></td>
                    <td><strong><?php echo 'Total: '; ?>&nbsp;</strong></td>
                    <td><strong><font color="blue"><?php echo number_format($total, 2, ",", ""); ?>&nbsp;</font></strong></td>
                    <td><strong><font color="red"><?php echo number_format($defice, 2, ",", ""); ?>&nbsp;</font></strong></td>
                </tr>
            <?php } ?>
            <?php
            $total = 0;
            $defice = 0;
            ?>
            <tr>
                <td><b><?php echo date('d/m/Y H:i', strtotime($item['events']['start'])); ?>&nbsp;</b></td>
                <?php if (!empty($item['event_types']['name'])) { ?>
                    <td><?php echo h($item['event_types']['name']); ?>&nbsp;</td>
                <?php } else { ?>
                    <td><?php echo h($item['convenios']['descricao'] . ' - ' . $item['convenios_categorias']['descricao']); ?>&nbsp;</td>
                <?php } ?>
                <td><?php echo h($item['especialistas']['nome'] . ' ' . $item['especialistas']['sobrenome']); ?>&nbsp;</td>
                <td><?php echo h($item['pacientes']['nome'] . ' ' . $item['pacientes']['sobrenome']); ?>&nbsp;</td>
                <td><?php echo h($item['formas_pagamentos']['descricao']); ?>&nbsp;</td>
                <td><?php echo number_format(h($item['contas_recebers']['valor']), 2, ",", ""); ?>&nbsp;</td>
                <?php if (!empty($item['contas_recebers_movs']['valorlancto'])) { ?>
                    <td><?php echo number_format(h($item['contas_recebers_movs']['valorlancto']), 2, ",", ""); ?>&nbsp;</td>
                <?php } else { ?>
                    <td><?php echo number_format(h(0), 2, ",", ""); ?>&nbsp;</td>
                <?php } ?>
                <?php if ($item[0]['cont'] == 0) { ?>
                    <td><strong><font color="red"><?php echo number_format(h($item['contas_recebers']['valor']), 2, ",", ""); ?>&nbsp;</font></strong></td>
                    <?php $total_defice = $total_defice + $item['contas_recebers']['valor']; ?>
                <?php } else { ?>
                    <td><?php echo ''; ?></td>
                <?php } ?>
            </tr>
        <?php } else { ?>
            <tr>
                <td><?php echo ''; ?></td>
                <td><?php echo ''; ?></td>
                <td><?php echo ''; ?></td>
                <td><?php echo ''; ?></td>
                <td><?php echo h($item['formas_pagamentos']['descricao']); ?>&nbsp;</td>
                <td><?php echo ''; ?></td>
                <td><?php echo number_format(h($item['contas_recebers_movs']['valorlancto']), 2, ",", ""); ?>&nbsp;</td>
                <td><?php echo ''; ?></td>
            </tr>
        <?php } ?>
        <?php $defice = $item['contas_recebers']['saldo']; ?>
        <?php $total = $total + $item['contas_recebers_movs']['valorlancto']; ?>
        <?php $event_id = $item['contas_recebers']['event_id']; ?>
        <?php $total_final = $total_final + $item['contas_recebers_movs']['valorlancto']; ?>
    <?php endforeach; ?>

    <?php if ($cont > 0) { ?>
        <?php $total_defice = $total_defice + $defice; ?>
        <tr>
            <td><?php echo ''; ?></td>
            <td><?php echo ''; ?></td>
            <td><?php echo ''; ?></td>
            <td><?php echo ''; ?></td>
            <td><?php echo ''; ?></td>
            <td><strong><?php echo 'Total: '; ?>&nbsp;</strong></td>
            <td><strong><font color="blue"><?php echo number_format($total, 2, ",", ""); ?>&nbsp;</font></strong></td>
            <td><strong><font color="red"><?php echo number_format($defice, 2, ",", ""); ?>&nbsp;</font></strong></td>
        </tr>
    <?php } ?>
    <tr>
        <td><?php echo ''; ?></td>
        <td><?php echo ''; ?></td>
        <td><?php echo ''; ?></td>
        <td><?php echo ''; ?></td>
        <td><?php echo ''; ?></td>
        <td><strong><font><?php echo 'Total final: '; ?>&nbsp;</font></strong></td>
        <td><strong><?php echo number_format($total_final, 2, ",", ""); ?>&nbsp;</strong></td>
        <td><strong><?php echo number_format($total_defice, 2, ",", ""); ?>&nbsp;</strong></td>
    </tr>
</table>