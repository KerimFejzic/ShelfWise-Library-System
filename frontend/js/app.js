// Milestone 1: local static templates and sample data only. No backend calls yet.
const validViews = ['dashboard','books','categories','members','loans','reservations','fines','profile','login','register'];
const app = document.querySelector('#app');
const navigation = document.querySelector('#navigation');
const currentView = document.querySelector('#currentView');
const menuButton = document.querySelector('#menuButton');

function currentRoute() {
  const route = window.location.hash.slice(1).toLowerCase();
  return validViews.includes(route) ? route : 'dashboard';
}

async function renderView() {
  const view = currentRoute();
  document.querySelectorAll('[data-view]').forEach(a => a.classList.toggle('active', a.dataset.view === view));
  currentView.textContent = view.charAt(0).toUpperCase() + view.slice(1);
  document.querySelector('.sidebar').classList.remove('open');
  try {
    const response = await fetch(`views/${view}.html`);
    if (!response.ok) throw new Error('Could not load template');
    app.innerHTML = await response.text();
    attachDemoActions();
  } catch (error) {
    app.innerHTML = '<div class="panel">Could not load the view. Please run this project through a local HTTP server (see README).</div>';
  }
}
function attachDemoActions() {
  app.querySelectorAll('[data-demo-action]').forEach(button => button.addEventListener('click', () => {
    window.alert('This is a static Milestone 1 preview. Saving and editing will be implemented in later milestones.');
  }));
  app.querySelectorAll('form').forEach(form => form.addEventListener('submit', event => {
    event.preventDefault();
    window.alert('Form preview only. No information is sent or saved in Milestone 1.');
  }));
  app.querySelectorAll('[data-search]').forEach(input => input.addEventListener('input', () => {
    const term = input.value.toLowerCase();
    app.querySelectorAll('[data-filter]').forEach(row => { row.hidden = !row.textContent.toLowerCase().includes(term); });
  }));
}
menuButton.addEventListener('click', () => document.querySelector('.sidebar').classList.toggle('open'));
window.addEventListener('hashchange', renderView);
renderView();
