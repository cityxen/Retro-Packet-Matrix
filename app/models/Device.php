<?php

class Device {
    public static function all(): array {
        return Database::query('SELECT * FROM devices ORDER BY last_seen DESC')->fetchAll();
    }

    public static function connected(): array {
        return Database::query("SELECT * FROM devices WHERE status='connected' ORDER BY last_seen DESC")->fetchAll();
    }

    public static function upsert(string $mac, string $ip, string $hostname = ''): void {
        Database::query(
            'INSERT INTO devices (mac_address, ip_address, hostname, status, last_seen)
             VALUES (?, ?, ?, "connected", NOW())
             ON DUPLICATE KEY UPDATE ip_address=VALUES(ip_address), hostname=VALUES(hostname),
             status="connected", last_seen=NOW()',
            [$mac, $ip, $hostname]
        );
    }

    public static function setStatus(string $mac, string $status): void {
        Database::query(
            'UPDATE devices SET status=? WHERE mac_address=?',
            [$status, $mac]
        );
    }

    public static function count(): int {
        return (int) Database::query("SELECT COUNT(*) FROM devices WHERE status='connected'")->fetchColumn();
    }
}
