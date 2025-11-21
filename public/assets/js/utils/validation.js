class FormValidator {
    constructor() {
        this.errors = {};
    }

    validate(form, rules) {
        this.errors = {};
        const formData = new FormData(form);
        
        for (const [field, fieldRules] of Object.entries(rules)) {
            const value = formData.get(field);
            
            for (const rule of fieldRules.split('|')) {
                const [ruleName, ruleParam] = rule.split(':');
                
                if (!this[ruleName]) continue;
                
                const isValid = this[ruleName](value, ruleParam);
                if (!isValid) {
                    if (!this.errors[field]) {
                        this.errors[field] = [];
                    }
                    this.errors[field].push(this.getErrorMessage(ruleName, field, ruleParam));
                    break;
                }
            }
        }
        
        return Object.keys(this.errors).length === 0;
    }

    required(value) {
        return value !== null && value !== undefined && value.toString().trim() !== '';
    }

    email(value) {
        if (!value) return true;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(value);
    }

    min(value, min) {
        if (!value) return true;
        return value.length >= parseInt(min);
    }

    max(value, max) {
        if (!value) return true;
        return value.length <= parseInt(max);
    }

    numeric(value) {
        if (!value) return true;
        return !isNaN(value);
    }

    getErrorMessage(rule, field, param) {
        const messages = {
            required: `Поле "${field}" обязательно для заполнения`,
            email: `Поле "${field}" должно содержать valid email адрес`,
            min: `Поле "${field}" должно содержать минимум ${param} символов`,
            max: `Поле "${field}" должно содержать максимум ${param} символов`,
            numeric: `Поле "${field}" должно содержать только числа`
        };
        
        return messages[rule] || `Ошибка в поле "${field}"`;
    }

    displayErrors(form) {
        // Очищаем предыдущие ошибки
        this.clearErrors(form);
        
        // Показываем новые ошибки
        for (const [field, messages] of Object.entries(this.errors)) {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.style.color = 'red';
                errorDiv.style.fontSize = '12px';
                errorDiv.style.marginTop = '5px';
                errorDiv.textContent = messages[0];
                
                input.parentNode.appendChild(errorDiv);
                input.style.borderColor = 'red';
            }
        }
    }

    clearErrors(form) {
        const errorMessages = form.querySelectorAll('.error-message');
        errorMessages.forEach(msg => msg.remove());
        
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.style.borderColor = '';
        });
    }
}

// Глобальный экземпляр валидатора
window.formValidator = new FormValidator();

// Пример использования:
// const rules = {
//     username: 'required|min:3|max:20',
//     email: 'required|email',
//     password: 'required|min:6'
// };
// 
// if (formValidator.validate(form, rules)) {
//     // Форма валидна
// } else {
//     formValidator.displayErrors(form);
// }