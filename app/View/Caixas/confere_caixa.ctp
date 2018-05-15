<?php $this->layout = 'naoLogado'; ?>
<br><br>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo 'Categoria'; ?></th>
        <th><?php echo 'Descrição'; ?></th>
        <th><?php echo 'Tipo exame'; ?></th>
        <th><?php echo 'Valor'; ?></th>
        <th><?php echo 'Usuário'; ?></th>
        <th><?php echo 'Dt lançamento'; ?></th>
    </tr>
    <?php foreach ($lancamentos as $item): ?>
        <tr>
            <td><?php echo $item['Categoria']['descricao']; ?>&nbsp;</td>
            <td><?php echo $item['Lancamento']['descricao']; ?>&nbsp;</td>
            <td><?php echo $item['Tipoexame']['descricao']; ?>&nbsp;</td>
            <td><?php echo number_format($item['Lancamento']['valor'], 2, ",", ""); ?>&nbsp;</td>
            <td><?php echo $item['User']['id'] . ' - ' . $item['User']['nome'] . ' ' . $item['User']['sobrenome']; ?>&nbsp;</td>
            <td><?php echo date('d/m/Y H:i', strtotime($item['Lancamento']['created'])); ?>&nbsp;</td>
        </tr>
    <?php endforeach; ?>
</table>
