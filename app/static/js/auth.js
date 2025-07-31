// Authentication System
class AuthSystem {
  constructor() {
    this.currentUser = null
    this.init()
  }

  init() {
    // Check if user is logged in on page load
    this.checkAuthState()
    this.setupEventListeners()
  }

  checkAuthState() {
    const userData = localStorage.getItem("selfmed_user")
    if (userData) {
      this.currentUser = JSON.parse(userData)
      this.updateUIForLoggedInUser()
    }
  }

  setupEventListeners() {
    // Login form
    const loginForm = document.getElementById("loginForm")
    if (loginForm) {
      loginForm.addEventListener("submit", (e) => this.handleLogin(e))
    }

    // Register form
    const registerForm = document.getElementById("registerForm")
    if (registerForm) {
      registerForm.addEventListener("submit", (e) => this.handleRegister(e))
    }

    // Password strength checker
    const registerPassword = document.getElementById("registerPassword")
    if (registerPassword) {
      registerPassword.addEventListener("input", (e) => this.checkPasswordStrength(e.target.value))
    }

    // Confirm password validation
    const confirmPassword = document.getElementById("confirmPassword")
    if (confirmPassword) {
      confirmPassword.addEventListener("input", (e) => this.validatePasswordMatch())
    }
  }

  async handleLogin(e) {
    e.preventDefault()
    const form = e.target
    const formData = new FormData(form)

    const loginData = {
      email: formData.get("email"),
      password: formData.get("password"),
      remember: formData.get("remember"),
    }

    // Show loading state
    this.setFormLoading(form, true)

    try {
      // Simulate API call - Replace with actual backend call
      const response = await this.simulateLogin(loginData)

      if (response.success) {
        this.currentUser = response.user
        localStorage.setItem("selfmed_user", JSON.stringify(response.user))

        // Update UI
        this.updateUIForLoggedInUser()
        closeModal("loginModal")

        // Show success message
        this.showNotification("Login realizado com sucesso!", "success")
      } else {
        this.showNotification(response.message || "Erro ao fazer login", "error")
      }
    } catch (error) {
      console.error("Login error:", error)
      this.showNotification("Erro interno. Tente novamente.", "error")
    } finally {
      this.setFormLoading(form, false)
    }
  }

  async handleRegister(e) {
    e.preventDefault()
    const form = e.target
    const formData = new FormData(form)

    // Validate passwords match
    if (formData.get("password") !== formData.get("confirmPassword")) {
      this.showNotification("As senhas não coincidem", "error")
      return
    }

    const registerData = {
      firstName: formData.get("firstName"),
      lastName: formData.get("lastName"),
      email: formData.get("email"),
      password: formData.get("password"),
    }

    // Show loading state
    this.setFormLoading(form, true)

    try {
      // Simulate API call - Replace with actual backend call
      const response = await this.simulateRegister(registerData)

      if (response.success) {
        this.currentUser = response.user
        localStorage.setItem("selfmed_user", JSON.stringify(response.user))

        // Update UI
        this.updateUIForLoggedInUser()
        closeModal("registerModal")

        // Show success message
        this.showNotification("Conta criada com sucesso!", "success")
      } else {
        this.showNotification(response.message || "Erro ao criar conta", "error")
      }
    } catch (error) {
      console.error("Register error:", error)
      this.showNotification("Erro interno. Tente novamente.", "error")
    } finally {
      this.setFormLoading(form, false)
    }
  }

  // Simulate API calls - Replace with actual backend integration
  async simulateLogin(loginData) {
    return new Promise((resolve) => {
      setTimeout(() => {
        // Simulate successful login
        resolve({
          success: true,
          user: {
            id: 1,
            firstName: "João",
            lastName: "Silva",
            email: loginData.email,
            joinDate: "2024-01-15",
            consultationsCount: 12,
          },
        })
      }, 1500)
    })
  }

  async simulateRegister(registerData) {
    return new Promise((resolve) => {
      setTimeout(() => {
        // Simulate successful registration
        resolve({
          success: true,
          user: {
            id: Date.now(),
            firstName: registerData.firstName,
            lastName: registerData.lastName,
            email: registerData.email,
            joinDate: new Date().toISOString().split("T")[0],
            consultationsCount: 0,
          },
        })
      }, 2000)
    })
  }

  updateUIForLoggedInUser() {
    const authButtons = document.getElementById("auth-buttons")
    const userProfile = document.getElementById("user-profile")
    const profileName = document.getElementById("profile-name")

    if (authButtons) authButtons.style.display = "none"
    if (userProfile) userProfile.style.display = "block"
    if (profileName) profileName.textContent = `${this.currentUser.firstName} ${this.currentUser.lastName}`

    // Update profile information in other pages
    this.updateProfileInfo()
  }

  updateProfileInfo() {
    // Update sidebar info in profile page
    const sidebarName = document.getElementById("sidebar-name")
    const sidebarEmail = document.getElementById("sidebar-email")
    const totalConsultations = document.getElementById("total-consultations")
    const memberSince = document.getElementById("member-since")

    if (sidebarName) sidebarName.textContent = `${this.currentUser.firstName} ${this.currentUser.lastName}`
    if (sidebarEmail) sidebarEmail.textContent = this.currentUser.email
    if (totalConsultations) totalConsultations.textContent = this.currentUser.consultationsCount
    if (memberSince) memberSince.textContent = new Date(this.currentUser.joinDate).getFullYear()

    // Update form fields in profile page
    const firstName = document.getElementById("firstName")
    const lastName = document.getElementById("lastName")
    const email = document.getElementById("email")

    if (firstName) firstName.value = this.currentUser.firstName
    if (lastName) lastName.value = this.currentUser.lastName
    if (email) email.value = this.currentUser.email
  }

