(function () {
  'use strict';
  var field = document.getElementById('dvlnt-sac-zip-codes');
  var count = document.getElementById('dvlnt-sac-zip-count');
  if (!field || !count) return;
  function updateCount() {
    var unique = {};
    field.value.split(/[\s,]+/).forEach(function (zip) { if (/^\d{5}$/.test(zip)) unique[zip] = true; });
    var total = Object.keys(unique).length;
    count.textContent = total + (total === 1 ? ' valid ZIP' : ' valid ZIPs');
  }
  field.addEventListener('input', updateCount);
  updateCount();
}());

