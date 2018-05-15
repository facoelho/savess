<?php

echo "<option value=\"\"> -- Selecione o tipo de exame --</option>";
foreach ($tipoexames as $key => $subcat) {
    echo "<option value=\"{$key}\">{$subcat}</option>";
}
?>