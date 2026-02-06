/**
 * Serving Hearts-Uganda Enhanced JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ===================================
    // Initialize AOS (Animate On Scroll)
    // ===================================
    AOS.init({
        duration: 1000,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });
    
    // ===================================
    // Navbar Scroll Effect
    // ===================================
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    
    // ===================================
    // Smooth Scroll for Anchor Links
    // ===================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                
                const target = document.querySelector(href);
                const navbarHeight = navbar.offsetHeight;
                const targetPosition = target.offsetTop - navbarHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // ===================================
    // Auto-close Mobile Menu on Link Click
    // ===================================
    const navLinks = document.querySelectorAll('.nav-link');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 992) {
                navbarCollapse.classList.remove('show');
            }
        });
    });
    
    // ===================================
    // Animated Counter for Statistics
    // ===================================
    const counters = document.querySelectorAll('.counter');
    let hasAnimated = false;
    
    function animateCounters() {
        if (hasAnimated) return;
        
        const statsSection = document.querySelector('.impact-stats');
        if (!statsSection) return;
        
        const rect = statsSection.getBoundingClientRect();
        const isVisible = rect.top < window.innerHeight && rect.bottom >= 0;
        
        if (isVisible) {
            hasAnimated = true;
            
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                const suffix = counter.textContent.replace(/[0-9]/g, '');
                let current = 0;
                const increment = target / 100;
                const duration = 2000; // 2 seconds
                const stepTime = duration / 100;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = formatNumber(target) + suffix;
                        clearInterval(timer);
                    } else {
                        counter.textContent = formatNumber(Math.floor(current)) + suffix;
                    }
                }, stepTime);
            });
        }
    }
    
    // Format number with commas
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    
    window.addEventListener('scroll', animateCounters);
    animateCounters(); // Check on page load
    
    // ===================================
    // Carousel Auto-play Enhancement
    // ===================================
    const heroCarousel = document.getElementById('heroCarousel');
    if (heroCarousel) {
        const carousel = new bootstrap.Carousel(heroCarousel, {
            interval: 5000, // 5 seconds
            pause: 'hover',
            wrap: true
        });
        
        // Pause on user interaction
        heroCarousel.addEventListener('mouseenter', () => {
            carousel.pause();
        });
        
        heroCarousel.addEventListener('mouseleave', () => {
            carousel.cycle();
        });
    }
    
    // ===================================
    // Parallax Effect for Hero Section
    // ===================================
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const heroSlides = document.querySelectorAll('.hero-slide');
        
        heroSlides.forEach(slide => {
            const speed = 0.5;
            slide.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });
    
    // ===================================
    // Back to Top Button
    // ===================================
    const backToTopButton = document.createElement('button');
    backToTopButton.innerHTML = '<i class="bi bi-arrow-up"></i>';
    backToTopButton.className = 'btn btn-primary back-to-top';
    backToTopButton.setAttribute('aria-label', 'Back to top');
    document.body.appendChild(backToTopButton);
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTopButton.classList.add('show');
        } else {
            backToTopButton.classList.remove('show');
        }
    });
    
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // ===================================
    // Image Lazy Loading
    // ===================================
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.add('loaded');
                    }
                    observer.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // ===================================
    // Card Hover Effect Enhancement
    // ===================================
    const programCards = document.querySelectorAll('.program-card');
    
    programCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-15px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // ===================================
    // Form Validation Helper
    // ===================================
    window.validateForm = function(formId) {
        const form = document.getElementById(formId);
        if (!form) return false;
        
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
                
                // Add error message if not exists
                if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('invalid-feedback')) {
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'invalid-feedback';
                    errorMsg.textContent = 'This field is required';
                    field.parentNode.insertBefore(errorMsg, field.nextSibling);
                }
            } else {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
            }
        });
        
        return isValid;
    };
    
    // Remove validation classes on input
    document.querySelectorAll('input, textarea, select').forEach(field => {
        field.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            this.classList.remove('is-valid');
        });
    });
    
    // ===================================
    // Toast Notification System
    // ===================================
    window.showToast = function(message, type = 'success') {
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        const toastId = 'toast-' + Date.now();
        const iconMap = {
            success: 'bi-check-circle-fill',
            error: 'bi-x-circle-fill',
            warning: 'bi-exclamation-triangle-fill',
            info: 'bi-info-circle-fill'
        };
        
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi ${iconMap[type]} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 4000
        });
        
        toast.show();
        
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    };
    
    // ===================================
    // Preloader (Optional)
    // ===================================
    window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            preloader.style.opacity = '0';
            setTimeout(() => {
                preloader.style.display = 'none';
            }, 300);
        }
    });
    
    // ===================================
    // Active Link Highlighting
    // ===================================
    const currentLocation = window.location.pathname.split('/').pop() || 'index.php';
    const menuItems = document.querySelectorAll('.nav-link');
    
    menuItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href === currentLocation) {
            item.classList.add('active');
        }
    });
    
    // ===================================
    // Sticky Elements on Scroll
    // ===================================
    const stickyElements = document.querySelectorAll('[data-sticky]');
    
    stickyElements.forEach(element => {
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    element.classList.add('is-sticky');
                } else {
                    element.classList.remove('is-sticky');
                }
            },
            { threshold: 0.1 }
        );
        
        observer.observe(element);
    });
    
    // ===================================
    // Enhanced Card Animations
    // ===================================
    const newsCards = document.querySelectorAll('.news-card');
    
    newsCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
    
    // ===================================
    // Print Functionality
    // ===================================
    window.printPage = function() {
        window.print();
    };
    
    // ===================================
    // Copy to Clipboard
    // ===================================
    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Copied to clipboard!', 'success');
        }).catch(() => {
            showToast('Failed to copy', 'error');
        });
    };
    
    // ===================================
    // Loading State Handler
    // ===================================
    window.setLoading = function(buttonId, isLoading) {
        const button = document.getElementById(buttonId);
        if (!button) return;
        
        if (isLoading) {
            button.disabled = true;
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
        } else {
            button.disabled = false;
            button.innerHTML = button.dataset.originalText;
        }
    };
    
    // ===================================
    // Read More / Read Less Toggle
    // ===================================
    window.toggleReadMore = function(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        const isExpanded = element.classList.toggle('expanded');
        const button = element.nextElementSibling;
        
        if (button && button.classList.contains('read-more-btn')) {
            button.textContent = isExpanded ? 'Read Less' : 'Read More';
        }
    };
    
    // ===================================
    // Console Log
    // ===================================
    console.log('%cServing Hearts-Uganda Website', 'color: #667eea; font-size: 20px; font-weight: bold;');
    console.log('%cDeveloped with ❤️ for community empowerment', 'color: #764ba2; font-size: 14px;');
    console.log('Version: 1.0.0');
});

// ===================================
// Utility Functions
// ===================================

// Debounce function for performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function for scroll events
function throttle(func, limit) {
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
}