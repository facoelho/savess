<br>
<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('controller' => 'Caixas', 'action' => 'index'), array('escape' => false));
?>
<br>
<br>
<?php echo $this->Form->create('Lancamento'); ?>
<fieldset>
    <?php
    echo $this->Form->input('dtcaixa', array('id' => 'dtcaixaID', 'type' => 'text', 'readonly', 'label' => 'Data do lançamento', 'value' => $caixa[0]['Caixa']['dtcaixa']));
    echo $this->Form->input('descricao');
    echo $this->Form->input('categorias_pai', array('id' => 'categorias_paiID', 'type' => 'select', 'options' => $categorias_pai, 'label' => 'Categoria pai', 'empty' => '-- Selecione a categoria pai --'));
    echo $this->Form->input('categoria_id', array('id' => 'categoriaID', 'type' => 'select', 'label' => 'Categorias'));
//    echo $this->Form->input('tipoexame_id', array('id' => 'tipoexameID', 'type' => 'select', 'label' => 'Tipos de exame', 'empty' => '-- Selecione o tipo de exame --'));
//    echo $this->Form->input('tipo', array('id' => 'tipoID', 'type' => 'select', 'options' => $tipo, 'label' => 'Tipo do lançamento'));
    echo $this->Form->input('valor', array('id' => 'valorID', 'type' => 'text', 'label' => 'Valor do lançamento'));
    echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa_id));
    ?>
</fieldset>
<?php echo $this->Form->end(__('SALVAR')); ?>

<script type="text/javascript">

    jQuery(document).ready(function() {

        $("#valorID").maskMoney({showSymbol: false, decimal: ",", thousands: "", precision: 2});

        $("#categorias_paiID").change(function() {
            $.ajax({async: true,
                data: $("#categorias_paiID").serialize(),
                dataType: "html",
                success: function(data, textStatus) {
                    $("#categoriaID").html(data);
                },
                type: "post",
                url: "\/Categorias\/buscaCategorias\/Lancamento\/" + $("#categorias_paiID option:selected").val()
            });
        });

//        $("#categoriaID").change(function() {
//            $.ajax({async: true,
//                data: $("#categoriaID").serialize(),
//                dataType: "html",
//                success: function(data, textStatus) {
//                    $("#tipoexameID").html(data);
//                },
//                type: "post",
//                url: "\/savess_cap/Tipoexames\/buscaTipoexames\/Lancamento\/" + $("#categoriaID option:selected").val()
//            });
//        });
    });
</script>
