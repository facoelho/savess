<div id="filtroGrade">
    <?php
    echo $this->Search->create();
    echo $this->Search->input('filter4', array('id' => 'especialistaID', 'class' => 'select-box', 'empty' => '-- Especialista --'));
    echo $this->Html->image("separador.png");
    echo $this->Search->input('filter1', array('id' => 'pacienteID', 'class' => 'select-box', 'empty' => '-- Paciente --'));
    echo $this->Html->image("separador.png");
    echo $this->Search->input('filter2', array('class' => 'select-box', 'empty' => '-- Todos lançamentos --'));
    echo $this->Html->image("separador.png");
    echo $this->Search->input('filter3', array('class' => 'input-box', 'id' => 'data1', 'placeholder' => 'dia/mês/ano'), array('class' => 'input-box', 'id' => 'data2', 'placeholder' => 'dia/mês/ano'));
    echo $this->Html->image("separador.png");
    ?>
    <input  type="submit" value="FILTRAR" class="botaoFiltro"/>
</div>
<br>
<br>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo $this->Paginator->sort('id'); ?></th>
        <th><?php echo $this->Paginator->sort('Paciente.nome', 'Paciente'); ?></th>
        <th><?php echo 'Data'; ?></th>
        <th><?php echo 'Início'; ?></th>
        <th><?php echo 'Fim'; ?></th>
        <th><?php echo $this->Paginator->sort('valor', '(R$) Consulta'); ?></th>
        <th><?php echo $this->Paginator->sort('saldo', '(R$) Défice'); ?></th>
        <th class="actions"><?php echo __('Ações'); ?></th>
    </tr>
    <?php foreach ($contasrecebers as $item): ?>
        <tr>
            <td><?php echo h($item['ContasReceber']['id']); ?>&nbsp;</td>
            <td><?php echo h($item['Paciente']['nome'] . ' ' . $item['Paciente']['sobrenome']); ?>&nbsp;</td>

            <?php $busca_evento = $this->requestAction('/ContasRecebers/busca_evento', array('pass' => array($item['ContasReceber']['event_id']))); ?>
            <td><?php echo date('d/m/Y', strtotime($busca_evento[0]['events']['start'])); ?>&nbsp;</td>

            <td><?php echo date('H:i', strtotime($busca_evento[0]['events']['start'])); ?>&nbsp;</td>
            <td><?php echo date('H:i', strtotime($busca_evento[0]['events']['end'])); ?>&nbsp;</td>

            <td><?php echo number_format(h($item['ContasReceber']['valor']), 2, ",", ""); ?>&nbsp;</td>

            <td><?php echo h(number_format($item['ContasReceber']['saldo'], 2, ",", "")); ?>&nbsp;</td>
            <td>
                <div id="botoes">
                    <?php
                    echo $this->Html->link($this->Html->image("botoes/view.png", array("alt" => "Visualizar lançamentos", "title" => "Visualizar lançamentos")), array('action' => 'view', $item['ContasReceber']['id']), array('escape' => false));
//                    echo $this->Html->link($this->Html->image("botoes/printer.png", array("alt" => "Imprimir recibo", "title" => "Imprimir recibo")), array('action' => 'imprimir_recibo', base64_encode($item['ContasReceber']['valor'] . '|' . $busca_evento[0]['events']['start'])), array('escape' => false, 'target' => '_blank'));
                    echo $this->Html->link($this->Html->image('botoes/excluir.gif', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $item['ContasReceber']['id']), array('escape' => false), __('Você realmete deseja apagar este lançamento?')
                    );
                    if ($item['ContasReceber']['saldo'] > 0) {
                        echo $this->Html->link($this->Html->image("botoes/pagar.png", array("alt" => "Efetuar pagamento", "title" => "Efetuar pagamento")), array('action' => 'add_lancamento', $item['ContasReceber']['id']), array('escape' => false));
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