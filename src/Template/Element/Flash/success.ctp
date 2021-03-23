<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="alert alert-success alert-dismissible" onclick="this.classList.add('hidden')">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
<h4>Alerta!</h4>
<?=$message?>
</div>