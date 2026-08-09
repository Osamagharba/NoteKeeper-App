const titleInput = document.querySelector('.note-title-input');
const bodyInput = document.querySelector('.note-body-input');
const msgArea = document.querySelector('.msg');
const errorMsg = document.querySelector('.error-msg');
const flashMsg = document.querySelector('.flash');
const noteForm = document.querySelector('.note-form');

function getText(element) {
  return element ? element.textContent.trim() : '';
}

function isVisible(element) {
  if (!element) return false;
  const style = window.getComputedStyle(element);
  return style.display !== 'none' && style.visibility !== 'hidden';
}

function updateMsgArea() {
  if (!msgArea) return;

  const hasFlashMessage = !!(flashMsg && getText(flashMsg) && isVisible(flashMsg));
  const hasErrorText = !!(errorMsg && getText(errorMsg) && isVisible(errorMsg));

  msgArea.classList.remove('has-content', 'success', 'error');

  if (hasFlashMessage && flashMsg.classList.contains('success')) {
    msgArea.classList.add('has-content', 'success');
  } else if ((hasFlashMessage && flashMsg.classList.contains('error')) || hasErrorText) {
    msgArea.classList.add('has-content', 'error');
  }
}

function assignNoteCardColors() {
  const noteCards = document.querySelectorAll('.note-card');
  const colorClasses = ['color-blue', 'color-purple', 'color-green', 'color-amber'];

  noteCards.forEach((card, index) => {
    card.classList.remove(...colorClasses);
    card.classList.add(colorClasses[index % colorClasses.length]);
  });
}

if (noteForm) {
  noteForm.addEventListener('submit', function (e) {
    if (!titleInput || !bodyInput) return;

    if (titleInput.value.trim() === '') {
      e.preventDefault();
      if (errorMsg) {
        errorMsg.style.display = 'block';
        errorMsg.style.visibility = 'visible';
        errorMsg.textContent = 'Please add a title for your note.';
      }
      if (flashMsg) {
        flashMsg.style.display = 'none';
      }
      updateMsgArea();
      titleInput.focus();
      return;
    }

    if (bodyInput.value.trim() === '') {
      e.preventDefault();
      if (errorMsg) {
        errorMsg.style.display = 'block';
        errorMsg.style.visibility = 'visible';
        errorMsg.textContent = 'Please add your note.';
      }
      if (flashMsg) {
        flashMsg.style.display = 'none';
      }
      updateMsgArea();
      bodyInput.focus();
      return;
    }

    if (errorMsg) {
      errorMsg.style.display = 'none';
      errorMsg.style.visibility = 'hidden';
      errorMsg.textContent = '';
    }

    updateMsgArea();
  });
}

document.addEventListener('DOMContentLoaded', () => {
  updateMsgArea();
  assignNoteCardColors();
});