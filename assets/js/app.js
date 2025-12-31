/**
 * DESMARÉ Finance - Main JavaScript
 */

// Toggle Sidebar (Mobile)
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.querySelector('.menu-toggle');
    
    if (sidebar && sidebar.classList.contains('active')) {
        if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
            sidebar.classList.remove('active');
            document.querySelector('.sidebar-overlay').classList.remove('active');
        }
    }
});

// Format number as currency
function formatCurrency(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(number);
}

// Format input as currency while typing
function formatInputCurrency(input) {
    let value = input.value.replace(/\D/g, '');
    value = parseInt(value) || 0;
    input.value = value.toLocaleString('id-ID');
    
    // Store raw value in hidden field if exists
    const hiddenField = document.getElementById(input.id + '_raw');
    if (hiddenField) {
        hiddenField.value = value;
    }
}

// Parse formatted currency back to number
function parseCurrency(formatted) {
    return parseInt(formatted.replace(/\D/g, '')) || 0;
}

// Confirm delete
function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
    return confirm(message);
}

// Modal Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(modal => {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
});

// Auto-hide alerts after 5 seconds
document.querySelectorAll('.alert').forEach(alert => {
    setTimeout(() => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        setTimeout(() => alert.remove(), 300);
    }, 5000);
});

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    let isValid = true;
    
    form.querySelectorAll('[required]').forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

// Edit data - populate form
function editData(id, data) {
    // Populate form fields
    Object.keys(data).forEach(key => {
        const field = document.getElementById('edit_' + key);
        if (field) {
            field.value = data[key];
        }
    });
    
    // Set form action
    const form = document.getElementById('editForm');
    if (form) {
        const actionField = form.querySelector('[name="action"]');
        if (actionField) actionField.value = 'update';
        
        const idField = form.querySelector('[name="id"]');
        if (idField) idField.value = id;
    }
    
    openModal('editModal');
}

// Reset form
function resetForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
    }
}

// Initialize tooltips and other components
document.addEventListener('DOMContentLoaded', function() {
    // Add loading state to forms on submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner" style="width: 16px; height: 16px; border-width: 2px;"></span> Memproses...';
            }
        });
    });
    
    // Format currency inputs
    document.querySelectorAll('.currency-input').forEach(input => {
        input.addEventListener('input', function() {
            formatInputCurrency(this);
        });
    });
    
    console.log('DESMARÉ Finance App initialized');
});
