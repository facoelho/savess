<?php $this->layout = 'naoLogado'; ?>
<?php //echo $this->Html->link($this->Html->image("logo_2.png", array("alt" => "SAVESS", "title" => "SAVESS")), array('action' => ''), array('escape' => false));     ?>
<?php
$total_defice = 0;
$total_final = 0;
?>
<br><br>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo 'Data'; ?></th>
        <th><?php echo 'Forma de consulta'; ?></th>
        <th><?php echo 'Especialista'; ?></th>
        <th><?php echo 'Paciente'; ?></th>
        <th><?php echo '(R$) Consulta'; ?></th>
        <th><?php echo '(R$) Pago'; ?></th>
        <th><?php echo '(R$) Défice'; ?></th>
    </tr>
    <?php foreach ($result as $item): ?>
        <tr>
            <td><?php echo date('d/m/Y H:i', strtotime($item['events']['start'])); ?>&nbsp;</td>
            <?php if (!empty($item['event_types']['name'])) { ?>
                <td><?php echo h($item['event_types']['name']); ?>&nbsp;</td>
            <?php } else { ?>
                <td><?php echo h($item['convenios']['descricao'] . ' - ' . $item['convenios_categorias']['descricao']); ?>&nbsp;</td>
            <?php } ?>
            <td><?php echo h($item['especialistas']['nome'] . ' ' . $item['especialistas']['sobrenome']); ?>&nbsp;</td>
            <td><?php echo h($item['pacientes']['nome'] . ' ' . $item['pacientes']['sobrenome']); ?>&nbsp;</td>
            <td><?php echo h(number_format($item['contas_recebers']['valor'], 2, ",", "")); ?>&nbsp;</td>
            <?php if (!empty($item[0]['valorlancto'])) { ?>
                <td><strong><font color="blue"><?php echo number_format($item[0]['valorlancto'], 2, ",", ""); ?>&nbsp;</font></strong></td>
            <?php } else { ?>
                <td><strong><font color="blue"><?php echo number_format(0, 2, ",", ""); ?>&nbsp;</font></strong></td>
            <?php } ?>
            <td><strong><font color="red"><?php echo number_format($item['contas_recebers']['saldo'], 2, ",", ""); ?>&nbsp;</font></strong></td>
            <?php $total_final = $total_final + $item[0]['valorlancto']; ?>
            <?php $total_defice = $total_defice + $item['contas_recebers']['saldo']; ?>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td><?php echo ''; ?></td>
        <td><?php echo ''; ?></td>
        <td><?php echo ''; ?></td>
        <td><?php echo ''; ?></td>
        <td><strong><font><?php echo 'Total final: '; ?>&nbsp;</font></strong></td>
        <td><strong><?php echo number_format($total_final, 2, ",", ""); ?>&nbsp;</strong></td>
        <td><strong><?php echo number_format($total_defice, 2, ",", ""); ?>&nbsp;</strong></td>
    </tr>
</table>

<div id="chart_div">
    <?php // $this->GoogleCharts->createJsChart($chart); ?>
</div>