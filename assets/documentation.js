/** Enhance local document links with a native dialog; ordinary links remain usable without it. @returns {void} */
(() => {
  let opener;
  /** Return keyboard focus to the link that opened the dialog. @returns {void} */
  function restoreFocus() { opener?.focus(); }
  /** Handle local document, close and backdrop clicks. @param {MouseEvent} event User click. @returns {void} */
  function handleDocumentClick(event) {
    const link = event.target.closest('[data-blp-document]');
    if (link) {
      const dialog = document.getElementById('blp-document-' + link.dataset.blpDocument);
      if (!dialog || typeof dialog.showModal !== 'function') return;
      event.preventDefault();
      opener = link;
      dialog.showModal();
      dialog.addEventListener('close', restoreFocus, { once: true });
    }
    const button = event.target.closest('[data-blp-close]');
    if (button) button.closest('dialog')?.close();
    const dialog = event.target.closest('.blp-document-dialog');
    if (dialog?.open && event.target === dialog) {
      const rect = dialog.getBoundingClientRect();
      if (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom) dialog.close();
    }
  }
  document.addEventListener('click', handleDocumentClick);
})();
