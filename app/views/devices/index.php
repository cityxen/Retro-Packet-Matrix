<?php include VIEWS . '/layout/header.php'; ?>

<div class="panel panel-full">
  <div class="panel-title">// DEVICE REGISTRY</div>

  <div class="toolbar">
    <input type="text" id="deviceFilter" class="form-input filter-input" placeholder="FILTER: MAC / IP / HOSTNAME...">
    <span class="toolbar-count"><?= count($devices) ?> TOTAL RECORDS</span>
  </div>

  <?php if (empty($devices)): ?>
    <p class="dim-text">No devices in registry. Activate the hotspot to begin scanning.</p>
  <?php else: ?>
    <table class="data-table data-table-full" id="deviceTable">
      <thead>
        <tr>
          <th>MAC ADDRESS</th>
          <th>IP ADDRESS</th>
          <th>HOSTNAME</th>
          <th>ALIAS</th>
          <th>STATUS</th>
          <th>FIRST SEEN</th>
          <th>LAST SEEN</th>
          <th>ACTIONS</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($devices as $d): ?>
        <tr data-mac="<?= htmlspecialchars($d['mac_address']) ?>">
          <td class="mono"><?= htmlspecialchars($d['mac_address']) ?></td>
          <td class="mono"><?= htmlspecialchars($d['ip_address'] ?? '--') ?></td>
          <td><?= htmlspecialchars($d['hostname'] ?? '--') ?></td>
          <td><?= htmlspecialchars($d['alias'] ?? '--') ?></td>
          <td><span class="status-badge status-<?= $d['status'] ?>"><?= strtoupper($d['status']) ?></span></td>
          <td class="dim-text"><?= htmlspecialchars($d['first_seen'] ?? '--') ?></td>
          <td class="dim-text"><?= htmlspecialchars($d['last_seen'] ?? '--') ?></td>
          <td class="action-cell">
            <?php if ($d['status'] !== 'blocked'): ?>
              <button class="btn btn-sm btn-danger btn-block-device" data-mac="<?= htmlspecialchars($d['mac_address']) ?>">BLOCK</button>
            <?php else: ?>
              <button class="btn btn-sm btn-ok btn-unblock-device" data-mac="<?= htmlspecialchars($d['mac_address']) ?>">UNBLOCK</button>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<script>
RPM.filterTable('deviceFilter', 'deviceTable');

document.querySelectorAll('.btn-block-device').forEach(function(btn) {
  btn.addEventListener('click', function() {
    RPM.post('/devices/block', { mac: this.dataset.mac }, function() { location.reload(); });
  });
});

document.querySelectorAll('.btn-unblock-device').forEach(function(btn) {
  btn.addEventListener('click', function() {
    RPM.post('/devices/unblock', { mac: this.dataset.mac }, function() { location.reload(); });
  });
});
</script>

<?php include VIEWS . '/layout/footer.php'; ?>
