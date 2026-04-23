let currentInput = '';
let previousInput = '';
let operator = null;
let waitingForOperand = false;

const display = document.getElementById('display');
const historyList = document.getElementById('historyList');
const errorMessage = document.getElementById('errorMessage');
const successMessage = document.getElementById('successMessage');

// Number buttons
document.querySelectorAll('.number').forEach(button => {
    button.addEventListener('click', () => {
        const value = button.getAttribute('data-value');
        
        if (waitingForOperand) {
            currentInput = value;
            waitingForOperand = false;
        } else {
            if (value === '.' && currentInput.includes('.')) return;
            if (value === '00' && currentInput === '0') return;
            currentInput = currentInput === '0' && value !== '.' ? value : currentInput + value;
        }
        
        updateDisplay();
    });
});

// Operator buttons
document.querySelectorAll('.operator').forEach(button => {
    button.addEventListener('click', () => {
        if (currentInput === '' && previousInput === '') return;
        
        if (currentInput !== '' && previousInput !== '' && operator) {
            calculate();
        }
        
        operator = button.getAttribute('data-operator');
        if (currentInput !== '') {
            previousInput = currentInput;
            currentInput = '';
        }
        waitingForOperand = false;
    });
});

// Equals button
document.querySelector('.equals').addEventListener('click', () => {
    if (currentInput === '' || previousInput === '' || operator === null) {
        showError('Please enter a complete calculation');
        return;
    }
    calculate();
});

// Clear button
document.querySelector('.clear').addEventListener('click', () => {
    currentInput = '';
    previousInput = '';
    operator = null;
    waitingForOperand = false;
    updateDisplay();
    showError('', false);
});

// Clear all history button
document.getElementById('clearHistoryBtn').addEventListener('click', () => {
    if (confirm('Are you sure you want to clear all calculation history?')) {
        clearAllHistory();
    }
});

function updateDisplay() {
    if (currentInput !== '') {
        display.value = currentInput;
    } else if (previousInput !== '') {
        display.value = previousInput;
    } else {
        display.value = '0';
    }
}

async function calculate() {
    const num1 = parseFloat(previousInput);
    const num2 = parseFloat(currentInput);
    
    if (isNaN(num1) || isNaN(num2)) {
        showError('Invalid numbers');
        return;
    }
    
    const calculation = {
        num1: num1,
        num2: num2,
        operator: operator
    };
    
    try {
        const response = await fetch('backend/api/save_calculation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(calculation)
        });
        
        const result = await response.json();
        
        if (result.success) {
            currentInput = result.result.toString();
            previousInput = '';
            operator = null;
            waitingForOperand = true;
            updateDisplay();
            loadHistory();
            showSuccess('Calculation saved successfully!');
            showError('', false);
        } else {
            showError(result.error || 'Calculation failed');
        }
    } catch (error) {
        showError('Network error: ' + error.message);
    }
}

async function loadHistory() {
    try {
        const response = await fetch('backend/api/get_history.php');
        const result = await response.json();
        
        if (result.success && result.data) {
            displayHistory(result.data);
        } else {
            historyList.innerHTML = '<div class="empty-history">No calculations yet</div>';
        }
    } catch (error) {
        console.error('Error loading history:', error);
        historyList.innerHTML = '<div class="empty-history">Failed to load history</div>';
    }
}

function displayHistory(calculations) {
    if (!calculations || calculations.length === 0) {
        historyList.innerHTML = '<div class="empty-history">No calculations yet</div>';
        return;
    }
    
    historyList.innerHTML = calculations.map(calc => {
        const operatorSymbol = getOperatorSymbol(calc.operator);
        const date = new Date(calc.created_at).toLocaleString();
        
        return `
            <div class="history-item" data-id="${calc.id}">
                <div class="history-item-content">
                    ${calc.num1} ${operatorSymbol} ${calc.num2} = ${calc.result}
                </div>
                <div class="history-item-date">${date}</div>
                <button onclick="deleteCalculation(${calc.id})">
                    <img src="assets/icons/delete-icon.svg" alt="Delete" style="width: 14px; height: 14px;">
                    Delete
                </button>
            </div>
        `;
    }).join('');
}

function getOperatorSymbol(operator) {
    const symbols = {
        '+': '+',
        '-': '-',
        '*': '×',
        '/': '÷'
    };
    return symbols[operator] || operator;
}

async function deleteCalculation(id) {
    try {
        const response = await fetch('backend/api/delete_calculation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id })
        });
        
        const result = await response.json();
        
        if (result.success) {
            loadHistory();
            showSuccess('Calculation deleted successfully!');
        } else {
            showError(result.error || 'Failed to delete calculation');
        }
    } catch (error) {
        showError('Failed to delete calculation');
    }
}

async function clearAllHistory() {
    try {
        // First get all history items
        const response = await fetch('backend/api/get_history.php');
        const result = await response.json();
        
        if (result.success && result.data) {
            // Delete each calculation
            for (const calc of result.data) {
                await fetch('backend/api/delete_calculation.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: calc.id })
                });
            }
            loadHistory();
            showSuccess('All history cleared successfully!');
        }
    } catch (error) {
        showError('Failed to clear history');
    }
}

function showError(message, show = true) {
    if (show && message) {
        errorMessage.textContent = message;
        errorMessage.classList.add('show');
        setTimeout(() => {
            errorMessage.classList.remove('show');
        }, 3000);
    } else {
        errorMessage.classList.remove('show');
    }
}

function showSuccess(message) {
    successMessage.textContent = message;
    successMessage.classList.add('show');
    setTimeout(() => {
        successMessage.classList.remove('show');
    }, 3000);
}

// Keyboard support
document.addEventListener('keydown', (e) => {
    if (e.key >= '0' && e.key <= '9') {
        document.querySelector(`.number[data-value="${e.key}"]`)?.click();
    } else if (e.key === '.') {
        document.querySelector('.number[data-value="."]')?.click();
    } else if (e.key === '+' || e.key === '-' || e.key === '*' || e.key === '/') {
        let operatorMap = {'+': '+', '-': '-', '*': '*', '/': '/'};
        document.querySelector(`.operator[data-operator="${operatorMap[e.key]}"]`)?.click();
    } else if (e.key === 'Enter' || e.key === '=') {
        document.querySelector('.equals')?.click();
    } else if (e.key === 'Escape' || e.key === 'c' || e.key === 'C') {
        document.querySelector('.clear')?.click();
    }
});

// Load history on page load
loadHistory();