<br>
<?php echo $this->Form->create('User'); ?>
<fieldset>
    <?php
    echo $this->Form->input('old_password', array('id' => 'old_password', 'type' => 'password', 'label' => 'Senha atual'));
    echo $this->Form->input('new_password', array('id' => 'new_password', 'type' => 'password', 'label' => 'Nova senha'));
    echo $this->Form->input('confirm_password', array('id' => 'confirm_password', 'type' => 'password', 'label' => 'Confirma nova senha'));
    ?>
</fieldset>
<?php echo $this->Form->end(__('Alterar')); ?>
<script type="text/javascript">
    jQuery(document).ready(function($){
        document.getElementById('old_password').focus();
    });
</script>