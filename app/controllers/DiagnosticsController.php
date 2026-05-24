<?php

class DiagnosticsController extends Controller {
    public function index(): void {
        $ifInfo = $this->safeExec('ip addr show');
        $arpTable = $this->safeExec('arp -n');

        $this->render('diagnostics/index', [
            'ifInfo'    => $ifInfo,
            'arpTable'  => $arpTable,
            'pageTitle' => 'Diagnostics',
        ]);
    }

    public function ping(): void {
        $target = $this->input('target', '');

        if (!filter_var($target, FILTER_VALIDATE_IP) && !filter_var($target, FILTER_VALIDATE_DOMAIN)) {
            $this->json(['success' => false, 'error' => 'Invalid target'], 400);
            return;
        }

        $target = escapeshellarg($target);
        $output = $this->safeExec("ping -c 4 -W 2 {$target}");
        $this->json(['success' => true, 'output' => $output]);
    }

    public function trace(): void {
        $target = $this->input('target', '');

        if (!filter_var($target, FILTER_VALIDATE_IP) && !filter_var($target, FILTER_VALIDATE_DOMAIN)) {
            $this->json(['success' => false, 'error' => 'Invalid target'], 400);
            return;
        }

        $target = escapeshellarg($target);
        $output = $this->safeExec("traceroute -m 15 {$target}");
        $this->json(['success' => true, 'output' => $output]);
    }
}
