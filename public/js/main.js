// Main JavaScript for PPJUB Public Website

document.addEventListener('DOMContentLoaded', function() {
    
    // Tab Functionality
    const tabBtns = document.querySelectorAll('.tab-btn');
    const searchTypeInput = document.getElementById('search_type');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all tabs
            tabBtns.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Update hidden input value
            const tabType = this.getAttribute('data-tab');
            searchTypeInput.value = tabType;
            
            // Update search placeholder based on tab
            const searchInput = document.getElementById('search_term');
            if (tabType === 'kematian') {
                searchInput.placeholder = 'Masukkan nama atau nombor IC si mati';
            } else {
                searchInput.placeholder = 'Masukkan nama atau nombor IC ahli PPJUB';
            }
        });
    });
    
    // Mobile Menu Toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileMenuBtn && navMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            this.classList.toggle('active');
        });
    }
    
    // Search Form Validation
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchTerm = document.getElementById('search_term').value.trim();
            
            if (searchTerm.length < 2) {
                e.preventDefault();
                showAlert('Sila masukkan sekurang-kurangnya 2 aksara untuk carian.', 'error');
                return false;
            }
        });
    }
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
    
    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add loading state to search button
    const searchBtn = document.querySelector('.search-btn');
    if (searchBtn) {
        searchForm.addEventListener('submit', function() {
            searchBtn.innerHTML = '<span class="loading-spinner"></span> Mencari...';
            searchBtn.disabled = true;
        });
    }
    
    // Add hover effects to result cards
    const resultCards = document.querySelectorAll('.result-card');
    resultCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Initialize tooltips for status badges
    initializeTooltips();
    
    // Add search suggestions (if needed)
    initializeSearchSuggestions();
});

// Show alert function
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const searchContainer = document.querySelector('.search-container');
    if (searchContainer) {
        searchContainer.insertBefore(alertDiv, searchContainer.firstChild);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            alertDiv.style.opacity = '0';
            setTimeout(() => {
                alertDiv.remove();
            }, 300);
        }, 5000);
    }
}

// Initialize tooltips
function initializeTooltips() {
    const statusBadges = document.querySelectorAll('.status');
    statusBadges.forEach(badge => {
        const status = badge.textContent.toLowerCase();
        let tooltipText = '';
        
        switch(status) {
            case 'selesai':
                tooltipText = 'Perkhidmatan telah selesai';
                break;
            case 'proses':
                tooltipText = 'Perkhidmatan sedang dijalankan';
                break;
            case 'belum-mula':
                tooltipText = 'Perkhidmatan belum dimulakan';
                break;
            case 'aktif':
                tooltipText = 'Ahli PPJUB yang aktif';
                break;
            case 'tidak-aktif':
                tooltipText = 'Ahli PPJUB yang tidak aktif';
                break;
        }
        
        if (tooltipText) {
            badge.setAttribute('title', tooltipText);
        }
    });
}

// Initialize search suggestions
function initializeSearchSuggestions() {
    const searchInput = document.getElementById('search_term');
    if (!searchInput) return;
    
    // Add input event listener for real-time suggestions
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        if (query.length >= 2) {
            // Here you could implement AJAX search suggestions
            // For now, we'll just add a visual indicator
            this.style.borderColor = '#28a745';
        } else {
            this.style.borderColor = '#e9ecef';
        }
    });
    
    // Add focus effects
    searchInput.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
    });
    
    searchInput.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
    });
}

// Add CSS for loading spinner
const style = document.createElement('style');
style.textContent = `
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .nav-menu.active {
        display: flex;
    }
    
    @media (max-width: 768px) {
        .nav-menu {
            display: none;
        }
        
        .nav-menu.active {
            display: flex;
        }
    }
`;
document.head.appendChild(style);
