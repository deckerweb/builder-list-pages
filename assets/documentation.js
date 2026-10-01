/* Progressive enhancement: document links also work without JavaScript. */
(() => {
  let opener;
  document.addEventListener('click', (event) => {
    const link = event.target.closest('[data-blp-document]');
    if (link) {
      const dialog = document.getElementById('blp-document-' + link.dataset.blpDocument);
      if (!dialog || typeof dialog.showModal !== 'function') return;
      event.preventDefault();
      opener = link;
      dialog.showModal();
      dialog.addEventListener('close', () => opener?.focus(), { once: true });
    }
    const button = event.target.closest('[data-blp-close]');
    if (button) button.closest('dialog')?.close();
  });
})();
