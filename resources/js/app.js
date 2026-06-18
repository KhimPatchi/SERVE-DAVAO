import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Landing Page & General UI Logic


// Contact form handling
function initContactForm() {
    const contactForm = document.getElementById('contactForm');
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    const successText = document.getElementById('successText');
    const errorText = document.getElementById('errorText');

    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Clear previous messages
            successMessage.classList.add('hidden');
            errorMessage.classList.add('hidden');

            // reCAPTCHA check
            const recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse) {
                errorText.textContent = 'Please complete the reCAPTCHA verification.';
                errorMessage.classList.remove('hidden');
                return;
            }

            // Show loading state
            const submitButton = contactForm.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<span>Sending...</span>';
            submitButton.disabled = true;

            // Get form data
            const formData = new FormData(contactForm);
            // Explicitly set the token just to be sure
            formData.set('g-recaptcha-response', recaptchaResponse);

            // Send AJAX request
            fetch(contactForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    return response.json().then(data => {
                        return { status: response.status, body: data };
                    });
                })
                .then(({ status, body }) => {
                    if (status >= 200 && status < 300 && body.success) {
                        successText.textContent = body.message || 'Thank you! Your message has been sent successfully.';
                        successMessage.classList.remove('hidden');
                        contactForm.reset();
                        grecaptcha.reset(); // Reset captcha for next use
                    } else {
                        // Handle validation errors or server errors
                        let msg = body.message || 'There was an error sending your message.';

                        if (body.errors) {
                            // If it's a validation error list, grab the first one
                            const firstError = Object.values(body.errors)[0];
                            if (firstError) msg = firstError[0] || msg;
                        }

                        errorText.textContent = msg;
                        errorMessage.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    errorText.textContent = 'Network error. Please try again.';
                    errorMessage.classList.remove('hidden');
                })
                .finally(() => {
                    // Reset button
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                });
        });
    }
}

// Smooth scrolling for navigation links
function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Scroll animation observer
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.scroll-fade-in, .scroll-fade-in-left, .scroll-fade-in-right, .stagger-animate').forEach(el => {
        observer.observe(el);
    });
}

// Enhanced hover effects
function initHoverEffects() {
    const hoverElements = document.querySelectorAll('a, button, .feature-card, .event-card, .contact-card');
    hoverElements.forEach(el => {
        el.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-2px)';
        });
        el.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0)';
        });
    });
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    initSmoothScrolling();
    initScrollAnimations();
    initHoverEffects();
    initContactForm();

});
