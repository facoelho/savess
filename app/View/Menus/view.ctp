<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false));
echo $this->Form->postLink($this->Html->image('botoes/excluir.png', array('alt' => 'Exluir', 'title' => 'Exluir')), array('action' => 'delete', $menu['Menu']['id']), array('escape' => false), __('Você realmete deseja apagar esse item?'));
?>
<br>
<br>
<p>
Menu: 
<?php echo $menu['Menu']['nome']; ?>
<br>
Controller: 
<?php echo $menu['Menu']['controller']; ?>
<br>
<?php
if ($menu['Menu']['mostramenu'] == 1) {
    ?>
    Mostra no menu: SIM
    <br>
    Número do menu: <?php echo $menu['Menu']['menu']; ?>
    <br>
    Ordem do menu: <?php echo $menu['Menu']['ordem']; ?>
    <?php
} else {
    ?>
    Mostra no menu: NÃO
    <?php
}
?>
</p>