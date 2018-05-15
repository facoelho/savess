<?php
echo $this->Html->link($this->Html->image("botoes/add.png", array("alt" => "Adicionar", "title" => "Adicionar")), array('action' => 'add'), array('escape' => false));
//echo $this->Html->link($this->Html->image("botoes/imprimir.png", array("alt" => "Imprimir", "title" => "Imprimir")), array('action' => 'print'), array('escape' => false));
?>
<br>
<br>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo $this->Paginator->sort('id'); ?></th>
        <th><?php echo $this->Paginator->sort('razaosocial'); ?></th>
        <th><?php echo $this->Paginator->sort('nomefantasia'); ?></th>
        <th><?php echo $this->Paginator->sort('cnpj'); ?></th>
        <th class="actions"><?php echo __('Ações'); ?></th>
    </tr>
    <?php foreach ($empresas as $empresa): ?>
        <tr>
            <td><?php echo h($empresa['Empresa']['id']); ?>&nbsp;</td>
            <td><?php echo h($empresa['Empresa']['razaosocial']); ?>&nbsp;</td>
            <td><?php echo h($empresa['Empresa']['nomefantasia']); ?>&nbsp;</td>
            <td><?php
                echo substr($empresa['Empresa']['cnpj'], 0, 3) . "." .
                substr($empresa['Empresa']['cnpj'], 3, 3) . "." .
                substr($empresa['Empresa']['cnpj'], 6, 3) . "/" .
                substr($empresa['Empresa']['cnpj'], 9, 4) . "-" .
                substr($empresa['Empresa']['cnpj'], 13, 2);
                ?>&nbsp;</td>
            <td>
                <div id="botoes">
                    <?php
                    echo $this->Html->link($this->Html->image("botoes/view.png", array("alt" => "Visualizar", "title" => "Visualizar")), array('action' => 'view', $empresa['Empresa']['id']), array('escape' => false));
                    echo $this->Html->link($this->Html->image("botoes/editar.gif", array("alt" => "Editar", "title" => "Editar")), array('action' => 'edit', $empresa['Empresa']['id']), array('escape' => false));
                    echo $this->Form->postLink($this->Html->image('botoes/excluir.gif', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $empresa['Empresa']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?')
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