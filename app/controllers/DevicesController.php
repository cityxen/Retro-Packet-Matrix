<?php

class DevicesController extends Controller {
    public function index(): void {
        $devices = Device::all();
        $this->render('devices/index', [
            'devices'   => $devices,
            'pageTitle' => 'Connected Devices',
        ]);
    }

    public function block(): void {
        $mac = $this->validatedMac();
        if (!$mac) {
            $this->json(['success' => false, 'error' => 'Invalid MAC'], 400);
            return;
        }
        Device::setStatus($mac, 'blocked');
        $this->safeExec('hostapd_cli -i wlan0 disassociate ' . escapeshellarg($mac));
        $this->json(['success' => true]);
    }

    public function unblock(): void {
        $mac = $this->validatedMac();
        if (!$mac) {
            $this->json(['success' => false, 'error' => 'Invalid MAC'], 400);
            return;
        }
        Device::setStatus($mac, 'disconnected');
        $this->json(['success' => true]);
    }

    private function validatedMac(): ?string {
        $mac = $this->input('mac', '');
        return preg_match('/^([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}$/', $mac) ? $mac : null;
    }
}
