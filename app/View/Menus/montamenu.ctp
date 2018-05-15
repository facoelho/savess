<table cellpadding="0" cellspacing="0">
    <?php foreach ($menus as $menu): ?>
        <tr>
            <td><a href="../../<?php echo $menu['Menu']['controller'] ?>"><?php echo $menu['Menu']['nome'] ?></a></td>
        </tr>
    <?php endforeach; ?>
</table>
<br>
