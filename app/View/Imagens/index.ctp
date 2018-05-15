<br>
<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('controller' => 'Albuns', 'action' => 'index'), array('escape' => false));
?>
<div id="toporight">
    <?php
    echo $this->Html->link($this->Html->image("botoes/add.png", array("alt" => "Adicionar", "title" => "Adicionar")), array('action' => 'add/' . $albun_id), array('escape' => false));
    ?>
</div>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo $this->Paginator->sort('Albun.titulo', 'Diretório'); ?></th>
        <th><?php echo $this->Paginator->sort('titulo', 'Título'); ?></th>
        <th><?php echo $this->Paginator->sort('descricao', 'Descrição'); ?></th>
        <th><?php echo $this->Paginator->sort('Imagem', 'Imagem/foto'); ?></th>
        <th class="actions"><?php echo __('Ações'); ?></th>
    </tr>

    <?php foreach ($imagens as $item): ?>
        <tr>
            <td><?php echo h($item['Albun']['titulo']); ?>&nbsp;</td>
            <td><?php echo h($item['Imagen']['titulo']); ?>&nbsp;</td>
            <td><?php echo h($item['Imagen']['descricao']); ?>&nbsp;</td>

            <?php $extensao = substr($item['Imagen']['img_foto'], (strlen($item['Imagen']['img_foto']) - 4), strlen($item['Imagen']['img_foto'])); ?>

            <?php if (strtoupper($extensao) == strtoupper('.pdf')) { ?>
                <td><?php
                    echo "<div id='imagemfoto_index'>";
                    echo $this->Html->image("botoes/arquivo_pequeno.png");
                    echo "</div>";
                    ?>&nbsp;
                </td>
            <?php } else { ?>
                <td><?php
                    echo "<div id = 'imagemfoto_index'>";
                    echo $this->Html->image("imagemfoto/" . $empresa_id . "/" . $item['Imagen']['img_foto']);
                    echo "</div>";
                    ?>&nbsp;
                </td>
            <?php } ?>
            <td>
                <div id="botoes">
                    <?php
                    echo $this->Html->link($this->Html->image("botoes/view.png", array("alt" => "Visualizar", "title" => "Visualizar")), array('action' => 'view', $item['Imagen']['id']), array('escape' => false));
                    echo $this->Html->link($this->Html->image("botoes/editar.gif", array("alt" => "Editar", "title" => "Editar")), array('action' => 'edit', $item['Albun']['id'], $item['Imagen']['id']), array('escape' => false));
                    echo $this->Form->postLink($this->Html->image("botoes/excluir.gif", array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $item['Imagen']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?')
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
        echo "<p> &nbsp;
                | " . $this->Paginator->numbers() . "| </p>";
    } else {
        echo $this->Paginator->counter('{:count}') . " registros encontrados.";
    }
    ?>
</p>