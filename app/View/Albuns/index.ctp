<div id="filtroGrade">
    <?php
    echo $this->Search->create();
    echo $this->Search->input('filter1', array('id' => 'albunID', 'class' => 'select-box', 'empty' => '-- Selecione o diretório --'));
    echo $this->Html->image("separador.png");
    ?>
    <input  type="submit" value="FILTRAR" class="botaoFiltro"/>
    <div id="toporight">
        <?php
        echo $this->Html->link($this->Html->image("botoes/add.png", array("alt" => "Adicionar", "title" => "Adicionar")), array('action' => 'add'), array('escape' => false));
        ?>
    </div>
</div>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo $this->Paginator->sort('titulo', 'Pasta'); ?></th>
        <th><?php echo $this->Paginator->sort('Paciente.nome', 'Cliente'); ?></th>
        <th><?php echo $this->Paginator->sort('modified', 'Última alteração'); ?></th>
        <th class="actions"><?php echo __('Ações'); ?></th>
    </tr>
    <?php foreach ($albuns as $item): ?>
        <tr>
            <td><?php echo $this->Html->link($this->Html->image("botoes/pasta.png") . ' ' . h($item['Albun']['titulo']), array('controller' => 'Imagens', 'action' => 'index', $item['Albun']['id']), array('escape' => false)); ?>&nbsp;</td>
            <td><?php echo h($item['Paciente']['nome'] . ' ' . $item['Paciente']['sobrenome']); ?>&nbsp;</td>
            <td><?php echo date('d/m/Y H:i', strtotime($item['Albun']['modified'])); ?></td>
            <td>
                <div id="botoes">
                    <?php
//                    echo $this->Html->link($this->Html->image("botoes/novo.png", array("alt" => "Adicionar arquivo", "title" => "Adicionar arquivo")), array('controller' => 'Imagens', 'action' => 'add', $item['Albun']['id']), array('escape' => false));
                    echo $this->Html->link($this->Html->image("botoes/novo.png", array("alt" => "Adicionar arquivo", "title" => "Adicionar arquivo")), array('controller' => 'Imagens', 'action' => 'index', $item['Albun']['id']), array('escape' => false));
                    echo $this->Html->link($this->Html->image("botoes/editar.gif", array("alt" => "Editar", "title" => "Editar")), array('action' => 'edit', $item['Albun']['id']), array('escape' => false));
                    echo $this->Html->link($this->Html->image('botoes/excluir.gif', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $item['Albun']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?')
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