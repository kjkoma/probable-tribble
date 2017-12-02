<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="text-danger error" onclick="this.classList.add('hidden');"><?= $message ?></div>
