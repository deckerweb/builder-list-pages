/* The read-only diagnostics remain selectable without JavaScript. */
document.addEventListener('DOMContentLoaded', () => {
 const field = document.getElementById('blp-diagnostics');
 if (field) field.addEventListener('click', () => field.select());
});
