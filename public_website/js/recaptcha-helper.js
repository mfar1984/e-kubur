/**
 * reCAPTCHA Helper for PPJUB Public Website
 * Handles reCAPTCHA v2 Invisible integration
 */

class RecaptchaHelper {
    constructor() {
        this.siteKey = null;
        this.enabled = false;
        this.widgetId = null;
        this.isLoaded = false;
    }

    /**
     * Initialize reCAPTCHA
     */
    async init() {
        try {
    
            
            // Fetch configuration from API (absolute URL to Laravel)
            const response = await fetch('http://localhost:8000/api/v1/recaptcha/config');
            const data = await response.json();
            

            
            if (data.success && data.data.enabled) {
                this.enabled = true;
                this.siteKey = data.data.site_key;
                

                
                // Load Google reCAPTCHA script
                await this.loadRecaptchaScript();
                
                // Initialize reCAPTCHA widget
                this.initWidget();
                
    
            } else {

            }
        } catch (error) {
            console.error('Failed to initialize reCAPTCHA:', error);
        }
    }

    /**
     * Load Google reCAPTCHA script
     */
    loadRecaptchaScript() {
        return new Promise((resolve, reject) => {
            if (window.grecaptcha && window.grecaptcha.render) {
                this.isLoaded = true;
                resolve();
                return;
            }

            // Load script with explicit callback
            const script = document.createElement('script');
            script.src = 'https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit';
            script.async = true;
            script.defer = true;
            
            // Define global callback
            window.onRecaptchaLoad = () => {
    
                this.isLoaded = true;
                resolve();
            };
            
            script.onerror = () => {
                reject(new Error('Failed to load reCAPTCHA script'));
            };
            
            document.head.appendChild(script);
        });
    }

    /**
     * Initialize reCAPTCHA widget
     */
    initWidget() {
        if (!this.enabled || !this.siteKey || !this.isLoaded) {

            return;
        }

        // Double check grecaptcha is available
        if (typeof window.grecaptcha === 'undefined' || typeof window.grecaptcha.render !== 'function') {
            console.error('grecaptcha.render is not available');
            return;
        }

        // Check if container exists
        const container = document.getElementById('recaptcha-container');
        if (!container) {
            console.error('reCAPTCHA container not found');
            return;
        }

        try {
            // Clear container first
            container.innerHTML = '';
            


            
            // Create invisible reCAPTCHA widget
            this.widgetId = grecaptcha.render(container, {
                'sitekey': this.siteKey,
                'callback': this.onRecaptchaSuccess.bind(this),
                'expired-callback': this.onRecaptchaExpired.bind(this),
                'error-callback': this.onRecaptchaError.bind(this),
                'size': 'invisible',
                'badge': 'bottomright',
                'theme': 'light'
            });


            
            // Verify widget was created properly
            // Note: Invisible reCAPTCHA often returns ID 0, which is valid
            if (this.widgetId === null) {
                console.error('Widget ID is null, widget creation failed');
                console.error('Container HTML:', container.innerHTML);
                return;
            }
            
            // Widget ID 0 is valid for invisible reCAPTCHA

            

            
        } catch (error) {
            console.error('Failed to create reCAPTCHA widget:', error);
            console.error('Error details:', error.message, error.stack);
        }
    }

    /**
     * Execute reCAPTCHA verification
     */
    execute() {
        return new Promise((resolve, reject) => {
            if (!this.enabled || !this.isLoaded) {
    
                resolve(null); // reCAPTCHA disabled
                return;
            }

            // For invisible reCAPTCHA, widgetId can be 0
            if (this.widgetId === null) {
                console.error('Invalid widget ID:', this.widgetId);
                reject(new Error('Invalid reCAPTCHA widget ID'));
                return;
            }

            try {
                // Store resolve/reject for callback
                this.currentPromise = { resolve, reject };
                
                
                
                // Execute reCAPTCHA
                grecaptcha.execute(this.widgetId);
                
                // Set timeout for callback
                setTimeout(() => {
                    if (this.currentPromise) {
                        console.error('reCAPTCHA execution timeout');
                        this.currentPromise.reject(new Error('reCAPTCHA execution timeout'));
                        this.currentPromise = null;
                    }
                }, 10000); // 10 second timeout
                
            } catch (error) {
                console.error('reCAPTCHA execute error:', error);
                reject(error);
            }
        });
    }

    /**
     * Reset reCAPTCHA widget
     */
    reset() {
        if (this.enabled && this.widgetId) {
            grecaptcha.reset(this.widgetId);
        }
    }

    /**
     * Get reCAPTCHA response token
     */
    getResponse() {
        if (this.enabled && this.widgetId) {
            return grecaptcha.getResponse(this.widgetId);
        }
        return null;
    }

    /**
     * Callback when reCAPTCHA succeeds
     */
    onRecaptchaSuccess(token) {
        
        
        
        if (this.currentPromise && this.currentPromise.resolve) {
            this.currentPromise.resolve(token);
            this.currentPromise = null;

        } else {
            console.error('No current promise to resolve');
        }
    }

    /**
     * Callback when reCAPTCHA expires
     */
    onRecaptchaExpired() {
        if (this.currentPromise && this.currentPromise.reject) {
            this.currentPromise.reject(new Error('reCAPTCHA expired'));
            this.currentPromise = null;
        }
    }

    /**
     * Callback when reCAPTCHA errors
     */
    onRecaptchaError() {
        if (this.currentPromise && this.currentPromise.reject) {
            this.currentPromise.reject(new Error('reCAPTCHA error'));
            this.currentPromise = null;
        }
    }

    /**
     * Check if reCAPTCHA is ready
     */
    isReady() {
        // Widget ID 0 is valid for invisible reCAPTCHA
        const ready = this.enabled && this.isLoaded && this.widgetId !== null;
        return ready;
    }
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RecaptchaHelper;
} else {
    window.RecaptchaHelper = RecaptchaHelper;
}
