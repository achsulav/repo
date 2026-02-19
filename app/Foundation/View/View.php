<?php
namespace App\Foundation\View;

class View
{
    public static function render(string $view, array $params = [],string $layout= 'root')
    {
        $viewFile = BASE_PATH . '/resources/views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            echo "View not found: {$view}";
            return;
        }

        extract($params);
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = BASE_PATH . "/resources/views/root/{$layout}.php";

        if (!file_exists($layoutFile)) {
            echo "Layout not found.{$layout}";
            return;
        }
        require $layoutFile;
    }
}
