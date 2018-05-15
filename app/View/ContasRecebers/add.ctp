<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Tiposervico'); ?>
<fieldset>
    <?php
    echo $this->Form->input('descricao');
    echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa_id));
    ?>

    <div id="formMostraCor">
        <div id="colorSelector">
            <div style="background-color: #0000EE">
            </div>
        </div>
    </div>

    <div id="cortiposervico">
        <?php echo $this->Form->input('cor', array('id' => 'corTiposervico', 'label' => 'Cor')); ?>
    </div>

    <?php echo $this->Form->input('valor', array('id' => 'valor', 'type' => 'text', 'label' => 'Valor do tipo de serviço')); ?>
    <?php echo $this->Form->input('duracao_consulta', array('type' => 'text', 'label' => 'Duração do tipo de serviço')); ?>

</fieldset>

<?php echo $this->Form->end(__('Adicionar')); ?>

<script type="text/javascript">

    function myfunction(obj) {
        if (obj.checked) {
            if (obj.name == "cor") {
                $("#formMostraCor").show();
                document.getElementById('corPlugin').focus();
            }
        } else {
            if (obj.name == "cor") {
                $("#formMostraCor").hide();
            }
        }
    }

    jQuery(document).ready(function() {

        $("#valor").maskMoney({showSymbol: false, decimal: ",", thousands: "", precision: 2});

        $("#cortiposervico").hide();

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
                $('#corTiposervico').val(hex);

            }
        });
    });

</script>