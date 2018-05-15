<?php $this->layout = 'naoLogado'; ?>
<br>
<br>
<?php echo $this->Form->create('User', array('action' => 'login')); ?>

    <?php
    echo $this->Form->input('username', array('id' => 'username'));
    echo $this->Form->input('password');
    
    ?>
<?php echo $this->Form->end('Entrar'); ?>

<script type="text/javascript">
    jQuery(document).ready(function(){
        $('#username').focus();
    });
</script>