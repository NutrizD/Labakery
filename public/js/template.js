(function($) {
  'use strict';
  $(function() {
    var body = $('body');
    var contentWrapper = $('.content-wrapper');
    var scroller = $('.container-scroller');
    var footer = $('.footer');
    var sidebar = $('.sidebar');

    //Add active class to nav-link based on url dynamically
    //Active class can be hard coded directly in html file also as required

    function addActiveClass(element) {
      // Ignore collapse toggles like #employees-menu
      var rawHref = element.attr('href');
      if (!rawHref || rawHref.charAt(0) === '#') {
        return false;
      }
      
      if (current === "") {
        //for root url
        if (rawHref.indexOf("index.html") !== -1 || rawHref === '/') {
          element.parents('.nav-item').last().addClass('active');
          if (element.parents('.sub-menu').length) {
            element.addClass('active');
          }
          return true;
        }
      } else {
        //for other url
        var href = rawHref;
        
        // Get the current pathname for better matching
        var currentPath = location.pathname;
        
        // Laravel route-aware match: compare by route pattern
        var isMatch = false;
        
        // Special handling for different route types
        if (currentPath.startsWith('/transaksi/') && currentPath !== '/transaksi') {
          // Transaction detail page - only match transactions.index
          isMatch = href.indexOf('/transaksi') !== -1 && !href.match(/\/transaksi\/\d+$/);
        } else if (currentPath.startsWith('/laporan/transaction/')) {
          // Transaction detail page from reports - only match reports.index
          isMatch = href.indexOf('/laporan') !== -1 && !href.match(/\/laporan\/transaction\/\d+$/);
        } else if (currentPath.startsWith('/employees/')) {
          // Employee pages - only match employee routes
          isMatch = href.indexOf('/employees') !== -1;
        } else if (currentPath.startsWith('/products/')) {
          // Product pages - only match product routes
          isMatch = href.indexOf('/products') !== -1;
        } else if (currentPath.startsWith('/users/')) {
          // User pages - only match user routes (super admin only)
          isMatch = href.indexOf('/users') !== -1;
        } else if (currentPath.startsWith('/categories/')) {
          // Category pages - only match category routes
          isMatch = href.indexOf('/categories') !== -1;
        } else {
          // Default matching for other pages
          isMatch = href.indexOf(current) !== -1 || href.split('/').pop() === current;
        }
        
        if (isMatch) {
          element.parents('.nav-item').last().addClass('active');
          if (element.parents('.sub-menu').length) {
            element.addClass('active');
          }
          if (element.parents('.submenu-item').length) {
            element.addClass('active');
          }
          return true;
        }
      }
      return false;
    }

    var current = location.pathname.split("/").slice(-1)[0].replace(/^\/|\/$/g, '');
    // Close all collapses by default to avoid opening multiple groups
    sidebar.find('.collapse').each(function() {
      var $c = $(this);
      $c.removeClass('show');
      try { $c.collapse('hide'); } catch(e) {}
    });
    sidebar.find('.nav-link[aria-expanded]').attr('aria-expanded', 'false');

    var submenuToOpen = null;
    $('.nav li a', sidebar).each(function() {
      var $this = $(this);
      var isActive = addActiveClass($this);
      if (isActive && $this.parents('.sub-menu').length) {
        submenuToOpen = $this.closest('.collapse');
      }
    })

    // Only open submenu if we found an active submenu item
    // AND ensure we're not on a transaction detail page when opening employee menu
    if (submenuToOpen && submenuToOpen.length) {
      var currentPath = location.pathname;
      var isTransactionDetail = currentPath.startsWith('/transaksi/') && currentPath !== '/transaksi';
      var isReportTransactionDetail = currentPath.startsWith('/laporan/transaction/');
      var isEmployeeMenu = submenuToOpen.attr('id') === 'employees-menu';
      
      // Don't open employee menu if we're on a transaction detail page
      if (!((isTransactionDetail || isReportTransactionDetail) && isEmployeeMenu)) {
        submenuToOpen.addClass('show');
        submenuToOpen.prev('[data-toggle="collapse"]').attr('aria-expanded', 'true');
      }
    }

    $('.horizontal-menu .nav li a').each(function() {
      var $this = $(this);
      addActiveClass($this);
    })

    //Close other submenu in sidebar on opening any

    sidebar.on('show.bs.collapse', '.collapse', function() {
      sidebar.find('.collapse.show').collapse('hide');
    });


    //Change sidebar and content-wrapper height
    applyStyles();

    function applyStyles() {
      //Applying perfect scrollbar
      if (!body.hasClass("rtl")) {
        if ($('.settings-panel .tab-content .tab-pane.scroll-wrapper').length) {
          const settingsPanelScroll = new PerfectScrollbar('.settings-panel .tab-content .tab-pane.scroll-wrapper');
        }
        if ($('.chats').length) {
          const chatsScroll = new PerfectScrollbar('.chats');
        }
        if (body.hasClass("sidebar-fixed")) {
          if($('#sidebar').length) {
            var fixedSidebarScroll = new PerfectScrollbar('#sidebar .nav');
          }
        }
      }
    }

    $('[data-toggle="minimize"]').on("click", function() {
      if ((body.hasClass('sidebar-toggle-display')) || (body.hasClass('sidebar-absolute'))) {
        body.toggleClass('sidebar-hidden');
      } else {
        body.toggleClass('sidebar-icon-only');
      }
    });

    //checkbox and radios
    $(".form-check label,.form-radio label").append('<i class="input-helper"></i>');

    //Horizontal menu in mobile
    $('[data-toggle="horizontal-menu-toggle"]').on("click", function() {
      $(".horizontal-menu .bottom-navbar").toggleClass("header-toggled");
    });
    // Horizontal menu navigation in mobile menu on click
    var navItemClicked = $('.horizontal-menu .page-navigation >.nav-item');
    navItemClicked.on("click", function(event) {
      if(window.matchMedia('(max-width: 991px)').matches) {
        if(!($(this).hasClass('show-submenu'))) {
          navItemClicked.removeClass('show-submenu');
        }
        $(this).toggleClass('show-submenu');
      }        
    })

    $(window).scroll(function() {
      if(window.matchMedia('(min-width: 992px)').matches) {
        var header = $('.horizontal-menu');
        if ($(window).scrollTop() >= 70) {
          $(header).addClass('fixed-on-scroll');
        } else {
          $(header).removeClass('fixed-on-scroll');
        }
      }
    });
  });

  // focus input when clicking on search icon
  $('#navbar-search-icon').click(function() {
    $("#navbar-search-input").focus();
  });
  
})(jQuery);
