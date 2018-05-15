<br>
<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Empresa', array('type' => 'file')); ?>
<fieldset>
    <?php
    echo $this->Form->input('razaosocial', array('label' => 'Razão social'));
    echo $this->Form->input('nomefantasia', array('label' => 'Nome fantasia'));
    echo $this->Form->input('cnpjEmpresa', array('id' => 'cnpjEmpresa', 'label' => 'CNPJ', 'type' => 'text', 'value' => $this->request->data['Empresa']['cnpj']));
    echo $this->Form->input('inscEstadualEmpresa', array('id' => 'inscEstadualEmpresa', 'label' => 'Inscrição estadual', 'type' => 'text', 'value' => $this->request->data['Empresa']['inscestadual']));
    echo $this->Form->input('inscMunicipalEmpresa', array('id' => 'inscMunicipalEmpresa', 'label' => 'Inscrição municipal', 'type' => 'text', 'value' => $this->request->data['Empresa']['inscmunicipal']));
    echo $this->Form->input('email', array('label' => 'E-mail'));
    echo $this->Form->input('homepage');
    echo $this->Form->input('logoempresa', array('type' => 'file', 'class' => 'file', 'label' => 'Logo da empresa (retrato 110x120) ou (paisagem 200x120)'));
    echo $this->Form->input('cnpj', array('type' => 'hidden'));
    echo $this->Form->input('inscestadual', array('type' => 'hidden'));
    echo $this->Form->input('inscmunicipal', array('type' => 'hidden'));
    echo $this->Form->input('img_foto', array('type' => 'hidden'));
//    echo $this->Form->input('informaendereco', array(
//        'before' => '<br>',
//        'type' => 'checkbox',
//        'onclick' => 'seleciona()',
//        'hiddenField' => 'N',
//        'label' => '&nbsp;Informar endereço da empresa',
//        'id' => 'informaendereco',
//    ));
    ?>
    <div id='endereco'>
        <?php
//        echo $this->Form->input('estado_id', array('id' => 'estadoID', 'type' => 'select', 'options' => $estados, 'empty' => '-- Selecione o estado --'));
//        echo $this->Form->input('Endereco.0.cidade_id', array('id' => 'cidadeID', 'empty' => '', 'label' => 'Cidade:'));
//        echo $this->Form->input('Endereco.0.rua', array('id' => 'rua', 'label' => 'Rua:'));
//        echo $this->Form->input('Endereco.0.numero', array('id' => 'numero', 'label' => 'Número:'));
//        echo $this->Form->input('Endereco.0.complemento', array('id' => 'complemento', 'label' => 'Complemento:'));
//        echo $this->Form->input('Endereco.0.bairro', array('id' => 'bairro', 'label' => 'Bairro:'));
//        echo $this->Form->input('Endereco.0.cep', array('id' => 'cep', 'label' => 'Cep:'));
//        echo $this->Form->input('Endereco.0.ddd', array('id' => 'ddd', 'label' => 'ddd:'));
//        echo $this->Form->input('Endereco.0.fone', array('id' => 'fone', 'label' => 'Telefone:'));
//        echo $this->Form->input('Endereco.0.observacao', array('id' => 'cep', 'label' => 'Observação:'));
        ?>
    </div>
</fieldset>
<?php echo $this->Form->end(__('SALVAR')); ?>

<?php
$this->Js->get('#estadoID')->event(
        'change', $this->Js->request(
                array('controller' => 'Cidades', 'action' => 'buscaCidades', 'Empresa'), array('update' => '#cidadeID',
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
        document.getElementById('endereco').style.display = 'none';
        $("#cep").mask("99999-999");
        $("#cnpjEmpresa").mask("999.999.999/9999-99");
        $("#inscEstadualEmpresa").mask("999/9999999");
        $("#inscMunicipalEmpresa").mask("999/9999999");
    });
</script>
