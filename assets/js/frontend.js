(function () {
  'use strict';

  var modal = document.querySelector('[data-dvlnt-sac]');
  if (!modal || typeof dvlntSAC === 'undefined') return;

  var dialog = modal.querySelector('.dvlnt-sac__dialog');
  var form = modal.querySelector('.dvlnt-sac__form');
  var input = form.querySelector('input[name="zip"]');
  var error = modal.querySelector('.dvlnt-sac__error');
  var submit = form.querySelector('button[type="submit"]');
  var lastTrigger = null;

  function showState(name, zip) {
    modal.querySelectorAll('[data-sac-state]').forEach(function (state) {
      state.hidden = state.getAttribute('data-sac-state') !== name;
    });
    var activeState = modal.querySelector('[data-sac-state="' + name + '"]');
    dialog.setAttribute('aria-labelledby', activeState.getAttribute('data-title-id'));
    dialog.setAttribute('aria-describedby', activeState.getAttribute('data-description-id'));
    if (zip) {
      modal.querySelectorAll('[data-zip-template]').forEach(function (node) {
        var parts = node.getAttribute('data-zip-template').split('[ZIP]');
        node.textContent = '';
        parts.forEach(function (part, index) {
          node.appendChild(document.createTextNode(part));
          if (index < parts.length - 1) {
            var zipTag = document.createElement('strong');
            zipTag.className = 'dvlnt-sac__zip';
            zipTag.textContent = zip;
            node.appendChild(zipTag);
          }
        });
      });
    }
  }

  function openModal(trigger) {
    lastTrigger = trigger;
    showState('initial');
    error.textContent = '';
    input.value = '';
    modal.hidden = false;
    document.body.classList.add('dvlnt-sac-open');
    window.setTimeout(function () { input.focus(); }, 0);
  }

  function closeModal() {
    modal.hidden = true;
    document.body.classList.remove('dvlnt-sac-open');
    if (lastTrigger) lastTrigger.focus();
  }

  document.addEventListener('click', function (event) {
    var trigger = event.target.closest('.service-area-trigger');
    if (trigger) {
      event.preventDefault();
      openModal(trigger);
    }
  });

  modal.querySelectorAll('[data-sac-close]').forEach(function (button) {
    button.addEventListener('click', closeModal);
  });

  modal.querySelector('[data-sac-retry]').addEventListener('click', function () {
    showState('initial');
    input.value = '';
    error.textContent = '';
    input.focus();
  });

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    var zip = input.value.trim();
    if (!/^\d{5}$/.test(zip)) {
      error.textContent = dvlntSAC.invalidText;
      input.setAttribute('aria-invalid', 'true');
      input.focus();
      return;
    }

    error.textContent = '';
    input.removeAttribute('aria-invalid');
    submit.disabled = true;
    submit.textContent = dvlntSAC.checkingText;

    var body = new URLSearchParams({ action: 'dvlnt_sac_check_zip', nonce: dvlntSAC.nonce, zip: zip });
    fetch(dvlntSAC.ajaxUrl, { method: 'POST', credentials: 'same-origin', headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }, body: body.toString() })
      .then(function (response) { if (!response.ok) throw new Error('Request failed'); return response.json(); })
      .then(function (response) {
        if (!response.success || !response.data) throw new Error('Invalid response');
        showState(response.data.serviced ? 'success' : 'unavailable', response.data.zip);
        dialog.focus();
      })
      .catch(function () { error.textContent = dvlntSAC.errorText; input.focus(); })
      .finally(function () { submit.disabled = false; submit.textContent = submit.getAttribute('data-default-text'); });
  });

  document.addEventListener('keydown', function (event) {
    if (modal.hidden) return;
    if (event.key === 'Escape') { closeModal(); return; }
    if (event.key !== 'Tab') return;
    var focusable = Array.prototype.slice.call(dialog.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])')).filter(function (item) { return !item.closest('[hidden]'); });
    if (!focusable.length) return;
    var first = focusable[0];
    var last = focusable[focusable.length - 1];
    if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
    else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
  });
}());
