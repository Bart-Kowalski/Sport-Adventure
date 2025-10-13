// DataLayer Event Configuration

const DATALAYER_CONFIG = {
    // Debug settings - can be controlled via PHP
    debug: {
        enabled: false, // Disabled by default, controlled via admin settings
        logEvent: true,
        logError: true,
        logWarn: true,
        logPage: true,
        logInfo: true,
        logProducts: true
    },

    // Performance settings
    performance: {
        eventQueueSize: 10,
        eventQueueDelay: 50,
        productCacheDuration: 5 * 60 * 1000, // 5 minutes
        throttleDelay: 250
    },

    // Event definitions with optimized validation
    events: {
        view_item: {
            required: ['ecommerce'],
            validate: data => data?.ecommerce?.items?.length > 0,
            batchable: true
        },
        view_item_list: {
            required: ['ecommerce'],
            validate: data => data?.ecommerce?.items?.length > 0,
            batchable: true
        },
        add_to_cart: {
            required: ['ecommerce'],
            validate: data => data?.ecommerce?.items?.length > 0,
            batchable: false
        },
        remove_from_cart: {
            required: ['ecommerce'],
            validate: data => data?.ecommerce?.items?.length > 0,
            batchable: false
        },
        begin_checkout: {
            required: ['ecommerce'],
            validate: data => data?.ecommerce?.items?.length > 0,
            batchable: false
        },
        purchase: {
            required: ['ecommerce'],
            validate: data => data?.ecommerce?.items?.length > 0,
            batchable: false
        },
        product_detail_section: {
            required: ['section_id'],
            validate: data => typeof data?.section_id === 'string',
            batchable: true
        },
        product_info_request: {
            required: ['query_type'],
            validate: data => typeof data?.query_type === 'string',
            batchable: true
        },
        cart_update: {
            required: ['ecommerce'],
            validate: data => data?.ecommerce?.items?.length > 0,
            batchable: true
        },
        harmonogram_month_view: {
            required: ['ecommerce'],
            validate: data => data?.ecommerce?.items?.length > 0 && 
                             data?.ecommerce?.item_list_id === 'harmonogram' &&
                             data?.ecommerce?.item_list_name?.includes('harmonogram -'),
            batchable: true
        }
    },

    // Page type configurations with optimized selectors
    pageTypes: {
        product: {
            selectors: {
                addToCart: '.single_add_to_cart_button',
                variationForm: 'form.variations_form',
                productData: '[data-product]'
            }
        },
        category: {
            autoEvents: [],
            selectors: {
                productList: '.product',
                productData: '[data-product]'
            },
            observerThreshold: 0.5,
            observerRootMargin: '50px'
        },
        checkout: {
            autoEvents: [],
            selectors: {
                checkoutForm: 'form.checkout',
                cartItems: '.cart_item'
            }
        }
    },

    // Product data configuration with validation
    productData: {
        required: ['item_id', 'item_name', 'price', 'currency'],
        optional: [
            'item_variant',
            'item_category',
            'item_category2',
            'destination',
            'trip_dates',
            'trip_difficulty',
            'product_categories',
            'product_tags'
        ],
        validation: {
            item_id: value => typeof value === 'number' || typeof value === 'string',
            price: value => !isNaN(parseFloat(value)),
            currency: value => typeof value === 'string' && value.length === 3,
            trip_difficulty: value => value === null || (typeof value === 'number' && value >= 1 && value <= 5)
        }
    }
};

// Make config available globally
window.DATALAYER_CONFIG = DATALAYER_CONFIG;

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DATALAYER_CONFIG;
} 