<div id="toporight">
    <?php
    echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
    ?>
</div>
<?php
//echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
echo $this->Form->postLink($this->Html->image('botoes/excluir.png', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $convenios['Convenio']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?'));
?>
<br>
<br>
<br>
<p>
    <strong><?php echo $convenios['Convenio']['descricao']; ?></strong>
    <?php if (!empty($convenios['ConveniosCategoria'])) { ?>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo 'Categoria'; ?></th>
            <th><?php echo 'Valor'; ?></th>
            <th><?php echo 'Ativo'; ?></th>
            <th class="actions"><?php echo __('Ações'); ?></th>
        </tr>
        <?php foreach ($convenios['ConveniosCategoria'] as $key => $item) : ?>
            <tr>
                <td><?php echo $item['descricao']; ?></td>
                <td><?php echo number_format(h($item['valor']), 2, ",", ""); ?>&nbsp;</td>
                <?php if ($item['ativo'] == 'S') { ?>
                    <td><?php echo 'Ativo'; ?></td>
                <?php } else { ?>
                    <td><?php echo 'Inativo'; ?></td>
                <?php } ?>
                <td>
                    <div id="botoes">
                        <?php
                        echo $this->Html->link($this->Html->image("botoes/editar.gif", array("alt" => "Editar", "title" => "Editar")), array('controller' => 'ConveniosCategorias', 'action' => 'edit', $item['id']), array('escape' => false));
                        echo $this->Form->postLink($this->Html->image('botoes/excluir.gif', array('alt' => 'Exluir', 'title' => 'Exluir')), array('controller' => 'ConveniosCategorias', 'action' => 'delete', $item['id']), array('escape' => false), __('Você realmete deseja apagar esse item?')
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <br>
        <?php endforeach; ?>
    </table>
<?php } ?>
</p>