<?php $this->layout = 'naoLogado'; ?>
<?php
$entradas = 0;
$saidas = 0;
$retiradas = 0;
?>
<?php echo '<b>' . 'Caixa: ' . '</b>' . $lancamentos[0]['Caixa']['dtcaixa']; ?>
<br><br>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo 'Categoria'; ?></th>
        <th><?php echo 'Descrição'; ?></th>
        <th><?php echo 'Valor'; ?></th>
        <th><?php echo 'Usuário'; ?></th>
        <th><?php echo 'Dt lançamento'; ?></th>
        <th><?php echo 'Tipo'; ?></th>
        <th class="actions"><?php echo __('Ações'); ?></th>
    </tr>
    <?php foreach ($lancamentos as $item): ?>
        <tr>
            <td><?php echo $item['Categoria']['descricao']; ?>&nbsp;</td>
            <td><?php echo $item['Lancamento']['descricao']; ?>&nbsp;</td>
            <td><?php echo number_format($item['Lancamento']['valor'], 2, ",", ""); ?>&nbsp;</td>
            <td><?php echo $item['User']['id'] . ' - ' . $item['User']['nome'] . ' ' . $item['User']['sobrenome']; ?>&nbsp;</td>
            <td><?php echo date('d/m/Y H:i', strtotime($item['Lancamento']['created'])); ?>&nbsp;</td>
            <td><?php echo $item['Categoria']['tipo']; ?>&nbsp;</td>
            <td>
                <div id="botoes">
                    <?php
                    echo $this->Html->link($this->Html->image('botoes/excluir.gif', array('alt' => 'Exluir', 'title' => 'Exluir')), array('controller' => 'Lancamentos', 'action' => 'delete_lancamento', $id, $item['Lancamento']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?')
                    );
                    ?>
                </div>
            </td>
        </tr>
        <?php if ($item['Categoria']['tipo'] == 'E') { ?>
            <?php $entradas = $entradas + $item['Lancamento']['valor']; ?>
        <?php } elseif ($item['Categoria']['tipo'] == 'S') { ?>
            <?php $saidas = $saidas + $item['Lancamento']['valor']; ?>
        <?php } elseif ($item['Categoria']['tipo'] == 'R') { ?>
            <?php $retiradas = $retiradas + $item['Lancamento']['valor']; ?>
        <?php } ?>
    <?php endforeach; ?>
</table>
<br><br>
<table cellpadding="0" cellspacing="0">
    <tr>
        <td><?php echo '<b>' . 'Entradas: ' . '</b>' . number_format($entradas, 2, ",", ""); ?>&nbsp;</td>
    <tr>
    <tr>
        <td><?php echo '<b>' . 'Saídas: ' . '</b>' . number_format($saidas, 2, ",", ""); ?>&nbsp;</td>
    </tr>
    <tr>
        <td><?php echo '<b>' . 'Retiradas: ' . '</b>' . number_format($retiradas, 2, ",", ""); ?>&nbsp;</td>
    </tr>
</table>