<?php
    echo "<option value=\"\"> -- Selecione o estado --</option>";
    foreach($estados as $key => $subcat){ 
        echo "<option value=\"{$key}\">{$subcat}</option>";
    }
?>