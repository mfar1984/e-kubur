// Main JavaScript for PPJUB Public Website

// Live Date & Time Function
function updateDateTime() {
    const now = new Date();
    
    // Update date
    const dateElement = document.getElementById('current-date');
    if (dateElement) {
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        dateElement.textContent = now.toLocaleDateString('ms-MY', options);
    }
    
    // Update time
    const timeElement = document.getElementById('current-time');
    if (timeElement) {
        timeElement.textContent = now.toLocaleTimeString('ms-MY', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        });
    }
}

// Update date and time every second
setInterval(updateDateTime, 1000);

document.addEventListener('DOMContentLoaded', function() {
    // Footer modal links
    const modalOverlay = document.getElementById('modal-overlay');
    const modalTitle = document.getElementById('modal-title');
    const modalContent = document.getElementById('modal-content');
    const modalClose = document.getElementById('modal-close');

    function openModal(title, url) {
        modalTitle.textContent = title || 'Maklumat';
        modalContent.innerHTML = '<p>Memuatkan...</p>';
        modalOverlay.style.display = 'flex';
        // Fetch the HTML content from the page and inject
        fetch(url)
            .then(r => r.text())
            .then(html => { modalContent.innerHTML = html; })
            .catch(() => { modalContent.innerHTML = '<p>Ralat memuatkan kandungan.</p>'; });
    }

    if (modalClose) {
        modalClose.addEventListener('click', () => { modalOverlay.style.display = 'none'; });
    }
    if (modalOverlay) {
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) modalOverlay.style.display = 'none';
        });
    }

    const footerLinks = document.querySelectorAll('.footer-links a.footer-link');
    footerLinks.forEach(a => {
        a.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            const title = this.textContent.trim();
            // Map pretty routes to static pages
            const pageMap = {
                '/penafian': 'pages/penafian.html',
                '/privasi': 'pages/privasi.html',
                '/terma': 'pages/terma.html',
                '/peta-laman': 'pages/peta-laman.html'
            };
            openModal(title, pageMap[href] || href);
        });
    });

    // Show selected attachment file names
    const attachmentInput = document.getElementById('attachments');
    const attachmentList = document.getElementById('attachment-list');
    if (attachmentInput && attachmentList) {
        attachmentInput.addEventListener('change', function() {
            attachmentList.innerHTML = '';
            const files = Array.from(this.files || []);
            files.forEach(file => {
                const ext = (file.name.split('.').pop() || '').toLowerCase();
                const isImage = ['jpg','jpeg','png'].includes(ext);
                const iconClass = isImage ? 'fa-regular fa-image' : 'fa-regular fa-file-pdf';
                const li = document.createElement('li');
                const icon = document.createElement('i');
                icon.className = iconClass;
                const nameSpan = document.createElement('span');
                nameSpan.textContent = `${file.name} (${Math.round(file.size/1024)} KB)`;
                li.appendChild(icon);
                li.appendChild(nameSpan);
                attachmentList.appendChild(li);
            });
        });
    }
    // Initialize date and time
    updateDateTime();
    
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
            
            // Update search placeholder
            const searchInput = document.getElementById('search_term');
            searchInput.placeholder = 'Masukkan nama atau nombor IC si mati';
        });
    });
    
    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const nav = document.querySelector('.nav');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    
    if (mobileMenuBtn && nav) {
        mobileMenuBtn.addEventListener('click', function() {
            nav.classList.toggle('active');
            this.classList.toggle('active');
            if (mobileMenuOverlay) {
                mobileMenuOverlay.classList.toggle('active');
            }
            // Prevent body scroll when menu is open
            const isOpen = nav.classList.contains('active');
            document.body.style.overflow = isOpen ? 'hidden' : '';
            document.body.classList.toggle('menu-open', isOpen);
        });
        
        // Close menu when clicking overlay
        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener('click', function() {
                nav.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
                this.classList.remove('active');
                document.body.style.overflow = '';
                document.body.classList.remove('menu-open');
            });
        }
        
        // Close menu when clicking close button
        const mobileMenuClose = document.getElementById('mobileMenuClose');
        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', function() {
                nav.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
                if (mobileMenuOverlay) {
                    mobileMenuOverlay.classList.remove('active');
                }
                document.body.style.overflow = '';
                document.body.classList.remove('menu-open');
            });
        }
        
        // Close menu when clicking on nav links (auto-close)
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                nav.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
                if (mobileMenuOverlay) {
                    mobileMenuOverlay.classList.remove('active');
                }
                document.body.style.overflow = '';
                document.body.classList.remove('menu-open');
            });
        });
    }
    
    // Search Form Validation
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        // Check if this is a feedback form (has telefon field)
        const isFeedbackForm = searchForm.querySelector('#telefon');
        
        if (!isFeedbackForm) {
            // Only apply search validation for non-feedback forms
            searchForm.addEventListener('submit', function(e) {
                const searchTerm = document.getElementById('search_term');
                if (searchTerm && searchTerm.value.trim().length < 2) {
                    e.preventDefault();
                    showAlert('Sila masukkan sekurang-kurangnya 2 aksara untuk carian.', 'error');
                    return false;
                }
            });
        }
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
        // Check if this is a feedback form
        const isFeedbackForm = searchForm && searchForm.querySelector('#telefon');
        
        if (!isFeedbackForm) {
            // Only apply search loading for non-feedback forms
            searchForm.addEventListener('submit', function() {
                searchBtn.innerHTML = '<span class="loading-spinner"></span> Mencari...';
                searchBtn.disabled = true;
            });
        }
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

// Add CSS for loading spinner only (menu styles handled in stylesheet)
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
    @keyframes spin { to { transform: rotate(360deg); } }
`;
document.head.appendChild(style);
