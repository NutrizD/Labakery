
// Notification compatibility / lightweight system (no Sellora dependency)
(function(){
  
// === Lightweight Modal Card for Payment Success ===
function ensureLiteModal(){
  let overlay = document.querySelector('.lite-modal');
  if(!overlay){
    overlay = document.createElement('div');
    overlay.className = 'lite-modal';
    overlay.innerHTML = '<div class="lite-modal-backdrop"></div><div class="lite-modal-card" role="dialog" aria-modal="true" aria-labelledby="lite-modal-title"><div class="lite-modal-header"><h5 id="lite-modal-title"></h5><button type="button" class="lite-modal-close" aria-label="Close">&times;</button></div><div class="lite-modal-body"></div><div class="lite-modal-footer"></div></div>';
    document.body.appendChild(overlay);
    overlay.querySelector('.lite-modal-backdrop').addEventListener('click', ()=> closeLiteModal());
    overlay.querySelector('.lite-modal-close').addEventListener('click', ()=> closeLiteModal());
  }
  return overlay;
}
function openLiteModal(title, html, actions){
  const overlay = ensureLiteModal();
  overlay.querySelector('#lite-modal-title').textContent = title || '';
  overlay.querySelector('.lite-modal-body').innerHTML = html || '';
  const footer = overlay.querySelector('.lite-modal-footer');
  footer.innerHTML = '';
  (actions||[]).forEach(a=>{
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = a.className || 'btn btn-primary';
    btn.textContent = a.label || 'OK';
    btn.addEventListener('click', a.onClick || closeLiteModal);
    footer.appendChild(btn);
  });
  overlay.classList.add('show');
  document.body.classList.add('modal-open-lite');
  return overlay;
}
function closeLiteModal(){
  const overlay = document.querySelector('.lite-modal');
  if(overlay){
    overlay.classList.remove('show');
    document.body.classList.remove('modal-open-lite');
  }
}

  const TYPES = ['success','error','warning','info'];

  function ensureContainer(){
    let c = document.querySelector('.lite-notifications');
    if(!c){
      c = document.createElement('div');
      c.className = 'lite-notifications';
      c.style.cssText = 'position:fixed;top:20px;right:20px;z-index:10000;max-width:360px;';
      document.body.appendChild(c);
    }
    return c;
  }

  function toastHTML(message, type){
    const bg = {
      success: '#10b981',
      error:   '#ef4444',
      warning: '#d97706',
      info:    '#3b82f6'
    }[type] || '#3b82f6';
    return (
      '<div style="display:flex;gap:.5rem;align-items:flex-start;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:12px 14px;box-shadow:0 8px 24px rgba(0,0,0,.08);transform:translateX(100%);transition:transform .25s ease;margin-bottom:10px;">' +
        '<div style="width:10px;height:10px;border-radius:50%;margin-top:6px;background:'+bg+'"></div>' +
        '<div style="color:#374151;line-height:1.35">' + message + '</div>' +
      '</div>'
    );
  }

  function show(message, type='info', duration=5000){
    const wrap = ensureContainer();
    const holder = document.createElement('div');
    holder.innerHTML = toastHTML(message, (type||'info').toLowerCase());
    const el = holder.firstChild;
    wrap.appendChild(el);
    requestAnimationFrame(()=>{ el.style.transform = 'translateX(0)'; });
    if(duration>0){
      setTimeout(()=>{
        el.style.transform = 'translateX(100%)';
        setTimeout(()=> el.remove(), 250);
      }, duration);
    }
    return el;
  }

  if(typeof window.NotificationSystem !== 'object'){
    window.NotificationSystem = {};
  }
  window.NotificationSystem.show = function(a,b,c){
    // Support both signatures:
    // show(message, type='info', duration)
    // show(type, message, title)  // legacy
    if (typeof a === 'string' && TYPES.includes(a.toLowerCase()) && typeof b === 'string'){
      const type = a.toLowerCase();
      const msg = (typeof c === 'string' && c.length) ? (c + ' — ' + b) : b;
      return show(msg, type, 5000);
    }
    return show(a, b, c);
  };

  window.NotificationSystem.showSuccess = function(title, message, changeLabel, changeAmount){
    try {
    let html = '';
    if (title) html += '<strong>'+title+'</strong><br>';
    if (message) html += message;
    if (changeAmount){
      html += '<br><small>' + (changeLabel || 'Kembalian') + ': <b>' + changeAmount + '</b></small>';
    }
    var body = '<div class="success-card">' + html + '<div id="successDetails" class="mt-3"></div></div>';
    openLiteModal(title || 'Transaksi Berhasil', body, [
      {label:'Tutup', className:'btn btn-outline-secondary', onClick: closeLiteModal}
    ]);
    return true;
  } catch(e){ console.warn('showSuccess modal error', e); return false; }
  };
})();

// Keep a toast variant if needed elsewhere
window.NotificationSystem.showToastSuccess = window.NotificationSystem.showSuccess;
