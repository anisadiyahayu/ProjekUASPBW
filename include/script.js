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



function openEditModal(id, nama, deskripsi) {
  document.getElementById("edit_id").value = id;
  document.getElementById("edit_nama").value = nama;
  document.getElementById("edit_deskripsi").value = deskripsi;

  document.getElementById("editModal").classList.remove("hidden");
  document.getElementById("editModal").classList.add("flex");
}

function closeEditModal() {
  document.getElementById("editModal").classList.remove("flex");
  document.getElementById("editModal").classList.add("hidden");
}

function openTambahModal() {
  document.getElementById("tambahModal").classList.remove("hidden");
  document.getElementById("tambahModal").classList.add("flex");
}

function closeTambahModal() {
  document.getElementById("tambahModal").classList.remove("flex");
  document.getElementById("tambahModal").classList.add("hidden");
}

// Jalankan pemicu Feather Icons
feather.replace();

// Fungsi Modal Tambah
function openTambahUserModal() {
  const modal = document.getElementById("tambahUserModal");
  modal.classList.remove("hidden");
  modal.classList.add("flex");
}
function closeTambahUserModal() {
  const modal = document.getElementById("tambahUserModal");
  modal.classList.remove("flex");
  modal.classList.add("hidden");
}

// Fungsi Modal Edit Dinamis
function openEditUserModal(
  id,
  npm,
  nama,
  role,
  kelas,
  angkatan,
  no_hp,
  status,
) {
  // Set action form dinamis mengarah ke edit.php bawaan dengan parameter ID via GET
  document.getElementById("formEditUser").action = "edit.php?id=" + id;

  // Isi value elemen form berdasarkan data baris tabel yang di-klik
  document.getElementById("edit_npm").value = npm;
  document.getElementById("edit_nama").value = nama;
  document.getElementById("edit_role").value = role;
  document.getElementById("edit_kelas").value = kelas;
  document.getElementById("edit_angkatan").value = angkatan;
  document.getElementById("edit_no_hp").value = no_hp;
  document.getElementById("edit_status").value = status;

  // Munculkan Modal
  const modal = document.getElementById("editUserModal");
  modal.classList.remove("hidden");
  modal.classList.add("flex");
}
function closeEditUserModal() {
  const modal = document.getElementById("editUserModal");
  modal.classList.remove("flex");
  modal.classList.add("hidden");
}
