<?php
session_start();
unset($_SESSION['ID']);
?>

<script>
    alert('logOuted!')
    top.location.href = 'login_form.html';
</script>