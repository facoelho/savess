<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false));
?>
<br>
<br>
<?php echo $this->Form->create('Menu'); ?>
<fieldset>
    <?php
    echo $this->Form->input('nome');
    echo $this->Form->input('controller');
    echo $this->Form->input('mostramenu', array('id' => 'mostraMenu', 'type' => 'select', 'options' => $opcoes, 'label' => 'Mostra menu', 'empty' => ''));
    echo $this->Form->input('menu', array('id' => 'itemMenu'));
    echo $this->Form->input('ordem', array('id' => 'itemOrdem'));
    ?>
</fieldset>
<?php echo $this->Form->end(__('SALVAR')); ?>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        if ($("#mostraMenu option:selected").val() == 2) {
            $("#itemMenu").attr('disabled', 'disabled');
            $("#itemOrdem").attr('disabled', 'disabled');
        }
        $("#mostraMenu").change(function() {
            var itemSelecionado = $("#mostraMenu option:selected").val();
            if (itemSelecionado == 1) {
                $("#itemMenu").attr('disabled', false);
                $("#itemOrdem").attr('disabled', false);
            } else if (itemSelecionado == 2) {
                $("#itemMenu").attr('disabled', 'disabled');
                document.getElementById('itemMenu').value = '';
                $("#itemOrdem").attr('disabled', 'disabled');
                document.getElementById('itemOrdem').value = '';
            }
        });
    });

</script>
