<?php include VIEWS . '/layout/header.php'; ?>

<div class="panel-row">

  <!-- Tools -->
  <div class="panel">
    <div class="panel-title">// NETWORK TOOLS</div>

    <div class="diag-tool">
      <label class="form-label">PING</label>
      <div class="input-row">
        <input type="text" id="pingTarget" class="form-input" placeholder="IP or hostname">
        <button class="btn btn-primary" id="btnPing">[ PING ]</button>
      </div>
    </div>

    <div class="diag-tool">
      <label class="form-label">TRACEROUTE</label>
      <div class="input-row">
        <input type="text" id="traceTarget" class="form-input" placeholder="IP or hostname">
        <button class="btn btn-primary" id="btnTrace">[ TRACE ]</button>
      </div>
    </div>

    <pre class="terminal-output" id="diagOutput">// OUTPUT WILL APPEAR HERE</pre>
  </div>

  <!-- Interface Info -->
  <div class="panel panel-wide">
    <div class="panel-title">// INTERFACE INFO</div>
    <pre class="terminal-output"><?= htmlspecialchars($ifInfo ?: '(no data — run on Raspberry Pi)') ?></pre>

    <div class="panel-title" style="margin-top:1.5rem">// ARP TABLE</div>
    <pre class="terminal-output"><?= htmlspecialchars($arpTable ?: '(no data)') ?></pre>
  </div>

</div>

<script>
document.getElementById('btnPing').addEventListener('click', function() {
  var target = document.getElementById('pingTarget').value.trim();
  if (!target) return;
  document.getElementById('diagOutput').textContent = '>> PINGING ' + target + '...\n';
  RPM.post('/diagnostics/ping', { target: target }, function(res) {
    document.getElementById('diagOutput').textContent = res.output || res.error || 'No output.';
  });
});

document.getElementById('btnTrace').addEventListener('click', function() {
  var target = document.getElementById('traceTarget').value.trim();
  if (!target) return;
  document.getElementById('diagOutput').textContent = '>> TRACING ROUTE TO ' + target + '...\n';
  RPM.post('/diagnostics/trace', { target: target }, function(res) {
    document.getElementById('diagOutput').textContent = res.output || res.error || 'No output.';
  });
});
</script>

<?php include VIEWS . '/layout/footer.php'; ?>
