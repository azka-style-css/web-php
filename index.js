function setActiveNav(url) {
  document.querySelectorAll(".nav-item").forEach((item) => {
    item.classList.toggle("active", item.dataset.page === url);
  });
}

function currentPage() {
  return document.querySelector(".nav-item.active")?.dataset.page || "home.php";
}

function loadContent(url, containerId = "main-content") {
  const container = document.getElementById(containerId);
  if (!container) return;

  container.innerHTML = "<p>Loading data...</p>";
  setActiveNav(url);
  fetch(url)
    .then((response) => {
      if (!response.ok) throw new Error("Halaman gagal dimuat.");
      return response.text();
    })
    .then((html) => {
      container.innerHTML = html;
      applySavedSettings();
    })
    .catch((error) => {
      container.innerHTML = `<p>${escapeHtml(error.message)}</p>`;
    });
}

function escapeHtml(value) {
  const element = document.createElement("span");
  element.textContent = value;
  return element.innerHTML;
}

function setFontSize(size) {
  const selectedSize = ["small", "normal", "large"].includes(size) ? size : "normal";
  const sizes = { small: "14px", normal: "16px", large: "18px" };
  document.documentElement.style.fontSize = sizes[selectedSize];
  localStorage.setItem("dashboard-font-size", selectedSize);
  document.querySelectorAll("[data-font-size]").forEach((button) => {
    button.classList.toggle("selected", button.dataset.fontSize === selectedSize);
  });
}

function setLayout(layout) {
  const selectedLayout = layout === "compact" ? "compact" : "normal";
  document.body.dataset.layout = selectedLayout;
  localStorage.setItem("dashboard-layout", selectedLayout);
  document.querySelectorAll("[data-layout]").forEach((button) => {
    button.classList.toggle("selected", button.dataset.layout === selectedLayout);
  });
}

function applySavedSettings() {
  setFontSize(localStorage.getItem("dashboard-font-size"));
  setLayout(localStorage.getItem("dashboard-layout"));
}

function resetSettings() {
  localStorage.removeItem("dashboard-font-size");
  localStorage.removeItem("dashboard-layout");
  applySavedSettings();
}

function getCrudFormData(form) {
  return Object.fromEntries(new FormData(form).entries());
}

function validateCrudForm(form, data) {
  if (!form.checkValidity()) {
    form.reportValidity();
    return "Lengkapi semua kolom dengan benar.";
  }
  if (form.dataset.resource === "items") {
    if (!Number.isFinite(Number(data.price)) || Number(data.price) < 0) return "Harga harus angka nol atau lebih.";
    if (!Number.isInteger(Number(data.quantity)) || Number(data.quantity) < 0) return "Stok harus bilangan bulat nol atau lebih.";
  }
  if (form.dataset.resource === "employees" && data.username.trim().length < 3) return "Username minimal 3 karakter.";
  return "";
}

async function sendCrudRequest(form, action, extra = {}) {
  const payload = { ...getCrudFormData(form), ...extra, resource: form.dataset.resource, action };
  const response = await fetch("../../api.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
    body: new URLSearchParams(payload),
  });
  const result = await response.json();
  if (!response.ok || !result.success) throw new Error(result.message || "Perubahan gagal.");
  return result;
}

function resetCrudForm(form) {
  form.reset();
  form.querySelector("[name=id]").value = "";
  form.querySelector("[data-submit-label]").textContent = form.dataset.resource === "items" ? "Add item" : "Add employee";
  form.querySelector("[data-cancel-edit]").hidden = true;
  form.querySelector(".crud-message").textContent = "";
}

function startEdit(row) {
  const form = row.closest(".content-area")?.querySelector(".crud-form");
  if (!form) return;
  form.querySelector("[name=id]").value = row.dataset.id;
  Object.keys(row.dataset).forEach((key) => {
    const field = form.querySelector(`[name="${key}"]`);
    if (field) field.value = row.dataset[key];
  });
  form.querySelector("[data-submit-label]").textContent = "Save changes";
  form.querySelector("[data-cancel-edit]").hidden = false;
  form.scrollIntoView({ behavior: "smooth", block: "start" });
}

async function deleteRecord(button) {
  const form = button.closest(".content-area")?.querySelector(".crud-form");
  if (!form || !window.confirm("Hapus data ini? Tindakan ini tidak dapat dibatalkan.")) return;
  try {
    await sendCrudRequest(form, "delete", { id: button.dataset.deleteId });
    loadContent(currentPage());
  } catch (error) {
    form.querySelector(".crud-message").textContent = error.message;
  }
}

document.addEventListener("DOMContentLoaded", () => {
  applySavedSettings();
  document.addEventListener("click", (event) => {
    const navItem = event.target.closest(".nav-item");
    const pageButton = event.target.closest("[data-page-action]");
    const fontButton = event.target.closest("[data-font-size]");
    const layoutButton = event.target.closest("[data-layout]");
    const resetButton = event.target.closest("[data-reset-settings]");
    const refreshButton = event.target.closest("[data-refresh-page]");
    const editButton = event.target.closest("[data-edit-row]");
    const deleteButton = event.target.closest("[data-delete-id]");
    const cancelButton = event.target.closest("[data-cancel-edit]");

    if (navItem) {
      event.preventDefault();
      loadContent(navItem.dataset.page);
    }
    if (pageButton) loadContent(pageButton.dataset.pageAction);
    if (fontButton) setFontSize(fontButton.dataset.fontSize);
    if (layoutButton) setLayout(layoutButton.dataset.layout);
    if (resetButton) resetSettings();
    if (refreshButton) loadContent(currentPage());
    if (editButton) startEdit(editButton.closest("tr"));
    if (deleteButton) deleteRecord(deleteButton);
    if (cancelButton) resetCrudForm(cancelButton.closest(".crud-form"));
  });

  document.addEventListener("submit", async (event) => {
    const form = event.target.closest(".crud-form");
    if (!form) return;
    event.preventDefault();
    const data = getCrudFormData(form);
    const message = form.querySelector(".crud-message");
    const validationMessage = validateCrudForm(form, data);
    if (validationMessage) {
      message.textContent = validationMessage;
      return;
    }
    try {
      await sendCrudRequest(form, data.id ? "update" : "create");
      loadContent(currentPage());
    } catch (error) {
      message.textContent = error.message;
    }
  });

  loadContent("home.php");
});
