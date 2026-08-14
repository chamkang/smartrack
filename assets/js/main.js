

(function() {
  "use strict";

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    // switch to the solid header almost immediately (was 100px, which felt late)
    window.scrollY > 5 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  function mobileNavToogle() {
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavToggleBtn.classList.toggle('bi-list');
    mobileNavToggleBtn.classList.toggle('bi-x');
  }
  mobileNavToggleBtn.addEventListener('click', mobileNavToogle);

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns
   */// Function to toggle dropdown on click
function toggleDropdown(e) {
  e.preventDefault();
  const dropdownMenu = this.closest('li').querySelector('ul');
  
  if (dropdownMenu) {
      dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
  }
  
  // Stop propagation to prevent closing the parent dropdown
  e.stopImmediatePropagation();
}

// Function to show dropdown on hover
function showDropdown() {
  const dropdownMenu = this.querySelector('ul');
  if (dropdownMenu) {
      dropdownMenu.style.display = 'block'; // Show dropdown
  }
}

// Function to hide dropdown on mouse leave
function hideDropdown() {
  const dropdownMenu = this.querySelector('ul');
  if (dropdownMenu) {
      dropdownMenu.style.display = 'none'; // Hide dropdown
  }
}

// Attach event listeners for dropdown toggle
document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
  navmenu.addEventListener('click', toggleDropdown); // Click to toggle
  navmenu.parentNode.addEventListener('mouseenter', showDropdown); // Hover to show
  navmenu.parentNode.addEventListener('mouseleave', hideDropdown); // Leave to hide
});

/**
 * Dropdown tapping rules
 * ----------------------
 * The whole parent label is tappable, not just the small chevron.
 *
 *  - Menus with no page of their own (href="#", e.g. SERVICES, the EN/FR
 *    language switch) toggle their dropdown on click at ANY screen size.
 *  - Menus that do have a page (ABOUT US -> about.php) navigate on desktop and
 *    open the dropdown on mobile, where the first item ("Our Story") leads to
 *    that same page.
 */
(function () {
  var isMobileNav = function () {
    // Prefer matchMedia; fall back to innerWidth. Guard against a 0/undefined
    // width (some embedded webviews) wrongly reporting "mobile".
    if (window.matchMedia) return window.matchMedia('(max-width: 1199px)').matches;
    var w = window.innerWidth || document.documentElement.clientWidth || 1200;
    return w < 1200;
  };

  function closeSiblings(link, submenu) {
    var parentList = link.closest('ul');
    if (!parentList) return;
    parentList.querySelectorAll(':scope > li.dropdown > ul').forEach(function (other) {
      if (other !== submenu) other.style.display = 'none';
    });
  }

  document.querySelectorAll('.navmenu li.dropdown > a').forEach(function (link) {
    link.addEventListener('click', function (e) {
      var submenu = this.parentNode.querySelector('ul');
      if (!submenu) return;

      var href      = (this.getAttribute('href') || '').trim();
      var hasPage   = href && href !== '#';
      var isOpen    = submenu.style.display === 'block';

      // Menus without their own page always toggle (language switch, Services)
      if (!hasPage) {
        e.preventDefault();
        e.stopPropagation();
        closeSiblings(this, submenu);
        submenu.style.display = isOpen ? 'none' : 'block';
        return;
      }

      // Menus with a real page: desktop navigates, mobile opens the dropdown
      if (!isMobileNav()) return;
      if (isOpen) return;                 // second tap follows the link
      e.preventDefault();
      e.stopPropagation();
      closeSiblings(this, submenu);
      submenu.style.display = 'block';
    });
  });

  // Close open dropdowns when tapping elsewhere
  document.addEventListener('click', function (e) {
    if (e.target.closest('.navmenu li.dropdown')) return;
    document.querySelectorAll('.navmenu li.dropdown > ul').forEach(function (ul) {
      ul.style.display = '';
    });
  });

  // Reset inline display when resizing back to desktop so hover works again
  window.addEventListener('resize', function () {
    if (!isMobileNav()) {
      document.querySelectorAll('.navmenu li.dropdown > ul').forEach(function (ul) {
        ul.style.display = '';
      });
    }
  });
})();

 /*Close all dropdowns when clicking outside
document.addEventListener('click', function(e) {
  if (!e.target.closest('.navmenu') && !e.target.closest('.toggle-dropdown')) {
    document.querySelectorAll('.navmenu ul ul').forEach(menu => {
      menu.style.display = 'none'; // Hide all dropdowns
    });
  }
});*/

// Toggle dropdowns on click
document.querySelectorAll('.toggle-dropdown > a').forEach(navLink => {
  navLink.addEventListener('click', function(e) {
    e.preventDefault(); // Prevent default link behavior
    const dropdownMenu = this.nextElementSibling; // Get the dropdown menu
    const isVisible = dropdownMenu.style.display === 'block';
    
    document.querySelectorAll('.navmenu ul ul').forEach(menu => {
      menu.style.display = 'none'; // Hide all dropdowns on click
    });

    if (!isVisible) {
      dropdownMenu.style.display = 'block'; // Show the clicked dropdown
    }
  });
});


  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    let hidden = false;
    const hidePreloader = () => {
      if (hidden) return;
      hidden = true;
      preloader.style.transition = 'opacity .25s ease-out';
      preloader.style.opacity = '0';
      setTimeout(() => preloader.remove(), 250);
    };

    /* Hide as soon as the DOM is parsed. Stylesheets are render-blocking, so
       the page is already styled at this point — waiting for "load" meant
       waiting for every image and vendor script too, which held the spinner
       on screen long after the page was usable. */
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
      hidePreloader();
    } else {
      document.addEventListener('DOMContentLoaded', hidePreloader);
    }

    window.addEventListener('load', hidePreloader);   // in case DOM was already done
    setTimeout(hidePreloader, 3000);                  // failsafe: never trap the visitor
  }

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }
  scrollTop.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
  window.addEventListener('load', aosInit);

  /**
   * Initiate glightbox
   */
  const glightbox = GLightbox ({
    selector: '.glightbox'
  });

  /**
   * Init isotope layout and filters
   */
  document.querySelectorAll('.isotope-layout').forEach(function(isotopeItem) {
    let layout = isotopeItem.getAttribute('data-layout') ?? 'masonry';
    let filter = isotopeItem.getAttribute('data-default-filter') ?? '*';
    let sort = isotopeItem.getAttribute('data-sort') ?? 'original-order';

    let initIsotope;
    imagesLoaded(isotopeItem.querySelector('.isotope-container'), function() {
      initIsotope = new Isotope(isotopeItem.querySelector('.isotope-container'), {
        itemSelector: '.isotope-item',
        layoutMode: layout,
        filter: filter,
        sortBy: sort
      });
    });

    isotopeItem.querySelectorAll('.isotope-filters li').forEach(function(filters) {
      filters.addEventListener('click', function() {
        isotopeItem.querySelector('.isotope-filters .filter-active').classList.remove('filter-active');
        this.classList.add('filter-active');
        initIsotope.arrange({
          filter: this.getAttribute('data-filter')
        });
        if (typeof aosInit === 'function') {
          aosInit();
        }
      }, false);
    });

  });

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Initiate Pure Counter
   */
  new PureCounter();

})();