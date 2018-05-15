<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
echo $this->Form->postLink($this->Html->image('botoes/excluir.png', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $cidade['Cidade']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?'));
?>
<br>
<br>
<p>
<strong> Cidade: </strong>
<?php echo $cidade['Cidade']['nome']; ?>
<br>
<strong> Estado: </strong>
<?php echo $cidade['Estado']['nome']; ?>
<br>
<strong> País: </strong>
<?php echo $cidade['Estado']['Paise']['nome']; ?>
<br>
</p>