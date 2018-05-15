<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            .:: Savess - Autenticação ::.
        </title>
        <?php
        echo $this->Html->meta('icon');

        echo $this->Html->css('savemed');
        echo $this->Html->css('jquery-ui-1.10.3.custom.min');

        echo $this->Html->script(array('jquery.js'));

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');

        $dadosUser = $this->Session->read();
        ?>
    </head>
    <body>

        <div id="global">

            <div id="diferenca">

            </div>

            <div id="naologado">

            </div>

            <div id="conteudo">

                <div id="corpologin">
                    <?php echo $this->element('navegacao'); ?>
                    <?php echo $this->Session->flash(); ?>
                    <?php echo $this->fetch('content'); ?>
                </div>
            </div>

            <div id="rodape">
                <?php
                echo $this->Html->image("rodape.png");
                ?>
            </div>

        </div>

    </body>
</html>
