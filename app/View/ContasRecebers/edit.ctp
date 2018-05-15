<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false));
?>
<br>
<br>
<?php echo $this->Form->create('Tiposervico'); ?>
<fieldset>
    <?php
    echo $this->Form->input('descricao');
    ?>

    &nbsp;Cor<br>
    <div id="colorSelector">
        <div style="background-color: #<?php echo $cortiposervico; ?>">
        </div>
    </div>

    <?php
    echo $this->Form->input('duracao_consulta', array('type' => 'text', 'label' => 'Duração do tipo de serviço'));
    echo $this->Form->input('observacao');
    ?>

</fieldset>

<?php echo $this->Form->end(__('Editar')); ?>

<script type="text/javascript">

    jQuery(document).ready(function() {

        $('#colorSelector').ColorPicker({
            color: '#0000ff',
            onShow: function(colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function(colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            onChange: function(hsb, hex, rgb) {
                $('#colorSelector div').css('backgroundColor', '#' + hex);
                $('#cortiposervico').val(hex);

            }
        });
    });

</script>