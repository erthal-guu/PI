// DOM Elements
const symptomForm = document.getElementById("symptom-form")
const symptomsInput = document.getElementById("symptoms-input")
const consultBtn = document.getElementById("consult-btn")
const btnText = document.querySelector(".btn-text")
const loadingSpinner = document.querySelector(".loading-spinner")
const resultsArea = document.getElementById("results-area")
const aiResponse = document.getElementById("ai-response")
const navLinks = document.querySelectorAll(".nav-link")
const mobileMenuToggle = document.querySelector(".mobile-menu-toggle")
const navMenu = document.querySelector(".nav-menu")
const scrollIndicator = document.querySelector(".scroll-indicator")

// Navigation functionality
navLinks.forEach((link) => {
  link.addEventListener("click", (e) => {
    e.preventDefault()

    // Remove active class from all links
    navLinks.forEach((l) => l.classList.remove("active"))

    // Add active class to clicked link
    link.classList.add("active")

    // Get target section
    const targetId = link.getAttribute("href")
    const targetSection = document.querySelector(targetId)

    if (targetSection) {
      targetSection.scrollIntoView({
        behavior: "smooth",
        block: "start",
      })
    }

    // Close mobile menu if open
    if (navMenu.classList.contains("active")) {
      navMenu.classList.remove("active")
      const icon = mobileMenuToggle.querySelector("i")
      icon.classList.add("fa-bars")
      icon.classList.remove("fa-times")
    }
  })
})

// Mobile Menu Toggle
if (mobileMenuToggle) {
  mobileMenuToggle.addEventListener("click", () => {
    navMenu.classList.toggle("active")
    const icon = mobileMenuToggle.querySelector("i")
    icon.classList.toggle("fa-bars")
    icon.classList.toggle("fa-times")
  })
}

// Symptom analysis responses database
const symptomResponses = {
  // Respiratory symptoms
  tosse: {
    causes: ["Resfriado comum", "Gripe", "Alergia respiratória", "Irritação por poluição"],
    recommendations: [
      "Mantenha-se hidratado bebendo bastante água",
      "Use um umidificador ou respire vapor de água quente",
      "Evite irritantes como fumaça e poeira",
      "Descanse adequadamente",
    ],
    warning:
      "Se a tosse persistir por mais de 2 semanas, apresentar sangue ou vier acompanhada de febre alta, procure um médico.",
  },
  febre: {
    causes: ["Infecção viral", "Infecção bacteriana", "Reação inflamatória", "Desidratação"],
    recommendations: [
      "Mantenha-se hidratado com água, chás e sucos naturais",
      "Descanse em ambiente fresco e arejado",
      "Use roupas leves",
      "Monitore a temperatura regularmente",
    ],
    warning:
      "Febre acima de 39°C, que persiste por mais de 3 dias ou acompanhada de sintomas graves requer atenção médica imediata.",
  },
  "dor de cabeça": {
    causes: ["Tensão muscular", "Estresse", "Desidratação", "Falta de sono", "Enxaqueca"],
    recommendations: [
      "Descanse em ambiente escuro e silencioso",
      "Aplique compressas frias na testa",
      "Mantenha-se hidratado",
      "Pratique técnicas de relaxamento",
      "Mantenha horários regulares de sono",
    ],
    warning:
      "Dores de cabeça súbitas e intensas, acompanhadas de rigidez no pescoço, confusão ou alterações visuais requerem atendimento médico urgente.",
  },
  "dor de garganta": {
    causes: ["Infecção viral", "Infecção bacteriana", "Irritação por ar seco", "Refluxo gastroesofágico"],
    recommendations: [
      "Faça gargarejos com água morna e sal",
      "Beba líquidos mornos como chás com mel",
      "Use pastilhas para garganta",
      "Evite irritantes como fumaça",
      "Mantenha o ambiente umidificado",
    ],
    warning:
      "Se a dor de garganta vier acompanhada de febre alta, dificuldade para engolir ou manchas brancas na garganta, procure um médico.",
  },
  náusea: {
    causes: ["Indigestão", "Gastroenterite", "Ansiedade", "Gravidez", "Medicamentos"],
    recommendations: [
      "Coma alimentos leves e em pequenas quantidades",
      "Beba líquidos em pequenos goles",
      "Evite alimentos gordurosos e condimentados",
      "Descanse em posição elevada",
      "Experimente chá de gengibre",
    ],
    warning:
      "Náuseas persistentes acompanhadas de vômitos frequentes, desidratação ou dor abdominal intensa requerem avaliação médica.",
  },
}

