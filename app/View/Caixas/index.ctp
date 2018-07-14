<?php
echo $this->Html->link($this->Html->image("botoes/csv.png", array("alt" => "Exportar registros", "title" => "Exportar registros")), array('action' => 'exportar_csv'), array('escape' => false, 'target' => '_blank'));
echo $this->Html->link($this->Html->image("botoes/imprimir.png", array("alt" => "Imprimir", "title" => "Imprimir")), array('action' => 'imprimir_lista_caixas'), array('escape' => false, 'target' => '_blank'));
echo $this->Html->link($this->Html->image("botoes/add.png", array("alt" => "Adicionar", "title" => "Adicionar")), array('action' => 'add'), array('escape' => false));
?>
<br>
<br>
<div id="filtroGrade">
    <?php
    echo $this->Search->create();
    echo $this->Search->input('filter1', array('class' => 'input-box', 'placeholder' => 'Descrição'));
    echo $this->Html->image("separador.png");
    echo $this->Search->input('filter2', array('id' => 'categorias_paiID', 'class' => 'select-box', 'placeholder' => 'Categoria pai', 'empty' => '-- Categoria pai --'));
    echo $this->Html->image("separador.png");
    echo $this->Search->input('categoria_id', array('id' => 'categoriaID', 'class' => 'select-box', 'empty' => '-- Categorias --'));
    echo $this->Html->image("separador.png");
    echo $this->Search->input('filter5', array('id' => 'tipoID', 'class' => 'select-box', 'empty' => '-- Tipo --'));
    echo $this->Html->image("separador.png");
    echo $this->Search->input('filter4', array('class' => 'input-box', 'id' => 'data1', 'placeholder' => 'dia/mês/ano', 'title' => 'Data inicial'), array('class' => 'input-box', 'id' => 'data2', 'placeholder' => 'dia/mês/ano', 'title' => 'Data final'));
    echo $this->Html->image("separador.png");
    ?>
    <input type="submit" value="Filtrar" class="botaoFiltro"/>

</div>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo $this->Paginator->sort('id'); ?></th>
        <th><?php echo $this->Paginator->sort('dtcaixa', 'Data caixa'); ?></th>
        <!--<th><?php echo $this->Paginator->sort('saldo', 'Saldo'); ?></th>-->
        <th><?php echo $this->Paginator->sort('status', 'Status'); ?></th>
        <th class="actions"><?php echo __('Ações'); ?></th>
    </tr>
    <?php foreach ($caixas as $item): ?>
        <tr>
            <td><?php echo h($item['Caixa']['id']); ?>&nbsp;</td>
            <td><?php echo $item['Caixa']['dtcaixa']; ?>&nbsp;</td>
            <!--<td><?php echo h(number_format($item['Caixa']['saldo'], 2, ",", "")); ?>&nbsp;</td>-->
            <?php if ($item['Caixa']['status'] == 'A') { ?>
                <td><?php echo 'ABERTO'; ?>&nbsp;</td>
            <?php } else { ?>
                <td><?php echo 'FECHADO'; ?>&nbsp;</td>
            <?php } ?>
            <td>
                <div id="botoes">
                    <?php
                    echo $this->Html->link($this->Html->image("botoes/printer.png", array("alt" => "Conferência de caixa", "title" => "Conferência de caixa")), array('action' => 'confere_caixa', $item['Caixa']['id']), array('escape' => false, 'target' => '_blank'));
                    if ($adminholding == 1) {
                        if ($item['Caixa']['status'] == 'A') {
                            echo $this->Html->link($this->Html->image("botoes/pagar.png", array("alt" => "Efetuar lançamento no caixa", "title" => "Efetuar lançamento no caixa")), array('controller' => 'Lancamentos', 'action' => 'add', $item['Caixa']['id']), array('escape' => false));
                        }
                        echo $this->Html->link($this->Html->image("botoes/editar.gif", array("alt" => "Editar", "title" => "Editar")), array('action' => 'edit', $item['Caixa']['id']), array('escape' => false));
                        echo $this->Html->link($this->Html->image('botoes/excluir.gif', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $item['Caixa']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?')
                        );
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
        echo "<p> &nbsp; | " . $this->Paginator->numbers(array('first' => 4, 'last' => 4)) . "| </p>";
    } else {
        echo $this->Paginator->counter('{:count}') . " registros encontrados.";
    }
    ?>
</p>

<script type="text/javascript">

    jQuery(document).ready(function() {

        $("#data1").mask("99/99/9999");
        $("#data2").mask("99/99/9999");

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
    });
</script>