<?php
use App\Foundation\Application;

$session = Application::$app->session;
$flashTypes = ['success', 'error', 'info', 'warning'];
?>

<div id="toast-container" class="toast-container">
    <?php foreach ($flashTypes as $type): ?>
        <?php if ($session->hasFlash($type)): ?>
            <div class="toast toast-<?= $type ?>" data-type="<?= $type ?>">
                <div class="toast-content">
                    <?php if ($type === 'success'): ?>
                        <svg class="toast-icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                    <?php elseif ($type === 'error'): ?>
                        <svg class="toast-icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    <?php elseif ($type === 'info'): ?>
                        <svg class="toast-icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <?php elseif ($type === 'warning'): ?>
                        <svg class="toast-icon" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                    <?php endif; ?>
                    <?= htmlspecialchars($session->getFlash($type)) ?>
                </div>
                <button class="toast-close">&times;</button>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
