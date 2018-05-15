<?php
    echo "<option value=\"\"> -- Selecione a cidade --</option>";
    foreach($cidades as $key => $subcat){ 
        echo "<option value=\"{$key}\">{$subcat}</option>";
    }
?>