<?php

/**
 * Returns the URL for a Vite asset.
 *
 * @param string $path
 * @return string
 */
function vite_asset(string $path)
{
    $manifestPath = BASE_PATH . '/public/build/.vite/manifest.json';
    
    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        if (isset($manifest[$path])) {
            return '/build/' . $manifest[$path]['file'];
        }
        if ($path === '@vite/client') return '';
    }

    $baseUrl = getenv('VITE_URL') ?: ($_ENV['VITE_URL'] ?? 'http://localhost:5173');
    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}

function vite_css(string $path)
{
    $manifestPath = BASE_PATH . '/public/build/.vite/manifest.json';
    
    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        if (isset($manifest[$path]['css'])) {
            return '/build/' . $manifest[$path]['css'][0];
        }
    }
    return '';
}
