
// Small UX niceties
document.addEventListener('DOMContentLoaded', function(){
  // Ripple-safe: ensure .btn have no double-submit
  document.querySelectorAll('form[data-once]')?.forEach(form => {
    form.addEventListener('submit', () => {
      form.querySelectorAll('button[type="submit"]').forEach(b=> b.disabled = true);
    });
  });
});


// === Sidebar Active Link & Collapse Arrow ===
document.addEventListener('DOMContentLoaded', function(){
  try {
    var current = window.location.pathname.replace(/\/+$/, '');
    document.querySelectorAll('.sidebar .nav .nav-link[href]').forEach(function(a){
      var href = a.getAttribute('href');
      if(!href) return;
      var path = href.replace(window.location.origin, '').replace(/\/+$/, '');
      if(path && (current === path || current.startsWith(path + '/'))){
        a.classList.add('active');
        // Expand parent collapse if exists
        var collapse = a.closest('.collapse');
        if(collapse && !collapse.classList.contains('show')){
          collapse.classList.add('show');
          var parentToggle = document.querySelector('[data-toggle="collapse"][href="#'+collapse.id+'"], [data-toggle="collapse"][data-target="#'+collapse.id+'"]');
          if(parentToggle){ parentToggle.setAttribute('aria-expanded','true'); }
        }
      }
    });
    // Rotate arrow based on collapse events (Bootstrap)
    document.querySelectorAll('.collapse').forEach(function(el){
      el.addEventListener('shown.bs.collapse', function(e){
        var id = el.getAttribute('id');
        var toggles = document.querySelectorAll('[data-toggle="collapse"][href="#'+id+'"], [data-toggle="collapse"][data-target="#'+id+'"]');
        toggles.forEach(function(t){ t.setAttribute('aria-expanded','true'); });
      });
      el.addEventListener('hidden.bs.collapse', function(e){
        var id = el.getAttribute('id');
        var toggles = document.querySelectorAll('[data-toggle="collapse"][href="#'+id+'"], [data-toggle="collapse"][data-target="#'+id+'"]');
        toggles.forEach(function(t){ t.setAttribute('aria-expanded','false'); });
      });
    });
  } catch(e){ console.warn('Sidebar enhancer error', e); }
});
