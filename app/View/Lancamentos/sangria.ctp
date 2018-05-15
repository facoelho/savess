<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('controller' => 'Caixas', 'action' => 'index'), array('escape' => false));
?>
<br>
<br>
<?php echo $this->Form->create('Lancamento'); ?>
<fieldset>
    <?php
    echo $this->Form->input('dtcaixa', array('id' => 'dtcaixa', 'class' => 'data', 'type' => 'text', 'label' => 'Data do caixa', 'readonly', 'value' => $caixa[0]['Caixa']['dtcaixa']));
    echo $this->Form->input('saldo', array('id' => 'saldo', 'label' => 'Saldo atual do caixa', 'readonly', 'value' => $caixa[0]['Caixa']['saldo']));
    echo $this->Form->input('valor', array('id' => 'valorID', 'type' => 'text', 'label' => 'Valor do lançamento'));
    echo $this->Form->input('caixa_id', array('id' => 'caixaID', 'type' => 'hidden', 'value' => $caixa[0]['Caixa']['id']));
    ?>
</fieldset>

<?php echo $this->Form->end(__('SALVAR')); ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        $("#dtcaixa").mask("99/99/9999");
        $("#dtcaixa").mask("99/99/9999");
        $("#valorID").maskMoney({showSymbol: false, decimal: ",", thousands: "", precision: 2});
    });
</script>