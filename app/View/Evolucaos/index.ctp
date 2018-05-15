<?php
//echo $this->Html->link($this->Html->image("botoes/add.png", array("alt" => "Adicionar", "title" => "Adicionar")), array('action' => 'add'), array('escape' => false));
//echo $this->Html->link($this->Html->image("botoes/imprimir.png", array("alt" => "Imprimir", "title" => "Imprimir")), array('action' => 'print'), array('escape' => false));
?>
<div id="filtroGrade">
    <?php
    echo $this->Search->create();
    echo $this->Search->input('filter3', array('id' => 'especialistaID', 'class' => 'select-box', 'empty' => '-- Especialista --'));
    echo $this->Html->image("separador.png");
    echo $this->Search->input('filter1', array('id' => 'pacienteID', 'class' => 'select-box', 'empty' => '-- Paciente --'));
    echo $this->Html->image("separador.png");
    echo $this->Search->input('filter2', array('class' => 'input-box', 'id' => 'data1', 'placeholder' => 'dia/mês/ano'), array('class' => 'input-box', 'id' => 'data2', 'placeholder' => 'dia/mês/ano'));
    echo $this->Html->image("separador.png");
    ?>
    <input  type="submit" value="FILTRAR" class="botaoFiltro"/>

</div>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo $this->Paginator->sort('id', 'Id'); ?></th>
        <th><?php echo $this->Paginator->sort('start', 'Inicio'); ?></th>
        <th><?php echo $this->Paginator->sort('end', 'Fim'); ?></th>
        <th><?php echo $this->Paginator->sort('Paciente.nome', 'Paciente'); ?></th>
        <th><?php echo $this->Paginator->sort('EventType.name', 'Tipo de evento'); ?></th>
        <th class="actions"><?php echo __('Ações'); ?></th>
    </tr>
    <?php foreach ($events as $item): ?>
        <tr>
            <td><?php echo h($item['Event']['id']); ?>&nbsp;</td>
            <td><?php echo h(date('d/m/Y H:i', strtotime($item['Event']['start']))); ?>&nbsp;</td>
            <td><?php echo h(date('d/m/Y H:i', strtotime($item['Event']['end']))); ?>&nbsp;</td>
            <td><?php echo h($item['Paciente']['nome'] . ' ' . $item['Paciente']['sobrenome']); ?>&nbsp;</td>
            <td><?php echo h($item['EventType']['name']); ?>&nbsp;</td>
            <td>
                <div id="botoes">
                    <?php
                    echo $this->Html->link($this->Html->image("botoes/editar.gif", array("alt" => "Editar", "title" => "Editar")), array('action' => 'edit', $item['Event']['id'], $item['Paciente']['id']), array('escape' => false));
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
<script type="text/javascript">
    jQuery(document).ready(function() {
        document.getElementById('especialistaID').focus();
        $("#data1").mask("99/99/9999");
        $("#data2").mask("99/99/9999");

        $(".input-box").datepicker({
            dateFormat: 'dd/mm/yy',
            dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
            dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            nextText: 'Próximo',
            prevText: 'Anterior'
        });
    });
</script>