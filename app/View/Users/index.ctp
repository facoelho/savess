<?php
if ($adminholding == 1) {
    echo $this->Html->link($this->Html->image("botoes/add.png", array("alt" => "Adicionar", "title" => "Adicionar")), array('action' => 'add'), array('escape' => false));
    echo "<br><br>";
}
?>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo $this->Paginator->sort('id'); ?></th>
        <th><?php echo $this->Paginator->sort('nome'); ?></th>
        <th><?php echo $this->Paginator->sort('sobrenome'); ?></th>
        <th><?php echo $this->Paginator->sort('email'); ?></th>
        <th><?php echo $this->Paginator->sort('ultimoacesso'); ?></th>
        <th>Holding</th>
        <th class="actions"><?php echo __('Ações'); ?></th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo h($user['User']['id']); ?>&nbsp;</td>
            <td><?php echo h($user['User']['nome']); ?>&nbsp;</td>
            <td><?php echo h($user['User']['sobrenome']); ?>&nbsp;</td>
            <td><?php echo h($user['User']['username']); ?>&nbsp;</td>
            <?php if (!empty($user['User']['ultimoacesso'])) { ?>
                <td><?php echo h(date('d/m/Y', strtotime($user['User']['ultimoacesso'])) . " " . date('H:i', strtotime($user['User']['ultimoacesso']))); ?>&nbsp;</td>
            <?php } else { ?>
                <td><?php echo ''; ?>&nbsp;</td>
            <?php } ?>
            <td><?php echo h($user['Holding']['nome']); ?>&nbsp;</td>
            <td>
                <div id="botoes">
                    <?php
                    echo $this->Html->link($this->Html->image("botoes/view.png", array("alt" => "Visualizar", "title" => "Visualizar")), array('action' => 'view', $user['User']['id']), array('escape' => false));
                    echo $this->Html->link($this->Html->image("botoes/editar.gif", array("alt" => "Editar", "title" => "Editar")), array('action' => 'edit', $user['User']['id']), array('escape' => false));
                    if ($adminholding == 1) {
                        echo $this->Form->postLink($this->Html->image('botoes/excluir.gif', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $user['User']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?')
                        );
                    }
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