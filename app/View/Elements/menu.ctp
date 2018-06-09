<div id="menu">
    <ul id="jsddm">

        <?php
        $menu_1 = 0;
        $menu_2 = 0;
        $menu_3 = 0;
        $menu_4 = 0;
        $menu_5 = 0;
        $menu_6 = 0;
        $menu_7 = 0;
        $menu_8 = 0;
        $menu_9 = 0;
        $menu_10 = 0;

        foreach ($menuCarregado as $itemMenu):

            if ($itemMenu['Menu']['menu'] == 1 && $menu_1 == 0) {
                ?>
                <li><a href="http://www.savess.com.br/Menus/montamenu/1">CADASTROS</a>
                    <ul>
                        <?php
                        $menu_1++;
                    } elseif ($itemMenu['Menu']['menu'] == 2 && $menu_2 == 0) {
                        if ($menu_1 != 0) {
                            ?>
                        </ul>
                    </li>
                    <?php
                }
                ?>
                <li><a href="http://www.savess.com.br/Menus/montamenu/2">TESOURARIA</a>
                    <ul>
                        <?php
                        $menu_2++;
                    } elseif ($itemMenu['Menu']['menu'] == 3 && $menu_3 == 0) {
                        if ($menu_1 != 0 || $menu_2 != 0) {
                            ?>
                        </ul>
                    </li>
                    <?php
                }
                ?>
                <li><a href="http://www.savess.com.br/Menus/montamenu/3">CONSULTAS</a>
                    <ul>
                        <?php
                        $menu_3++;
                    } elseif ($itemMenu['Menu']['menu'] == 4 && $menu_4 == 0) {
                        ?>
                    </ul>
                </li>
                <li><a href="http://www.savess.com.br/Menus/montamenu/4">ARQUIVOS</a>
                    <ul>
                        <?php
                        $menu_4++;
                    } elseif ($itemMenu['Menu']['menu'] == 5 && $menu_5 == 0) {
                        ?>
                    </ul>
                </li>
                <li><a href="http://www.savess.com.br/Menus/montamenu/5">RELATÓRIOS</a>
                    <ul>
                        <?php
                        $menu_5++;
                    } elseif ($itemMenu['Menu']['menu'] == 6 && $menu_6 == 0) {
                        ?>
                    </ul>
                </li>
                <li><a href="http://www.savess.com.br/Menus/montamenu/6">AJUSTES</a>
                    <ul>
                        <?php
                        $menu_6++;
                    }
                    ?>
                    <li><?php echo $this->Html->link($itemMenu['Menu']['nome'], array('controller' => $itemMenu['Menu']['controller'], 'action' => 'index')); ?></li>
                    <?php
                endforeach;
                ?>

            </ul>
        </li>

    </ul>
</div>