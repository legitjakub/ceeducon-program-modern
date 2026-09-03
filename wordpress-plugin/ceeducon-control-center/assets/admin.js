/**
 * Shared behaviour for the CEEDUCON screens: the group navigation and search on
 * the texts screen, the {{token}} preview, the media pickers and copy buttons.
 */
(function () {
  'use strict';

  var doc = document;

  function readJson(id) {
    var node = doc.getElementById(id);
    if (!node) { return null; }
    try { return JSON.parse(node.textContent); } catch (error) { return null; }
  }

  /* ----------------------------------------------------------------------
   * Copy buttons
   * -------------------------------------------------------------------- */

  doc.addEventListener('click', function (event) {
    var button = event.target.closest('[data-cc-copy]');
    if (!button) { return; }
    event.preventDefault();

    var text = button.getAttribute('data-cc-copy');
    var done = function () {
      button.classList.add('is-copied');
      window.setTimeout(function () { button.classList.remove('is-copied'); }, 1200);
    };

    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(done, done);
      return;
    }
    // Older browsers in the admin still need the hidden-textarea route.
    var field = doc.createElement('textarea');
    field.value = text;
    doc.body.appendChild(field);
    field.select();
    try { doc.execCommand('copy'); } catch (error) { /* nothing to do */ }
    doc.body.removeChild(field);
    done();
  });

  /* ----------------------------------------------------------------------
   * Texts screen
   * -------------------------------------------------------------------- */

  var contentForm = doc.querySelector('[data-cc-content]');
  if (contentForm) {
    var tokens = readJson('cc-tokens-data') || {};
    var nav = contentForm.querySelector('[data-cc-groupnav]');
    var panels = Array.prototype.slice.call(contentForm.querySelectorAll('[data-cc-group-panel]'));
    var activeInput = contentForm.querySelector('[data-cc-active-group]');
    var search = contentForm.querySelector('[data-cc-search]');
    var status = contentForm.querySelector('[data-cc-search-status]');
    var empty = contentForm.querySelector('[data-cc-empty]');
    var previewToggle = contentForm.querySelector('[data-cc-preview-toggle]');

    var showGroup = function (index) {
      panels.forEach(function (panel) {
        panel.hidden = panel.getAttribute('data-cc-group-panel') !== String(index);
      });
      Array.prototype.forEach.call(nav.children, function (button) {
        button.classList.toggle('is-active', button.getAttribute('data-cc-group') === String(index));
      });
      if (activeInput) { activeInput.value = String(index); }
    };

    nav.addEventListener('click', function (event) {
      var button = event.target.closest('[data-cc-group]');
      if (!button) { return; }
      if (search) { search.value = ''; }
      runSearch();
      showGroup(button.getAttribute('data-cc-group'));
      contentForm.scrollIntoView({ block: 'start', behavior: 'smooth' });
    });

    var expand = function (value) {
      return String(value).replace(/\{\{[a-z_]+\}\}/g, function (token) {
        return Object.prototype.hasOwnProperty.call(tokens, token) ? tokens[token] : token;
      });
    };

    var updatePreview = function (field) {
      var preview = field.querySelector('[data-cc-preview]');
      var input = field.querySelector('[data-cc-input]');
      if (!preview || !input) { return; }
      var on = previewToggle && previewToggle.checked;
      var value = input.value;
      // Only worth showing where a token actually changes something.
      preview.hidden = !on || value.indexOf('{{') === -1;
      if (!preview.hidden) { preview.textContent = expand(value); }
    };

    var allFields = Array.prototype.slice.call(contentForm.querySelectorAll('[data-cc-field]'));

    if (previewToggle) {
      previewToggle.addEventListener('change', function () {
        allFields.forEach(updatePreview);
      });
    }

    contentForm.addEventListener('input', function (event) {
      var field = event.target.closest('[data-cc-field]');
      if (field) { updatePreview(field); }
    });

    contentForm.addEventListener('click', function (event) {
      var reset = event.target.closest('[data-cc-reset]');
      if (!reset) { return; }
      event.preventDefault();
      var field = reset.closest('[data-cc-field]');
      var input = field.querySelector('[data-cc-input]');
      input.value = reset.getAttribute('data-cc-default') || '';
      field.classList.remove('is-edited');
      updatePreview(field);
      input.focus();
    });

    function runSearch() {
      var query = search ? search.value.trim().toLowerCase() : '';

      if (query === '') {
        panels.forEach(function (panel) {
          panel.hidden = panel.getAttribute('data-cc-group-panel') !== String(activeInput ? activeInput.value : 0);
        });
        allFields.forEach(function (field) { field.hidden = false; });
        if (status) { status.textContent = ''; }
        if (empty) { empty.hidden = true; }
        return;
      }

      var found = 0;
      panels.forEach(function (panel) {
        var visible = 0;
        Array.prototype.forEach.call(panel.querySelectorAll('[data-cc-field]'), function (field) {
          var hit = field.getAttribute('data-cc-search').indexOf(query) !== -1;
          field.hidden = !hit;
          if (hit) { visible++; }
        });
        panel.hidden = visible === 0;
        found += visible;
      });

      if (status) { status.textContent = found + '×'; }
      if (empty) { empty.hidden = found !== 0; }
    }

    if (search) {
      search.addEventListener('input', runSearch);
      search.addEventListener('search', runSearch);
    }
  }

  /* ----------------------------------------------------------------------
   * Media pickers on the edition screen
   * -------------------------------------------------------------------- */

  Array.prototype.forEach.call(doc.querySelectorAll('[data-cc-media]'), function (wrap) {
    var idField = wrap.querySelector('[data-cc-media-id]');
    var preview = wrap.querySelector('[data-cc-media-preview]');
    var remove = wrap.querySelector('[data-cc-media-remove]');
    var frame = null;

    wrap.querySelector('[data-cc-media-select]').addEventListener('click', function () {
      if (!window.wp || !window.wp.media) { return; }
      if (!frame) {
        frame = window.wp.media({ title: 'CEEDUCON', multiple: false, library: { type: 'image' } });
        frame.on('select', function () {
          var item = frame.state().get('selection').first().toJSON();
          idField.value = item.id;
          var url = (item.sizes && item.sizes.medium) ? item.sizes.medium.url : item.url;
          preview.innerHTML = '';
          var image = doc.createElement('img');
          image.src = url;
          image.alt = '';
          preview.appendChild(image);
          if (remove) { remove.hidden = false; }
        });
      }
      frame.open();
    });

    if (remove) {
      remove.addEventListener('click', function () {
        idField.value = '0';
        preview.textContent = '';
        remove.hidden = true;
      });
    }
  });
}());