// Function to analyze symptoms using keyword matching
function analyzeSymptoms(symptoms) {
  const lowerSymptoms = symptoms.toLowerCase()
  const foundSymptoms = []

  // Check for keyword matches
  Object.keys(symptomResponses).forEach((symptom) => {
    if (lowerSymptoms.includes(symptom)) {
      foundSymptoms.push({
        symptom: symptom,
        data: symptomResponses[symptom],
      })
    }
  })

  // If specific symptoms found, return detailed response
  if (foundSymptoms.length > 0) {
    return generateDetailedResponse(foundSymptoms)
  }

  // Generic response for unrecognized symptoms
  return generateGenericResponse(symptoms)
}

function generateDetailedResponse(foundSymptoms) {
  let response = '<div class="symptom-analysis">'

  foundSymptoms.forEach((item, index) => {
    const { symptom, data } = item

    response += `
            <div class="symptom-section">
                <h5>Análise para: ${symptom.charAt(0).toUpperCase() + symptom.slice(1)}</h5>
                
                <div class="causes-section">
                    <strong>Possíveis causas:</strong>
                    <ul>
                        ${data.causes.map((cause) => `<li>${cause}</li>`).join("")}
                    </ul>
                </div>
                
                <div class="recommendations-section">
                    <strong>Recomendações gerais:</strong>
                    <ul>
                        ${data.recommendations.map((rec) => `<li>${rec}</li>`).join("")}
                    </ul>
                </div>
                
                <div class="warning-section">
                    <strong>⚠️ Atenção:</strong> ${data.warning}
                </div>
            </div>
        `

    if (index < foundSymptoms.length - 1) {
      response += '<hr style="margin: 1.5rem 0; border: 1px solid #f5f5f5;">'
    }
  })

  response += "</div>"
  return response
}

function generateGenericResponse(symptoms) {
  const genericAdvice = [
    "Mantenha-se hidratado bebendo bastante água",
    "Descanse adequadamente e durma bem",
    "Mantenha uma alimentação equilibrada",
    "Evite automedicação",
    "Monitore seus sintomas e sua evolução",
    "Procure um ambiente calmo e arejado",
  ]

  const randomAdvice = genericAdvice.sort(() => 0.5 - Math.random()).slice(0, 4)

  return `
        <div class="generic-response">
            <p><strong>Baseado nos sintomas descritos, aqui estão algumas orientações gerais:</strong></p>
            
            <div class="general-recommendations">
                <strong>Recomendações gerais:</strong>
                <ul>
                    ${randomAdvice.map((advice) => `<li>${advice}</li>`).join("")}
                </ul>
            </div>
            
            <div class="generic-warning">
                <strong>⚠️ Importante:</strong> Como não foi possível identificar sintomas específicos em sua descrição, 
                recomendamos que procure orientação médica para uma avaliação adequada, especialmente se os sintomas 
                persistirem, piorarem ou causarem preocupação.
            </div>
            
            <div class="emergency-info">
                <strong>🚨 Procure atendimento médico imediatamente se apresentar:</strong>
                <ul>
                    <li>Dificuldade para respirar</li>
                    <li>Dor no peito intensa</li>
                    <li>Febre muito alta (acima de 39°C)</li>
                    <li>Vômitos persistentes</li>
                    <li>Confusão mental ou desmaios</li>
                    <li>Qualquer sintoma que cause grande preocupação</li>
                </ul>
            </div>
        </div>
    `
}

