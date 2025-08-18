// Simple sidebar fix with logo handling
document.addEventListener('DOMContentLoaded', function() {
    console.log('Simple sidebar fix loaded');
    
    // Fix logo visibility
    function fixLogo() {
        // Show only the main logo on desktop
        const mainLogo = document.querySelector('.brand-logo');
        const miniLogo = document.querySelector('.brand-logo-mini');
        
        if (mainLogo && miniLogo) {
            // Check if we're on mobile
            if (window.innerWidth <= 991) {
                // Mobile: hide main logo, show mini logo
                mainLogo.style.setProperty('display', 'none', 'important');
                mainLogo.style.setProperty('visibility', 'hidden', 'important');
                mainLogo.style.setProperty('opacity', '0', 'important');
                
                miniLogo.style.setProperty('display', 'flex', 'important');
                miniLogo.style.setProperty('align-items', 'center', 'important');
                miniLogo.style.setProperty('visibility', 'visible', 'important');
                miniLogo.style.setProperty('opacity', '1', 'important');
            } else {
                // Desktop: show main logo, hide mini logo
                mainLogo.style.setProperty('display', 'flex', 'important');
                mainLogo.style.setProperty('align-items', 'center', 'important');
                mainLogo.style.setProperty('visibility', 'visible', 'important');
                mainLogo.style.setProperty('opacity', '1', 'important');
                
                miniLogo.style.setProperty('display', 'none', 'important');
                miniLogo.style.setProperty('visibility', 'hidden', 'important');
                miniLogo.style.setProperty('opacity', '0', 'important');
            }
        }
        
        console.log('Logo fixed - no duplication');
    }
    
    // Apply logo fix
    fixLogo();
    setTimeout(fixLogo, 100);
    setTimeout(fixLogo, 500);
    
    // Handle logo switching on window resize
    window.addEventListener('resize', function() {
        fixLogo();
    });
    
    // Simple sidebar positioning
    function fixSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const mainPanel = document.querySelector('.main-panel');
        
        if (sidebar) {
            // Ensure sidebar is visible and positioned
            sidebar.style.setProperty('display', 'block', 'important');
            sidebar.style.setProperty('visibility', 'visible', 'important');
            sidebar.style.setProperty('opacity', '1', 'important');
            sidebar.style.setProperty('position', 'fixed', 'important');
            sidebar.style.setProperty('left', '0', 'important');
            sidebar.style.setProperty('top', '60px', 'important');
            sidebar.style.setProperty('width', '235px', 'important');
            sidebar.style.setProperty('height', 'calc(100vh - 60px)', 'important');
            sidebar.style.setProperty('z-index', '1000', 'important');
            sidebar.style.setProperty('background', '#fff', 'important');
            sidebar.style.setProperty('box-shadow', '2px 0 10px rgba(0,0,0,0.1)', 'important');
            sidebar.style.setProperty('overflow-y', 'auto', 'important');
            sidebar.style.setProperty('overflow-x', 'hidden', 'important');
            
            console.log('Sidebar positioned');
        }
        
        if (mainPanel) {
            // Position main panel
            mainPanel.style.setProperty('margin-left', '235px', 'important');
            mainPanel.style.setProperty('width', 'calc(100% - 235px)', 'important');
        }
    }
    
    // Apply sidebar fix
    fixSidebar();
    setTimeout(fixSidebar, 100);
    setTimeout(fixSidebar, 500);
    
    // Aggressive menu clickability fix
    function aggressiveFixMenuClickability() {
        const allMenuLinks = document.querySelectorAll('.sidebar .nav-item .nav-link');
        console.log('Found', allMenuLinks.length, 'menu links');
        
        allMenuLinks.forEach(function(link, index) {
            const linkText = link.textContent.trim();
            const href = link.getAttribute('href');
            const dataToggle = link.getAttribute('data-toggle');
            
            // Remove any problematic attributes
            link.classList.remove('disabled');
            link.removeAttribute('disabled');
            link.removeAttribute('aria-disabled');
            
            // Force clickable styles
            link.style.setProperty('pointer-events', 'auto', 'important');
            link.style.setProperty('cursor', 'pointer', 'important');
            link.style.setProperty('position', 'relative', 'important');
            link.style.setProperty('z-index', '1001', 'important');
            
            // Clear any existing onclick that might interfere
            link.onclick = null;
            
            // Remove any existing event listeners by cloning
            const newLink = link.cloneNode(true);
            link.parentNode.replaceChild(newLink, link);
            
            // Add click handler based on type
            if (dataToggle === 'collapse') {
                // This is a submenu toggle - handle collapse/expand
                newLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const targetId = this.getAttribute('href');
                    const target = document.querySelector(targetId);
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    
                    if (target) {
                        if (isExpanded) {
                            // Collapse
                            target.classList.remove('show');
                            this.setAttribute('aria-expanded', 'false');
                        } else {
                            // Expand
                            target.classList.add('show');
                            this.setAttribute('aria-expanded', 'true');
                        }
                    }
                    
                    return false;
                });
            } else {
                // This is a regular navigation link
                newLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    if (href && href !== '#' && !href.includes('#')) {
                        window.location.href = href;
                    }
                    return false;
                });
            }
        });
        
        console.log('Menu clickability applied');
    }
    
    // Apply aggressive menu clickability fix
    aggressiveFixMenuClickability();
    setTimeout(aggressiveFixMenuClickability, 100);
    setTimeout(aggressiveFixMenuClickability, 500);
    
    // Keep checking menu clickability
    setInterval(aggressiveFixMenuClickability, 2000);
    
    // Add event delegation for submenu toggles
    document.addEventListener('click', function(e) {
        const target = e.target.closest('.sidebar .nav-link');
        if (target && target.getAttribute('data-toggle') === 'collapse') {
            e.preventDefault();
            e.stopPropagation();
            
            const targetId = target.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            const isExpanded = target.getAttribute('aria-expanded') === 'true';
            
            if (targetElement) {
                if (isExpanded) {
                    // Collapse
                    targetElement.classList.remove('show');
                    target.setAttribute('aria-expanded', 'false');
                } else {
                    // Expand
                    targetElement.classList.add('show');
                    target.setAttribute('aria-expanded', 'true');
                }
            }
        }
    });
    
    // Remove any overlays that might block the sidebar
    function removeOverlays() {
        const overlays = document.querySelectorAll('.sidebar-overlay, .modal-backdrop, .overlay, .backdrop');
        overlays.forEach(function(overlay) {
            overlay.style.setProperty('display', 'none', 'important');
            overlay.style.setProperty('pointer-events', 'none', 'important');
            overlay.style.setProperty('z-index', '-1', 'important');
        });
    }
    
    // Apply overlay removal
    removeOverlays();
    setTimeout(removeOverlays, 100);
    setTimeout(removeOverlays, 500);
    setInterval(removeOverlays, 3000);
    
    // Ensure sidebar stays fixed during scroll
    window.addEventListener('scroll', function() {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.style.setProperty('position', 'fixed', 'important');
            sidebar.style.setProperty('top', '60px', 'important');
            sidebar.style.setProperty('left', '0', 'important');
        }
    });
    
    // Mobile responsive handling
    function handleMobileSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const mainPanel = document.querySelector('.main-panel');
        
        if (window.innerWidth <= 991) {
            // Mobile: hide sidebar by default
            if (sidebar) {
                sidebar.style.setProperty('transform', 'translateX(-100%)', 'important');
            }
            if (mainPanel) {
                mainPanel.style.setProperty('margin-left', '0', 'important');
                mainPanel.style.setProperty('width', '100%', 'important');
            }
        } else {
            // Desktop: show sidebar
            if (sidebar) {
                sidebar.style.setProperty('transform', 'translateX(0)', 'important');
            }
            if (mainPanel) {
                mainPanel.style.setProperty('margin-left', '235px', 'important');
                mainPanel.style.setProperty('width', 'calc(100% - 235px)', 'important');
            }
        }
    }
    
    // Apply mobile handling
    handleMobileSidebar();
    window.addEventListener('resize', handleMobileSidebar);
});
