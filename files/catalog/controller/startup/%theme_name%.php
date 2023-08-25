<?php
namespace Opencart\Catalog\Controller\Extension\%Name%\Startup;

class %ThemeName% extends \Opencart\System\Engine\Controller
{
    public function index(): void
    {
        if ($this->config->get('%theme_name%_status')) {
            $this->event->register('view/*/before', new \Opencart\System\Engine\Action('extension/%theme_name%/startup/%theme_name%|event'));
        }
    }

    public function event(string &$route, array &$args, mixed &$output): void
    {
        $override = [
            'common/header',
        ];

        if (in_array($route, $override)) {
            $route = 'extension/%theme_name%/' . $route;
        }
    }
}
