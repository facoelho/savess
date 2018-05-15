<br>
<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('controller' => 'Convenios', 'action' => 'index'), array('escape' => false));
?>
<div id="toporight">
    <?php
    echo $this->Html->link($this->Html->image("botoes/add.png", array("alt" => "Adicionar", "title" => "Adicionar")), array('action' => 'add/' . $convenio_id), array('escape' => false));
//echo $this->Html->link($this->Html->image("botoes/imprimir.png", array("alt" => "Imprimir", "title" => "Imprimir")), array('action' => 'print'), array('escape' => false));
    ?>
</div>
<br>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo $this->Paginator->sort('id'); ?></th>
        <th><?php echo $this->Paginator->sort('Convenio.descricao', 'Convênio'); ?></th>
        <th><?php echo $this->Paginator->sort('descricao', 'Categoria'); ?></th>
        <th><?php echo $this->Paginator->sort('valor', 'Valor'); ?></th>
        <th><?php echo $this->Paginator->sort('ativo', 'Ativo'); ?></th>

        <th class="actions"><?php echo __('Ações'); ?></th>
    </tr>
    <?php foreach ($convenioscategorias as $item): ?>
        <tr>
            <td><?php echo h($item['ConveniosCategoria']['id']); ?>&nbsp;</td>
            <td><?php echo h($item['Convenio']['descricao']); ?>&nbsp;</td>
            <td><?php echo h($item['ConveniosCategoria']['descricao']); ?>&nbsp;</td>
            <td><?php echo number_format(h($item['ConveniosCategoria']['valor']), 2, ",", ""); ?>&nbsp;</td>
            <?php if ($item['ConveniosCategoria']['ativo'] == 'S') { ?>
                <td><?php echo 'Sim'; ?></td>
            <?php } else { ?>
                <td><?php echo 'Não'; ?></td>
            <?php } ?>
            <td>
                <div id="botoes">
                    <?php
//                    echo $this->Html->link($this->Html->image("botoes/view.png", array("alt" => "Visualizar", "title" => "Visualizar")), array('action' => 'view', $item['ConveniosCategoria']['id']), array('escape' => false));
                    echo $this->Html->link($this->Html->image("botoes/editar.gif", array("alt" => "Editar", "title" => "Editar")), array('action' => 'edit', $item['ConveniosCategoria']['id']), array('escape' => false));
                    echo $this->Form->postLink($this->Html->image('botoes/excluir.gif', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $item['Convenio']['id'], $item['ConveniosCategoria']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?')
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