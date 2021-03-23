<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="alert alert-info alert-dismissible" onclick="this.classList.add('hidden')">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
<h4>Alerta!</h4>
<?=$message?>
</div>