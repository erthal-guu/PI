// Profile Management
class ProfileManager {
  constructor() {
    this.init()
  }

  init() {
    this.setupTabNavigation()
    this.setupFormHandlers()
  }

  setupTabNavigation() {
    const tabLinks = document.querySelectorAll(".profile-nav-item")
    const tabContents = document.querySelectorAll(".tab-content")

    tabLinks.forEach((link) => {
      link.addEventListener("click", (e) => {
        e.preventDefault()

        // Remove active class from all tabs
        tabLinks.forEach((l) => l.classList.remove("active"))
        tabContents.forEach((c) => c.classList.remove("active"))

        // Add active class to clicked tab
        link.classList.add("active")
        const targetTab = document.getElementById(link.dataset.tab)
        if (targetTab) {
          targetTab.classList.add("active")
        }
      })
    })
  }

  setupFormHandlers() {
    // Personal info form
    const personalInfoForm = document.getElementById("personal-info-form")
    if (personalInfoForm) {
      personalInfoForm.addEventListener("submit", (e) => this.handlePersonalInfoUpdate(e))
    }

    // Password form
    const passwordForm = document.getElementById("password-form")
    if (passwordForm) {
      passwordForm.addEventListener("submit", (e) => this.handlePasswordChange(e))
    }

    // Preferences form
    const preferencesForm = document.getElementById("preferences-form")
    if (preferencesForm) {
      preferencesForm.addEventListener("submit", (e) => this.handlePreferencesUpdate(e))
    }
  }

  async handlePersonalInfoUpdate(e) {
    e.preventDefault()
    const form = e.target
    const formData = new FormData(form)

    const updateData = {
      firstName: formData.get("firstName"),
      lastName: formData.get("lastName"),
      email: formData.get("email"),
      phone: formData.get("phone"),
      birthDate: formData.get("birthDate"),
    }

    try {
      // Show loading state
      this.setFormLoading(form, true)

      // Simulate API call - Replace with actual backend call
      const response = await this.simulateProfileUpdate(updateData)

      if (response.success) {
        // Update current user data
        const currentUser = window.auth.getCurrentUser()
        Object.assign(currentUser, updateData)
        localStorage.setItem("selfmed_user", JSON.stringify(currentUser))

        // Update UI
        window.auth.updateUIForLoggedInUser()
        this.toggleEdit("personal-info", false)

        window.auth.showNotification("Informações atualizadas com sucesso!", "success")
      } else {
        window.auth.showNotification(response.message || "Erro ao atualizar informações", "error")
      }
    } catch (error) {
      console.error("Profile update error:", error)
      window.auth.showNotification("Erro interno. Tente novamente.", "error")
    } finally {
      this.setFormLoading(form, false)
    }
  }

  async handlePasswordChange(e) {
    e.preventDefault()
    const form = e.target
    const formData = new FormData(form)

    const passwordData = {
      currentPassword: formData.get("currentPassword"),
      newPassword: formData.get("newPassword"),
      confirmNewPassword: formData.get("confirmNewPassword"),
    }

    // Validate new passwords match
    if (passwordData.newPassword !== passwordData.confirmNewPassword) {
      window.auth.showNotification("As novas senhas não coincidem", "error")
      return
    }

    try {
      // Show loading state
      this.setFormLoading(form, true)

      // Simulate API call - Replace with actual backend call
      const response = await this.simulatePasswordChange(passwordData)

      if (response.success) {
        form.reset()
        window.auth.showNotification("Senha alterada com sucesso!", "success")
      } else {
        window.auth.showNotification(response.message || "Erro ao alterar senha", "error")
      }
    } catch (error) {
      console.error("Password change error:", error)
      window.auth.showNotification("Erro interno. Tente novamente.", "error")
    } finally {
      this.setFormLoading(form, false)
    }
  }

  async handlePreferencesUpdate(e) {
    e.preventDefault()
    const form = e.target
    const formData = new FormData(form)

    const preferencesData = {
      emailNotifications: formData.get("emailNotifications") === "on",
      consultationReminders: formData.get("consultationReminders") === "on",
      dataSharing: formData.get("dataSharing") === "on",
    }

    try {
      // Show loading state
      this.setFormLoading(form, true)

      // Simulate API call - Replace with actual backend call
      const response = await this.simulatePreferencesUpdate(preferencesData)

      if (response.success) {
        window.auth.showNotification("Preferências salvas com sucesso!", "success")
      } else {
        window.auth.showNotification(response.message || "Erro ao salvar preferências", "error")
      }
    } catch (error) {
      console.error("Preferences update error:", error)
      window.auth.showNotification("Erro interno. Tente novamente.", "error")
    } finally {
      this.setFormLoading(form, false)
    }
  }

  // Simulate API calls - Replace with actual backend integration
  async simulateProfileUpdate(data) {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve({ success: true })
      }, 1000)
    })
  }

  async simulatePasswordChange(passwordData) {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve({ success: true })
      }, 1500)
    })
  }

  async simulatePreferencesUpdate(preferencesData) {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve({ success: true })
      }, 1000)
    })
  }

  toggleEdit(tabId, enable = null) {
    const form = document.getElementById(`${tabId}-form`)
    const inputs = form.querySelectorAll('input:not([type="checkbox"])')
    const actions = form.querySelector(".form-actions")
    const editBtn = document.querySelector(".btn-edit")

    const isEditing = enable !== null ? enable : inputs[0].readOnly

    inputs.forEach((input) => {
      input.readOnly = !isEditing
      if (isEditing) {
        input.classList.add("editable")
      } else {
        input.classList.remove("editable")
      }
    })

    if (actions) {
      actions.style.display = isEditing ? "flex" : "none"
    }

    if (editBtn) {
      editBtn.innerHTML = isEditing ? '<i class="fas fa-times"></i> Cancelar' : '<i class="fas fa-edit"></i> Editar'
    }
  }

  cancelEdit(tabId) {
    // Reset form values
    const form = document.getElementById(`${tabId}-form`)
    const currentUser = window.auth.getCurrentUser()

    if (currentUser) {
      const firstName = form.querySelector("#firstName")
      const lastName = form.querySelector("#lastName")
      const email = form.querySelector("#email")

      if (firstName) firstName.value = currentUser.firstName
      if (lastName) lastName.value = currentUser.lastName
      if (email) email.value = currentUser.email
    }

    this.toggleEdit(tabId, false)
  }

  setFormLoading(form, loading) {
    const submitBtn = form.querySelector('button[type="submit"]')
    const btnText = submitBtn.querySelector(".btn-text") || submitBtn.childNodes[0]
    const spinner = submitBtn.querySelector(".loading-spinner")

    if (loading) {
      submitBtn.disabled = true
      if (btnText) btnText.style.display = "none"
      if (spinner) spinner.style.display = "block"
    } else {
      submitBtn.disabled = false
      if (btnText) btnText.style.display = "block"
      if (spinner) spinner.style.display = "none"
    }
  }
}

// Initialize profile manager
const profileManager = new ProfileManager()

// Global functions for profile page
function toggleEdit(tabId) {
  profileManager.toggleEdit(tabId)
}

function cancelEdit(tabId) {
  profileManager.cancelEdit(tabId)
}

// Password toggle function (same as auth.js)
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
