// ==========================================
// ADMIN.JS - JavaScript do Painel Admin
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    
    // Toggle Sidebar (Mobile)
    const toggleSidebar = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('adminSidebar');
    
    if (toggleSidebar && sidebar) {
        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });

        // Fechar sidebar ao clicar fora (mobile)
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(e.target) && !toggleSidebar.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    }

    // Auto-fechar alertas
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const closeBtn = alert.querySelector('.close-alert');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                alert.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            });
        }

        // Auto-fechar após 5 segundos
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 300);
            }
        }, 5000);
    });

    // Confirmação de exclusão
    const deleteLinks = document.querySelectorAll('a[href*="/delete/"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Tem certeza que deseja excluir este registro?')) {
                e.preventDefault();
            }
        });
    });

    // Preview de imagem no formulário
    const imageInput = document.querySelector('input[type="file"][name="image"]');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.getElementById('image-preview-admin');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.id = 'image-preview-admin';
                        preview.style.marginTop = '15px';
                        imageInput.parentNode.appendChild(preview);
                    }
                    preview.innerHTML = `
                        <p style="margin-bottom: 10px; font-weight: 500;">Prévia:</p>
                        <img src="${e.target.result}" style="max-width: 200px; border-radius: 8px; border: 2px solid var(--admin-border);">
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Validação de formulários
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'var(--admin-danger)';
                    
                    setTimeout(() => {
                        field.style.borderColor = '';
                    }, 3000);
                } else {
                    field.style.borderColor = '';
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
            }
        });
    });

    // Contador de caracteres
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        const counter = document.createElement('div');
        counter.style.fontSize = '0.85rem';
        counter.style.color = '#64748b';
        counter.style.marginTop = '5px';
        textarea.parentNode.appendChild(counter);

        function updateCounter() {
            const length = textarea.value.length;
            counter.textContent = `${length} caracteres`;
        }

        textarea.addEventListener('input', updateCounter);
        updateCounter();
    });

    // Formatação de preço
    const priceInputs = document.querySelectorAll('input[name="price"]');
    priceInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value) {
                const value = parseFloat(this.value);
                if (!isNaN(value)) {
                    this.value = value.toFixed(2);
                }
            }
        });
    });

    // Tabela com ordenação (simples)
    const tables = document.querySelectorAll('.data-table');
    tables.forEach(table => {
        const headers = table.querySelectorAll('th');
        headers.forEach((header, index) => {
            if (header.textContent.trim() && index < headers.length - 1) { // Não ordenar coluna de ações
                header.style.cursor = 'pointer';
                header.title = 'Clique para ordenar';
                
                header.addEventListener('click', function() {
                    sortTable(table, index);
                });
            }
        });
    });

    function sortTable(table, columnIndex) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        const sortedRows = rows.sort((a, b) => {
            const aText = a.children[columnIndex].textContent.trim();
            const bText = b.children[columnIndex].textContent.trim();
            
            // Tentar comparar como números
            const aNum = parseFloat(aText.replace(/[^\d.-]/g, ''));
            const bNum = parseFloat(bText.replace(/[^\d.-]/g, ''));
            
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return aNum - bNum;
            }
            
            // Comparar como texto
            return aText.localeCompare(bText);
        });

        // Reordenar as linhas
        sortedRows.forEach(row => tbody.appendChild(row));
    }

    // Busca em tabelas
    const searchInputs = document.querySelectorAll('.table-search');
    searchInputs.forEach(input => {
        const table = input.closest('.data-card').querySelector('.data-table');
        
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // Gráfico de vendas (se existir Chart.js)
    if (typeof Chart !== 'undefined') {
        const salesChart = document.getElementById('salesChart');
        if (salesChart) {
            fetch(salesChart.dataset.url)
                .then(response => response.json())
                .then(data => {
                    new Chart(salesChart, {
                        type: 'line',
                        data: {
                            labels: data.map(d => d.date),
                            datasets: [{
                                label: 'Vendas',
                                data: data.map(d => d.revenue),
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
        }
    }

    // Confirmar mudança de status
    const statusForms = document.querySelectorAll('form[action*="update-status"]');
    statusForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Deseja realmente alterar o status deste pedido?')) {
                e.preventDefault();
            }
        });
    });
});

// Animação de slideOut para alertas
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }
`;
document.head.appendChild(style);

// Função utilitária para formatar moeda
function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
}

// Função utilitária para formatar data
function formatDate(dateString) {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
}