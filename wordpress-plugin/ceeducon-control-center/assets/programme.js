/**
 * The programme editor.
 *
 * The whole programme is one JSON document held in memory and posted back in a
 * single field. That is deliberate: a form with one input per session field —
 * plus one per room checkbox — passes PHP's default max_input_vars long before
 * a two-day, nine-room programme is fully posted, and everything past the limit
 * is dropped without a word.
 */
(function () {
  'use strict';

  var mount = document.getElementById('cc-programme-app');
  var dataNode = document.getElementById('cc-programme-data');
  var labelNode = document.getElementById('cc-programme-labels');
  if (!mount || !dataNode || !labelNode) { return; }

  var data = JSON.parse(dataNode.textContent);
  var L = JSON.parse(labelNode.textContent);

  var form = document.querySelector('[data-cc-programme-form]');
  var payload = form ? form.querySelector('[data-cc-payload]') : null;
  var dirtyBadge = document.querySelector('[data-cc-dirty]');
  var searchInput = document.querySelector('[data-cc-prog-search]');
  var viewSwitch = document.querySelector('[data-cc-view-switch]');

  var ui = { day: 0, view: 'schedule', query: '', open: null };
  var dirty = false;
  var drawer = null;

  /* ----------------------------------------------------------------------
   * Small DOM helpers
   * -------------------------------------------------------------------- */

  function el(tag, attrs, children) {
    var node = document.createElement(tag);
    Object.keys(attrs || {}).forEach(function (key) {
      var value = attrs[key];
      if (value === null || value === undefined || value === false) { return; }
      if (key === 'class') { node.className = value; return; }
      if (key === 'text') { node.textContent = value; return; }
      if (key === 'html') { node.innerHTML = value; return; }
      if (key.slice(0, 2) === 'on') { node.addEventListener(key.slice(2), value); return; }
      if (value === true) { node.setAttribute(key, ''); return; }
      node.setAttribute(key, value);
    });
    (children || []).forEach(function (child) {
      if (child === null || child === undefined) { return; }
      node.appendChild(typeof child === 'string' ? document.createTextNode(child) : child);
    });
    return node;
  }

  function labelled(text, control, hint, className) {
    return el('label', { class: 'cc-field ' + (className || '') }, [
      el('span', { class: 'cc-field-label', text: text }),
      control,
      hint ? el('small', { text: hint }) : null
    ]);
  }

  function select(value, options, onChange, emptyLabel) {
    var node = el('select', { class: 'cc-input', onchange: function () { onChange(node.value); } });
    if (emptyLabel !== undefined) {
      node.appendChild(el('option', { value: '', text: emptyLabel }));
    }
    options.forEach(function (option) {
      node.appendChild(el('option', { value: option.id, text: option.label }));
    });
    node.value = value || '';
    return node;
  }

  function input(type, value, onChange, attrs) {
    var node = el('input', Object.assign({ type: type, class: 'cc-input', value: value == null ? '' : value }, attrs || {}));
    node.addEventListener('input', function () { onChange(node.value); });
    return node;
  }

  function markDirty() {
    dirty = true;
    if (dirtyBadge) { dirtyBadge.hidden = false; }
  }

  /* ----------------------------------------------------------------------
   * Lookups
   * -------------------------------------------------------------------- */

  function byId(list, id) {
    var found = null;
    (list || []).forEach(function (row) { if (row.id === id) { found = row; } });
    return found;
  }

  function themeOf(session) { return byId(data.themes, session.theme); }
  function typeOf(session) { return byId(data.types, session.type); }
  function formatOf(session) { return byId(data.formats, session.format); }

  function slotLabel(day, slot) {
    var time = slot.start && slot.end ? slot.start + '–' + slot.end : L.from + ' ?';
    return (day.label || L.day) + ' · ' + time;
  }

  function duration(slot) {
    if (!slot.start || !slot.end) { return ''; }
    var toMinutes = function (value) {
      var parts = value.split(':');
      return parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
    };
    var minutes = toMinutes(slot.end) - toMinutes(slot.start);
    return minutes > 0 ? minutes + ' min' : '';
  }

  function matchesQuery(session) {
    if (ui.query === '') { return true; }
    var haystack = [
      session.title,
      (session.speakers || []).join(' '),
      (session.rooms || []).join(' '),
      session.abstract || '',
      (themeOf(session) || {}).label || '',
      (typeOf(session) || {}).label || ''
    ].join(' ').toLowerCase();
    return haystack.indexOf(ui.query) !== -1;
  }

  /* ----------------------------------------------------------------------
   * Schedule view
   * -------------------------------------------------------------------- */

  function renderDayTabs() {
    var tabs = el('div', { class: 'cc-daytabs' }, []);

    data.days.forEach(function (day, index) {
      var count = 0;
      day.slots.forEach(function (slot) { count += (slot.sessions || []).length; });
      tabs.appendChild(el('button', {
        type: 'button',
        class: 'cc-daytab' + (index === ui.day ? ' is-active' : ''),
        onclick: function () { ui.day = index; render(); }
      }, [
        el('strong', { text: day.label || (L.day + ' ' + (index + 1)) }),
        el('span', { text: (day.date || '—') + ' · ' + count })
      ]));
    });

    tabs.appendChild(el('button', {
      type: 'button',
      class: 'cc-daytab cc-daytab--add',
      text: L.addDay,
      onclick: function () {
        data.days.push({ date: '', label: L.day + ' ' + (data.days.length + 1), title: L.newDayTitle, slots: [] });
        ui.day = data.days.length - 1;
        markDirty();
        render();
      }
    }));

    return tabs;
  }

  function renderDayMeta(day) {
    return el('div', { class: 'cc-panel cc-daymeta' }, [
      labelled(L.dayDate, input('date', day.date, function (value) { day.date = value; markDirty(); })),
      labelled(L.dayLabel, input('text', day.label, function (value) { day.label = value; markDirty(); })),
      labelled(L.dayTitle, input('text', day.title, function (value) { day.title = value; markDirty(); }), null, 'cc-grow'),
      el('button', {
        type: 'button',
        class: 'cc-danger-link',
        text: L.removeDay,
        onclick: function () {
          if (!window.confirm(L.confirmDay)) { return; }
          data.days.splice(ui.day, 1);
          ui.day = Math.max(0, ui.day - 1);
          markDirty();
          render();
        }
      })
    ]);
  }

  function sessionCard(dayIndex, slotIndex, sessionIndex) {
    var session = data.days[dayIndex].slots[slotIndex].sessions[sessionIndex];
    var theme = themeOf(session);
    var type = typeOf(session);
    var format = formatOf(session);

    var card = el('article', {
      class: 'cc-session',
      draggable: 'true',
      tabindex: '0',
      'data-day': dayIndex,
      'data-slot': slotIndex,
      'data-session': sessionIndex
    }, [
      el('span', { class: 'cc-session-bar', style: 'background:' + ((theme && theme.color) || '#c9d4e2') }),
      el('div', { class: 'cc-session-body' }, [
        el('h4', { text: session.title || L.newSession }),
        el('p', { class: 'cc-session-meta' }, [
          el('span', {
            class: 'cc-room' + (session.rooms.length ? '' : ' is-missing'),
            text: session.rooms.length ? session.rooms.join(' + ') : L.noRoom
          }),
          type ? el('span', { class: 'cc-tag', style: 'background:' + type.color, text: type.label }) : null,
          format ? el('span', { class: 'cc-tag cc-tag--ghost', text: format.label }) : null
        ]),
        el('p', { class: 'cc-session-people' }, [
          el('span', {
            class: session.speakers.length ? '' : 'is-missing',
            text: session.speakers.length ? session.speakers.join(', ') : L.noSpeakers
          })
        ]),
        el('p', { class: 'cc-session-flags' }, [
          el('span', {
            class: 'cc-flag' + (session.abstract ? ' is-ok' : ' is-missing'),
            text: session.abstract ? L.hasAbstract : L.noAbstract
          }),
          theme ? el('span', { class: 'cc-flag', text: theme.label }) : null
        ])
      ]),
      el('div', { class: 'cc-session-actions' }, [
        el('button', { type: 'button', class: 'cc-icon-button', title: L.duplicate, text: '⧉', onclick: function (event) {
          event.stopPropagation();
          var copy = JSON.parse(JSON.stringify(session));
          data.days[dayIndex].slots[slotIndex].sessions.splice(sessionIndex + 1, 0, copy);
          markDirty();
          render();
        } }),
        el('button', { type: 'button', class: 'cc-icon-button cc-icon-button--danger', title: L.remove, text: '×', onclick: function (event) {
          event.stopPropagation();
          if (!window.confirm(L.confirmSession)) { return; }
          data.days[dayIndex].slots[slotIndex].sessions.splice(sessionIndex, 1);
          if (ui.open) { ui.open = null; closeDrawer(); }
          markDirty();
          render();
        } })
      ])
    ]);

    card.addEventListener('click', function () { openDrawer(dayIndex, slotIndex, sessionIndex); });
    card.addEventListener('keydown', function (event) {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        openDrawer(dayIndex, slotIndex, sessionIndex);
      }
    });
    card.addEventListener('dragstart', function (event) {
      event.dataTransfer.effectAllowed = 'move';
      event.dataTransfer.setData('text/plain', JSON.stringify({ d: dayIndex, s: slotIndex, i: sessionIndex }));
      card.classList.add('is-dragging');
    });
    card.addEventListener('dragend', function () { card.classList.remove('is-dragging'); });

    if (ui.open && ui.open.d === dayIndex && ui.open.s === slotIndex && ui.open.i === sessionIndex) {
      card.classList.add('is-open');
    }

    return card;
  }

  function slotCard(dayIndex, slotIndex) {
    var day = data.days[dayIndex];
    var slot = day.slots[slotIndex];
    var isBreak = slot.type === 'break';

    var durationNode = el('span', { class: 'cc-slot-duration', text: duration(slot) });
    var timeChanged = function () {
      durationNode.textContent = duration(slot);
      markDirty();
    };

    var head = el('header', { class: 'cc-slot-head' }, [
      labelled(L.from, input('time', slot.start, function (value) { slot.start = value; timeChanged(); }), null, 'cc-time'),
      labelled(L.to, input('time', slot.end, function (value) { slot.end = value; timeChanged(); }), null, 'cc-time'),
      durationNode,
      labelled('', select(slot.type, [
        { id: 'sessions', label: L.sessions },
        { id: 'break', label: L.break }
      ], function (value) {
        slot.type = value;
        if (value === 'break') {
          slot.break = slot.break || 'coffee';
          slot.span = slot.span || 'all';
          slot.title = slot.title || L.break;
        }
        markDirty();
        render();
      }), null, 'cc-slot-kind'),
      el('span', { class: 'cc-spacer' }),
      el('button', { type: 'button', class: 'cc-icon-button', title: L.moveUp, text: '↑', disabled: slotIndex === 0, onclick: function () {
        day.slots.splice(slotIndex - 1, 0, day.slots.splice(slotIndex, 1)[0]);
        markDirty();
        render();
      } }),
      el('button', { type: 'button', class: 'cc-icon-button', title: L.moveDown, text: '↓', disabled: slotIndex === day.slots.length - 1, onclick: function () {
        day.slots.splice(slotIndex + 1, 0, day.slots.splice(slotIndex, 1)[0]);
        markDirty();
        render();
      } }),
      el('button', { type: 'button', class: 'cc-icon-button cc-icon-button--danger', title: L.remove, text: '×', onclick: function () {
        if (!window.confirm(L.confirmSlot)) { return; }
        day.slots.splice(slotIndex, 1);
        markDirty();
        render();
      } })
    ]);

    var body;
    if (isBreak) {
      body = el('div', { class: 'cc-slot-break' }, [
        labelled(L.breakTitle, input('text', slot.title || '', function (value) { slot.title = value; markDirty(); }), null, 'cc-grow'),
        labelled(L.breakKind, select(slot.break || 'coffee', [
          { id: 'registration', label: 'Registrace' },
          { id: 'coffee', label: 'Přestávka na kávu' },
          { id: 'lunch', label: 'Oběd' }
        ], function (value) { slot.break = value; markDirty(); }))
      ]);
    } else {
      var visible = 0;
      body = el('div', { class: 'cc-slot-sessions' }, []);
      slot.sessions.forEach(function (session, index) {
        if (!matchesQuery(session)) { return; }
        visible++;
        body.appendChild(sessionCard(dayIndex, slotIndex, index));
      });
      if (visible === 0) {
        body.appendChild(el('p', { class: 'cc-slot-empty', text: L.noSessions }));
      }
      body.appendChild(el('button', {
        type: 'button',
        class: 'cc-add',
        text: L.addSession,
        onclick: function () {
          slot.sessions.push({ title: '', rooms: [], theme: '', speakers: [], format: '', type: '', abstract: '' });
          markDirty();
          render();
          openDrawer(dayIndex, slotIndex, slot.sessions.length - 1);
        }
      }));
    }

    var card = el('section', { class: 'cc-slot' + (isBreak ? ' cc-slot--break' : '') }, [head, body]);

    // Dropping a session onto a slot moves it there — the quickest way to fix
    // a session that was planned into the wrong hour.
    card.addEventListener('dragover', function (event) {
      if (isBreak) { return; }
      event.preventDefault();
      card.classList.add('is-drop');
    });
    card.addEventListener('dragleave', function () { card.classList.remove('is-drop'); });
    card.addEventListener('drop', function (event) {
      card.classList.remove('is-drop');
      if (isBreak) { return; }
      event.preventDefault();
      var from;
      try { from = JSON.parse(event.dataTransfer.getData('text/plain')); } catch (error) { return; }
      if (from.d === dayIndex && from.s === slotIndex) { return; }
      var moved = data.days[from.d].slots[from.s].sessions.splice(from.i, 1)[0];
      if (!moved) { return; }
      slot.sessions.push(moved);
      ui.open = null;
      closeDrawer();
      markDirty();
      render();
    });

    return card;
  }

  function renderSchedule() {
    var day = data.days[ui.day];
    if (!day) {
      ui.day = 0;
      day = data.days[0];
    }

    mount.appendChild(renderDayTabs());
    if (!day) { return; }

    mount.appendChild(renderDayMeta(day));

    if (ui.query !== '') {
      mount.appendChild(el('p', { class: 'cc-filter-note', text: L.filtered }));
    }

    var list = el('div', { class: 'cc-slots' }, []);
    day.slots.forEach(function (slot, index) {
      list.appendChild(slotCard(ui.day, index));
    });
    mount.appendChild(list);

    mount.appendChild(el('div', { class: 'cc-slot-adders' }, [
      el('button', { type: 'button', class: 'cc-add', text: L.addSlot, onclick: function () {
        day.slots.push({ id: '', start: '', end: '', type: 'sessions', sessions: [] });
        markDirty();
        render();
      } }),
      el('button', { type: 'button', class: 'cc-add', text: L.addBreak, onclick: function () {
        day.slots.push({ id: '', start: '', end: '', type: 'break', break: 'coffee', title: L.break, span: 'all', sessions: [] });
        markDirty();
        render();
      } })
    ]));
  }

  /* ----------------------------------------------------------------------
   * Session drawer
   * -------------------------------------------------------------------- */

  function closeDrawer() {
    if (drawer) {
      drawer.remove();
      drawer = null;
    }
    document.body.classList.remove('cc-has-drawer');
  }

  function openDrawer(dayIndex, slotIndex, sessionIndex) {
    closeDrawer();
    ui.open = { d: dayIndex, s: slotIndex, i: sessionIndex };
    document.body.classList.add('cc-has-drawer');

    var session = data.days[dayIndex].slots[slotIndex].sessions[sessionIndex];
    var titleNode = el('h2', { text: session.title || L.newSession });

    var rooms = el('div', { class: 'cc-room-picker' }, data.rooms.map(function (room) {
      var checkbox = el('input', { type: 'checkbox', value: room });
      checkbox.checked = session.rooms.indexOf(room) !== -1;
      checkbox.addEventListener('change', function () {
        if (checkbox.checked) {
          if (session.rooms.indexOf(room) === -1) { session.rooms.push(room); }
        } else {
          session.rooms = session.rooms.filter(function (name) { return name !== room; });
        }
        markDirty();
        refreshCard();
      });
      return el('label', { class: 'cc-room-option' }, [checkbox, el('span', { text: room })]);
    }));

    var moveOptions = [];
    data.days.forEach(function (day, di) {
      day.slots.forEach(function (slot, si) {
        if (slot.type === 'break') { return; }
        moveOptions.push({ id: di + ':' + si, label: slotLabel(day, slot) });
      });
    });

    var body = el('div', { class: 'cc-drawer-body' }, [
      labelled(L.title, input('text', session.title, function (value) {
        session.title = value;
        titleNode.textContent = value || L.newSession;
        markDirty();
        refreshCard();
      })),
      el('div', { class: 'cc-drawer-row' }, [
        labelled(L.theme, select(session.theme, data.themes, function (value) {
          session.theme = value;
          markDirty();
          refreshCard();
        }, L.noTheme)),
        labelled(L.type, select(session.type, data.types, function (value) {
          session.type = value;
          markDirty();
          refreshCard();
        }, L.none)),
        labelled(L.format, select(session.format, data.formats, function (value) {
          session.format = value;
          markDirty();
          refreshCard();
        }, L.none))
      ]),
      labelled(L.rooms, rooms),
      labelled(L.speakers, (function () {
        var area = el('textarea', { class: 'cc-input', rows: '4' });
        area.value = session.speakers.join('\n');
        area.addEventListener('input', function () {
          session.speakers = area.value.split('\n').map(function (name) { return name.trim(); })
            .filter(function (name) { return name !== ''; });
          markDirty();
          refreshCard();
        });
        return area;
      }()), L.speakersHint),
      labelled(L.abstract, (function () {
        var area = el('textarea', { class: 'cc-input cc-abstract', rows: '10' });
        area.value = session.abstract || '';
        area.addEventListener('input', function () {
          session.abstract = area.value;
          markDirty();
          refreshCard();
        });
        return area;
      }()), L.abstractHint),
      labelled(L.moveTo, select(dayIndex + ':' + slotIndex, moveOptions, function (value) {
        var parts = value.split(':');
        var target = { d: parseInt(parts[0], 10), s: parseInt(parts[1], 10) };
        if (target.d === dayIndex && target.s === slotIndex) { return; }
        var moved = data.days[dayIndex].slots[slotIndex].sessions.splice(sessionIndex, 1)[0];
        data.days[target.d].slots[target.s].sessions.push(moved);
        ui.day = target.d;
        ui.open = null;
        markDirty();
        closeDrawer();
        render();
      }))
    ]);

    drawer = el('aside', { class: 'cc-drawer', role: 'dialog', 'aria-label': L.title }, [
      el('header', { class: 'cc-drawer-head' }, [
        titleNode,
        el('button', { type: 'button', class: 'cc-icon-button', title: L.close, text: '×', onclick: function () {
          ui.open = null;
          closeDrawer();
          render();
        } })
      ]),
      body
    ]);

    document.body.appendChild(drawer);
    var first = drawer.querySelector('input, textarea, select');
    if (first) { first.focus(); }

    function refreshCard() {
      var selector = '[data-day="' + dayIndex + '"][data-slot="' + slotIndex + '"][data-session="' + sessionIndex + '"]';
      var existing = mount.querySelector(selector);
      if (!existing) { return; }
      var replacement = sessionCard(dayIndex, slotIndex, sessionIndex);
      replacement.classList.add('is-open');
      existing.replaceWith(replacement);
    }
  }

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && drawer) {
      ui.open = null;
      closeDrawer();
      render();
    }
  });

  /* ----------------------------------------------------------------------
   * Setup view — rooms, themes, formats, types
   * -------------------------------------------------------------------- */

  function taxonomyTable(title, key, note, withSoft) {
    var table = el('table', { class: 'cc-table' }, [
      el('thead', {}, [el('tr', {}, [
        el('th', { text: L.id }),
        el('th', { text: L.label }),
        el('th', { text: L.color }),
        el('th', {})
      ])]),
      el('tbody', {}, data[key].map(function (row, index) {
        return el('tr', {}, [
          el('td', {}, [el('code', { text: row.id })]),
          el('td', {}, [input('text', row.label, function (value) { row.label = value; markDirty(); })]),
          el('td', {}, [
            input('color', row.color || '#0d5e9d', function (value) { row.color = value; markDirty(); }),
            withSoft ? input('color', row.softColor || '#d9e2f3', function (value) { row.softColor = value; markDirty(); }) : null
          ]),
          el('td', {}, [el('button', {
            type: 'button', class: 'cc-icon-button cc-icon-button--danger', text: '×', title: L.remove,
            onclick: function () {
              data[key].splice(index, 1);
              markDirty();
              render();
            }
          })])
        ]);
      }))
    ]);

    return el('section', { class: 'cc-panel' }, [
      el('h2', { text: title }),
      note ? el('p', { class: 'description', text: note }) : null,
      table,
      el('button', { type: 'button', class: 'cc-add', text: L.addRow, onclick: function () {
        var name = window.prompt(L.id);
        if (!name) { return; }
        var id = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        if (id === '' || byId(data[key], id)) { return; }
        var row = { id: id, label: name, color: '#0d5e9d' };
        if (withSoft) { row.softColor = '#d9e2f3'; }
        data[key].push(row);
        markDirty();
        render();
      } })
    ]);
  }

  function renderSetup() {
    var roomsArea = el('textarea', { class: 'cc-input', rows: '10' });
    roomsArea.value = data.rooms.join('\n');
    roomsArea.addEventListener('input', function () {
      data.rooms = roomsArea.value.split('\n').map(function (room) { return room.trim(); })
        .filter(function (room) { return room !== ''; });
      markDirty();
    });

    mount.appendChild(el('section', { class: 'cc-panel' }, [
      el('h2', { text: L.rooms }),
      el('p', { class: 'description', text: L.roomsHint }),
      roomsArea
    ]));

    mount.appendChild(taxonomyTable(L.theme, 'themes', L.idLocked, true));
    mount.appendChild(taxonomyTable(L.type, 'types', L.idLocked, false));
    mount.appendChild(taxonomyTable(L.format, 'formats', L.idLocked, false));
  }

  /* ----------------------------------------------------------------------
   * Wiring
   * -------------------------------------------------------------------- */

  function render() {
    mount.textContent = '';
    if (ui.view === 'setup') {
      renderSetup();
    } else {
      renderSchedule();
    }
  }

  if (viewSwitch) {
    viewSwitch.addEventListener('click', function (event) {
      var button = event.target.closest('[data-cc-view]');
      if (!button) { return; }
      ui.view = button.getAttribute('data-cc-view');
      Array.prototype.forEach.call(viewSwitch.children, function (child) {
        child.classList.toggle('is-active', child === button);
      });
      closeDrawer();
      ui.open = null;
      render();
    });
  }

  if (searchInput) {
    searchInput.addEventListener('input', function () {
      ui.query = searchInput.value.trim().toLowerCase();
      render();
    });
  }

  if (form && payload) {
    form.addEventListener('submit', function () {
      payload.value = JSON.stringify(data);
      dirty = false;
    });
  }

  window.addEventListener('beforeunload', function (event) {
    if (!dirty) { return; }
    event.preventDefault();
    event.returnValue = L.leave;
    return L.leave;
  });

  render();
}());
