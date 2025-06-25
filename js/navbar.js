
  const navbar = document.querySelector('.navbar-scroll');
  const dropdownMenu = document.getElementById('dropdownMenu');
  const icon = document.querySelector('.menu-icon');

  function toggleMenu() {
    dropdownMenu.classList.toggle('show');
  }

  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  window.addEventListener('click', function (e) {
    if (!dropdownMenu.contains(e.target) && !icon.contains(e.target)) {
      dropdownMenu.classList.remove('show');
    }
  });

