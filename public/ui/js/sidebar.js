/**
 * Sidebar Controller
 * Scope: UI only — toggle, accordion, no fetch logic touched. C:\laragon\www\project-abhati\public\ui\js\sidebar.js
 */
(function () {
  'use strict';

  const STORAGE_KEY = 'sb_collapsed';
  const sidebar   = document.getElementById('app-sidebar');
  const toggleBtn = document.getElementById('sb-toggle');
  const body      = document.querySelector('.has-sidebar');

  if (!sidebar || !toggleBtn) return;

  // ─── State ───────────────────────────────────────────────
  function isCollapsed() {
    return sidebar.classList.contains('collapsed');
  }

  function setCollapsed(val) {
    sidebar.classList.toggle('collapsed', val);
    body?.classList.toggle('sidebar-collapsed', val);
    toggleBtn.setAttribute('aria-expanded', String(!val));
    try { localStorage.setItem(STORAGE_KEY, val ? '1' : '0'); } catch (_) {}
    // When collapsing, close all open accordions (they hide via CSS but reset state)
    if (val) closeAllAccordions();
  }

  // ─── Restore persisted state ──────────────────────────────
  (function restoreState() {
    let saved = '0';
    try { saved = localStorage.getItem(STORAGE_KEY) ?? '0'; } catch (_) {}
    if (saved === '1') setCollapsed(true);
  })();

  // ─── Toggle button ────────────────────────────────────────
  toggleBtn.addEventListener('click', () => setCollapsed(!isCollapsed()));

  // Keyboard: Enter/Space on toggle
  toggleBtn.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggleBtn.click(); }
  });

  // ─── Accordion ────────────────────────────────────────────
  function getSubmenu(trigger) {
    const id = trigger.getAttribute('aria-controls');
    return id ? document.getElementById(id) : null;
  }

  function openAccordion(trigger, submenu) {
    trigger.setAttribute('aria-expanded', 'true');
    submenu.classList.add('open');
  }

  function closeAccordion(trigger, submenu) {
    trigger.setAttribute('aria-expanded', 'false');
    submenu.classList.remove('open');
  }

  function closeAllAccordions() {
    document.querySelectorAll('[data-sb-accordion]').forEach((t) => {
      const sm = getSubmenu(t);
      if (sm) closeAccordion(t, sm);
    });
  }

  document.querySelectorAll('[data-sb-accordion]').forEach((trigger) => {
    const submenu = getSubmenu(trigger);
    if (!submenu) return;

    function handleToggle() {
      if (isCollapsed()) return; // popout handles hover in collapsed mode
      const isOpen = trigger.getAttribute('aria-expanded') === 'true';
      if (!isOpen) {
        // Close siblings first (single-open accordion)
        closeAllAccordions();
        openAccordion(trigger, submenu);
      } else {
        closeAccordion(trigger, submenu);
      }
    }

    trigger.addEventListener('click', handleToggle);
    trigger.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); handleToggle(); }
    });
  });

  // ─── Collapsed: close popout on click outside ─────────────
  document.addEventListener('click', (e) => {
    if (!isCollapsed()) return;
    if (!sidebar.contains(e.target)) {
      // nothing extra needed — popouts are CSS :hover driven
    }
  });

  // ─── Keyboard nav: Escape closes popout focus ─────────────
  sidebar.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isCollapsed()) sidebar.querySelector('.sb-nav-link')?.focus();
  });

  // ─── CTA button wiring (UI only, data logic stays external) ──
  // Both buttons dispatch a custom event; your existing fetch handler listens to it.
  function dispatchAddTask() {
    document.dispatchEvent(new CustomEvent('sidebar:addTask'));
  }

  document.getElementById('sb-add-task-btn')?.addEventListener('click', dispatchAddTask);
  document.getElementById('sb-add-task-btn-mini')?.addEventListener('click', dispatchAddTask);

})();