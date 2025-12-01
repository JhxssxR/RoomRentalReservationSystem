/**
 * Room Rental Reservation System
 * Main JavaScript File
 */

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

/**
 * Initialize Application
 */
function initializeApp() {
    initMobileNav();
    initFormValidation();
    initDatePickers();
    initAlertDismiss();
    initImagePreview();
    initConfirmDialogs();
}

/**
 * Mobile Navigation Toggle
 */
function initMobileNav() {
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (menuButton && mobileMenu) {
        menuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
}

/**
 * Form Validation
 */
function initFormValidation() {
    const forms = document.querySelectorAll('[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(form)) {
                e.preventDefault();
            }
        });
    });
}

function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('[required]');
    
    // Clear previous errors
    form.querySelectorAll('.error-message').forEach(el => el.remove());
    form.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            showInputError(input, 'This field is required');
            isValid = false;
        } else if (input.type === 'email' && !isValidEmail(input.value)) {
            showInputError(input, 'Please enter a valid email address');
            isValid = false;
        } else if (input.type === 'password' && input.value.length < 6) {
            showInputError(input, 'Password must be at least 6 characters');
            isValid = false;
        } else if (input.dataset.match) {
            const matchInput = document.getElementById(input.dataset.match);
            if (matchInput && input.value !== matchInput.value) {
                showInputError(input, 'Passwords do not match');
                isValid = false;
            }
        }
    });
    
    return isValid;
}

function showInputError(input, message) {
    input.classList.add('input-error', 'border-red-500');
    const error = document.createElement('p');
    error.className = 'error-message text-red-500 text-sm mt-1';
    error.textContent = message;
    input.parentNode.appendChild(error);
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

/**
 * Date Picker Initialization
 */
function initDatePickers() {
    const checkInDate = document.getElementById('check_in_date');
    const checkOutDate = document.getElementById('check_out_date');
    
    if (checkInDate) {
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        checkInDate.setAttribute('min', today);
        
        checkInDate.addEventListener('change', function() {
            if (checkOutDate) {
                // Set minimum checkout date to check-in date
                checkOutDate.setAttribute('min', this.value);
                
                // Clear checkout if it's before check-in
                if (checkOutDate.value && checkOutDate.value < this.value) {
                    checkOutDate.value = '';
                }
            }
            
            calculateTotalPrice();
        });
    }
    
    if (checkOutDate) {
        checkOutDate.addEventListener('change', calculateTotalPrice);
    }
}

/**
 * Calculate Total Price based on dates
 */
function calculateTotalPrice() {
    const checkIn = document.getElementById('check_in_date');
    const checkOut = document.getElementById('check_out_date');
    const pricePerNight = document.getElementById('price_per_night');
    const totalPrice = document.getElementById('total_price');
    
    if (checkIn && checkOut && pricePerNight && totalPrice && checkIn.value && checkOut.value) {
        const start = new Date(checkIn.value);
        const end = new Date(checkOut.value);
        const nights = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        
        if (nights > 0) {
            const price = parseFloat(pricePerNight.value) || 0;
            const total = nights * price;
            totalPrice.textContent = formatCurrency(total);
            
            if (document.getElementById('total_price_input')) {
                document.getElementById('total_price_input').value = total;
            }
        }
    }
}

/**
 * Format Currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP'
    }).format(amount);
}

/**
 * Alert Dismiss Functionality
 */
function initAlertDismiss() {
    const alerts = document.querySelectorAll('[data-dismiss="alert"]');
    
    alerts.forEach(button => {
        button.addEventListener('click', function() {
            const alert = this.closest('.alert');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            }
        });
    });
    
    // Auto dismiss alerts after 5 seconds
    document.querySelectorAll('.alert-auto-dismiss').forEach(alert => {
        setTimeout(() => {
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    });
}

/**
 * Image Preview for File Upload
 */
function initImagePreview() {
    const imageInputs = document.querySelectorAll('[data-image-preview]');
    
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const previewId = this.dataset.imagePreview;
            const preview = document.getElementById(previewId);
            
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
}

/**
 * Confirmation Dialogs
 */
function initConfirmDialogs() {
    const confirmButtons = document.querySelectorAll('[data-confirm]');
    
    confirmButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.dataset.confirm || 'Are you sure you want to proceed?';
            
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Show Notification
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 fade-in alert-${type}`;
    notification.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button class="ml-4" data-dismiss="alert">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto dismiss
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-10px)';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
    
    // Manual dismiss
    notification.querySelector('[data-dismiss="alert"]').addEventListener('click', () => {
        notification.remove();
    });
}

/**
 * Room Filter Functionality
 */
function filterRooms(filterType, filterValue) {
    const rooms = document.querySelectorAll('.room-card');
    
    rooms.forEach(room => {
        const roomValue = room.dataset[filterType];
        
        if (filterValue === 'all' || roomValue === filterValue) {
            room.style.display = 'block';
        } else {
            room.style.display = 'none';
        }
    });
}

/**
 * Table Search Functionality
 */
function searchTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    
    if (!input || !table) return;
    
    input.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
}

/**
 * Toggle Password Visibility
 */
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}
