/* ========================================
   SISTEMA GERADOR DE GRAFOS - JS GLOBAL
   ======================================== */

// ========================================
// DARK MODE SYSTEM
// ========================================
class DarkModeManager {
    constructor() {
        this.theme = localStorage.getItem('theme') || 'light';
        this.init();
    }

    init() {
        this.applyTheme();
        this.bindEvents();
    }

    applyTheme() {
        document.documentElement.setAttribute('data-theme', this.theme);
        this.updateToggleButton();
    }

    toggle() {
        this.theme = this.theme === 'light' ? 'dark' : 'light';
        localStorage.setItem('theme', this.theme);
        this.applyTheme();
        
        // Disparar evento para outros componentes
        document.dispatchEvent(new CustomEvent('themeChanged', {
            detail: { theme: this.theme }
        }));
    }

    updateToggleButton() {
        const toggleBtn = document.querySelector('[data-theme-toggle]');
        if (toggleBtn) {
            const icon = toggleBtn.querySelector('i');
            if (icon) {
                icon.className = this.theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }
        }
    }

    bindEvents() {
        const toggleBtn = document.querySelector('[data-theme-toggle]');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.toggle());
        }
    }
}

// ========================================
// NOTIFICATION SYSTEM
// ========================================
class NotificationSystem {
    constructor() {
        this.container = this.createContainer();
        this.notifications = new Map();
    }

    createContainer() {
        let container = document.querySelector('.notification-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'notification-container';
            document.body.appendChild(container);
        }
        return container;
    }

    show(message, type = 'info', duration = 3000) {
        const id = Date.now() + Math.random();
        const notification = this.createNotification(id, message, type, duration);
        
        this.container.appendChild(notification);
        this.notifications.set(id, notification);

        // Auto remove
        setTimeout(() => {
            this.remove(id);
        }, duration);

        return id;
    }

