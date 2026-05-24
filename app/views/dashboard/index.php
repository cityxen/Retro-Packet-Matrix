<?php include VIEWS . '/layout/header.php'; ?>

<div class="dashboard-grid">

  <!-- Status Widgets Row -->
  <div class="widget-row">

    <div class="widget <?= $hotspot['enabled'] ? 'widget-online' : 'widget-offline' ?>">
      <div class="widget-label">HOTSPOT</div>
      <div class="widget-value"><?= $hotspot['enabled'] ? 'ONLINE' : 'OFFLINE' ?></div>
      <div class="widget-sub"><?= htmlspecialchars($hotspot['ssid']) ?></div>
    </div>

    <div class="widget">
      <div class="widget-label">DEVICES</div>
      <div class="widget-value"><?= $deviceCount ?></div>
      <div class="widget-sub">CONNECTED</div>
    </div>

    <div class="widget">
      <div class="widget-label">DHCP LEASES</div>
      <div class="widget-value"><?= $leaseCount ?></div>
      <div class="widget-sub">ACTIVE</div>
    </div>

    <div class="widget">
      <div class="widget-label">UPTIME</div>
      <div class="widget-value uptime-val"><?= htmlspecialchars($uptime) ?></div>
      <div class="widget-sub">SYSTEM</div>
    </div>

  </div><!-- /.widget-row -->

  <!-- Network Map + System Stats -->
  <div class="panel-row">

    <div class="panel panel-wide">
      <div class="panel-title">// NETWORK MAP</div>
      <pre class="ascii-map">
  +--[RPM NODE]--+
  |  <?= str_pad(htmlspecialchars($hotspot['ssid']), 12) ?>|
  |  CH: <?= str_pad($hotspot['channel'], 9) ?>|
  |  <?= str_pad($hotspot['band'], 12) ?>|
  +--------------+
         |
    [wlan0 AP]
         |
  +------+-------+
<?php foreach (array_slice($recentDevices, 0, 5) as $d): ?>
  | <?= str_pad(htmlspecialchars($d['ip_address'] ?? '?.?.?.?'), 15) ?>|
<?php endforeach; ?>
<?php if (empty($recentDevices)): ?>
  | (no devices)  |
<?php endif; ?>
  +---------------+
      </pre>
    </div>

    <div class="panel">
      <div class="panel-title">// SYSTEM STATS</div>
      <table class="data-table">
        <tr>
          <td class="td-label">LOAD AVG</td>
          <td><?= htmlspecialchars(implode(' ', array_slice($loadAvg, 0, 3))) ?></td>
        </tr>
        <tr>
          <td class="td-label">MEM TOTAL</td>
          <td><?= $mem['total'] ?> MB</td>
        </tr>
        <tr>
          <td class="td-label">MEM USED</td>
          <td><?= $mem['used'] ?> MB (<?= $mem['pct'] ?>%)</td>
        </tr>
        <tr>
          <td class="td-label">MEM FREE</td>
          <td><?= $mem['free'] ?> MB</td>
        </tr>
      </table>
      <div class="mem-bar-wrap">
        <div class="mem-bar" style="width:<?= $mem['pct'] ?>%"></div>
      </div>
      <div class="mem-bar-label"><?= $mem['pct'] ?>% MEMORY UTILIZATION</div>
    </div>

  </div><!-- /.panel-row -->

  <!-- Recent Devices Table -->
  <div class="panel panel-full">
    <div class="panel-title">// RECENTLY SEEN DEVICES</div>
    <?php if (empty($recentDevices)): ?>
      <p class="dim-text">No devices detected. Bring the hotspot online.</p>
    <?php else: ?>
      <table class="data-table data-table-full">
        <thead>
          <tr>
            <th>MAC ADDRESS</th>
            <th>IP ADDRESS</th>
            <th>HOSTNAME</th>
            <th>STATUS</th>
            <th>LAST SEEN</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recentDevices as $d): ?>
          <tr>
            <td class="mono"><?= htmlspecialchars($d['mac_address']) ?></td>
            <td class="mono"><?= htmlspecialchars($d['ip_address'] ?? '--') ?></td>
            <td><?= htmlspecialchars($d['hostname'] ?? '--') ?></td>
            <td><span class="status-badge status-<?= $d['status'] ?>"><?= strtoupper($d['status']) ?></span></td>
            <td class="dim-text"><?= htmlspecialchars($d['last_seen'] ?? '--') ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

</div><!-- /.dashboard-grid -->

<?php include VIEWS . '/layout/footer.php'; ?>
