<?php $this->layout = 'naoLogado'; ?>
<?php $mesano = ''; ?>
<?php $entradas = 0; ?>
<?php $saidas = 0; ?>
<?php $lucro = 0; ?>
<?php $saldo = 0; ?>
<?php $cont = 0; ?>
<br><br>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo 'Mês/Ano'; ?></th>
        <th><?php echo 'Tipo'; ?></th>
        <th><?php echo 'Valor'; ?></th>
    </tr>
    <?php foreach ($result as $key => $item): ?>
        <?php if ($mesano <> $item[0]['mesano']) { ?>
            <?php if ($cont > 0) { ?>
                <tr>
                    <td><?php echo ''; ?></td>
                    <td><?php echo 'Lucro'; ?></td>
                    <td><?php echo number_format($entradas - $saidas, 2, ",", ""); ?></td>
                </tr>
                <tr>
                    <td><?php echo ''; ?></td>
                    <td><?php echo 'Saldo'; ?></td>
                    <td><?php echo number_format((($entradas - $saidas) - $retiradas), 2, ",", ""); ?></td>
                </tr>
                <?php $saldo = $saldo + (($entradas - $saidas) - $retiradas); ?>
                <?php $entradas = 0; ?>
                <?php $saidas = 0; ?>
            <?php } ?>
            <tr>
                <td><b><?php echo $item[0]['mesano']; ?></b></td>
                <td colspan="3"><?php echo ''; ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td><?php echo ''; ?>&nbsp;</td>
            <?php if ($item[0]['tipo'] == 'Entradas') { ?>
                <?php $entradas = $item[0]['valor']; ?>
                <td><?php echo $item[0]['tipo']; ?>&nbsp;</td>
            <?php } elseif ($item[0]['tipo'] == 'Saidas') { ?>
                <?php $saidas = $item[0]['valor']; ?>
                <td><?php echo $item[0]['tipo']; ?>&nbsp;</td>
            <?php } elseif ($item[0]['tipo'] == 'Retiradas') { ?>
                <td><?php echo $item[0]['tipo']; ?>&nbsp;</td>
                <?php $retiradas = $item[0]['valor']; ?>
            <?php } ?>
            <td><?php echo $item[0]['valor']; ?>&nbsp;</td>
        </tr>
        <?php $mesano = $item[0]['mesano']; ?>
        <?php $cont++; ?>
    <?php endforeach; ?>
    <tr>
        <td><?php echo ''; ?></td>
        <td><?php echo 'Lucro'; ?></td>
        <td><?php echo number_format($entradas - $saidas, 2, ",", ""); ?></td>
    </tr>
    <tr>
        <td><?php echo ''; ?></td>
        <td><?php echo 'Saldo'; ?></td>
        <td><?php echo number_format((($entradas - $saidas) - $retiradas), 2, ",", ""); ?></td>
    </tr>
    <?php $saldo = $saldo + (($entradas - $saidas) - $retiradas); ?>
    <?php $entradas = 0; ?>
    <?php $saidas = 0; ?>
    <tr>
        <td colspan="3"><?php echo ''; ?></td>
    </tr>
    <tr>
        <td><?php echo ''; ?></td>
        <td><b><?php echo 'Saldo final'; ?></b></td>
        <td><b><?php echo number_format($saldo, 2, ",", ""); ?></b></td>
    </tr>
</table>
<br><br>

<?php $column_chart_linha->div('chart_div_linha'); ?>
<?php $column_chart_barras->div('chart_div'); ?>
<div id="chart_div_linha">
    <?php $this->GoogleCharts->createJsChart($column_chart_linha); ?>
</div>
<div id="chart_div">
    <?php $this->GoogleCharts->createJsChart($column_chart_barras); ?>
</div>