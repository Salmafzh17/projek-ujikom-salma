document.addEventListener("DOMContentLoaded", function () {
  const authWrapper = document.querySelector(".auth-wrapper");
  const loginContainer = document.querySelector(".login-container");
  const registerContainer = document.querySelector(".register-container");
  const toggleLinks = document.querySelectorAll(".toggle-form");

  toggleLinks.forEach((link) => {
    link.addEventListener("click", function (event) {
      event.preventDefault();

      loginContainer.classList.toggle("move-out-left");
      registerContainer.classList.toggle("move-in-right");
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const tasks = document.querySelectorAll("li");

  tasks.forEach((task) => {
    task.addEventListener("mouseenter", function () {
      this.style.transform = "scale(1.05)";
      this.style.transition = "0.3s";
    });

    task.addEventListener("mouseleave", function () {
      this.style.transform = "scale(1)";
    });
  });
});

// JavaScript untuk mengatur deadline berdasarkan kategori
document.getElementById("task_type").addEventListener("change", function () {
  let deadlineInput = document.getElementById("deadline");
  if (this.value === "list_tugas") {
    deadlineInput.setAttribute("required", "required"); // Deadline wajib diisi
  } else {
    deadlineInput.removeAttribute("required"); // Deadline opsional
  }
});

// Hapus notifikasi setelah 3 detik
setTimeout(function () {
  let notif = document.querySelector(".notification");
  if (notif) {
    notif.style.opacity = "0";
    setTimeout(() => notif.remove(), 500);
  }
}, 3000);
