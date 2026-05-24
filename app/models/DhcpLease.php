<?php

class DhcpLease {
    public static function all(): array {
        return Database::query('SELECT * FROM dhcp_leases ORDER BY created_at DESC')->fetchAll();
    }

    public static function count(): int {
        return (int) Database::query('SELECT COUNT(*) FROM dhcp_leases')->fetchColumn();
    }

    public static function syncFromFile(string $path = '/var/lib/misc/dnsmasq.leases'): int {
        if (!file_exists($path)) {
            return 0;
        }

        $synced = 0;
        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $parts = explode(' ', $line);
            if (count($parts) < 4) continue;

            [$expires, $mac, $ip, $hostname] = $parts;
            $expiresAt = date('Y-m-d H:i:s', (int) $expires);

            Database::query(
                'INSERT INTO dhcp_leases (mac_address, ip_address, hostname, expires_at)
                 VALUES (?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE ip_address=VALUES(ip_address), hostname=VALUES(hostname), expires_at=VALUES(expires_at)',
                [$mac, $ip, $hostname === '*' ? '' : $hostname, $expiresAt]
            );
            $synced++;
        }
        return $synced;
    }
}
