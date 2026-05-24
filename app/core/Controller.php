<?php

abstract class Controller {
    protected View $view;

    public function __construct() {
        $this->view = new View();
    }

    protected function render(string $template, array $data = []): void {
        $this->view->render($template, $data);
    }

    protected function json(mixed $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $url): void {
        header('Location: ' . $url);
        exit;
    }

    protected function input(string $key, mixed $default = null): mixed {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function safeExec(string $cmd): string {
        $allowed = [
            'ping', 'traceroute', 'ip', 'ifconfig',
            'iwconfig', 'iw', 'arp', 'systemctl',
            'hostapd_cli', 'cat /var/lib/misc/dnsmasq.leases',
        ];

        $base = strtok(trim($cmd), ' ');
        foreach ($allowed as $permit) {
            if (str_starts_with($cmd, $permit)) {
                return shell_exec($cmd . ' 2>&1') ?? '';
            }
        }

        return '[BLOCKED: command not in allowlist]';
    }
}
