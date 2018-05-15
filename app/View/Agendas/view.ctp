<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false));
echo $this->Form->postLink($this->Html->image('botoes/excluir.png', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $tiposervico['Tiposervico']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?'));
?>
<br>
<br>
<p>
<strong> Descrição: </strong>
<?php echo $tiposervico ['Tiposervico']['descricao']; ?>
<br>
<strong> Cor: </strong>
<br>
<?php
if (!empty($tiposervico['Tiposervico']['cor'])) {
    ?>
    <div id="colorSelector">
        <div style="background-color: #<?php echo $tiposervico['Tiposervico']['cor']; ?>"></div>
    </div>
    <?php
}
?>
<strong> Observação: </strong>
<?php echo $tiposervico['Tiposervico']['cor']; ?>
<br>
</p>