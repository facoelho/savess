<br>
<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<br>
<?php
$extensao = substr($imagen['Imagen']['img_foto'], (strlen($imagen['Imagen']['img_foto']) - 4), strlen($imagen['Imagen']['img_foto']));
echo "<div id='imagemfoto'>";
if (strtoupper($extensao) == strtoupper('.pdf')) {
    echo $this->Html->link($this->Html->image("botoes/arquivo_grande.png"), "/img/" . "imagemfoto/" . $empresa_id . "/" . $imagen['Imagen']['img_foto'], array('escape' => false, 'target' => '_blank'));
} else {
    echo $this->Html->link($this->Html->image("imagemfoto/" . $empresa_id . "/" . $imagen['Imagen']['img_foto']), "/img/" . "imagemfoto/" . $empresa_id . "/" . $imagen['Imagen']['img_foto'], array('escape' => false, 'target' => '_blank'));
}
echo "</div>";
?>
<br><br>
<p>
    <strong> Imagem: </strong>
    <?php echo $imagen['Imagen']['img_foto']; ?>
    <br>
    <strong> Diretório: </strong>
    <?php echo $imagen['Albun']['titulo']; ?>
    <br>
    <strong> Título: </strong>
    <?php echo $imagen['Imagen']['titulo']; ?>
    <br>
    <strong> Data de criação: </strong>
    <?php echo date('d/m/Y H:i', strtotime($imagen['Imagen']['created'])); ?>
    <br>
    <strong> Última modificação: </strong>
    <?php echo date('d/m/Y H:i', strtotime($imagen['Imagen']['modified'])); ?>
    <br>
</p>