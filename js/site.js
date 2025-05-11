document.addEventListener('DOMContentLoaded', function () {
  const toggle = document.querySelector('#toggle-navigation') || document.querySelector('.menu-toggle');
  const menu = document.querySelector('#menu') || document.querySelector('#menu-web');

  if (toggle && menu) {
      toggle.addEventListener('click', () => {
          menu.classList.toggle('show');
      });
  }
});