    createNotification(id, message, type, duration) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${this.getIcon(type)} me-2"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-auto" onclick="window.NotificationSystem.remove(${id})"></button>
            </div>
            <div class="notification-progress"></div>
        `;
        return notification;
    }

    getIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }

    remove(id) {
        const notification = this.notifications.get(id);
        if (notification) {
            notification.style.animation = 'notificationSlideOut 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
                this.notifications.delete(id);
            }, 300);
        }
    }

    clear() {
        this.notifications.forEach((notification, id) => {
            this.remove(id);
        });
    }
}

// ========================================
// CUSTOM POPUP SYSTEM
// ========================================
class PopupSystem {
    static show(options) {
        const popup = document.createElement('div');
        popup.className = 'custom-popup-overlay';
        
        const { title, message, buttons = [], type = 'info' } = options;
        
        popup.innerHTML = `
            <div class="custom-popup">
                <div class="custom-popup-header">
                    <i class="fas fa-${this.getIcon(type)} text-${type} me-2"></i>
                    <h5 class="mb-0">${title}</h5>
                </div>
                <div class="custom-popup-body">
                    <p class="mb-0">${message}</p>
                </div>
                <div class="custom-popup-footer">
                    ${buttons.map(btn => `
                        <button type="button" class="btn btn-${btn.class || 'secondary'}" 
                                onclick="${btn.onclick || 'PopupSystem.close()'}">
                            ${btn.text}
                        </button>
                    `).join('')}
                </div>
            </div>
        `;
        
        document.body.appendChild(popup);
        return popup;
    }

    static getIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle',
            question: 'question-circle'
        };
        return icons[type] || 'info-circle';
    }

    static close() {
        const popup = document.querySelector('.custom-popup-overlay');
        if (popup) {
            popup.style.animation = 'popupSlideOut 0.2s ease';
            setTimeout(() => {
                if (popup.parentNode) {
                    popup.parentNode.removeChild(popup);
                }
            }, 200);
        }
    }
}

// ========================================
// FORM VALIDATION
// ========================================
class FormValidator {
    static validateForm(form) {
        const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!this.validateInput(input)) {
                isValid = false;
            }
        });
        
        return isValid;
    }

    static validateInput(input) {
        const value = input.value.trim();
        const type = input.type;
        let isValid = true;
        let message = '';

        // Required validation
        if (input.hasAttribute('required') && !value) {
            isValid = false;
            message = 'Este campo é obrigatório';
        }

        // Email validation
        if (type === 'email' && value && !this.isValidEmail(value)) {
            isValid = false;
            message = 'Email inválido';
        }

        // Min length validation
        const minLength = input.getAttribute('minlength');
        if (minLength && value.length < parseInt(minLength)) {
            isValid = false;
            message = `Mínimo de ${minLength} caracteres`;
        }

        // Max length validation
        const maxLength = input.getAttribute('maxlength');
        if (maxLength && value.length > parseInt(maxLength)) {
            isValid = false;
            message = `Máximo de ${maxLength} caracteres`;
        }

        this.showInputError(input, message);
        return isValid;
    }

    static isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    static showInputError(input, message) {
        this.clearInputError(input);
        
        if (message) {
            input.classList.add('is-invalid');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = message;
            input.parentNode.appendChild(errorDiv);
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
    }

    static clearInputError(input) {
        input.classList.remove('is-invalid', 'is-valid');
        const errorDiv = input.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
}

// ========================================
// AJAX HELPER
// ========================================
class AjaxHelper {
    static async request(url, options = {}) {
        const defaultOptions = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            }
        };

        const config = { ...defaultOptions, ...options };

        try {
            const response = await fetch(url, config);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return await response.json();
            } else {
                return await response.text();
            }
        } catch (error) {
            console.error('Ajax request failed:', error);
            throw error;
        }
    }

    static async get(url) {
        return this.request(url, { method: 'GET' });
    }

    static async post(url, data) {
        return this.request(url, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    static async put(url, data) {
        return this.request(url, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    static async delete(url) {
        return this.request(url, { method: 'DELETE' });
    }
}

// ========================================
// UTILITY FUNCTIONS
// ========================================
const Utils = {
    // Debounce function
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Throttle function
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },

    // Format date
    formatDate(date, options = {}) {
        const defaultOptions = {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        };
        return new Date(date).toLocaleDateString('pt-BR', { ...defaultOptions, ...options });
    },

    // Copy to clipboard
    async copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            return true;
        } catch (err) {
            console.error('Failed to copy text: ', err);
            return false;
        }
    },

    // Generate random ID
    generateId() {
        return Date.now().toString(36) + Math.random().toString(36).substr(2);
    }
};

// ========================================
// INITIALIZATION
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Dark Mode
    window.darkMode = new DarkModeManager();
    
    // Initialize Notification System
    window.NotificationSystem = new NotificationSystem();
    
    // Initialize Popup System
    window.PopupSystem = PopupSystem;
    
    // Initialize Form Validator
    window.FormValidator = FormValidator;
    
    // Initialize Ajax Helper
    window.AjaxHelper = AjaxHelper;
    
    // Initialize Utils
    window.Utils = Utils;
    
    // Show session messages
    const successMessage = document.querySelector('[data-success-message]');
    if (successMessage) {
        window.NotificationSystem.show(successMessage.textContent, 'success');
    }
    
    const errorMessage = document.querySelector('[data-error-message]');
    if (errorMessage) {
        window.NotificationSystem.show(errorMessage.textContent, 'error');
    }
    
    console.log('🎯 Sistema Gerador de Grafos carregado com sucesso!');
    console.log('📊 Arquitetura: Clean Code + SOLID + Laravel 9');
    console.log('🚀 Status: Operacional');
});

// ========================================
// HAMBURGER MENU SYSTEM
// ========================================
class HamburgerMenuManager {
    constructor() {
        this.toggle = document.getElementById('hamburgerToggle');
        this.menu = document.getElementById('hamburgerMenu');
        this.isOpen = false;
        this.init();
    }

    init() {
        if (this.toggle && this.menu) {
            this.bindEvents();
        }
    }

    bindEvents() {
        // Toggle menu on button click
        this.toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleMenu();
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.toggle.contains(e.target) && !this.menu.contains(e.target)) {
                this.closeMenu();
            }
        });

        // Close menu when pressing Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closeMenu();
            }
        });

        // Close menu when clicking on menu items (except theme toggle)
        const menuItems = this.menu.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', (e) => {
                // Não fechar o menu se for o botão de tema
                if (!item.hasAttribute('data-theme-toggle')) {
                    this.closeMenu();
                }
            });
        });

    }

    toggleMenu() {
        if (this.isOpen) {
            this.closeMenu();
        } else {
            this.openMenu();
        }
    }

    openMenu() {
        this.menu.classList.add('show');
        this.isOpen = true;
        this.toggle.innerHTML = '<i class="fas fa-times"></i>';
        
        // Aplicar tema atual ao menu
        this.applyTheme();
        
        // Re-bind events para garantir que funcionem
        this.bindThemeEvents();
    }

    closeMenu() {
        this.menu.classList.remove('show');
        this.isOpen = false;
        this.toggle.innerHTML = '<i class="fas fa-bars"></i>';
    }

    applyTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        this.menu.setAttribute('data-theme', currentTheme);
    }

    bindThemeEvents() {
        // Re-bind do botão de tema no menu hambúrguer
        const themeToggle = this.menu.querySelector('[data-theme-toggle]');
        console.log('🔍 Procurando botão de tema no menu:', themeToggle);
        
        if (themeToggle) {
            // Remover listeners antigos
            if (this.handleThemeToggle) {
                themeToggle.removeEventListener('click', this.handleThemeToggle);
            }
            // Adicionar novo listener
            this.handleThemeToggle = (e) => {
                e.stopPropagation();
                console.log('🌙 Botão de tema clicado no menu hambúrguer');
                if (window.darkMode) {
                    window.darkMode.toggle();
                }
            };
            themeToggle.addEventListener('click', this.handleThemeToggle);
            console.log('✅ Listener de tema adicionado ao menu hambúrguer');
        } else {
            console.log('❌ Botão de tema não encontrado no menu hambúrguer');
        }
    }
}

// Initialize Hamburger Menu
document.addEventListener('DOMContentLoaded', function() {
    window.hamburgerMenu = new HamburgerMenuManager();
    console.log('🍔 Menu Hambúrguer carregado');
    
    // Listener para mudanças de tema
    document.addEventListener('themeChanged', function() {
        if (window.hamburgerMenu) {
            window.hamburgerMenu.applyTheme();
        }
    });
});