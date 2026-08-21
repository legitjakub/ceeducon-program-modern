/**
 * Adds and removes rows in the programme editor. Everything submits as plain
 * form arrays, so the page still works for editing existing rows if this
 * script never loads — only the add and remove buttons need it.
 */
(function () {
  "use strict";

  /** Rewrites the [n] indices of every field inside a container after a change. */
  function reindex(container, pattern, group) {
    var rows = container.querySelectorAll(":scope > [data-cp-row]");
    Array.prototype.forEach.call(rows, function (row, i) {
      Array.prototype.forEach.call(row.querySelectorAll("[name]"), function (field) {
        field.name = field.name.replace(pattern, group + "[" + i + "]");
      });
    });
  }

  function reindexSessions(wrap) {
    var rows = wrap.querySelectorAll(":scope > .cp-session");
    Array.prototype.forEach.call(rows, function (row, i) {
      Array.prototype.forEach.call(row.querySelectorAll("[name]"), function (field) {
        field.name = field.name.replace(/\[sessions\]\[\d+\]/, "[sessions][" + i + "]");
      });
    });
  }

  function blankSession(wrap) {
    var existing = wrap.querySelector(".cp-session");
    if (!existing) return null;
    var clone = existing.cloneNode(true);
    Array.prototype.forEach.call(clone.querySelectorAll("input, textarea, select"), function (f) {
      if (f.type === "checkbox" || f.type === "radio") f.checked = false;
      else if (f.tagName === "SELECT") f.selectedIndex = 0;
      else f.value = "";
    });
    return clone;
  }

  document.addEventListener("click", function (event) {
    var addSession = event.target.closest("[data-cp-add-session]");
    if (addSession) {
      event.preventDefault();
      var wrap = addSession.closest("[data-cp-sessions]");
      var row = blankSession(wrap);
      if (!row) {
        window.alert(
          "V tomto bloku zatím není žádná přednáška, ze které by šlo vyjít. Přidejte ji v bloku, kde už nějaká je, nebo uložte a načtěte stránku znovu."
        );
        return;
      }
      wrap.insertBefore(row, addSession);
      reindexSessions(wrap);
      var first = row.querySelector('input[type="text"]');
      if (first) first.focus();
      return;
    }

    var addSlot = event.target.closest("[data-cp-add-slot]");
    if (addSlot) {
      event.preventDefault();
      var day = addSlot.closest(".cp-day");
      var slots = day.querySelector("[data-cp-slots]");
      var last = slots.querySelector(".cp-slot:last-of-type");
      if (!last) return;
      var clone = last.cloneNode(true);
      Array.prototype.forEach.call(clone.querySelectorAll("input, textarea"), function (f) {
        if (f.type === "checkbox" || f.type === "radio") f.checked = false;
        else f.value = "";
      });
      // keep a single empty session row so the block is immediately usable
      var wrap = clone.querySelector("[data-cp-sessions]");
      if (wrap) {
        var rows = wrap.querySelectorAll(".cp-session");
        Array.prototype.forEach.call(rows, function (r, i) {
          if (i > 0) r.remove();
        });
      }
      slots.appendChild(clone);
      reindex(slots, /\[slots\]\[\d+\]/, "[slots]");
      Array.prototype.forEach.call(slots.querySelectorAll("[data-cp-sessions]"), reindexSessions);
      var t = clone.querySelector('input[type="time"]');
      if (t) t.focus();
      return;
    }

    var remove = event.target.closest("[data-cp-remove]");
    if (remove) {
      event.preventDefault();
      var target = remove.closest("[data-cp-row]");
      if (!target) return;
      var label = target.classList.contains("cp-session") ? "přednášku" : "celý časový blok";
      if (!window.confirm("Opravdu odebrat " + label + "? Změna se projeví až po uložení.")) return;
      var parent = target.parentElement;
      target.remove();
      if (parent.hasAttribute("data-cp-sessions")) reindexSessions(parent);
      else if (parent.hasAttribute("data-cp-slots")) {
        reindex(parent, /\[slots\]\[\d+\]/, "[slots]");
        Array.prototype.forEach.call(parent.querySelectorAll("[data-cp-sessions]"), reindexSessions);
      }
    }
  });

  /** Switching a block between sessions and break shows the matching fields. */
  document.addEventListener("change", function (event) {
    var select = event.target.closest("[data-cp-type]");
    if (!select) return;
    var slot = select.closest(".cp-slot");
    var isBreak = select.value === "break";
    slot.querySelector(".cp-break-fields").hidden = !isBreak;
    slot.querySelector("[data-cp-sessions]").hidden = isBreak;
    slot.classList.toggle("cp-slot--break", isBreak);
    slot.classList.toggle("cp-slot--sessions", !isBreak);
  });
})();
