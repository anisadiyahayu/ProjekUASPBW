document.addEventListener("DOMContentLoaded", () => {
  const sidebar = document.getElementById("sidebar");
  const content = document.getElementById("main-content");
  const toggle = document.getElementById("sidebar-toggle");

  const savedState = localStorage.getItem("sidebar");

  if (savedState === "collapsed") {
    sidebar.classList.add("sidebar-collapsed");
    content.classList.add("main-expanded");
  }

  if (window.innerWidth <= 768) {
    toggle.addEventListener("click", () => {
      sidebar.classList.toggle("show");
    });
  }
  
  toggle.addEventListener("click", () => {
    sidebar.classList.toggle("sidebar-collapsed");
    content.classList.toggle("main-expanded");

    if (sidebar.classList.contains("sidebar-collapsed")) {
      localStorage.setItem("sidebar", "collapsed");
    } else {
      localStorage.setItem("sidebar", "expanded");
    }
  });
});
