<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false));
?>
<br>
<br>
<?php echo $this->Form->create('Caixa'); ?>
<fieldset>
    <?php
    echo $this->Form->input('dtcaixa', array('id' => 'dtcaixa', 'class' => 'data', 'type' => 'text', 'label' => 'Data do caixa'));
    if (empty($saldo)) {
        echo $this->Form->input('saldo', array('id' => 'saldoID', 'type' => 'hidden', 'label' => 'Saldo do caixa'));
    } else {
        echo $this->Form->input('saldo', array('id' => 'saldo', 'type' => 'hidden', 'label' => 'Saldo do caixa', 'value' => $saldo[0][0]['saldo'], 'readonly'));
    }
    echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa_id));
    ?>
</fieldset>

<?php echo $this->Form->end(__('SALVAR')); ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        $("#saldoID").maskMoney({showSymbol: false, decimal: ",", thousands: "", precision: 2});
        $("#dtcaixa").mask("99/99/9999");
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
    });
</script>