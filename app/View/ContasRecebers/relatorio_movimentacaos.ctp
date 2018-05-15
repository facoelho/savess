<?php echo $this->Form->create('Relatorio', array('action' => '../ContasRecebers/relatorio_movimentacaos', 'target' => '_blank')); ?>
<fieldset>
    <?php
    echo $this->Form->input('inicio', array('id' => 'start', 'class' => 'data', 'type' => 'text', 'label' => 'Data inicial'));
    echo $this->Form->input('fim', array('id' => 'end', 'class' => 'data', 'type' => 'text', 'label' => 'Data final'));
    echo $this->Form->input('especialista_id', array('id' => 'especialistaID', 'type' => 'select', 'options' => $especialistas, 'label' => 'Especialistas', 'empty' => '-- Selecione o especialista --'));
    echo $this->Form->input('paciente_id', array('id' => 'pacienteID', 'type' => 'select', 'options' => $pacientes, 'label' => 'Pacientes', 'empty' => '-- Selecione o paciente --'));
    echo $this->Form->input('formaconsulta_id', array('id' => 'formaconsultaID', 'type' => 'select', 'options' => $forma_consulta, 'label' => 'Forma da consulta', 'empty' => '-- Selecione a forma da consulta --'));
    ?>
    <div id="formParticular">
        <?php echo $this->Form->input('event_type_id', array('id' => 'eventID', 'label' => 'Tipo de consulta', 'empty' => '-- Selecione o tipo de consulta --')); ?>
    </div>
    <div id="formConvenio">
        <?php
        echo $this->Form->input('ContasReceber.convenio_id', array('id' => 'convenioID', 'type' => 'select', 'options' => $convenios, 'label' => 'Convênio', 'empty' => '-- Selecione o Convênio --'));
        echo $this->Form->input('convenio_categoria_id', array('id' => 'categoriaID', 'type' => 'select', 'label' => 'Categorias'));
        ?>
    </div>
    <?php
    echo $this->Form->input('formapagamento', array('id' => 'formapagamentoID', 'type' => 'select', 'options' => $formaspagamento, 'label' => 'Formas de pagamento', 'empty' => '-- Selecione a forma de pagamento --'));
    echo $this->Form->input('lancamentos', array('id' => 'lancamentosID', 'type' => 'select', 'options' => $lancamentos, 'label' => 'Lançamentos'));
    echo $this->Form->input('tprelatorio', array('id' => 'tprelatorioID', 'type' => 'select', 'options' => $tprelatorio, 'label' => 'Tipo de relatório'));
    ?>
</fieldset>

<?php echo $this->Form->end(__('IMPRIMIR')); ?>

<?php
$this->Js->get('#convenioID')->event(
        'change', $this->Js->request(
                array('controller' => 'ConveniosCategorias', 'action' => 'buscaCategorias', 'ContasReceber'), array('update' => '#categoriaID',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            )),
                )
        )
);
?>

<script type="text/javascript">
    jQuery(document).ready(function() {

        document.getElementById('start').focus();
        $("#start").mask("99/99/9999");
        $("#end").mask("99/99/9999");

        $(".data").datepicker({
            dateFormat: 'dd/mm/yy',
            dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
            dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            nextText: 'Próximo',
            prevText: 'Anterior'
        });

        $("#formConvenio").hide();
        $("#formParticular").hide();

        $("#formaconsultaID").change(function() {
            if ($("#formaconsultaID").val() == 'P') {
                $("#formParticular").show();
                $("#formConvenio").hide();
                $("#convenioID").val("");
            } else if ($("#formaconsultaID").val() == 'C') {
                $("#formParticular").hide();
                $("#eventID").val("");
                $("#formConvenio").show();
            } else {
                $("#formParticular").hide();
                $("#formConvenio").hide();
                $("#convenioID").val("");
                $("#eventID").val("");
            }
        });
    });
</script>