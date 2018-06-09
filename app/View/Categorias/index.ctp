<?php
echo $this->Html->link($this->Html->image("botoes/add.png", array("alt" => "Adicionar", "title" => "Adicionar")), array('action' => 'add'), array('escape' => false));
//echo $this->Html->link($this->Html->image("botoes/imprimir.png", array("alt" => "Imprimir", "title" => "Imprimir")), array('action' => 'print'), array('escape' => false));
?>
<br>
<br>
<div id="filtroGrade">
    <?php
    echo $this->Search->create();
    echo $this->Search->input('filter1', array('class' => 'input-box', 'placeholder' => 'Descrição'));
    echo $this->Html->image("separador.png");
    echo $this->Search->input('filter2', array('class' => 'select-box', 'placeholder' => 'Ativo', 'empty' => '-- Ativo --'));
    echo $this->Html->image("separador.png");
    ?>
    <input type="submit" value="Filtrar" class="botaoFiltro"/>

</div>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo $this->Paginator->sort('id'); ?></th>
        <th><?php echo $this->Paginator->sort('descricao', 'Descrição'); ?></th>
        <th><?php echo $this->Paginator->sort('categoria_pai_id', 'Categoria pai'); ?></th>
        <th><?php echo $this->Paginator->sort('ativo', 'Ativo'); ?></th>
        <th class="actions"><?php echo __('Ações'); ?></th>
    </tr>
    <?php foreach ($categorias as $item): ?>
        <tr>
            <td><?php echo h($item['Categoria']['id']); ?>&nbsp;</td>
            <td><?php echo h($item['Categoria']['descricao']); ?>&nbsp;</td>
            <td><?php echo h($item['Categoriapai']['descricao']); ?>&nbsp;</td>
            <?php if ($item['Categoria']['ativo'] == 'S') { ?>
                <td><?php echo 'SIM'; ?>&nbsp;</td>
            <?php } else { ?>
                <td><?php echo 'NÃO'; ?>&nbsp;</td>
            <?php } ?>

            <td>
                <div id="botoes">
                    <?php
                    echo $this->Html->link($this->Html->image("botoes/view.png", array("alt" => "Visualizar", "title" => "Visualizar")), array('action' => 'view', $item['Categoria']['id']), array('escape' => false));
                    echo $this->Html->link($this->Html->image("botoes/editar.gif", array("alt" => "Editar", "title" => "Editar")), array('action' => 'edit', $item['Categoria']['id']), array('escape' => false));
                    echo $this->Html->link($this->Html->image('botoes/excluir.gif', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $item['Categoria']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?')
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