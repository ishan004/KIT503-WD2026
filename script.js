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

  // Registration form validation
  const registrationForm = document.getElementById("registrationForm")

  if (registrationForm) {
    registrationForm.addEventListener("submit", function (event) {
      const fname = document.getElementById("fname").value.trim()
      const lname = document.getElementById("lname").value.trim()
      const email = document.getElementById("email").value.trim()
      const password = document.getElementById("psw").value
      const confirmPassword = document.getElementById("pswConfirm").value
      const researchSelected = document.querySelector(
        'input[name="research"]:checked',
      )
      const terms = document.getElementById("terms")

      if (!fname || !lname || !email || !password || !confirmPassword) {
        alert("Please fill all fields.")
        event.preventDefault()
        return
      }

      const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,}$/i
      if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.")
        event.preventDefault()
        return
      }

      if (password !== confirmPassword) {
        alert("Passwords do not match.")
        event.preventDefault()
        return
      }

      if (password.length < 7 || password.length > 12) {
        alert("Password must be between 7 and 12 characters.")
        event.preventDefault()
        return
      }

      if (!/\d/.test(password)) {
        alert("Password must contain at least one number.")
        event.preventDefault()
        return
      }

      if (!researchSelected) {
        alert("Please select whether you are a research student.")
        event.preventDefault()
        return
      }

      if (!terms.checked) {
        alert("You must agree to the terms and conditions.")
        event.preventDefault()
        return
      }
    })
  }
})
