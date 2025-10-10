// Contador de caracteres do textarea
const symptomsTextarea = document.getElementById('symptoms');
const charCount = document.getElementById('char-count');

if (symptomsTextarea && charCount) {
    symptomsTextarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;
        
        if (count > 1000) {
            charCount.style.color = '#ef4444';
        } else if (count > 800) {
            charCount.style.color = '#f59e0b';
        } else {
            charCount.style.color = '#6b7280';
        }
    });
}

// Atualização do valor do slider de intensidade
const intensitySlider = document.getElementById('intensity');
const intensityValue = document.getElementById('intensity-value');

if (intensitySlider && intensityValue) {
    intensitySlider.addEventListener('input', function() {
        intensityValue.textContent = this.value;
        
        // Muda a cor baseado na intensidade
        const value = parseInt(this.value);
        if (value <= 3) {
            intensityValue.style.color = '#10b981';
        } else if (value <= 6) {
            intensityValue.style.color = '#f59e0b';
        } else {
            intensityValue.style.color = '#ef4444';
        }
    });
}

// Submissão do formulário com IA
const consultationForm = document.getElementById('consultation-form');
const submitBtn = document.getElementById('submit-btn');
const resultsSection = document.getElementById('results-section');
const analysisResults = document.getElementById('analysis-results');

if (consultationForm) {
    consultationForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Pega os valores do formulário
        const sintomas = document.getElementById('symptoms').value;
        const duracao = document.getElementById('duration').value;
        const intensidade = document.getElementById('intensity').value;
        
        // Validação básica
        if (!sintomas || !duracao || !intensidade) {
            showNotification('Por favor, preencha todos os campos', 'warning');
            return;
        }
        
        // Mostra estado de carregamento
        setLoadingState(true);
        
        try {
            // Envia para o backend processar com IA
            const response = await fetch('../controller/processar_consulta_ia.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    sintomas: sintomas,
                    duracao: duracao,
                    intensidade: intensidade
                })
            });
            
            const data = await response.json();
            
            if (data.erro) {
                showNotification(data.erro + (data.detalhes ? ': ' + data.detalhes : ''), 'error');
                setLoadingState(false);
                return;
            }
            
            if (data.sucesso) {
                // Exibe os resultados
                displayResults(data.analise);
                showNotification('Análise concluída com sucesso!', 'success');
            }
            
        } catch (error) {
            console.error('Erro:', error);
            showNotification('Erro ao processar consulta. Tente novamente.', 'error');
        } finally {
            setLoadingState(false);
        }
    });
}

// Função para mostrar estado de carregamento
function setLoadingState(isLoading) {
    const btnText = submitBtn.querySelector('.btn-text');
    const loadingSpinner = submitBtn.querySelector('.loading-spinner');
    
    if (isLoading) {
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        loadingSpinner.style.display = 'inline-block';
        submitBtn.style.opacity = '0.7';
        submitBtn.style.cursor = 'not-allowed';
    } else {
        submitBtn.disabled = false;
        btnText.style.display = 'inline';
        loadingSpinner.style.display = 'none';
        submitBtn.style.opacity = '1';
        submitBtn.style.cursor = 'pointer';
    }
}

// Função para exibir os resultados da IA
function displayResults(analise) {
    // Formata a resposta da IA (converte quebras de linha em HTML)
    const formattedAnalise = analise
        .replace(/\n\n/g, '</p><p>')
        .replace(/\n/g, '<br>')
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/^(\d+\.)/gm, '<br><strong>$1</strong>');
    
    analysisResults.innerHTML = `
        <div class="analysis-content">
            <div class="analysis-intro">
                <i class="fas fa-check-circle"></i>
                <p>Análise completa dos seus sintomas:</p>
            </div>
            <div class="analysis-text">
                <p>${formattedAnalise}</p>
            </div>
            <div class="analysis-footer">
                <div class="disclaimer">
                    <i class="fas fa-info-circle"></i>
                    <p><strong>Lembre-se:</strong> Esta é uma orientação preliminar. Consulte um médico para diagnóstico adequado.</p>
                </div>
            </div>
        </div>
    `;
    
    consultationForm.style.display = 'none';
    resultsSection.style.display = 'block';

    resultsSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function startNewConsultation() {
    consultationForm.reset();
    document.getElementById('char-count').textContent = '0';
    document.getElementById('intensity-value').textContent = '5';
    
    resultsSection.style.display = 'none';
    consultationForm.style.display = 'block';
    
    consultationForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
}


function showNotification(message, type = 'info') {
    // Remove notificação anterior se existir
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Cria nova notificação
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-times-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    notification.innerHTML = `
        <i class="fas ${icons[type]}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    // Anima a entrada
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Remove após 5 segundos
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);
}

// Adiciona estilos para as notificações
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateX(400px);
        transition: transform 0.3s ease;
        z-index: 10000;
        max-width: 400px;
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .notification i {
        font-size: 1.25rem;
    }
    
    .notification-success {
        background: #10b981;
        color: white;
    }
    
    .notification-error {
        background: #ef4444;
        color: white;
    }
    
    .notification-warning {
        background: #f59e0b;
        color: white;
    }
    
    .notification-info {
        background: #3b82f6;
        color: white;
    }
`;
document.head.appendChild(notificationStyles);