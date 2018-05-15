<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Cidade'); ?>
<fieldset>
    <?php
    echo $this->Form->input('nome');
    echo $this->Form->input('pais_id', array('id' => 'paisID', 'type' => 'select', 'options' => $paises, 'label' => 'País', 'empty' => ' -- Selecione o país -- '));
    echo $this->Form->input('estado_id', array('id' => 'estadoID', 'type' => 'select', 'label' => 'Estados'));
    echo $this->Form->input('holding_id', array('type' => 'hidden', 'value' => $holding_id));
    ?>
</fieldset>
<?php echo $this->Form->end(__('SALVAR')); ?>
<?php
$this->Js->get('#paisID')->event(
        'change', $this->Js->request(
                array('controller' => 'Estados', 'action' => 'buscaEstados', 'Cidade'), array('update' => '#estadoID',
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