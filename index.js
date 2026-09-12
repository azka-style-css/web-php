function setActiveNav(url) {
  document.querySelectorAll(".nav-item").forEach((item) => {
    item.classList.toggle("active", item.dataset.page === url);
  });
}

function loadContent(url, containerId = "main-content") {
  const container = document.getElementById(containerId);

  if (!container) return;

  container.innerHTML = "<p>Loading data...</p>";
  setActiveNav(url);

  fetch(url)
    .then((response) => response.text())
    .then((html) => {
      container.innerHTML = html;
    })
    .catch((error) => {
      container.innerHTML = '<p style="color: red;">Gagal memuat konten.</p>';
    });
}

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".nav-item").forEach((item) => {
    item.addEventListener("click", (event) => {
      event.preventDefault();
      loadContent(item.dataset.page);
    });
  });

  loadContent("home.php");
});
