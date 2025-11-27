// ==========================================
// MAIN.JS - JavaScript da Loja
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    
    // Auto-fechar alertas
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.animation = 'slideUp 0.3s ease';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });

    // Confirmação de remoção de item do carrinho
    const removeLinks = document.querySelectorAll('a[href*="cart/remove"]');
    removeLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Deseja remover este item do carrinho?')) {
                e.preventDefault();
            }
        });
    });

    // Atualizar carrinho automaticamente ao mudar quantidade
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        let timeout;
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                this.closest('form').submit();
            }, 1000);
        });
    });

    // Validação do formulário de checkout
    const checkoutForm = document.querySelector('form[action*="process-order"]');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const address = this.querySelector('[name="shipping_address"]').value;
            const payment = this.querySelector('[name="payment_method"]:checked');

            if (!address.trim()) {
                e.preventDefault();
                alert('Por favor, preencha o endereço de entrega.');
                return;
            }

            if (!payment) {
                e.preventDefault();
                alert('Por favor, selecione uma forma de pagamento.');
                return;
            }

            return confirm('Confirma a finalização do pedido?');
        });
    }

    // Validação do formulário de registro
    const registerForm = document.querySelector('form[action*="register-post"]');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = this.querySelector('#password').value;
            const confirmPassword = this.querySelector('#confirm_password').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('As senhas não coincidem!');
                return false;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert('A senha deve ter no mínimo 6 caracteres!');
                return false;
            }
        });
    }

    // Máscara de telefone (simples)
    const phoneInput = document.querySelector('input[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            
            if (value.length > 6) {
                value = value.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, '($1) $2-$3');
            } else if (value.length > 2) {
                value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
            } else if (value.length > 0) {
                value = value.replace(/^(\d*)/, '($1');
            }
            
            e.target.value = value;
        });
    }

    // Adicionar animação de carregamento nos botões de submit
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            if (form && form.checkValidity()) {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
                
                // Reabilitar após 5 segundos (caso não redirecione)
                setTimeout(() => {
                    this.disabled = false;
                    this.innerHTML = this.dataset.originalText || 'Enviar';
                }, 5000);
            }
        });
    });

    // Contador de caracteres em textarea
    const textareas = document.querySelectorAll('textarea[maxlength]');
    textareas.forEach(textarea => {
        const maxLength = textarea.getAttribute('maxlength');
        const counter = document.createElement('div');
        counter.className = 'character-counter';
        counter.style.fontSize = '0.85rem';
        counter.style.color = '#64748b';
        counter.style.marginTop = '5px';
        textarea.parentNode.appendChild(counter);

        function updateCounter() {
            const remaining = maxLength - textarea.value.length;
            counter.textContent = `${remaining} caracteres restantes`;
        }

        textarea.addEventListener('input', updateCounter);
        updateCounter();
    });

    // Smooth scroll para âncoras
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // Preview de imagem antes do upload
    const imageInput = document.querySelector('input[type="file"][accept*="image"]');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.getElementById('image-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.id = 'image-preview';
                        preview.style.marginTop = '15px';
                        imageInput.parentNode.appendChild(preview);
                    }
                    preview.innerHTML = `<img src="${e.target.result}" style="max-width: 200px; border-radius: 8px; border: 2px solid #e2e8f0;">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

// Animação de slideUp para alertas
const style = document.createElement('style');
style.textContent = `
    @keyframes slideUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
`;
document.head.appendChild(style);