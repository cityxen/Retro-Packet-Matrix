<?php

class View {
    public function render(string $template, array $data = []): void {
        extract($data, EXTR_SKIP);
        $file = VIEWS . '/' . $template . '.php';

        if (!file_exists($file)) {
            throw new RuntimeException("View not found: {$template}");
        }

        include $file;
    }
}
