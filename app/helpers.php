<?php
use App\Foundation\FlasherManager;

/**
 * Renders the notifications.
 *
 * @param array $criteria
 * @param string $format
 * @param array $context
 * @return void
 */
function app_flasher_render(array $criteria = array(), $format = 'html', array $context = array())
{
    echo FlasherManager::getFlasher()->render($criteria, $format, $context);
}

/**
 * Returns the URL for a Vite asset.
 *
 * @param string $path
 * @return string
 */
function vite_asset(string $path)
{
    $baseUrl = getenv('VITE_URL') ?: ($_ENV['VITE_URL'] ?? 'http://localhost:5173');
    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}
