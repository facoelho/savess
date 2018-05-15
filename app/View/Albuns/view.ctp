<br>
<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('Albuns' => 'index', 'action' => 'index'), array('escape' => false));
?>
<div id="toporight">
    <?php
    echo $this->Html->link($this->Html->image("botoes/add.png", array("alt" => "Adicionar", "title" => "Adicionar")), array('action' => 'add/' . $id), array('escape' => false));
//echo $this->Html->link($this->Html->image("botoes/imprimir.png", array("alt" => "Imprimir", "title" => "Imprimir")), array('action' => 'print'), array('escape' => false));
    ?>
</div>
<br>
<br>
<?php if (!empty($albun['Imagen'])) { ?>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo 'Arquivo'; ?></th>
            <th><?php echo 'Descrição'; ?></th>
            <th><?php echo 'Imagem/foto'; ?></th>
            <th class="actions"><?php echo __('Ações'); ?></th>
        </tr>
        <?php foreach ($albun['Imagen'] as $key => $item): ?>
            <tr>
                <td><?php echo h($item['titulo']); ?>&nbsp;</td>
                <td><?php echo h($item['descricao']); ?>&nbsp;</td>
                <td><?php
                    $extensao = substr($item['img_foto'], (strlen($item['img_foto']) - 4), strlen($item['img_foto']));
                    echo "<div id='imagemfoto_index'>";
                    if (strtoupper($extensao) == strtoupper('.pdf')) {
                        echo $this->Html->image("botoes/arquivo.png");
                    } else {
                        echo $this->Html->image("imagemfoto/" . $empresa_id . "/" . $item['img_foto']);
                    }
                    echo "</div>";
                    ?>&nbsp;
                </td>
                <td>
                    <div id="botoes">
                        <?php
                        echo $this->Html->link($this->Html->image("botoes/view.png", array("alt" => "Visualizar", "title" => "Visualizar")), array('controller' => 'Imagens', 'action' => 'view', $item['id']), array('escape' => false));
                        echo $this->Html->link($this->Html->image("botoes/editar.gif", array("alt" => "Editar", "title" => "Editar")), array('controller' => 'Imagens', 'action' => 'edit', $item['id']), array('escape' => false));
                        echo $this->Form->postLink($this->Html->image('botoes/excluir.gif', array('alt' => 'Exluir', 'title' => 'Exluir')), array('controller' => 'Imagens', 'action' => 'delete', $item['id']), array('escape' => false), __('Você realmete deseja apagar esse item?')
                        );
                        ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php } ?>