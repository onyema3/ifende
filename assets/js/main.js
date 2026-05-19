(function(){
'use strict';

/* PAGE PRELOADER — fade out once DOM is ready */
var preloader = document.getElementById('sitePreloader');
if (preloader) {
  window.addEventListener('load', function() {
    preloader.classList.add('loaded');
    // Remove from DOM after transition completes
    setTimeout(function() { preloader.remove(); }, 500);
  });
  // Failsafe: remove preloader after 4s even if load event stalls
  setTimeout(function() {
    if (preloader && !preloader.classList.contains('loaded')) {
      preloader.classList.add('loaded');
    }
  }, 4000);
}

/* CUSTOM CURSOR — only on non-touch devices */
var cursor = document.getElementById('cursor');
var ring = document.getElementById('cursorRing');
var isTouchDevice = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);

if (cursor && ring && !isTouchDevice) {
  document.documentElement.classList.add('has-custom-cursor');

  document.addEventListener('mousemove', function(e) {
    cursor.style.left = e.clientX + 'px';
    cursor.style.top = e.clientY + 'px';
    setTimeout(function() {
      ring.style.left = e.clientX + 'px';
      ring.style.top = e.clientY + 'px';
    }, 80);
  });

  document.querySelectorAll('a, button, [role="button"]').forEach(function(el) {
    el.addEventListener('mouseenter', function() {
      cursor.style.width = '24px';
      cursor.style.height = '24px';
      ring.style.width = '56px';
      ring.style.height = '56px';
      ring.style.borderColor = 'var(--green)';
    });
    el.addEventListener('mouseleave', function() {
      cursor.style.width = '12px';
      cursor.style.height = '12px';
      ring.style.width = '36px';
      ring.style.height = '36px';
      ring.style.borderColor = 'rgba(33,161,78,0.5)';
    });
  });
}

/* NAV SCROLL */
var nav = document.getElementById('siteNav');
if (nav) {
  window.addEventListener('scroll', function() {
    nav.classList.toggle('scrolled', window.scrollY > 60);
  }, { passive: true });
}

/* DARK / LIGHT MODE TOGGLE */
var themeToggle = document.getElementById('themeToggle');
if (themeToggle) {
  themeToggle.addEventListener('click', function() {
    var current = document.documentElement.getAttribute('data-theme') || 'dark';
    var next = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('ifende-theme', next);
  });

  // Listen for system theme changes (if user hasn't manually chosen).
  window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', function(e) {
    if (!localStorage.getItem('ifende-theme')) {
      document.documentElement.setAttribute('data-theme', e.matches ? 'light' : 'dark');
    }
  });
}

/* MOBILE DRAWER with proper ARIA */
window.toggleDrawer = function() {
  var btn = document.getElementById('hamburger');
  var drawer = document.getElementById('mobileDrawer');
  if (!btn || !drawer) return;

  var isOpen = btn.classList.contains('open');
  btn.classList.toggle('open', !isOpen);
  drawer.classList.toggle('open', !isOpen);
  document.body.style.overflow = isOpen ? '' : 'hidden';

  // Update ARIA states
  btn.setAttribute('aria-expanded', String(!isOpen));
  drawer.setAttribute('aria-hidden', String(isOpen));

  // Trap focus in drawer when open
  if (!isOpen) {
    var firstLink = drawer.querySelector('a');
    if (firstLink) firstLink.focus();
  }
};

document.querySelectorAll('#mobileDrawer a').forEach(function(a) {
  a.addEventListener('click', function() {
    document.getElementById('hamburger').classList.remove('open');
    document.getElementById('mobileDrawer').classList.remove('open');
    document.body.style.overflow = '';
    document.getElementById('hamburger').setAttribute('aria-expanded', 'false');
    document.getElementById('mobileDrawer').setAttribute('aria-hidden', 'true');
  });
});

// Close drawer on Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    var btn = document.getElementById('hamburger');
    if (btn && btn.classList.contains('open')) {
      window.toggleDrawer();
      btn.focus();
    }
  }
});

/* SCROLL REVEAL — respects prefers-reduced-motion */
var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
var reveals = document.querySelectorAll('.reveal');

if (prefersReducedMotion) {
  // If user prefers reduced motion, show elements immediately
  reveals.forEach(function(r) { r.classList.add('visible'); });
} else if ('IntersectionObserver' in window) {
  var ro = new IntersectionObserver(function(entries) {
    entries.forEach(function(e) {
      if (e.isIntersecting) {
        e.target.classList.add('visible');
        ro.unobserve(e.target);
      }
    });
  }, { threshold: 0.1 });
  reveals.forEach(function(r) { ro.observe(r); });
} else {
  reveals.forEach(function(r) { r.classList.add('visible'); });
}

