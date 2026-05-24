<?php

class DhcpController extends Controller {
    public function index(): void {
        DhcpLease::syncFromFile();
        $leases  = DhcpLease::all();
        $dnsEntries = Database::query('SELECT * FROM dns_entries ORDER BY hostname')->fetchAll();

        $this->render('dhcp/index', [
            'leases'     => $leases,
            'dnsEntries' => $dnsEntries,
            'pageTitle'  => 'DHCP / DNS',
        ]);
    }

    public function addDns(): void {
        $hostname = strip_tags($this->input('hostname', ''));
        $ip       = $this->input('ip', '');

        if (!filter_var($ip, FILTER_VALIDATE_IP) || empty($hostname)) {
            $this->json(['success' => false, 'error' => 'Invalid input'], 400);
            return;
        }

        Database::query(
            'INSERT INTO dns_entries (hostname, ip_address) VALUES (?, ?)',
            [$hostname, $ip]
        );
        $this->json(['success' => true]);
    }

    public function deleteDns(): void {
        $id = (int) $this->input('id', 0);
        if ($id < 1) {
            $this->json(['success' => false, 'error' => 'Invalid ID'], 400);
            return;
        }
        Database::query('DELETE FROM dns_entries WHERE id=?', [$id]);
        $this->json(['success' => true]);
    }
}
