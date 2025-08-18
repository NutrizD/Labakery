<!-- Notification Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header" id="notificationHeader">
        <h5 class="modal-title" id="notificationModalLabel">Notifikasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="notificationBody">
        <div class="text-center">
          <i id="notificationIcon" class="mb-3" style="font-size: 3rem;"></i>
          <p id="notificationMessage"></p>
        </div>
      </div>
      <div class="modal-footer" id="notificationFooter">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="successModalLabel">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/></svg> Berhasil
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success mb-3 icon"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/></svg>
          <h5 id="successTitle">Operasi Berhasil!</h5>
          <p id="successMessage" class="text-muted"></p>
          <div id="successDetails" class="alert alert-success" style="display: none;">
            <strong id="successDetailLabel"></strong> <span id="successDetailValue"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="confirmationModalLabel">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><polygon points="12 2 2 22 22 22 12 2"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="20" x2="12" y2="20"/></svg> Konfirmasi
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-warning mb-3 icon"><polygon points="12 2 2 22 22 22 12 2"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="20" x2="12" y2="20"/></svg>
          <h5 id="confirmationTitle">Konfirmasi Aksi</h5>
          <p id="confirmationMessage"></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-warning" id="confirmAction">Ya, Lanjutkan</button>
      </div>
    </div>
  </div>
</div>

<script>
(function () {
  // Pastikan jQuery & Bootstrap modal tersedia
  if (typeof $ === 'undefined' || !$.fn || !$.fn.modal) {
    console.warn('NotificationSystem: jQuery/Bootstrap Modal tidak ditemukan.');
  }

  // Helper: set kelas header & ikon berdasarkan tipe
  function applyTypeStyles(type, headerEl, iconEl) {
    const headerBase = ['modal-header'];
    const iconBase = [];

    const map = {
      success: { header: ['bg-success', 'text-white'], icon: ['ti-check-circle', 'text-white'] },
      error:   { header: ['bg-danger', 'text-white'],  icon: ['ti-close-circle', 'text-white'] },
      warning: { header: ['bg-warning', 'text-dark'],  icon: ['ti-alert', 'text-dark'] },
      info:    { header: ['bg-info', 'text-white'],    icon: ['ti-info-alt', 'text-white'] },
      default: { header: [],                            icon: ['ti-bell'] }
    };

    const picked = map[type] || map.default;

    headerEl.className = headerBase.concat(picked.header).join(' ');
    iconEl.className   = iconBase.concat(picked.icon).join(' ');
  }

  window.NotificationSystem = {
    // Tampilkan modal notifikasi generik
    show: function (type, message, title = 'Notifikasi') {
      const modal   = document.getElementById('notificationModal');
      const header  = document.getElementById('notificationHeader');
      const icon    = document.getElementById('notificationIcon');
      const msgEl   = document.getElementById('notificationMessage');
      const titleEl = document.getElementById('notificationModalLabel');

      if (!modal || !header || !icon || !msgEl || !titleEl) {
        console.warn('NotificationSystem.show: elemen modal notifikasi tidak lengkap.');
        return;
      }

      applyTypeStyles(type, header, icon);
      titleEl.textContent = title;
      msgEl.textContent   = message;

      $(modal).modal('show');
    },

    // Tampilkan modal sukses dengan detail opsional
    showSuccess: function (title, message, detailLabel = null, detailValue = null) {
      const titleEl   = document.getElementById('successTitle');
      const msgEl     = document.getElementById('successMessage');
      const detailsEl = document.getElementById('successDetails');
      const labelEl   = document.getElementById('successDetailLabel');
      const valueEl   = document.getElementById('successDetailValue');

      if (!titleEl || !msgEl || !detailsEl || !labelEl || !valueEl) {
        console.warn('NotificationSystem.showSuccess: elemen modal sukses tidak lengkap.');
        return;
      }

      titleEl.textContent = title || 'Berhasil';
      msgEl.textContent   = message || '';

      if (detailLabel && detailValue) {
        labelEl.textContent = detailLabel;
        valueEl.textContent = detailValue;
        detailsEl.style.display = 'block';
      } else {
        detailsEl.style.display = 'none';
      }

      $('#successModal').modal('show');
    },

    // Tampilkan modal konfirmasi dengan callback saat "Ya, Lanjutkan"
    showConfirmation: function (title, message, onConfirm) {
      const titleEl   = document.getElementById('confirmationTitle');
      const msgEl     = document.getElementById('confirmationMessage');
      const confirmEl = $('#confirmAction');

      if (!titleEl || !msgEl || confirmEl.length === 0) {
        console.warn('NotificationSystem.showConfirmation: elemen modal konfirmasi tidak lengkap.');
        return;
      }

      titleEl.textContent = title || 'Konfirmasi Aksi';
      msgEl.textContent   = message || ''
