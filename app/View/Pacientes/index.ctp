<div id="toporight">
    <?php
    echo $this->Html->link($this->Html->image("botoes/add.png", array("alt" => "Adicionar", "title" => "Adicionar")), array('action' => 'add'), array('escape' => false));
//echo $this->Html->link($this->Html->image("botoes/imprimir.png", array("alt" => "Imprimir", "title" => "Imprimir")), array('action' => 'print'), array('escape' => false));
    ?>
</div>
<br>
<br>
<div id="filtroGrade">
    <?php
    echo $this->Search->create();
    echo $this->Search->input('filter1', array('class' => 'input-box', 'placeholder' => 'Nome'));
    echo $this->Html->image("separador.png");
    echo $this->Search->input('filter2', array('class' => 'input-box', 'placeholder' => 'Sobrenome'));
    echo $this->Html->image("separador.png");
    echo $this->Search->input('filter3', array('class' => 'input-box', 'placeholder' => 'Celular'));
    echo $this->Html->image("separador.png");
    ?>
    <input  type="submit" value="Filtrar" class="botaoFiltro"/>

</div>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo $this->Paginator->sort('id'); ?></th>
        <th><?php echo $this->Paginator->sort('nome', 'Nome'); ?></th>
        <th><?php echo $this->Paginator->sort('sobrenome', 'Sobrenome'); ?></th>
        <!--<th><?php echo $this->Paginator->sort('sexo', 'Sexo'); ?></th>-->
        <th><?php echo $this->Paginator->sort('cpf', 'CPF'); ?></th>
        <th><?php echo 'Fone'; ?></th>
        <th><?php echo 'Celular'; ?></th>
        <th class="actions"><?php echo __('Ações'); ?></th>
    </tr>
    <?php foreach ($pacientes as $item): ?>
        <tr>
            <td><?php echo h($item['Paciente']['id']); ?>&nbsp;</td>
            <td><?php echo h($item['Paciente']['nome']); ?>&nbsp;</td>
            <td><?php echo h($item['Paciente']['sobrenome']); ?>&nbsp;</td>
            <td><?php
                echo substr($item['Paciente']['cpf'], 0, 3) . "." .
                substr($item['Paciente']['cpf'], 3, 3) . "." .
                substr($item['Paciente']['cpf'], 6, 3) . "-" .
                substr($item['Paciente']['cpf'], 9, 2);
                ?>&nbsp;</td>
            <td><?php echo h('(' . $item['Paciente']['dddfone'] . ')' . $item['Paciente']['fone']); ?>&nbsp;</td>
            <td><?php echo h('(' . $item['Paciente']['dddcelular'] . ')' . $item['Paciente']['celular']); ?>&nbsp;</td>
            <td>
                <div id="botoes">
                    <?php
                    echo $this->Html->link($this->Html->image("botoes/view.png", array("alt" => "Visualizar", "title" => "Visualizar")), array('action' => 'view', $item['Paciente']['id']), array('escape' => false));
//                    echo $this->Html->link($this->Html->image("botoes/editar.gif", array("alt" => "Editar", "title" => "Editar")), array('action' => 'edit', $item['Paciente']['id']), array('escape' => false));
                    echo $this->Html->link($this->Html->image('botoes/excluir.gif', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $item['Paciente']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?')
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