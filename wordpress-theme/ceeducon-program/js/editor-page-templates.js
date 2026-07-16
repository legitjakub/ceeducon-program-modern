(function (wp) {
  const templates = window.CEEDUCON_PAGE_BLOCK_TEMPLATES || {};
  let didInsert = false;

  if (!wp || !wp.domReady || !wp.data || !wp.blocks || !templates) {
    return;
  }

  const readString = (value) => {
    if (typeof value === 'string') {
      return value;
    }

    if (value && typeof value.raw === 'string') {
      return value.raw;
    }

    if (value && typeof value.rendered === 'string') {
      return value.rendered;
    }

    return '';
  };

  const normalise = (value) =>
    readString(value)
      .toLowerCase()
      .replace(/<[^>]+>/g, '')
      .replace(/&nbsp;/g, ' ')
      .trim();

  const pageKeyFromState = () => {
    const editor = wp.data.select('core/editor');
    if (!editor) {
      return '';
    }

    const postType = editor.getCurrentPostType && editor.getCurrentPostType();
    if (postType && postType !== 'page') {
      return '';
    }

    const template = readString(editor.getEditedPostAttribute('template'));
    const slug = normalise(editor.getEditedPostAttribute('slug'));
    const title = normalise(editor.getEditedPostAttribute('title'));
    const probe = `${template} ${slug} ${title}`;

    if (/front-page|homepage|\bhome\b/.test(probe)) {
      return 'home';
    }

    if (/programme|program/.test(probe)) {
      return 'programme';
    }

    if (/practical/.test(probe)) {
      return 'practical';
    }

    if (/speaker/.test(probe)) {
      return 'speakers';
    }

    if (/contact/.test(probe)) {
      return 'contact';
    }

    if (/media|press/.test(probe)) {
      return 'media';
    }

    if (/about/.test(probe)) {
      return 'about';
    }

    return '';
  };

  const insertTemplateIfEmpty = () => {
    if (didInsert) {
      return true;
    }

    const blockEditor = wp.data.select('core/block-editor');
    const editor = wp.data.select('core/editor');
    const dispatcher = wp.data.dispatch('core/block-editor');
    const notices = wp.data.dispatch('core/notices');

    if (!blockEditor || !editor || !dispatcher) {
      return false;
    }

    const blocks = blockEditor.getBlocks();
    const content = readString(editor.getEditedPostAttribute('content')).trim();
    const hasRealBlocks = blocks.some((block) => {
      const blockContent = normalise(block.attributes && block.attributes.content);
      return !['core/freeform', 'core/paragraph'].includes(block.name) || blockContent !== '';
    });

    if (hasRealBlocks || content !== '') {
      return true;
    }

    const key = pageKeyFromState();
    const template = key ? templates[key] : '';
    if (!template) {
      return false;
    }

    const parsedBlocks = wp.blocks.parse(template);
    if (!parsedBlocks.length) {
      return false;
    }

    dispatcher.insertBlocks(parsedBlocks);
    didInsert = true;

    if (notices && notices.createInfoNotice) {
      notices.createInfoNotice(
        'CEEDUCON bloky byly vloženy do prázdné stránky. Uprav texty přímo v blocích a klikni na Uložit/Aktualizovat.',
        { type: 'snackbar', isDismissible: true }
      );
    }

    return true;
  };

  wp.domReady(() => {
    let attempts = 0;
    const unsubscribe = wp.data.subscribe(() => {
      attempts += 1;

      if (insertTemplateIfEmpty() || attempts > 80) {
        unsubscribe();
      }
    });

    insertTemplateIfEmpty();
  });
})(window.wp);
