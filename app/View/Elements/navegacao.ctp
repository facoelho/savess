<p>
    <?php
    if ($this->action == "index") {
        if ($title_for_layout != "Home") {
            echo $this->Html->link(__('Home'), array('controller' => 'homes', 'action' => 'index'));
            echo " > ";
            echo $title_for_layout;
        }
    } else if ($this->action == "add") {
        echo $this->Html->link(__('Home'), array('controller' => 'homes', 'action' => 'index'));
        echo " > ";
        echo $this->Html->link(__($title_for_layout), array('action' => 'index'));
        echo " > Adicionar";
    } else if ($this->action == "edit") {
        echo $this->Html->link(__('Home'), array('controller' => 'homes', 'action' => 'index'));
        echo " > ";
        echo $this->Html->link(__($title_for_layout), array('action' => 'index'));
        echo " > Editar";
    } else if ($this->action == "view") {
        echo $this->Html->link(__('Home'), array('controller' => 'homes', 'action' => 'index'));
        echo " > ";
        echo $this->Html->link(__($title_for_layout), array('action' => 'index'));
        echo " > Visualizar";
    }
    ?>
</p>
<br>