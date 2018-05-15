<?php $this->layout = 'naoLogado'; ?>
<?php echo $this->Html->link($this->Html->image("logo_2.png", array("alt" => "SAVESS", "title" => "SAVESS")), array('action' => ''), array('escape' => false)); ?>
<br><br>
<strong><font size="3"><?php echo 'Recebi(emos) de ' ?></strong> <?php echo $dadosUser['nomeEmpresa'] ?>
<br><br>
<strong><font size="3"><?php echo 'Endereço ' ?></strong> <?php echo $endereco[0]['Endereco']['rua'] . ', ' . $endereco[0]['Endereco']['numero'] . ' ' . $endereco[0]['Endereco']['complemento'] ?>
<br><br>
<strong><font size="3"><?php echo 'A importância de ' ?></strong> <?php echo $extenso ?><br><br>
<strong><font size="3"><?php echo 'Referente ' ?></strong> <?php echo date('d/m/Y H:i', strtotime($start)); ?><br><br>

<?php ' a importância de R$ ' . $valor . ' (' . $extenso . ' )'; ?>&nbsp;</font></strong>