<?php $this->layout = 'naoLogado'; ?>
<?php $dtcaixa = ''; ?>
<?php $total = 0; ?>

<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo 'Data caixa'; ?></th>
        <th><?php echo 'Categoria'; ?></th>
        <th><?php echo 'Descrição'; ?></th>
        <th><?php echo 'Valor'; ?></th>
        <th><?php echo 'Usuário'; ?></th>
        <th><?php echo 'Dt lançamento'; ?></th>
        <th><?php echo 'Tipo'; ?></th>
    </tr>
    <?php foreach ($caixas as $item): ?>
        <tr>
            <td><?php echo '<b>' . $item['Caixa']['dtcaixa'] . '</b>'; ?></td>
            <td><?php echo $item['Categoria']['descricao']; ?>&nbsp;</td>
            <td><?php echo $item['Lancamento']['descricao']; ?>&nbsp;</td>
            <?php if ($item['Categoria']['tipo'] == 'E') { ?>
                <td><font color="blue"><?php echo number_format($item['Lancamento']['valor'], 2, ',', '.'); ?>&nbsp;</font></td>
            <?php } elseif ($item['Categoria']['tipo'] == 'S') { ?>
                <td><font color="red"><?php echo number_format($item['Lancamento']['valor'], 2, ',', '.'); ?>&nbsp;</font></td>
            <?php } elseif ($item['Categoria']['tipo'] == 'R') { ?>
                <td><font color="green"><?php echo number_format($item['Lancamento']['valor'], 2, ',', '.'); ?>&nbsp;</font></td>
            <?php } ?>
            <td><?php echo $item['User']['id'] . ' - ' . $item['User']['nome'] . ' ' . $item['User']['sobrenome']; ?>&nbsp;</td>
            <td><?php echo date('d/m/Y H:i', strtotime($item['Lancamento']['created'])); ?>&nbsp;</td>
            <td><?php echo $item['Categoria']['tipo']; ?>&nbsp;</td>
        </tr>
        <?php $total = $total + $item['Lancamento']['valor'] ?>
    <?php endforeach; ?>
    <tr>
        <td colspan="3"><b><?php echo ''; ?>&nbsp;</b></td>
        <td colspan="3"><b><?php echo number_format($total, 2, ",", "."); ?>&nbsp;</b></td>
    </tr>
</table>