/* MARQUEE — pause on hover/focus for accessibility */
var track = document.getElementById('marqueeTrack');
if (track) {
  track.innerHTML += track.innerHTML;

  // Pause marquee on hover
  var marqueeSection = track.closest('.marquee-section');
  if (marqueeSection) {
    marqueeSection.addEventListener('mouseenter', function() {
      track.style.animationPlayState = 'paused';
    });
    marqueeSection.addEventListener('mouseleave', function() {
      track.style.animationPlayState = 'running';
    });
  }
}

/* CONTACT FORM */
var form = document.getElementById('contactForm');
if (form) {
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    var btn = document.getElementById('submitBtn');
    var msg = document.getElementById('formMsg');
    btn.textContent = 'Sending...';
    btn.classList.add('loading');
    btn.setAttribute('aria-busy', 'true');

    var name = (document.getElementById('fname').value + ' ' + document.getElementById('lname').value).trim();
    var email = document.getElementById('femail').value;
    var subject = document.getElementById('fsubject') ? document.getElementById('fsubject').value : 'Portfolio Enquiry';
    var message = document.getElementById('fmessage').value;
    var data = { _subject: 'Portfolio Enquiry: ' + subject, _replyto: email, name: name, email: email, subject: subject, message: message };
    var fs = ifendeData.formspree || '';
    var w3 = ifendeData.web3forms || '';

    /* Try WP AJAX first */
    var fd = new FormData();
    fd.append('action', 'ifende_contact');
    fd.append('nonce', ifendeData.nonce);
    fd.append('name', name);
    fd.append('email', email);
    fd.append('subject', subject);
    fd.append('message', message);

    fetch(ifendeData.ajaxUrl, { method: 'POST', body: fd })
      .then(function(r) { return r.json(); })
      .then(function(res) {
        if (res.success) { showSuccess(btn, msg); }
        else if (fs) { tryFormspree(data, fs, btn, msg); }
        else if (w3) { tryWeb3(data, w3, btn, msg); }
        else { doMailtoFallback(data, btn, msg); }
      })
      .catch(function() {
        fs ? tryFormspree(data, fs, btn, msg) : doMailtoFallback(data, btn, msg);
      });
  });
}

function tryFormspree(data, id, btn, msg) {
  fetch('https://formspree.io/f/' + id, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify(data)
  })
    .then(function(r) { r.ok ? showSuccess(btn, msg) : doMailtoFallback(data, btn, msg); })
    .catch(function() { doMailtoFallback(data, btn, msg); });
}

function tryWeb3(data, key, btn, msg) {
  fetch('https://api.web3forms.com/submit', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify(Object.assign({}, data, { access_key: key }))
  })
    .then(function(r) { r.ok ? showSuccess(btn, msg) : doMailtoFallback(data, btn, msg); })
    .catch(function() { doMailtoFallback(data, btn, msg); });
}

function doMailtoFallback(data, btn, msg) {
  // Fetch email via AJAX to avoid exposing it in page source.
  var fd = new FormData();
  fd.append('action', 'ifende_get_email');
  fd.append('nonce', ifendeData.nonce);
  fetch(ifendeData.ajaxUrl, { method: 'POST', body: fd })
    .then(function(r) { return r.json(); })
    .then(function(res) {
      var email = (res.data && res.data.email) ? res.data.email : 'hello@ifende.com';
      doMailto(data, email, btn, msg);
    })
    .catch(function() {
      doMailto(data, 'hello@ifende.com', btn, msg);
    });
}

function doMailto(data, email, btn, msg) {
  var body = 'Name: ' + data.name + '\nEmail: ' + data.email + '\n\nMessage:\n' + data.message;
  window.location.href = 'mailto:' + email + '?subject=' + encodeURIComponent(data._subject) + '&body=' + encodeURIComponent(body);
  setTimeout(function() { showSuccess(btn, msg); }, 1000);
}

function showSuccess(btn, msg) {
  btn.textContent = 'Message Sent \u2713';
  btn.classList.remove('loading');
  btn.setAttribute('aria-busy', 'false');
  if (msg) {
    msg.style.display = 'block';
    msg.textContent = "Thank you! I'll get back to you shortly.";
    msg.setAttribute('role', 'status');
    msg.setAttribute('aria-live', 'polite');
  }
}

/* BACK TO TOP BUTTON */
var backToTop = document.getElementById('backToTop');
if (backToTop) {
  window.addEventListener('scroll', function() {
    if (window.scrollY > 600) {
      backToTop.classList.add('visible');
    } else {
      backToTop.classList.remove('visible');
    }
  }, { passive: true });

  backToTop.addEventListener('click', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

})();
