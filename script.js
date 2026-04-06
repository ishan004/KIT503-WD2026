document.addEventListener("DOMContentLoaded", function () {
  // Highlight current nav link
  const navLinks = document.querySelectorAll(".nav-link")
  const currentPage = window.location.pathname.split("/").pop()

  for (let i = 0; i < navLinks.length; i++) {
    const link = navLinks[i]
    const linkPage = link.getAttribute("href")

    if (linkPage === currentPage) {
      link.classList.add("active")
    }
  }

  // Toggle abstract box on details page
  const abstractBtn = document.getElementById("toggleAbstractBtn")
  const abstractBox = document.getElementById("abstractBox")

  if (abstractBtn && abstractBox) {
    abstractBtn.addEventListener("click", function () {
      abstractBox.classList.toggle("hidden")
    })
  }

  // Delete confirmation for any delete form/button
  const deleteForms = document.querySelectorAll(".delete-form")
  for (let i = 0; i < deleteForms.length; i++) {
    deleteForms[i].addEventListener("submit", function (event) {
      const ok = confirm("Are you sure you want to delete this submission?")
      if (!ok) {
        event.preventDefault()
      }
    })
  }
})
