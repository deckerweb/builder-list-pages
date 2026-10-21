/** Native dialog enhancement; document links and close forms work without it. */
(() => {
  if (document.documentElement.dataset.blpDocuments === 'ready') return;
  document.documentElement.dataset.blpDocuments = 'ready';
  const native = window.HTMLDialogElement?.prototype;
  if (!native?.showModal || !native?.close) return;
  let opener;
  /** Hide closed dialogs and return focus. @param {Event} event Native close event. @returns {void} */
  function closed(event) { event.target.hidden = true; opener?.focus(); }
  /** Open documents only after successful native enhancement. @param {MouseEvent} event User interaction. @returns {void} */
  function click(event) {
    const link = event.target.closest('[data-blp-document]');
    if (link) {
      const dialog = document.getElementById('blp-document-' + link.dataset.blpDocument);
      if (!dialog) return;
      try {
        dialog.hidden = false;
        native.showModal.call(dialog);
        if (!dialog.open) { dialog.hidden = true; return; }
        opener = link;
        dialog.addEventListener('close', closed, {once:true});
        event.preventDefault();
      } catch { dialog.hidden = true; }
      return;
    }
    const button = event.target.closest('[data-blp-close]');
    const dialog = event.target.closest('.blp-document-dialog');
    if (button && dialog?.open) { native.close.call(dialog); return; }
    if (dialog?.open && event.target === dialog) {
      const rect = dialog.getBoundingClientRect();
      if (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom) native.close.call(dialog);
    }
  }
  document.addEventListener('click', click, true);
})();
