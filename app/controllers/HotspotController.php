<?php

class HotspotController extends Controller {
    public function index(): void {
        $config = HotspotConfig::get();
        $this->render('hotspot/index', [
            'config'    => $config,
            'pageTitle' => 'Hotspot Control',
        ]);
    }

    public function toggle(): void {
        $config  = HotspotConfig::get();
        $enabled = !(bool) $config['enabled'];
        HotspotConfig::setEnabled($enabled);

        $action = $enabled ? 'start' : 'stop';
        $this->safeExec("systemctl {$action} hostapd");
        $this->safeExec("systemctl {$action} dnsmasq");

        $this->json(['success' => true, 'enabled' => $enabled]);
    }

    public function save(): void {
        $data = [
            'ssid'        => substr(strip_tags($this->input('ssid', 'RPM-Network')), 0, 32),
            'channel'     => (int) $this->input('channel', 6),
            'band'        => $this->input('band', '2.4GHz'),
            'security'    => $this->input('security', 'wpa2'),
            'password'    => substr($this->input('password', ''), 0, 64),
            'max_clients' => (int) $this->input('max_clients', 10),
        ];

        if (!in_array($data['band'], ['2.4GHz', '5GHz'], true)) {
            $this->json(['success' => false, 'error' => 'Invalid band'], 400);
            return;
        }

        HotspotConfig::save($data);
        $this->json(['success' => true]);
    }
}
