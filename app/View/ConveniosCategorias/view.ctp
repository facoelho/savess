<div id="toporight">
    <?php
    echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
    ?>
</div>
<?php
//echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
echo $this->Form->postLink($this->Html->image('botoes/excluir.png', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $convenios['Convenio']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?'));
?>
<br>
<br>
<p>
    <strong> Nome: </strong>
    <?php echo $convenios['Convenio']['descricao']; ?>
    <br>
</p>