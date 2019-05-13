<!DOCTYPE html>
<html>
    <head>
        <?php
        echo $this->Html->charset();
        //Pegando dados da sessão do usuário
        $dadosUser = $this->Session->read();
        ?>
        <title>
            .:: Savess <?php echo " - " . $title_for_layout . " - " . $dadosUser['nomeEmpresa'] . " "; ?> ::.

        </title>
        <?php
        echo $this->Html->meta('icon');

        echo $this->Html->css('savemed');
        echo $this->Html->css('south-street/jquery-ui-1.10.3.custom.min');
        echo $this->Html->css('colorpicker/colorpicker');

        echo $this->Html->script(array('jquery.js', 'gerais.js', 'jquery-ui.js', 'jquery.maskedinput.min.js', 'jquery.maskMoney.js', 'jquery-ui-1.10.3.custom.min.js', 'colorpicker.js'));

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
    </head>
    <body>
        <div id="global">
            <div id="diferenca">

            </div>

            <div id="topo">

                <div id="topoleftp">
                    <?php
                    echo $this->Html->link($this->Html->image("logo.png", array("alt" => "Insira sua marca", "title" => "Insira sua marca")), array('controller' => 'homes', 'action' => 'index'), array('escape' => false));
                    ?>
                </div>

                <?php echo $this->element('menu'); ?>

                <?php
                echo $this->Html->link($this->Html->image("logout.png", array("alt" => "Sair", "title" => "Sair")), array('controller' => 'users', 'action' => 'logout'), array('escape' => false));
                ?>
                <div id="toporight">

                </div>
            </div>
            <div id="titulopagina">
                <div id="internadomenu">
                    <?php
                    if ($dadosUser['empresa_tipologo'] == 'R') {
                        ?>
                        <div id="topoleftrcli">
                            <?php
                            echo $this->Html->image("empresas/" . $dadosUser['empresa_logo']);
                            ?>
                        </div>
                        <?php
                    } elseif ($dadosUser['empresa_tipologo'] == "P") {
                        ?>
                        <div id="topoleftpcli">
                            <?php
                            echo $this->Html->image("empresas/" . $dadosUser['empresa_logo']);
                            ?>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div id="topoleftrcli">
                            <?php
                            echo $this->Html->image("marca.png", array("alt" => "Insira sua marca", "title" => "Insira sua marca"));
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <br><br>
                    Bem vindo, <span class="fontNomeUsuario"><b><?php echo $dadosUser['Auth']['User']['nome']; ?></b></span>.
                    <br> <span class="fontUltimoAcesso">Último acesso: <?php echo date('d/m/Y', strtotime($dadosUser['Auth']['User']['ultimoacesso'])) . " " . date('H:i', strtotime($dadosUser['Auth']['User']['ultimoacesso'])); ?></span>
                    <br> <span class="fontUltimoAcesso">Logado em: <b><?php echo $dadosUser['nomeEmpresa']; ?></b>
                        <br> <span class="fontUltimoAcesso">Validade: <b><?php echo $dadosUser['Auth']['User']['Holding']['validade']; ?></b>
                        </span>
                        <br></br>
                        <?php if (count($dadosUser['empresasCombo']) > 1) { ?>
                            <?php $array_url = explode('/', $this->here); ?>
                            <?php if (array_search('savess_cap', $array_url)) { ?>
                                <select name="trocaEmpresa" id="trocaEmpresa" class="trocaEmpresa" title="Trocar a empresa" onChange="location.href = 'http://localhost/savess_cap/users/trocaEmpresa/' + this.value;">
                                    <option value="">Alterar empresa</option>
                                    <?php for ($i = 0; $i < count($dadosUser['empresasCombo']); $i++) { ?>
                                        <option value="<?php echo $dadosUser['empresasCombo'][$i]['Empresa']['id']; ?>"><?php echo $dadosUser['empresasCombo'][$i]['Empresa']['nomefantasia']; ?></option>
                                    <?php } ?>
                                </select>
                            <?php } else { ?>
                                <select name="trocaEmpresa" id="trocaEmpresa" class="trocaEmpresa" title="Trocar a empresa" onChange="location.href = 'http://www.savess.com.br/users/trocaEmpresa/' + this.value;">
                                    <option value="">Alterar empresa</option>
                                    <?php for ($i = 0; $i < count($dadosUser['empresasCombo']); $i++) { ?>
                                        <option value="<?php echo $dadosUser['empresasCombo'][$i]['Empresa']['id']; ?>"><?php echo $dadosUser['empresasCombo'][$i]['Empresa']['nomefantasia']; ?></option>
                                    <?php } ?>
                                </select>
                            <?php } ?>
                        <?php } ?>
                </div>
                <?php //echo $title_for_layout; ?>
            </div>
            <div id="conteudo">
                <div id="corpo">
                    <?php echo $this->element('navegacao'); ?>
                    <?php echo $this->Session->flash(); ?>
                    <?php echo $this->fetch('content'); ?>
                </div>

            </div>

            <div id="rodape">
                <?php echo $this->Js->writeBuffer(); ?>
                <?php
                echo $this->Html->link($this->Html->image("rodape.png", array("alt" => "Contato", "title" => "Contato")), array('controller' => '../site/Contatos', 'action' => 'index'), array('escape' => false, 'target' => '_blank'));
                ?>
            </div>

        </div>

    </body>
</html>
