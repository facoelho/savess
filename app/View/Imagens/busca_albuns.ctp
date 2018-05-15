<?php
    echo "<option value=\"\"> -- Selecione o album --</option>";
    foreach($imagens as $key => $subcat){ 
        echo "<option value=\"{$key}\">{$subcat}</option>";
    }
?>