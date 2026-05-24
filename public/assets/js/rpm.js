'use strict';

const RPM = {

  post(url, data, callback) {
    const form = new FormData();
    Object.entries(data).forEach(([k, v]) => form.append(k, v));

    fetch(url, { method: 'POST', body: form })
      .then(r => r.json())
      .then(callback)
      .catch(err => console.error('[RPM]', err));
  },

  filterTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    if (!input || !table) return;

    input.addEventListener('input', function() {
      const q = this.value.toLowerCase();
      table.querySelectorAll('tbody tr').forEach(function(row) {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
      });
    });
  },

  startClock() {
    const el = document.getElementById('sysClock');
    if (!el) return;
    function tick() {
      const now = new Date();
      el.textContent = now.toTimeString().slice(0, 8);
    }
    tick();
    setInterval(tick, 1000);
  },

};

document.addEventListener('DOMContentLoaded', function() {
  RPM.startClock();
});
