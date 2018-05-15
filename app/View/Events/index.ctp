<br>
<div id="filtroGrade">
    <?php
    echo $this->Search->create();
    echo $this->Search->input('filter1', array('class' => 'input-box', 'id' => 'data1', 'placeholder' => 'dia/mês/ano'));
    echo $this->Html->image("separador.png");
    ?>
    <input id="button" type="submit" value="FILTRAR" class="botaoFiltro"/>
</div>
<br>
<?php
$data_extenso = new DateTime($data);
date_default_timezone_set('America/Sao_Paulo');
$formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::FULL, IntlDateFormatter::NONE, IntlDateFormatter::GREGORIAN);
$data_anterior = date('Y-m-d', strtotime("-1 days", strtotime($data)));
$data_posterior = date('Y-m-d', strtotime("+1 days", strtotime($data)));
?>
<br>
<br>
<div id="agenda_especialistas">
    <?php foreach ($especialistas as $key => $especialista): ?>
        <?php $count = 0; ?>
        <div id="agenda_especialistas_tabela">
            <table cellpadding="0" cellspacing="0">
                <th width="8%"><?php echo ''; ?></th>
                <th><?php echo $especialista; ?></th>
                <?php foreach ($horarios as $horario): ?>
                    <?php if ($count == 0) { ?>
                        <tr>
                            <?php if ($horario < 10) { ?>
                                <?php $hora = '0' . $horario ?>
                            <?php } else { ?>
                                <?php $hora = $horario ?>
                            <?php } ?>
                            <?php if (substr($hora, 3, 2) == '00') { ?>
                                <td title='Clique para agendar'><b><?php echo $this->Html->link($hora, array('action' => 'agendamento', base64_encode($hora), $key), array('escape' => false), __('Agendar horário para o(a) ' . $especialista . ' às ' . $hora . ' ?')); ?></b></td>
                            <?php } else { ?>
                                <td title='Clique para agendar'><?php echo $this->Html->link($hora, array('action' => 'agendamento', base64_encode($hora), $key), array('escape' => false), __('Agendar horário para o(a) ' . $especialista . ' às ' . $hora . ' ?')); ?></td>
                            <?php } ?>

                            <?php foreach ($events as $item): ?>
                                <?php $minuto_inicio = substr($item['Event']['start'], 14, 2); ?>
                                <?php $minuto_fim = substr($item['Event']['end'], 14, 2); ?>
                                <?php $cor = $item['EventType']['color']; ?>

                                <?php if ($key == $item['Event']['especialista_id']) { ?>
                                    <?php if ($hora == substr($item['Event']['start'], 11, 5)) { ?>
                                        <?php $hora_fim = substr($item['Event']['end'], 11, 5); ?>
                                        <td colspan="1" title='<?php echo 'Tipo de serviço: ' . $item['EventType']['name'] . ' - ' . ' Valor: ' . number_format($item['EventType']['valor'], 2, ",", "") ?>' bgcolor='<?php echo $cor; ?>'><font color="white"><?php echo $item['Paciente']['nome'] . ' até ' . substr($item['Event']['end'], 11, 5); ?></td></font>
                                        <?php if ($minuto_fim == '00') { ?>
                                            <?php $minuto_fim = 59; ?>
                                        <?php } ?>
                                        <?php $diferenca = $this->requestAction('/Events/calcula_diferenca', array('pass' => array($item['Event']['start'], $item['Event']['end']))); ?>
                                        <?php $contador = 0 ?>
                                        <?php $hora_aux = substr($hora, 0, 2); ?>
                                        <?php while ($contador < ($diferenca - 1)) : ?>
                                            <?php $minuto_inicio = $minuto_inicio + 15; ?>
                                            <?php $count = $count + 1; ?>
                                            <?php $contador++ ?>
                                            <?php if ($minuto_inicio > 45) { ?>
                                                <?php $minuto_aux = '00'; ?>
                                                <?php $minuto_inicio = 0; ?>
                                                <?php $hora_aux = $hora_aux + 1; ?>
                                            <?php } else { ?>
                                                <?php $minuto_aux = $minuto_inicio; ?>
                                            <?php } ?>
                                        <tr>
                                            <?php if ($minuto_aux == 0) { ?>
                                                <?php if ($hora_aux < 10) { ?>
                                                    <td><b><?php echo '0' . $hora_aux . ':' . $minuto_aux; ?></b></td>
                                                    <td colspan="1" title='<?php echo 'Tipo de serviço: ' . $item['EventType']['name'] . ' - ' . ' Valor: ' . number_format($item['EventType']['valor'], 2, ",", "") ?>' bgcolor='<?php echo $cor; ?>'><font color="white"><?php echo ''; ?></td></font>
                                                <?php } else { ?>
                                                    <td><b><?php echo $hora_aux . ':' . $minuto_aux; ?></b></td>
                                                    <td colspan="1" title='<?php echo 'Tipo de serviço: ' . $item['EventType']['name'] . ' - ' . ' Valor: ' . number_format($item['EventType']['valor'], 2, ",", "") ?>' bgcolor='<?php echo $cor; ?>'><font color="white"><?php echo ''; ?></td></font>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <?php if ($hora_aux < 10) { ?>
                                                    <td><?php echo '0' . $hora_aux . ':' . $minuto_aux; ?></td>
                                                    <td colspan="1" title='<?php echo 'Tipo de serviço: ' . $item['EventType']['name'] . ' - ' . ' Valor: ' . number_format($item['EventType']['valor'], 2, ",", "") ?>' bgcolor='<?php echo $cor; ?>'><font color="white"><?php echo ''; ?></td></font>
                                                <?php } else { ?>
                                                    <td><?php echo $hora_aux . ':' . $minuto_aux; ?></td>
                                                    <td colspan="1" title='<?php echo 'Tipo de serviço: ' . $item['EventType']['name'] . ' - ' . ' Valor: ' . number_format($item['EventType']['valor'], 2, ",", "") ?>' bgcolor='<?php echo $cor; ?>'><font color="white"><?php echo ''; ?></td></font>
                                                <?php } ?>
                                            <?php } ?>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php } ?>
                            <?php } ?>
                        <?php endforeach; ?>
                        </tr>
                    <?php } else { ?>
                        <?php $count = $count - 1; ?>
                    <?php } ?>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endforeach; ?>
</div>

<script type="text/javascript">

    jQuery(document).ready(function() {
        document.getElementById('button').focus();
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