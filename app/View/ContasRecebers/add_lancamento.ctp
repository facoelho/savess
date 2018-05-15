<br>
<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td colspan="6" align="center" font-size="1"><b><?php echo 'Lançamentos anteriores'; ?></b></td>
    </tr>
    <tr>
        <th>Data do lançamento</th>
        <th>Valor do lançamento</th>
        <th>Obs</th>
        <th>Usuário</th>
        <th class="actions"><?php echo __('Ações'); ?></th>
    </tr>
    <?php foreach ($contas_recebers['ContasRecebersMov'] as $item) : ?>

        <?php $usuario = $this->requestAction('/ContasRecebers/busca_usuario', array('pass' => array($user_id))); ?>
        <tr>
            <td><?php echo date('d/m/Y H:i', strtotime($item['created'])); ?></td>
            <td><?php echo number_format(h($item['valorlancto']), 2, ",", ""); ?>&nbsp;</td>
            <td><?php echo $item['obs']; ?></td>
            <td><?php echo $usuario; ?></td>
            <td><?php echo $this->Form->postLink($this->Html->image('botoes/excluir.gif', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'excluir_lancamento', $contas_recebers['ContasReceber']['id'], $item['id']), array('escape' => false), __('Você realmete deseja apagar este lançamento?')); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<br>
<br>

<?php echo $this->Form->create('ContasReceber'); ?>
<fieldset>
    <?php
    echo $this->Form->input('valorlancto', array('id' => 'valorlancto', 'type' => 'text', 'label' => 'Valor do lançamento'));
    echo $this->Form->input('forma_pagamento_id', array('id' => 'forma_pagamento_id', 'type' => 'select', 'options' => $formaspagamentos, 'label' => 'Forma de pagamento'));
    echo $this->Form->input('obs', array('id' => 'obs', 'type' => 'textarea', 'label' => 'Observação'));
    echo $this->Form->input('saldo', array('id' => 'saldo', 'type' => 'text', 'label' => 'Saldo restante', 'readonly', 'value' => number_format(h($contas_recebers['ContasReceber']['saldo']), 2, ",", "")));
    echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $user_id));
    ?>

</fieldset>

<?php echo $this->Form->end(__('SALVAR')); ?>

<script type="text/javascript">

    jQuery(document).ready(function() {
        $("#valorlancto").maskMoney({showSymbol: false, decimal: ",", thousands: "", precision: 2});
        $("#valorlancto").change(function() {
            $i = 0;
            if (parseFloat($('#saldo').val().replace(",", ".")) - parseFloat($('#valorlancto').val().replace(",", ".")) < 0) {
                $('#valorlancto').val('');
                $i = 1;
            }
            if ($i > 0) {
                alert("Valor do lançamento não pode ser maior que o saldo restante");
                document.getElementById('valorlancto').focus();
            }
        })
    });

</script>