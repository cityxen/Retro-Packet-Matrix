<?php

class HotspotConfig {
    public static function get(): array {
        $row = Database::query('SELECT * FROM hotspot_configs ORDER BY id DESC LIMIT 1')->fetch();
        return $row ?: [
            'ssid'        => 'RPM-Network',
            'channel'     => 6,
            'band'        => '2.4GHz',
            'security'    => 'wpa2',
            'password'    => '',
            'max_clients' => 10,
            'enabled'     => 0,
        ];
    }

    public static function save(array $data): bool {
        $existing = Database::query('SELECT id FROM hotspot_configs LIMIT 1')->fetch();

        if ($existing) {
            Database::query(
                'UPDATE hotspot_configs SET ssid=?, channel=?, band=?, security=?, password=?, max_clients=? WHERE id=?',
                [$data['ssid'], $data['channel'], $data['band'], $data['security'], $data['password'], $data['max_clients'], $existing['id']]
            );
        } else {
            Database::query(
                'INSERT INTO hotspot_configs (ssid, channel, band, security, password, max_clients) VALUES (?,?,?,?,?,?)',
                [$data['ssid'], $data['channel'], $data['band'], $data['security'], $data['password'], $data['max_clients']]
            );
        }
        return true;
    }

    public static function setEnabled(bool $state): void {
        Database::query('UPDATE hotspot_configs SET enabled=? ORDER BY id LIMIT 1', [(int) $state]);
    }
}
