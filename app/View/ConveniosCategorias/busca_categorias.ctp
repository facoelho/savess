<?php

echo "<option value=\"\"> -- Selecione a categoria --</option>";
foreach ($convenioscategorias as $key => $subcat) {
    echo "<option value=\"{$key}\">{$subcat}</option>";
}
?>