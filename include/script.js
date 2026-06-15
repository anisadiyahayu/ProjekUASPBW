document.addEventListener("DOMContentLoaded", () => {

  const sidebar = document.getElementById("sidebar");
  const content = document.getElementById("main-content");
  const toggle = document.getElementById("sidebar-toggle");
  const menuIcon = document.getElementById("menu-icon");

  const savedState = localStorage.getItem("sidebar");

  function updateIcon() {

    if (sidebar.classList.contains("sidebar-collapsed")) {
      menuIcon.innerHTML = `
        <line x1="4" y1="6" x2="20" y2="6"></line>
        <line x1="4" y1="12" x2="20" y2="12"></line>
        <line x1="4" y1="18" x2="20" y2="18"></line>
      `;

    } else {

      menuIcon.innerHTML = `
        <path d="M18 6 6 18"></path>
        <path d="m6 6 12 12"></path>
      `;

    }

  }

  if (savedState === "collapsed") {
    sidebar.classList.add("sidebar-collapsed");
    content.classList.add("main-expanded");
  }

  updateIcon();

  toggle.addEventListener("click", () => {

    if (window.innerWidth <= 768) {

      sidebar.classList.toggle("show");

    } else {

      sidebar.classList.toggle("sidebar-collapsed");
      content.classList.toggle("main-expanded");

      if (sidebar.classList.contains("sidebar-collapsed")) {
        localStorage.setItem("sidebar", "collapsed");
      } else {
        localStorage.setItem("sidebar", "expanded");
      }

      updateIcon();
    }

  });

});