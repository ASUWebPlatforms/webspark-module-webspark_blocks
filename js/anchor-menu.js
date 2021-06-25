(function ($, Drupal, drupalSettings) {

  Drupal.behaviors.anchorMenu = {
    attach: function (context, settings) {
      if ($(context).find('#uds-anchor-menu').length) {

        let links = $('.webspark-anchor-link-data');
        if (!links.length) {
          return;
        }

        links.each(function(i, item) {
          let icon = $(item).siblings('.anchor-link-icon').html();
          let title = $(item).data('title');
          let href = $(item).attr('id');

          $('#uds-anchor-menu .nav').append('<a class="nav-link" href="#' + href + '">' + icon + '</span>' + title + '</a>');
        });


        $('#headerContainer header').attr('id', 'global-header');


        // We use setTimeout to compensate header built by react 🤦
        setTimeout(function() {
          let globalHeader = document.getElementById('global-header');
          let navbar = document.getElementById('uds-anchor-menu');

          // Compensate for a fixed admin toolbar.
          let offset = $('#toolbar-administration').length ? 78 : 0;

          initializeAnchorMenu(globalHeader, navbar, offset);
        }, 100);

        $('.uds-anchor-menu').show();
      }
    }
  };


  function initializeAnchorMenu(globalHeader, navbar, offset) {
    const anchors = navbar.getElementsByClassName('nav-link');
    const navbarInitialPosition = navbar.getBoundingClientRect().top + window.scrollY;
    const anchorTargets = new Map();
    let previousScrollPosition = window.scrollY;

    // Cache the anchor target elements by mapping them as a key/pair so don't have to
    // parse the dom on every scroll event
    for (anchor of anchors) {
      const targetId = anchor.getAttribute('href').replace('#', '');
      const target = document.getElementById(targetId);
      anchorTargets.set(anchor, target);
    }

    window.addEventListener("scroll", function () {
      const navbarY = navbar.getBoundingClientRect().top;
      const headerHeight = globalHeader.offsetHeight;

      // If scrolling DOWN
      if (
        window.scrollY > previousScrollPosition &&
        !navbar.classList.contains('uds-anchor-menu-sticky')
      ) {
        if (navbarY > offset && navbarY < headerHeight + offset) {
          globalHeader.style.top = -(headerHeight - navbarY) + 'px';
        } else if (navbarY <= offset) {
          globalHeader.style.top = -globalHeader.offsetHeight + 'px';
          navbar.classList.add('uds-anchor-menu-sticky');
          navbar.style.top = offset + 'px';
        }
      }
      // If scrolling UP
      if (
        window.scrollY < previousScrollPosition &&
        window.scrollY < (navbarInitialPosition)
      ) {
        navbar.classList.remove('uds-anchor-menu-sticky');
        if (globalHeader.getBoundingClientRect().top < offset) {
          const localOffset = globalHeader.getBoundingClientRect().top + navbarY + offset;
          globalHeader.style.top = (localOffset + offset < 0 ? localOffset + offset : offset) + 'px';
        }
      }

      for (let [anchor, target] of anchorTargets) {
        const offsets = navbar.offsetHeight;

        if (
          target.getBoundingClientRect().top < offsets &&
          target.getBoundingClientRect().top + target.offsetHeight > offsets
        ) {
          anchor.classList.add('active');
        } else {
          anchor.classList.remove('active');
        }
      }

      previousScrollPosition = window.scrollY;
    });

    // Set click event of anchors
    for (let [anchor, anchorTarget] of anchorTargets) {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();

        // Compensate for height of navbar so content appears below it
        let scrollBy =
          anchorTarget.getBoundingClientRect().top - navbar.offsetHeight - offset;

        // If window hasn't been scrolled, compensate for header shrinking.
        const approximateHeaderSize = 65;
        if (window.scrollY === 0) scrollBy += approximateHeaderSize;

        // If navbar hasn't been stickied yet, that means global header is still in view, so compensate for header height
        if (!navbar.classList.contains('uds-anchor-menu-sticky')) {
          if (window.scrollY > 0) scrollBy += 24;
          scrollBy -= globalHeader.offsetHeight;
        }

        window.scrollBy({
          top: scrollBy,
          behavior: 'smooth',
        });

        // Remove active class from other anchor in navbar, and add it to the clicked anchor
        const active = navbar.querySelector('.nav-link.active');

        if (active) active.classList.remove('active');

        e.target.classList.add('active');
      });
    }
  }

})(jQuery, Drupal, drupalSettings);