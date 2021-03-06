<div id="toporight">
    <?php
    echo $this->Html->link($this->Html->image("botoes/add.png", array("alt" => "Adicionar", "title" => "Adicionar")), array('action' => 'add'), array('escape' => false));
//echo $this->Html->link($this->Html->image("botoes/imprimir.png", array("alt" => "Imprimir", "title" => "Imprimir")), array('action' => 'print'), array('escape' => false));
    ?>
</div>
<br>
<br>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo $this->Paginator->sort('id'); ?></th>
        <th><?php echo $this->Paginator->sort('nome', 'Nome'); ?></th>
        <th><?php echo $this->Paginator->sort('sobrenome', 'Sobrenome'); ?></th>
        <th><?php echo $this->Paginator->sort('ativo', 'Status'); ?></th>
        <th class="actions"><?php echo __('Ações'); ?></th>
    </tr>
    <?php foreach ($especialistas as $item): ?>
        <tr>
            <td><?php echo h($item['Especialista']['id']); ?>&nbsp;</td>
            <td><?php echo h($item['Especialista']['nome']); ?>&nbsp;</td>
            <td><?php echo h($item['Especialista']['sobrenome']); ?>&nbsp;</td>
            <?php if ($item['Especialista']['ativo'] == 'S') { ?>
                <td><?php echo 'Ativo'; ?>&nbsp;</td>
            <?php } else { ?>
                <td><?php echo 'Inativo'; ?>&nbsp;</td>
            <?php } ?>
            <td>
                <div id="botoes">
                    <?php
                    echo $this->Html->link($this->Html->image("botoes/view.png", array("alt" => "Visualizar", "title" => "Visualizar")), array('action' => 'view', $item['Especialista']['id']), array('escape' => false));
                    echo $this->Html->link($this->Html->image("botoes/editar.gif", array("alt" => "Editar", "title" => "Editar")), array('action' => 'edit', $item['Especialista']['id']), array('escape' => false));
                    echo $this->Form->postLink($this->Html->image('botoes/excluir.gif', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $item['Especialista']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?')
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