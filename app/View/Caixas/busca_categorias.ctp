<?php

echo "<option value=\"\"> -- Selecione a categoria --</option>";
foreach ($categorias as $key => $subcat) {
    echo "<option value=\"{$key}\">{$subcat}</option>";
}
?>