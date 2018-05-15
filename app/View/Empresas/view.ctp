<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false));
?>
<br>
<br>
<?php
if ($empresa['Empresa']['tipoimagem'] == "R") {
    echo "<div id='imagemEmpresaRetrato'>";
    echo $this->Html->image("empresas/" . $empresa['Empresa']['img_foto'], array("alt" => $empresa['Empresa']['nomefantasia'], "title" => $empresa['Empresa']['nomefantasia']));
    echo "</div>";
} elseif ($empresa['Empresa']['tipoimagem'] == "P") {
    echo "<div id='imagemEmpresaPaisagem'>";
    echo $this->Html->image("empresas/" . $empresa['Empresa']['img_foto'], array("alt" => $empresa['Empresa']['nomefantasia'], "title" => $empresa['Empresa']['nomefantasia']));
    echo "</div>";
}
?>
<br>
<p>

    <strong> Razão social: </strong>
    <?php echo $empresa['Empresa']['razaosocial']; ?>
    <br>
    <strong> Nome fantasia: </strong>
    <?php echo $empresa['Empresa']['nomefantasia']; ?>
    <br>
    <strong> CNPJ: </strong>
    <?php
    echo substr($empresa['Empresa']['cnpj'], 0, 3) . "." .
    substr($empresa['Empresa']['cnpj'], 3, 3) . "." .
    substr($empresa['Empresa']['cnpj'], 6, 3) . "/" .
    substr($empresa['Empresa']['cnpj'], 9, 4) . "-" .
    substr($empresa['Empresa']['cnpj'], 13, 2);
    ?>
    <br>
    <strong> Inscrição estadual: </strong>
<?php echo substr($empresa['Empresa']['inscestadual'], 0, 3) . "/" .
 substr($empresa['Empresa']['inscestadual'], 3, 7);
?>
    <br>
    <strong> Inscrição municipal: </strong>
    <?php echo substr($empresa['Empresa']['inscmunicipal'], 0, 3) . "/" .
    substr($empresa['Empresa']['inscmunicipal'], 3, 7);
    ?>
    <br>
    <strong> E-mail: </strong>
<?php echo $empresa['Empresa']['email']; ?>
    <br>
    <strong> Homepage: </strong>
<?php echo $empresa['Empresa']['homepage']; ?>
    <br>
    <strong> Última modifiação: </strong>
<?php echo date('d/m/Y', strtotime($empresa['Empresa']['modified'])); ?>
    <br>
    <strong> Criação: </strong>
<?php echo date('d/m/Y', strtotime($empresa['Empresa']['created'])); ?>
    <br>
</p>
<?php unset($group); ?>
