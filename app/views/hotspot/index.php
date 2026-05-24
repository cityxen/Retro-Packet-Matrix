<?php include VIEWS . '/layout/header.php'; ?>

<div class="panel panel-full">
  <div class="panel-title">// HOTSPOT CONTROL PANEL</div>

  <div class="hotspot-status-bar">
    <div class="hs-indicator <?= $config['enabled'] ? 'ind-on' : 'ind-off' ?>" id="hsIndicator"></div>
    <span id="hsStatusText"><?= $config['enabled'] ? 'ACCESS POINT ACTIVE' : 'ACCESS POINT INACTIVE' ?></span>
    <button class="btn btn-toggle" id="btnToggle" data-enabled="<?= (int) $config['enabled'] ?>">
      <?= $config['enabled'] ? '[ SHUTDOWN AP ]' : '[ ACTIVATE AP ]' ?>
    </button>
  </div>

  <hr class="retro-hr">

  <form id="hotspotForm" class="retro-form">
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">SSID (NETWORK NAME)</label>
        <input type="text" name="ssid" class="form-input" maxlength="32"
               value="<?= htmlspecialchars($config['ssid']) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">CHANNEL</label>
        <select name="channel" class="form-input">
          <?php foreach ([1,2,3,4,5,6,7,8,9,10,11,36,40,44,48] as $ch): ?>
            <option value="<?= $ch ?>" <?= $config['channel'] == $ch ? 'selected' : '' ?>><?= $ch ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">BAND</label>
        <select name="band" class="form-input">
          <option value="2.4GHz" <?= $config['band'] === '2.4GHz' ? 'selected' : '' ?>>2.4 GHz</option>
          <option value="5GHz"   <?= $config['band'] === '5GHz'   ? 'selected' : '' ?>>5 GHz</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">SECURITY</label>
        <select name="security" class="form-input">
          <option value="wpa2" <?= $config['security'] === 'wpa2' ? 'selected' : '' ?>>WPA2</option>
          <option value="wpa3" <?= $config['security'] === 'wpa3' ? 'selected' : '' ?>>WPA3</option>
          <option value="open" <?= $config['security'] === 'open' ? 'selected' : '' ?>>OPEN (no password)</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">PASSWORD</label>
        <input type="password" name="password" class="form-input" maxlength="64"
               value="<?= htmlspecialchars($config['password'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">MAX CLIENTS</label>
        <input type="number" name="max_clients" class="form-input" min="1" max="255"
               value="<?= (int) $config['max_clients'] ?>">
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">[ SAVE CONFIGURATION ]</button>
    </div>
    <div class="form-msg" id="formMsg"></div>
  </form>
</div>

<script>
document.getElementById('btnToggle').addEventListener('click', function() {
  RPM.post('/hotspot/toggle', {}, function(res) {
    const on = res.enabled;
    document.getElementById('hsIndicator').className = 'hs-indicator ' + (on ? 'ind-on' : 'ind-off');
    document.getElementById('hsStatusText').textContent = on ? 'ACCESS POINT ACTIVE' : 'ACCESS POINT INACTIVE';
    this.textContent = on ? '[ SHUTDOWN AP ]' : '[ ACTIVATE AP ]';
    this.dataset.enabled = on ? '1' : '0';
  }.bind(this));
});

document.getElementById('hotspotForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(this));
  RPM.post('/hotspot/save', data, function(res) {
    document.getElementById('formMsg').textContent = res.success ? '>> CONFIG SAVED OK' : '>> ERROR: ' + (res.error || 'unknown');
    document.getElementById('formMsg').className = 'form-msg ' + (res.success ? 'msg-ok' : 'msg-err');
  });
});
</script>

<?php include VIEWS . '/layout/footer.php'; ?>
