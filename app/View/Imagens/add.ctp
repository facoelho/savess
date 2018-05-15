<br>
<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Imagen', array('type' => 'file')); ?>
<fieldset>
    <?php
    echo $this->Form->input('albun_id', array('id' => 'imagenID', 'type' => 'select', 'options' => $albuns, 'label' => 'Diretório'));
    echo $this->Form->input('titulo');
    echo $this->Form->input('descricao');
//    echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa_id));
    echo $this->Form->input('imagemfoto', array('type' => 'file', 'class' => 'file', 'label' => 'Selecione a imagem/foto'));
    echo $this->Form->input('img_foto', array('type' => 'hidden'));
    ?>
</fieldset>
<?php echo $this->Form->end(__('SALVAR')); ?>

<script type="text/javascript">

    jQuery(document).ready(function() {

        document.getElementById('imagenID').focus();

    });
</script>