document.body.classList.add('js');

const toggle = document.querySelector('#toggle-navigation');
const menu = document.querySelector('#menu');
const menuItems = document.querySelectorAll('#menu li a');

if (toggle) {
  toggle.addEventListener('click', function () {
    if (menu.classList.contains('is-active')) {
      this.setAttribute('aria-expanded', 'false');
      menu.classList.remove('is-active');
    } else {
      menu.classList.add('is-active');
      this.setAttribute('aria-expanded', 'true');
    }
  });
}


// document.body.classList.add('js');

// var toggle = document.querySelector('#toggle-navigation');
// var menu = document.querySelector('#menu');
// var menuItems = document.querySelectorAll('#menu li a');

// if (toggle) {
//   toggle.addEventListener('click', function () {
//     if (menu.classList.contains('is-active')) {
//       this.setAttribute('aria-expanded', 'false');
//       menu.classList.remove('is-active');
//     } else {
//       menu.classList.add('is-active');
//       this.setAttribute('aria-expanded', 'true');
//     }
//   });
// }