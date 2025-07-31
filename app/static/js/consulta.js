// DOM Elements
const consultationForm = document.getElementById("consultation-form")
const symptomsTextarea = document.getElementById("symptoms")
const charCount = document.getElementById("char-count")
const intensitySlider = document.getElementById("intensity")
const intensityValue = document.getElementById("intensity-value")
const submitBtn = document.getElementById("submit-btn")
const btnText = document.querySelector(".btn-text")
const loadingSpinner = document.querySelector(".loading-spinner")
const resultsSection = document.getElementById("results-section")
const analysisResults = document.getElementById("analysis-results")

// Character Counter
symptomsTextarea.addEventListener("input", () => {
  const currentLength = symptomsTextarea.value.length
  charCount.textContent = currentLength

  if (currentLength > 1000) {
    charCount.style.color = "var(--accent-dark)"
  } else {
    charCount.style.color = "var(--text-light)"
  }
})

// Intensity Slider
intensitySlider.addEventListener("input", () => {
  intensityValue.textContent = intensitySlider.value
})

// Form Submission
consultationForm.addEventListener("submit", async (e) => {
  e.preventDefault()

  const formData = new FormData(consultationForm)
  const symptoms = formData.get("symptoms").trim()
  const duration = formData.get("duration")
  const intensity = formData.get("intensity")

  if (!symptoms || !duration) {
    alert("Por favor, preencha todos os campos obrigatórios.")
    return
  }

  // Show loading state
  submitBtn.disabled = true
  btnText.style.display = "none"
  loadingSpinner.style.display = "block"

  try {
    // Simulate API call - Replace this with your actual backend call
    await simulateAPICall(symptoms, duration, intensity)

    // Show placeholder message since backend will handle the actual analysis
    showPlaceholderResults()
  } catch (error) {
    console.error("Error submitting consultation:", error)
    showErrorMessage()
  } finally {
    // Reset button state
    submitBtn.disabled = false
    btnText.style.display = "block"
    loadingSpinner.style.display = "none"
  }
})

// Simulate API Call (Replace with actual backend integration)
async function simulateAPICall(symptoms, duration, intensity) {
  return new Promise((resolve) => {
    setTimeout(resolve, 2000) // Simulate network delay
  })
}

// Show Placeholder Results
function showPlaceholderResults() {
  const placeholderHTML = `
    <div class="placeholder-message">
      <div class="placeholder-icon">
        <i class="fas fa-cog fa-spin"></i>
      </div>
      <h4>Consulta Enviada com Sucesso!</h4>
      <p>Seus sintomas foram registrados e estão sendo processados por nossa inteligência artificial.</p>
      
      <div class="next-steps">
        <h5><i class="fas fa-list-check"></i> Próximos Passos:</h5>
        <ul>
          <li>Nossa IA está analisando seus sintomas</li>
          <li>Você receberá orientações personalizadas em breve</li>
          <li>Mantenha-se atento aos seus sintomas</li>
          <li>Em caso de emergência, procure atendimento médico imediatamente</li>
        </ul>
      </div>
      
      <div class="important-reminder">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Lembrete:</strong> Esta análise não substitui consulta médica profissional. 
        Para diagnóstico e tratamento adequados, sempre consulte um médico.
      </div>
    </div>
  `

  analysisResults.innerHTML = placeholderHTML
  resultsSection.style.display = "block"

  // Scroll to results
  resultsSection.scrollIntoView({
    behavior: "smooth",
    block: "start",
  })
}

// Show Error Message
function showErrorMessage() {
  const errorHTML = `
    <div class="error-message">
      <div class="error-icon">
        <i class="fas fa-exclamation-circle"></i>
      </div>
      <h4>Ops! Algo deu errado</h4>
      <p>Não foi possível processar sua consulta no momento. Por favor, tente novamente em alguns instantes.</p>
      <p>Se o problema persistir, recomendamos que procure orientação médica diretamente.</p>
    </div>
  `

  analysisResults.innerHTML = errorHTML
  resultsSection.style.display = "block"
}

// Start New Consultation
function startNewConsultation() {
  // Reset form
  consultationForm.reset()
  charCount.textContent = "0"
  intensityValue.textContent = "5"

  // Hide results
  resultsSection.style.display = "none"

  // Scroll to top of form
  consultationForm.scrollIntoView({
    behavior: "smooth",
    block: "start",
  })
}

// Add CSS for placeholder and error messages
const additionalStyles = `
  <style>
    .placeholder-message, .error-message {
      text-align: center;
      padding: 2rem;
    }
    
    .placeholder-icon, .error-icon {
      font-size: 3rem;
      margin-bottom: 1rem;
    }
    
    .placeholder-icon {
      color: var(--primary-color);
    }
    
    .error-icon {
      color: var(--accent-dark);
    }
    
    .placeholder-message h4, .error-message h4 {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--primary-color);
      margin-bottom: 1rem;
    }
    
    .error-message h4 {
      color: var(--accent-dark);
    }
    
    .next-steps {
      background: var(--gray-light);
      padding: 1.5rem;
      border-radius: 8px;
      margin: 1.5rem 0;
      text-align: left;
    }
    
    .next-steps h5 {
      color: var(--primary-color);
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .next-steps ul {
      list-style: none;
      padding: 0;
    }
    
    .next-steps li {
      padding: 0.5rem 0;
      position: relative;
      padding-left: 1.5rem;
    }
    
    .next-steps li::before {
      content: "✓";
      position: absolute;
      left: 0;
      color: var(--primary-color);
      font-weight: bold;
    }
    
    .important-reminder {
      background: var(--accent-dark);
      color: white;
      padding: 1rem;
      border-radius: 8px;
      margin-top: 1.5rem;
      display: flex;
      align-items: flex-start;
      gap: 0.5rem;
      text-align: left;
    }
    
    .error-message {
      background: #ffebee;
      border: 1px solid var(--accent-light);
      border-radius: 8px;
    }
  </style>
`

// Inject additional styles
document.head.insertAdjacentHTML("beforeend", additionalStyles)

// Mobile menu functionality (same as main script)
const mobileMenuToggle = document.querySelector(".mobile-menu-toggle")
const navMenu = document.querySelector(".nav-menu")

if (mobileMenuToggle) {
  mobileMenuToggle.addEventListener("click", () => {
    navMenu.classList.toggle("active")
    const icon = mobileMenuToggle.querySelector("i")
    icon.classList.toggle("fa-bars")
    icon.classList.toggle("fa-times")
  })
}
