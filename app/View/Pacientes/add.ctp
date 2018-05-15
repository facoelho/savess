<br>
<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Paciente'); ?>
<fieldset>
    <?php
    echo $this->Form->input('nome');
    echo $this->Form->input('sobrenome');
    echo $this->Form->input('cpf', array('id' => 'cpf', 'label' => 'CPF', 'type' => 'text'));
    echo $this->Form->input('rg', array('id' => 'rg', 'label' => 'RG', 'type' => 'text'));
    echo $this->Form->input('sexo', array('type' => 'select', 'options' => $opcoes, 'label' => 'Sexo', 'empty' => 'Selecione o sexo'));
    echo $this->Form->input('dtnascimento', array('id' => 'dtnascimento', 'class' => 'data', 'type' => 'text', 'label' => 'Data de nascimento'));
    echo $this->Form->input('dddfone', array('id' => 'ddd', 'label' => 'DDD:', 'type' => 'numeric', 'maxlength' => 3));
    echo $this->Form->input('fone', array('id' => 'fone', 'label' => 'Telefone:', 'type' => 'text', 'maxlength' => 9));
    echo $this->Form->input('dddcelular', array('id' => 'ddd', 'label' => 'DDD:', 'type' => 'numeric', 'maxlength' => 3));
    echo $this->Form->input('celular', array('id' => 'fone', 'label' => 'Celular:', 'type' => 'text', 'maxlength' => 9));
    echo $this->Form->input('email');
    echo $this->Form->input('valordesconto', array('id' => 'valordesconto', 'type' => 'text', 'label' => 'Valor de desconto', 'placeholder' => 'Informe o valor do desconto'));
    echo $this->Form->input('holding_id', array('type' => 'hidden', 'value' => $holding_id));
    echo $this->Form->input('ativo', array('id' => 'ativoID', 'type' => 'select', 'options' => $status, 'label' => 'Status'));
    echo $this->Form->input('obs', array('id' => 'obs', 'type' => 'textarea', 'label' => 'Observação', 'escape' => false, 'style' => 'height: 250px;'));
    echo $this->Form->input('informaendereco', array(
        'before' => '<br>',
        'type' => 'checkbox',
        'onclick' => 'seleciona()',
        'hiddenField' => 'N',
        'label' => '&nbsp;Informar endereço do cliente',
        'id' => 'informaendereco',
    ));
    ?>
    <div id='endereco'>
        <?php
        echo $this->Form->input('estado_id', array('id' => 'estadoID', 'type' => 'select', 'options' => $estados, 'empty' => '-- Selecione o estado --'));
        echo $this->Form->input('Endereco.0.estado_id', array('id' => 'estado_idID', 'type' => 'hidden'));
        echo $this->Form->input('Endereco.0.cidade_id', array('id' => 'cidadeID', 'empty' => '', 'label' => 'Cidade:'));
        echo $this->Form->input('Endereco.0.rua', array('id' => 'rua', 'label' => 'Rua:'));
        echo $this->Form->input('Endereco.0.numero', array('id' => 'numero', 'label' => 'Número:'));
        echo $this->Form->input('Endereco.0.complemento', array('id' => 'complemento', 'label' => 'Complemento:'));
        echo $this->Form->input('Endereco.0.bairro', array('id' => 'bairro', 'label' => 'Bairro:'));
        echo $this->Form->input('Endereco.0.cep', array('id' => 'cep', 'label' => 'Cep:'));
        echo $this->Form->input('Endereco.0.observacao', array('id' => 'cep', 'label' => 'Observação:'));
        ?>
    </div>
</fieldset>

<?php echo $this->Form->end(__('SALVAR')); ?>

<?php
$this->Js->get('#estadoID')->event(
        'change', $this->Js->request(
                array('controller' => 'Cidades', 'action' => 'buscaCidades', 'Paciente'), array('update' => '#cidadeID',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            )),
                )
        )
);
?>

<script type="text/javascript">

    function seleciona() {

        var input = document.getElementById("informaendereco");

        if (input.checked) {
            $("#endereco").find("*").prop("disabled", false);
            document.getElementById('endereco').style.display = 'inline';
        } else {
            $("#endereco").find("*").prop("disabled", true);
            document.getElementById('endereco').style.display = 'none';
        }

    }

    jQuery(document).ready(function() {
        $("#endereco").find("*").prop("disabled", true);
        document.getElementById('endereco').style.display = 'none';
        $("#valordesconto").maskMoney({showSymbol: false, decimal: ",", thousands: "", precision: 2});
        $("#cpf").mask("999.999.999-99");
        $("#rg").mask("99.999.99-999");
        $("#cep").mask("99999-999");
        $("#dtnascimento").mask("99/99/9999");
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

    });
</script>