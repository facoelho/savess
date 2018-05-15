<?php
//echo $this->Html->link($this->Html->image("botoes/add.png", array("alt" => "Adicionar", "title" => "Adicionar")), array('action' => 'add'), array('escape' => false));
$paciente_id = '';
$tiposervico_id = '';
$cont = 1;
?>
<div id="filtroGrade">
    <?php
    echo $this->Search->create();
    echo $this->Search->input('filter1', array('class' => 'input-box', 'id' => 'data1', 'placeholder' => 'dia/mês/ano'));
    echo $this->Html->image("separador.png");
    echo $this->Search->input('filter2', array('class' => 'input-box', 'id' => 'descricao', 'placeholder' => 'Imóvel descrição'));
    echo $this->Html->image("separador.png");
    ?>
    <input  type="submit" value="Filtrar" class="botaoFiltro"/>

</div>
<br>
<br>
<?php if (!empty($data)) { ?>
    <table cellpadding="0" cellspacing="0">
        <?php while ($horaInicial->add(new DateInterval('PT50M')) < $horaFinal) { ?>
            <?php $consulta = $this->requestAction('/Agendas/busca_consulta', array('pass' => array((substr($data, 6, 4) . '-' . substr($data, 3, 2) . '-' . substr($data, 0, 2)), $horaInicial, $especialista_id))); ?>
            <tr>
                <?php if (!empty($consulta)) { ?>
                    <?php $param = explode('|', $consulta); ?>
                    <?php if (($param[3] == $paciente_id) and ($param[4] == $tiposervico_id)) { ?>
                        <?php if ($cont < $periodos) { ?>
                            <td bgcolor="#<?php echo $param[1]; ?>"><b><?php echo $horaInicial->format('H:i'); ?> </b></td>
                        <?php } else { ?>
                            <td><b><a href="/savemed/Agendas/add/<?php echo $data . '|' . $horaInicial->format('H') . '|' . $horaInicial->format('i') . '|' . $especialista_id; ?>"><?php echo $horaInicial->format('H:i'); ?></a></b></td>
                        <?php } ?>
                        <?php $cont = $cont + 1; ?>
                    <?php } else { ?>
                        <td bgcolor="#<?php echo $param[1]; ?>"><b><?php echo $horaInicial->format('H:i'); ?> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $param[0]; ?>&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $param[2]; ?></b></td>
                        <?php $cont = 1; ?>
                    <?php } ?>
                <?php } else { ?>
                    <td><b><a href="/savemed/Agendas/add/<?php echo $data . '|' . $horaInicial->format('H') . '|' . $horaInicial->format('i') . '|' . $especialista_id; ?>"><?php echo $horaInicial->format('H:i'); ?></a></b></td>
                    <?php $cont = 0; ?>
                <?php } ?>
            </tr>
            <?php
            if (!empty($consulta)) {
                $periodos = $param[5];
                $paciente_id = $param[3];
                $tiposervico_id = $param[4];
            }
            ?>
        <?php } ?>
    </table>
<?php } ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        document.getElementById('data1').focus();

        $("#data1").mask("99/99/9999");

        $(".input-box").datepicker({
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