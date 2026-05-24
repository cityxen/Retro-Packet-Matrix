<?php include VIEWS . '/layout/header.php'; ?>

<div class="panel-row">

  <!-- DHCP Leases -->
  <div class="panel panel-wide">
    <div class="panel-title">// DHCP LEASE TABLE</div>

    <?php if (empty($leases)): ?>
      <p class="dim-text">No active leases. Hotspot may be offline.</p>
    <?php else: ?>
      <table class="data-table data-table-full">
        <thead>
          <tr>
            <th>MAC ADDRESS</th>
            <th>IP ADDRESS</th>
            <th>HOSTNAME</th>
            <th>EXPIRES</th>
            <th>TYPE</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($leases as $lease): ?>
          <tr>
            <td class="mono"><?= htmlspecialchars($lease['mac_address']) ?></td>
            <td class="mono"><?= htmlspecialchars($lease['ip_address']) ?></td>
            <td><?= htmlspecialchars($lease['hostname'] ?: '--') ?></td>
            <td class="dim-text"><?= htmlspecialchars($lease['expires_at'] ?? '--') ?></td>
            <td><span class="status-badge <?= $lease['static'] ? 'status-connected' : '' ?>">
              <?= $lease['static'] ? 'STATIC' : 'DYNAMIC' ?>
            </span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <!-- DNS Entries -->
  <div class="panel">
    <div class="panel-title">// CUSTOM DNS ENTRIES</div>

    <form id="dnsForm" class="retro-form">
      <div class="form-group">
        <label class="form-label">HOSTNAME</label>
        <input type="text" name="hostname" class="form-input" placeholder="mydevice.local">
      </div>
      <div class="form-group">
        <label class="form-label">IP ADDRESS</label>
        <input type="text" name="ip" class="form-input" placeholder="192.168.4.10">
      </div>
      <button type="submit" class="btn btn-primary">[ ADD ENTRY ]</button>
      <div class="form-msg" id="dnsMsg"></div>
    </form>

    <hr class="retro-hr">

    <?php if (empty($dnsEntries)): ?>
      <p class="dim-text">No custom DNS entries configured.</p>
    <?php else: ?>
      <table class="data-table data-table-full">
        <thead><tr><th>HOSTNAME</th><th>IP</th><th></th></tr></thead>
        <tbody id="dnsTable">
          <?php foreach ($dnsEntries as $entry): ?>
          <tr data-id="<?= $entry['id'] ?>">
            <td><?= htmlspecialchars($entry['hostname']) ?></td>
            <td class="mono"><?= htmlspecialchars($entry['ip_address']) ?></td>
            <td><button class="btn btn-sm btn-danger btn-del-dns" data-id="<?= $entry['id'] ?>">DEL</button></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

</div>

<script>
document.getElementById('dnsForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(this));
  RPM.post('/dhcp/dns/add', data, function(res) {
    if (res.success) { location.reload(); }
    else {
      document.getElementById('dnsMsg').textContent = '>> ERROR: ' + (res.error || 'invalid input');
      document.getElementById('dnsMsg').className = 'form-msg msg-err';
    }
  });
});

document.querySelectorAll('.btn-del-dns').forEach(function(btn) {
  btn.addEventListener('click', function() {
    RPM.post('/dhcp/dns/delete', { id: this.dataset.id }, function() { location.reload(); });
  });
});
</script>

<?php include VIEWS . '/layout/footer.php'; ?>
