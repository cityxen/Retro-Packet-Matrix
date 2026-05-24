<?php

class DashboardController extends Controller {
    public function index(): void {
        $hotspot = HotspotConfig::get();
        $deviceCount = Device::count();
        $leaseCount  = DhcpLease::count();
        $recentDevices = Device::connected();

        $uptime  = $this->safeExec('cat /proc/uptime') ?: '0';
        $uptimeSec = (int) explode(' ', $uptime)[0];
        $uptimeFmt = $this->formatUptime($uptimeSec);

        $loadAvg = $this->safeExec('cat /proc/loadavg') ?: '-- -- --';
        $memInfo = $this->parseMemInfo();

        $this->render('dashboard/index', [
            'hotspot'       => $hotspot,
            'deviceCount'   => $deviceCount,
            'leaseCount'    => $leaseCount,
            'recentDevices' => $recentDevices,
            'uptime'        => $uptimeFmt,
            'loadAvg'       => explode(' ', trim($loadAvg)),
            'mem'           => $memInfo,
            'pageTitle'     => 'Command Center',
        ]);
    }

    private function formatUptime(int $seconds): string {
        $d = intdiv($seconds, 86400);
        $h = intdiv($seconds % 86400, 3600);
        $m = intdiv($seconds % 3600, 60);
        return "{$d}d {$h}h {$m}m";
    }

    private function parseMemInfo(): array {
        $raw = @file_get_contents('/proc/meminfo');
        if (!$raw) return ['total' => 0, 'free' => 0, 'used' => 0, 'pct' => 0];

        preg_match('/MemTotal:\s+(\d+)/', $raw, $total);
        preg_match('/MemAvailable:\s+(\d+)/', $raw, $avail);

        $total = (int)($total[1] ?? 0);
        $avail = (int)($avail[1] ?? 0);
        $used  = $total - $avail;
        $pct   = $total > 0 ? round(($used / $total) * 100) : 0;

        return [
            'total' => round($total / 1024),
            'used'  => round($used / 1024),
            'free'  => round($avail / 1024),
            'pct'   => $pct,
        ];
    }
}
