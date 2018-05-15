<br>
<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td colspan="6" align="center" font-size="1"><b><?php echo 'Lançamentos'; ?></b></td>
    </tr>
    <tr>
        <th>Data do lançamento</th>
        <th>Valor do lançamento</th>
        <th>Forma de pagamento</th>
        <th>Obs</th>
        <th>Usuário</th>
    </tr>
    <?php foreach ($contas_recebers['ContasRecebersMov'] as $item) : ?>

        <?php $usuario = $this->requestAction('/ContasRecebers/busca_usuario', array('pass' => array($user_id))); ?>
        <tr>
            <td><?php echo date('d/m/Y H:i', strtotime($item['created'])); ?></td>
            <td><?php echo number_format(h($item['valorlancto']), 2, ",", ""); ?>&nbsp;</td>
            <td><?php echo $item['FormasPagamento']['descricao']; ?></td>
            <td><?php echo $item['obs']; ?></td>
            <td><?php echo $usuario; ?></td>
        </tr>
    <?php endforeach; ?>
</table>