  logout() {
    this.currentUser = null
    localStorage.removeItem("selfmed_user")

    // Reset UI
    const authButtons = document.getElementById("auth-buttons")
    const userProfile = document.getElementById("user-profile")

    if (authButtons) authButtons.style.display = "flex"
    if (userProfile) userProfile.style.display = "none"

    // Redirect to home if on protected page
    if (window.location.pathname.includes("perfil") || window.location.pathname.includes("historico")) {
      window.location.href = "index.html"
    }

    this.showNotification("Logout realizado com sucesso!", "success")
  }

  checkPasswordStrength(password) {
    const strengthIndicator = document.getElementById("passwordStrength")
    if (!strengthIndicator) return

    let strength = 0
    const feedback = []

    if (password.length >= 8) strength++
    else feedback.push("Pelo menos 8 caracteres")

    if (/[a-z]/.test(password)) strength++
    else feedback.push("Letra minúscula")

    if (/[A-Z]/.test(password)) strength++
    else feedback.push("Letra maiúscula")

    if (/[0-9]/.test(password)) strength++
    else feedback.push("Número")

    if (/[^A-Za-z0-9]/.test(password)) strength++
    else feedback.push("Caractere especial")

    const strengthLevels = ["Muito fraca", "Fraca", "Regular", "Boa", "Forte"]
    const strengthColors = ["#ff4444", "#ff8800", "#ffaa00", "#88cc00", "#00cc44"]

    strengthIndicator.innerHTML = `
      <div class="strength-bar">
        <div class="strength-fill" style="width: ${(strength / 5) * 100}%; background: ${strengthColors[strength - 1] || "#ddd"}"></div>
      </div>
      <div class="strength-text" style="color: ${strengthColors[strength - 1] || "#666"}">
        ${strengthLevels[strength - 1] || ""}
      </div>
      ${feedback.length > 0 ? `<div class="strength-feedback">Adicione: ${feedback.join(", ")}</div>` : ""}
    `
  }

  validatePasswordMatch() {
    const password = document.getElementById("registerPassword")
    const confirmPassword = document.getElementById("confirmPassword")

    if (password && confirmPassword) {
      if (password.value !== confirmPassword.value) {
        confirmPassword.setCustomValidity("As senhas não coincidem")
      } else {
        confirmPassword.setCustomValidity("")
      }
    }
  }

  setFormLoading(form, loading) {
    const submitBtn = form.querySelector('button[type="submit"]')
    const btnText = submitBtn.querySelector(".btn-text")
    const spinner = submitBtn.querySelector(".loading-spinner")

    if (loading) {
      submitBtn.disabled = true
      btnText.style.display = "none"
      spinner.style.display = "block"
    } else {
      submitBtn.disabled = false
      btnText.style.display = "block"
      spinner.style.display = "none"
    }
  }

  showNotification(message, type = "info") {
    // Create notification element
    const notification = document.createElement("div")
    notification.className = `notification ${type}`
    notification.innerHTML = `
      <div class="notification-content">
        <i class="fas ${type === "success" ? "fa-check-circle" : type === "error" ? "fa-exclamation-circle" : "fa-info-circle"}"></i>
        <span>${message}</span>
      </div>
      <button class="notification-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
      </button>
    `

    // Add to page
    document.body.appendChild(notification)

    // Auto remove after 5 seconds
    setTimeout(() => {
      if (notification.parentElement) {
        notification.remove()
      }
    }, 5000)
  }

  isLoggedIn() {
    return this.currentUser !== null
  }

  getCurrentUser() {
    return this.currentUser
  }
}

// Initialize auth system
const auth = new AuthSystem()

// Modal functions
function openModal(modalId) {
  const modal = document.getElementById(modalId)
  if (modal) {
    modal.style.display = "flex"
    document.body.style.overflow = "hidden"
  }
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId)
  if (modal) {
    modal.style.display = "none"
    document.body.style.overflow = "auto"
  }
}

function switchModal(currentModalId, targetModalId) {
  closeModal(currentModalId)
  setTimeout(() => openModal(targetModalId), 300)
}

// Profile menu functions
function toggleProfileMenu() {
  const profileMenu = document.getElementById("profile-menu")
  if (profileMenu) {
    profileMenu.classList.toggle("active")
  }
}

// Password toggle function
function togglePassword(inputId) {
  const input = document.getElementById(inputId)
  const button = input.nextElementSibling
  const icon = button.querySelector("i")

  if (input.type === "password") {
    input.type = "text"
    icon.classList.remove("fa-eye")
    icon.classList.add("fa-eye-slash")
  } else {
    input.type = "password"
    icon.classList.remove("fa-eye-slash")
    icon.classList.add("fa-eye")
  }
}

// Logout function
function logout() {
  auth.logout()
}

// Close modals when clicking outside
window.addEventListener("click", (e) => {
  if (e.target.classList.contains("modal")) {
    e.target.style.display = "none"
    document.body.style.overflow = "auto"
  }
})

// Close profile menu when clicking outside
document.addEventListener("click", (e) => {
  const profileDropdown = document.querySelector(".profile-dropdown")
  const profileMenu = document.getElementById("profile-menu")

  if (profileDropdown && !profileDropdown.contains(e.target)) {
    if (profileMenu) {
      profileMenu.classList.remove("active")
    }
  }
})

// Check authentication on protected pages
document.addEventListener("DOMContentLoaded", () => {
  const protectedPages = ["perfil.html", "historico.html"]
  const currentPage = window.location.pathname.split("/").pop()

  if (protectedPages.includes(currentPage) && !auth.isLoggedIn()) {
    window.location.href = "index.html"
  }
})
