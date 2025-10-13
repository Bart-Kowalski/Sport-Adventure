(function($) {
    class DataLayer {
        constructor(config) {
            this.config = config;
            this.debug = config.debug;
            window.dataLayer = window.dataLayer || [];
            this.productDataCache = new Map();
            this.eventQueue = [];
            this.isProcessingQueue = false;
            this.throttledPush = this.throttle(this.push.bind(this), config.performance?.throttleDelay || 250);
            
            // Log initialization only if debug is enabled
            if (this.debug?.enabled) {
                this.log('info', 'DataLayer initialized with config:', config);
            }
        }

        /**
         * Throttle function to limit rate of executions
         */
        throttle(func, limit) {
            let inThrottle;
            return function(...args) {
                if (!inThrottle) {
                    func.apply(this, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            }
        }

        /**
         * Push event to dataLayer with validation and debugging
         */
        push(eventName, data) {
            // Skip if event not defined in config
            if (!this.config.events[eventName]) {
                this.log('error', `Event "${eventName}" not defined in configuration`);
                return;
            }

            // Queue the event for batch processing
            if (this.config.events[eventName].batchable) {
                this.eventQueue.push({ eventName, data });
                this.processEventQueue();
            } else {
                // Process critical events immediately
                this.processEvent(eventName, data);
            }
        }

        /**
         * Process queued events in batches
         */
        async processEventQueue() {
            if (this.isProcessingQueue) return;
            this.isProcessingQueue = true;

            while (this.eventQueue.length > 0) {
                const batch = this.eventQueue.splice(0, this.config.performance?.eventQueueSize || 10);
                for (const event of batch) {
                    await this.processEvent(event.eventName, event.data);
                }
                // Small delay between batches
                if (this.eventQueue.length > 0) {
                    await new Promise(resolve => setTimeout(resolve, this.config.performance?.eventQueueDelay || 50));
                }
            }

            this.isProcessingQueue = false;
        }

        /**
         * Process individual event
         */
        async processEvent(eventName, data) {
            const eventConfig = this.config.events[eventName];
            
            // Validate required parameters
            const missingParams = this.validateRequiredParams(eventConfig.required, data);
            if (missingParams.length > 0) {
                this.log('error', `Missing required parameters for "${eventName}":`, missingParams);
                return;
            }

            // Run custom validation if defined
            if (eventConfig.validate && !eventConfig.validate(data)) {
                this.log('error', `Validation failed for "${eventName}"`);
                return;
            }

            // Clear ecommerce object if present
            if (data.ecommerce) {
                window.dataLayer.push({ ecommerce: null });
            }

            // Add user_id to base event data
            const eventData = {
                event: eventName,
                user_id: window.gtm_custom_user_id || '',
                ...data
            };
            
            // Remove any root-level items array to avoid duplication with ecommerce.items
            if (eventData.ecommerce && eventData.ecommerce.items && eventData.items) {
                delete eventData.items;
            }
            
            window.dataLayer.push(eventData);
            
            this.log('event', `DataLayer Event: ${eventName}`, data);
        }

        /**
         * Initialize page-specific tracking with debouncing
         */
        initPage(pageType) {
            const pageConfig = this.config.pageTypes[pageType];
            if (!pageConfig) {
                this.log('warn', `No configuration found for page type: ${pageType}`);
                return;
            }

            this.log('page', `Initializing page type: ${pageType}`);

            // Auto-fire configured events for this page type
            if (pageConfig.autoEvents) {
                // Stagger automatic events
                pageConfig.autoEvents.forEach((eventName, index) => {
                    setTimeout(() => {
                        this.handleAutoEvent(eventName, pageConfig);
                    }, index * 100); // Stagger by 100ms
                });
            }
        }

        /**
         * Handle automatic events based on configuration
         */
        handleAutoEvent(eventName, pageConfig) {
            // Skip events that we handle manually in datalayer.js
            if (eventName === 'view_item' || eventName === 'view_item_list' || eventName === 'begin_checkout') return;

            switch(eventName) {
                default:
                    // Handle other auto events...
                    break;
            }
        }

        /**
         * Get product data with validation and caching
         */
        getProductData($element, includeQuantity = true) {
            const productId = $element.data('product_id');
            
            // Check cache first
            const cacheKey = `${productId}-${includeQuantity}`;
            if (this.productDataCache.has(cacheKey)) {
                return this.productDataCache.get(cacheKey);
            }

            // Get base product data
            const baseData = this.getBaseProductData($element);
            if (!baseData) return null;

            const data = {
                ...baseData,
                quantity: includeQuantity ? this.getProductQuantity($element) : null
            };

            // Validate required fields
            const missingParams = this.validateRequiredParams(
                this.config.productData.required, 
                data
            );

            if (missingParams.length > 0) {
                this.log('error', 'Missing required product data:', missingParams);
                return null;
            }

            // Cache the result
            this.productDataCache.set(cacheKey, data);
            
            // Clear cache after configured duration
            setTimeout(() => {
                this.productDataCache.delete(cacheKey);
            }, this.config.performance?.productCacheDuration || 5 * 60 * 1000);

            return data;
        }

        /**
         * Get base product data from various sources with error handling
         */
        getBaseProductData($element) {
            try {
                // First try saDataLayer
                if (window.saDataLayer?.product) {
                    return window.saDataLayer.product;
                }
                
                // Then try data attributes
                const productData = $element.data('product');
                if (productData) {
                    return productData;
                }
                
                // Finally try WooCommerce data
                const $form = $element.closest('form.cart');
                if ($form.length) {
                    const variationId = $form.find('input[name="variation_id"]').val();
                    const productId = $form.find('input[name="product_id"]').val();
                    
                    if (variationId && window.saDataLayer?.product?.variations) {
                        const variation = window.saDataLayer.product.variations.find(v => v.variation_id === parseInt(variationId));
                        if (variation) {
                            return {
                                ...window.saDataLayer.product,
                                price: variation.price,
                                item_variant: variation.date
                            };
                        }
                    }
                    
                    return window.saDataLayer?.product || null;
                }
                
                this.log('warn', 'Product data not available in any source');
                return null;
            } catch (error) {
                this.log('error', 'Error getting product data:', error);
                return null;
            }
        }

        /**
         * Get product quantity
         */
        getProductQuantity($element) {
            const $qty = $element.find('input.qty');
            return $qty.length ? parseInt($qty.val()) || 1 : 1;
        }

        /**
         * Validate required parameters
         */
        validateRequiredParams(required, data) {
            if (!required) return [];
            return required.filter(param => {
                const value = this.getNestedValue(data, param);
                return value === undefined || value === null || value === '';
            });
        }

        /**
         * Get nested object value using dot notation
         */
        getNestedValue(obj, path) {
            return path.split('.').reduce((current, key) => 
                current && current[key] !== undefined ? current[key] : undefined, obj);
        }

        /**
         * Enhanced logging with different levels and formatting
         */
        log(level, ...args) {
            if (!this.debug.enabled || !this.debug[`log${level.charAt(0).toUpperCase()}${level.slice(1)}`]) return;

            const styles = {
                event: 'color: #28a745; font-weight: bold',
                error: 'color: #dc3545; font-weight: bold',
                warn: 'color: #ffc107; font-weight: bold',
                page: 'color: #17a2b8; font-weight: bold',
                info: 'color: #6c757d'
            };

            console.log(`%c[DataLayer ${level.toUpperCase()}]`, styles[level], ...args);
        }

        /**
         * Clean up resources
         */
        destroy() {
            this.productDataCache.clear();
            this.eventQueue = [];
            this.isProcessingQueue = false;
        }
    }

    // Export for use in other files
    window.DataLayer = DataLayer;
})(jQuery); 