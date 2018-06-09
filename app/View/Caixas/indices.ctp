<?php echo $this->Form->create('Relatorio', array('escape' => false, 'target' => '_blank')); ?>
<fieldset>
    <?php
    echo $this->Form->input('dtdespesa_inicio', array('id' => 'dtinicioID', 'class' => 'data', 'type' => 'text', 'label' => 'Data inicio da despesa'));
    echo $this->Form->input('dtdespesa_fim', array('id' => 'dtfimID', 'class' => 'data', 'type' => 'text', 'label' => 'Data final da despesa'));
    echo $this->Form->input('categorias_pai', array('id' => 'categorias_paiID', 'type' => 'select', 'options' => $categorias_pai, 'label' => 'Categoria pai', 'empty' => '-- Selecione a categoria pai --'));
    echo $this->Form->input('categoria_id', array('id' => 'categoriaID', 'type' => 'select', 'label' => 'Categorias'));
    echo $this->Form->input('tipo', array('id' => 'tipoID', 'type' => 'select', 'options' => $tipo, 'label' => 'Tipo de lançamento', 'empty' => '-- Selecione o tipo de lançamento --'));
//    echo $this->Form->input('Tipoexame.Tipoexame', array('id' => 'tipoexameID', 'title' => 'CTRL + Click (para selecionar mais de um)', 'label' => 'Escolha os tipos de exame', 'type' => 'select', 'multiple' => true));
//    echo $this->Form->input('tipografico', array('id' => 'tipograficoID', 'options' => $tipografico, 'type' => 'select', 'label' => 'Tipos de gráfico'));
    ?>
</fieldset>
<?php echo $this->Form->end(__('Imprimir')); ?>

<script>

    jQuery(document).ready(function() {

        $("#dtinicioID").mask("99/99/9999");
        $("#dtfimID").mask("99/99/9999");

        $("#categorias_paiID").change(function() {
            $("#tipoexameID").text('');
        });

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

        $("#categorias_paiID").change(function() {
            $.ajax({async: true,
                data: $("#categorias_paiID").serialize(),
                dataType: "html",
                success: function(data, textStatus) {
                    $("#categoriaID").html(data);
                },
                type: "post",
                url: "\/savess_cap\/Categorias\/buscaCategorias\/Lancamento\/" + $("#categorias_paiID option:selected").val()
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
//                url: "\/savess_cap/Tipoexames\/buscaRelatoriotipoexames\/Caixa\/" + $("#categoriaID option:selected").val()
//            });
//        });
    });
</script>