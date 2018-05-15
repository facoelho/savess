<?php
echo $this->Html->link($this->Html->image("botoes/add.png", array("alt" => "Adicionar", "title" => "Adicionar")), array('action' => 'add'), array('escape' => false));
?>
<br>
<br>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo $this->Paginator->sort('id'); ?></th>
        <th><?php echo $this->Paginator->sort('dtcaixa', 'Data caixa'); ?></th>
        <!--<th><?php echo $this->Paginator->sort('saldo', 'Saldo'); ?></th>-->
        <th><?php echo $this->Paginator->sort('status', 'Status'); ?></th>
        <th class="actions"><?php echo __('Ações'); ?></th>
    </tr>
    <?php foreach ($caixas as $item): ?>
        <tr>
            <td><?php echo h($item['Caixa']['id']); ?>&nbsp;</td>
            <td><?php echo $item['Caixa']['dtcaixa']; ?>&nbsp;</td>
            <!--<td><?php echo h(number_format($item['Caixa']['saldo'], 2, ",", "")); ?>&nbsp;</td>-->
            <?php if ($item['Caixa']['status'] == 'A') { ?>
                <td><?php echo 'ABERTO'; ?>&nbsp;</td>
            <?php } else { ?>
                <td><?php echo 'FECHADO'; ?>&nbsp;</td>
            <?php } ?>
            <td>
                <div id="botoes">
                    <?php
                    echo $this->Html->link($this->Html->image("botoes/printer.png", array("alt" => "Conferência de caixa", "title" => "Conferência de caixa")), array('action' => 'confere_caixa', $item['Caixa']['id']), array('escape' => false, 'target' => '_blank'));
                    if ($item['Caixa']['status'] == 'A') {
                        echo $this->Html->link($this->Html->image("botoes/pagar.png", array("alt" => "Efetuar lançamento no caixa", "title" => "Efetuar lançamento no caixa")), array('controller' => 'Lancamentos', 'action' => 'add', $item['Caixa']['id']), array('escape' => false));
                    }
                    echo $this->Html->link($this->Html->image("botoes/editar.gif", array("alt" => "Editar", "title" => "Editar")), array('action' => 'edit', $item['Caixa']['id']), array('escape' => false));
                    echo $this->Html->link($this->Html->image('botoes/excluir.gif', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $item['Caixa']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?')
                    );
                    ?>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<br>
<p>
    <?php
    if ($this->Paginator->counter('{:pages}') > 1) {
        echo "<p> &nbsp; | " . $this->Paginator->numbers() . "| </p>";
    } else {
        echo $this->Paginator->counter('{:count}') . " registros encontrados.";
    }
    ?>
</p>