// Form submission handler
symptomForm.addEventListener("submit", async (e) => {
  e.preventDefault()

  const symptoms = symptomsInput.value.trim()

  if (!symptoms) {
    alert("Por favor, descreva seus sintomas antes de consultar.")
    return
  }

  // Show loading state
  consultBtn.disabled = true
  btnText.style.display = "none"
  loadingSpinner.style.display = "block"

  try {
    // Simulate AI processing time
    await new Promise((resolve) => setTimeout(resolve, 2000))

    // Analyze symptoms
    const analysis = analyzeSymptoms(symptoms)

    // Display results
    aiResponse.innerHTML = analysis
    resultsArea.style.display = "block"

    // Scroll to results
    resultsArea.scrollIntoView({
      behavior: "smooth",
      block: "start",
    })
  } catch (error) {
    console.error("Error analyzing symptoms:", error)
    aiResponse.innerHTML = `
            <div class="error-message">
                <p><strong>Ops! Ocorreu um erro ao processar sua consulta.</strong></p>
                <p>Por favor, tente novamente em alguns instantes. Se o problema persistir, 
                recomendamos que procure orientação médica diretamente.</p>
            </div>
        `
    resultsArea.style.display = "block"
  } finally {
    // Reset button state
    consultBtn.disabled = false
    btnText.style.display = "block"
    loadingSpinner.style.display = "none"
  }
})

// Clear results when user starts typing new symptoms
symptomsInput.addEventListener("input", () => {
  if (resultsArea.style.display === "block") {
    resultsArea.style.display = "none"
  }
})

// Parallax Effect for Hero Section
window.addEventListener("scroll", () => {
  const scrolled = window.pageYOffset
  const heroImage = document.querySelector(".hero-image")

  if (heroImage) {
    const speed = scrolled * 0.5
    heroImage.style.transform = `scale(1.1) translateY(${speed}px)`
  }
})

// Intersection Observer for Animations
const observerOptions = {
  threshold: 0.1,
  rootMargin: "0px 0px -50px 0px",
}

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add("fade-in")
    }
  })
}, observerOptions)

// Observe elements for animation
document.addEventListener("DOMContentLoaded", () => {
  const animateElements = document.querySelectorAll(".feature-card, .step-item, .about-item")
  animateElements.forEach((el) => observer.observe(el))
})

// Header Background on Scroll
window.addEventListener("scroll", () => {
  const header = document.querySelector(".header")
  if (window.scrollY > 100) {
    header.style.background = "rgba(40, 116, 166, 0.95)"
    header.style.backdropFilter = "blur(10px)"
  } else {
    header.style.background = "linear-gradient(135deg, #2874a6 0%, #a6d3f2 100%)"
    header.style.backdropFilter = "none"
  }
})

// Scroll Indicator Click
if (scrollIndicator) {
  scrollIndicator.addEventListener("click", () => {
    const featuresSection = document.querySelector(".features-section")
    if (featuresSection) {
      featuresSection.scrollIntoView({
        behavior: "smooth",
        block: "start",
      })
    }
  })
}

// Add loading states and interactions
document.addEventListener("DOMContentLoaded", () => {
  // Add hover effects to cards
  const cards = document.querySelectorAll(".feature-card, .info-card")
  cards.forEach((card) => {
    card.addEventListener("mouseenter", () => {
      card.style.transform = "translateY(-5px)"
    })

    card.addEventListener("mouseleave", () => {
      card.style.transform = "translateY(0)"
    })
  })
})

// Add some CSS for the analysis sections
const additionalStyles = `
    <style>
        .symptom-analysis .symptom-section {
            margin-bottom: 1.5rem;
        }
        
        .symptom-analysis h5 {
            color: #2874a6;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .causes-section, .recommendations-section {
            margin-bottom: 1rem;
        }
        
        .warning-section {
            background-color: #e57373;
            color: white;
            padding: 0.8rem;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        
        .generic-response .generic-warning,
        .generic-response .emergency-info {
            background-color: #cc0000;
            color: white;
            padding: 1rem;
            border-radius: 6px;
            margin: 1rem 0;
            font-size: 0.9rem;
        }
        
        .emergency-info {
            background-color: #d32f2f !important;
        }
        
        .error-message {
            background-color: #e57373;
            color: white;
            padding: 1rem;
            border-radius: 6px;
            text-align: center;
        }
        
        .symptom-analysis ul,
        .generic-response ul {
            margin: 0.5rem 0;
            padding-left: 1.5rem;
        }
        
        .symptom-analysis li,
        .generic-response li {
            margin: 0.3rem 0;
        }

        .mobile-menu-toggle {
            display: none;
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
        }

        .nav-menu.active {
            display: block;
        }

        .nav-menu {
            display: none;
        }

        .scroll-indicator {
            cursor: pointer;
        }

        .fade-in {
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }
    </style>
`

// Inject additional styles
document.head.insertAdjacentHTML("beforeend", additionalStyles)
