// Initialize data layer with performance optimizations
(function($) {
    window.dataLayer = window.dataLayer || [];

    // Create DataLayer instance with configuration
    const dataLayerInstance = new DataLayer(window.DATALAYER_CONFIG || {
        debug: {
            enabled: false,
            logEvent: false,
            logError: false,
            logWarn: false,
            logPage: false,
            logInfo: false,
            logProducts: false
        },
        performance: {
            eventQueueSize: 10,
            eventQueueDelay: 50,
            productCacheDuration: 5 * 60 * 1000,
            throttleDelay: 250
        }
    });

    // Make instance available globally for debugging
    window.dataLayerInstance = dataLayerInstance;

    // --- Centralized DataLayer Event API for Cart/Checkout ---
    // Usage: window.dataLayerInstance.pushEvent(eventName, eventData)
    dataLayerInstance.pushEvent = function(eventName, eventData) {
        dataLayerInstance.push(eventName, eventData);
    };

    // Helper function to safely push events to the data layer
    function pushToDataLayer(data) {
        // Ensure dataLayer exists
        window.dataLayer = window.dataLayer || [];
        
        // Validate data object
        if (!data || typeof data !== 'object') {
            console.warn('Invalid data passed to pushToDataLayer:', data);
            return;
        }

        // Debug logging if enabled
        if (window.DATALAYER_CONFIG?.debug?.enabled) {
            console.log('Pushing to dataLayer:', data);
        }

        try {
            // Handle ecommerce data specially
            if (data.ecommerce) {
                // Clear previous ecommerce object to prevent data mixing
                window.dataLayer.push({ ecommerce: null });
                
                // Validate ecommerce data
                if (data.ecommerce.items) {
                    data.ecommerce.items = data.ecommerce.items.filter(item => item != null);
                    
                    // Ensure required fields are present
                    data.ecommerce.items = data.ecommerce.items.map(item => ({
                        item_id: item.item_id || '',
                        item_name: item.item_name || '',
                        price: parseFloat(item.price || 0),
                        currency: item.currency || 'PLN',
                        ...item
                    }));
                }
            }

            // Clean data to remove any conflicting root-level arrays
            const cleanData = { ...data };
            
            // Remove root-level items if ecommerce.items exists
            if (cleanData.ecommerce && cleanData.ecommerce.items && cleanData.items) {
                delete cleanData.items;
            }

            // Push to dataLayer
            window.dataLayer.push(cleanData);
        } catch (error) {
            console.error('Error pushing to dataLayer:', error);
            if (window.DATALAYER_CONFIG?.debug?.logError) {
                console.error('Failed data:', data);
            }
        }
    }

    // Helper function to get product data from element
    function getProductDataFromElement($element, includeQuantity = true) {
        return dataLayerInstance.getProductData($element, includeQuantity);
    }

    // Helper function to get consistent item_id from various sources
    function getConsistentItemId($element, $title = null) {
        // Priority 1: Check if we have saDataLayer with product data
        if (typeof saDataLayer !== 'undefined' && saDataLayer.product && saDataLayer.product.item_id) {
            return saDataLayer.product.item_id;
        }
        
        // Priority 2: Check data attributes on the element
        let itemId = $element.data('product-id') || $element.data('product_id') || $element.data('id');
        
        // Priority 3: If we have a title element, try to extract from URL
        if (!itemId && $title) {
            const href = $title.attr('href');
            if (href) {
                // Try to extract product ID from URL parameters first
                const urlParams = new URLSearchParams(href.split('?')[1] || '');
                itemId = urlParams.get('product_id') || urlParams.get('p');
                
                // If still no ID, try to get it from the URL path
                if (!itemId) {
                    const urlParts = href.split('/');
                    const wyprawaIndex = urlParts.indexOf('wyprawa');
                    if (wyprawaIndex !== -1 && urlParts[wyprawaIndex + 1]) {
                        const slug = urlParts[wyprawaIndex + 1];
                        // Only use numeric slugs as IDs
                        if (/^\d+$/.test(slug)) {
                            itemId = slug;
                        }
                    }
                }
            }
        }
        
        // Priority 4: Check for WooCommerce product ID in form
        if (!itemId) {
            const $form = $element.closest('form');
            if ($form.length) {
                itemId = $form.find('input[name="product_id"]').val() || 
                         $form.find('input[name="variation_id"]').val() ||
                         $form.data('product-id');
            }
        }
        
        return itemId || '';
    }

    // Function to initialize product list tracking
    function initProductListTracking() {
        const $productLists = $('.product-10__grid');
        if ($productLists.length === 0) return;

        // Keep track of observed lists to prevent duplicates
        const observedLists = new Set();

        $productLists.each(function() {
            const $productList = $(this);
            const listId = $productList.data('list-id');
            
            // Skip if already observed
            if (observedLists.has(listId)) return;
            observedLists.add(listId);

            const listName = $productList.data('list-name');
            const listType = $productList.data('list-type');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const items = [];
                        const $products = $productList.find('.card-product-10__wrapper');
                        
                        $products.each(function() {
                            const $product = $(this);
                            const $article = $product.find('article');
                            const $title = $article.find('.wyprawa-card__title a');
                            const $price = $article.find('.wyprawa-card__price');
                            const $location = $article.find('.wyprawa-card__location');
                            const $tags = $article.find('.wyprawa-card__sale-text');
                            
                            // Get all tags
                            const tags = $tags.map(function() {
                                return $(this).text().trim();
                            }).get();

                            // Extract price and currency
                            const priceText = $price.text().trim();
                            const priceMatch = priceText.match(/(\d+(?:,\d+)?)\s*(USD|PLN|EUR)/);
                            const price = priceMatch ? parseFloat(priceMatch[1].replace(',', '')) : 0;
                            const currency = priceMatch ? priceMatch[2] : 'PLN';
                            
                            // Get consistent item_id using helper function
                            const itemId = getConsistentItemId($product, $title);
                            
                            const productData = {
                                item_id: itemId || '',
                                item_name: $title.text().trim(),
                                price: price,
                                currency: currency,
                                item_category: tags[0] || '',
                                item_category2: tags[1] || '',
                                destination: $location.text().trim(),
                                product_tags: tags,
                                item_list_id: listId || window.location.pathname,
                                item_list_name: listName || listType || document.title
                            };

                            items.push(productData);
                        });

                        if (items.length) {
                            if (window.DATALAYER_CONFIG?.debug?.enabled) {
                                console.log('Pushing view_item_list event:', {
                                    list_id: listId,
                                    list_name: listName,
                                    items_count: items.length
                                });
                            }
                            
                            dataLayerInstance.trackViewItemList(listId || window.location.pathname, listName || listType || document.title, items);
                        }

                        // Unobserve after first view
                        observer.unobserve(entry.target);
                    }
                });
            }, { 
                threshold: [0.25], // Lower threshold for more reliable triggering
                rootMargin: '100px' // Increased margin for earlier detection
            });

            observer.observe($productList[0]);
        });
    }

    // Global tracking for harmonogram month lists to prevent duplicates
    let harmonogramObservedMonthLists = new Set();

    // Function to initialize harmonogram month list tracking
    function initHarmonogramTracking() {
        const $monthLists = $('.month-wyprawa-list');
        if ($monthLists.length === 0) {
            if (window.DATALAYER_CONFIG?.debug?.enabled) {
                console.log('No month-wyprawa-list elements found');
            }
            return;
        }

        if (window.DATALAYER_CONFIG?.debug?.enabled) {
            console.log('Found', $monthLists.length, 'month lists');
        }

        $monthLists.each(function() {
            const $monthList = $(this);
            const monthYear = $monthList.attr('miesiac');
            
            if (window.DATALAYER_CONFIG?.debug?.enabled) {
                console.log('Processing month list:', monthYear, $monthList);
            }
            
            // Skip if no month attribute or already observed
            if (!monthYear || harmonogramObservedMonthLists.has(monthYear)) {
                if (window.DATALAYER_CONFIG?.debug?.enabled) {
                    console.log('Skipping month list:', monthYear, 'already observed or no month attr');
                }
                return;
            }
            harmonogramObservedMonthLists.add(monthYear);

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (window.DATALAYER_CONFIG?.debug?.enabled) {
                        console.log('Intersection observer triggered for:', monthYear, 'isIntersecting:', entry.isIntersecting);
                    }
                    
                    if (entry.isIntersecting) {
                        const items = [];
                        // Look for li elements containing articles (harmonogram structure)
                        const $listItems = $monthList.find('li');
                        
                        if (window.DATALAYER_CONFIG?.debug?.enabled) {
                            console.log('Found', $listItems.length, 'list items in month:', monthYear);
                        }
                        
                        $listItems.each(function() {
                            const $listItem = $(this);
                            const $article = $listItem.find('article');
                            
                            if (!$article.length) {
                                if (window.DATALAYER_CONFIG?.debug?.enabled) {
                                    console.log('No article found in list item');
                                }
                                return;
                            }
                            
                            const $title = $article.find('.wyprawa-card__title a, h3 a').first();
                            const $price = $article.find('.wyprawa-card__price').first();
                            const $location = $article.find('.wyprawa-card__location').first();
                            const $tags = $article.find('.wyprawa-card__sale-text');
                            
                            if (window.DATALAYER_CONFIG?.debug?.enabled) {
                                console.log('Product elements found:', {
                                    title: $title.length,
                                    price: $price.length,
                                    location: $location.length,
                                    tags: $tags.length
                                });
                            }
                            
                            // Skip if no title found
                            if (!$title.length || !$title.text().trim()) {
                                if (window.DATALAYER_CONFIG?.debug?.enabled) {
                                    console.log('No title found, skipping item');
                                }
                                return;
                            }
                            
                            // Get all tags
                            const tags = $tags.map(function() {
                                return $(this).text().trim();
                            }).get();

                            // Extract price and currency
                            const priceText = $price.text().trim();
                            const priceMatch = priceText.match(/(\d+(?:,\d+)?)\s*(USD|PLN|EUR)/);
                            const price = priceMatch ? parseFloat(priceMatch[1].replace(',', '')) : 0;
                            const currency = priceMatch ? priceMatch[2] : 'PLN';
                            
                            // Get consistent item_id using helper function
                            const itemId = getConsistentItemId($listItem, $title);
                            
                            const productData = {
                                item_id: itemId,
                                item_name: $title.text().trim(),
                                price: price,
                                currency: currency,
                                item_category: tags[0] || '',
                                item_category2: tags[1] || '',
                                destination: $location.text().trim(),
                                product_tags: tags,
                                item_list_id: 'harmonogram',
                                item_list_name: `harmonogram - ${monthYear}`
                            };

                            if (window.DATALAYER_CONFIG?.debug?.enabled) {
                                console.log('Adding product data:', productData);
                            }
                            items.push(productData);
                        });

                        if (items.length) {
                            if (window.DATALAYER_CONFIG?.debug?.enabled) {
                                console.log('Pushing harmonogram view_item_list event:', {
                                    month_year: monthYear,
                                    list_id: 'harmonogram',
                                    list_name: `harmonogram - ${monthYear}`,
                                    items_count: items.length
                                });
                            }
                            
                            dataLayerInstance.trackViewItemList('harmonogram', `harmonogram - ${monthYear}`, items);
                        } else {
                            if (window.DATALAYER_CONFIG?.debug?.enabled) {
                                console.log('No items found for month:', monthYear);
                            }
                        }

                        // Unobserve after first view
                        observer.unobserve(entry.target);
                    }
                });
            }, { 
                threshold: [0.1], // Lower threshold for more reliable triggering
                rootMargin: '50px' // Margin for earlier detection
            });

            if (window.DATALAYER_CONFIG?.debug?.enabled) {
                console.log('Setting up observer for month:', monthYear);
            }
            observer.observe($monthList[0]);
        });
    }

    // Initialize when DOM is ready and after a short delay to ensure all content is loaded
    $(document).ready(function() {
        // Initial attempt
        initProductListTracking();
        initHarmonogramTracking();
        
        // Retry after a short delay to catch any dynamically loaded content
        setTimeout(initProductListTracking, 1000);
        setTimeout(initHarmonogramTracking, 1000);

        // Track remove from cart events - handle the actual remove link click on cart page
        $(document).on('click', '.woocommerce-cart-form .remove', function(e) {
            const $removeLink = $(this);
            const $cartItem = $removeLink.closest('.cart_item');
            const productId = $removeLink.data('product_id');
            const quantity = parseInt($cartItem.find('.qty').val()) || 1;
            
            if (typeof saDataLayer !== 'undefined' && saDataLayer.cart_items && saDataLayer.cart_items[productId]) {
                const item = saDataLayer.cart_items[productId];
                const productData = {
                    item_id: item.item_id,
                    item_name: item.item_name,
                    price: parseFloat(item.price),
                    quantity: parseInt(item.quantity || 1),
                    currency: item.currency,
                    item_category: item.item_category || '',
                    item_category2: item.item_category2 || '',
                    variant: item.variant || (item.trip_dates?.[0] || null),
                    destination: item.destination || null,
                    trip_dates: item.trip_dates || [],
                    trip_difficulty: item.trip_difficulty || null,
                    product_categories: item.product_categories || [],
                    product_tags: item.product_tags || [],
                    reservation_deposit: item.reservation_deposit || null,
                    flight_cost: (item.flight_cost !== undefined && item.flight_cost !== '') ? item.flight_cost : null,
                    flight_currency: 'PLN'
                };
                dataLayerInstance.trackRemoveFromCart($removeLink, quantity);
            }
        });

        // Track cart updates with throttling
        let cartUpdateTimeout;
        let lastBeginCheckoutTime = 0;
        $(document.body).on('updated_cart_totals updated_checkout', function() {
            clearTimeout(cartUpdateTimeout);
            cartUpdateTimeout = setTimeout(() => {
                // Prevent duplicate events within 1 second
                if (Date.now() - lastBeginCheckoutTime < 1000) {
                    return;
                }
                
                $.ajax({
                    url: sa_datalayer_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'sa_get_cart_data'
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            lastBeginCheckoutTime = Date.now();
                            $(document).trigger('begin_checkout', [response.data]);
                        }
                    }
                });
            }, 250);
        });

        // Handle product info request
        $(document).on('wsf_submit_success', function(e, form, data) {
            if (!form || !form.attr('data-wsf-form-id')) return;

            const $form = $(form);
            const $queryTypeField = $form.find('.query-type span');
            
            if ($queryTypeField.length) {
                dataLayerInstance.trackProductInfoRequest($queryTypeField.text().trim());
            }
        });

        // Initialize page tracking if we have page type info
        if (window.saDataLayer?.page_type) {
            dataLayerInstance.initPage(window.saDataLayer.page_type);
        }
    });

    // Helper function to format price
    function formatPrice(price) {
        return parseFloat(price || 0).toFixed(2);
    }

    // Helper function to clean up product name from document title
    function cleanProductName(name) {
        if (!name) return '';
        // Remove " - Sport Adventure" and any extra whitespace
        return name.replace(/\s*[-–]\s*Sport Adventure\s*$/i, '').trim();
    }

    // Helper function to get trip difficulty value
    function getTripDifficulty() {
        const difficultyMap = {
            '1 Dla każdego': 1,
            '2 Dla początkujących': 2,
            '3 Dla aktywnych': 3,
            '4 Dla wytrwałych': 4,
            '5 Dla poszukujących wyzwań': 5
        };
        
        // Try to find the difficulty from ACF field
        const $difficulty = jQuery('[data-name="wyprawa__poziom-trudnosci"] input:checked, [name="wyprawa__poziom-trudnosci"]:checked');
        if ($difficulty.length) {
            const difficultyText = $difficulty.next('label').text().trim();
            return difficultyMap[difficultyText] || null;
        }

        // Try to find it from the page content
        const $difficultyText = jQuery('.difficulty-level, [class*="poziom-trudnosci"]').first();
        if ($difficultyText.length) {
            const text = $difficultyText.text().trim();
            // Try to match the number at the start of the text
            const match = text.match(/^(\d)/);
            if (match) {
                return parseInt(match[1]);
            }
            // If no number found, try to match the full text
            return difficultyMap[text] || null;
        }
        
        return null;
    }

    // Helper function to get destination
    function getDestination() {
        // Get location from WordPress localized data
        if (typeof saDataLayer !== 'undefined' && saDataLayer.location) {
            return saDataLayer.location;
        }
        return null;
    }

    // Helper function to get trip dates as array
    function getTripDates() {
        const dates = [];
        jQuery('select[name="attribute_pa_termin"] option').each(function() {
            const date = jQuery(this).text().trim();
            if (date && date !== 'Wybierz opcję') {
                dates.push(date);
            }
        });
        return dates;
    }

    // Helper function to get current page type and list name
    function getPageInfo() {
        // First check if we have page type from PHP
        if (typeof saDataLayer !== 'undefined' && saDataLayer.page_type) {
            return {
                pageType: saDataLayer.page_type,
                listName: saDataLayer.page_type
            };
        }

        const path = window.location.pathname;
        const taxonomyPages = [
            '/wyprawy-firmowe',
            '/wyprawy-w-polsce',
            '/wszystkie-wyprawy',
            '/wyprawy-2025',
            '/wyprawy-zagraniczne',
            '/wyprawy-w-himalaje',
            '/wyprawy-do-nepalu',
            '/wyprawy-do-annapurna-base-camp-w-nepalu',
            '/wyprawy-dla-kobiet',
            '/wszystkie-wyprawy'
        ];

        let pageType = '';
        let listName = '';

        // Check if we're on a product page
        if (path.includes('/wyprawa/') || jQuery('body').hasClass('single-product')) {
            pageType = 'product';
            if (window.DATALAYER_CONFIG?.debug?.enabled) {
                console.log('Detected product page from URL or body class');
            }
        }
        // Check if we're on a taxonomy page
        else if (taxonomyPages.some(tax => path.startsWith(tax))) {
            pageType = 'category';
            // Get the last part of the path for list name
            listName = path.split('/').filter(Boolean).pop();
        }
        // Check if we're on homepage
        else if (path === '/' || path === '') {
            pageType = 'home';
            listName = 'strona główna';
        }

        if (window.DATALAYER_CONFIG?.debug?.enabled) {
            console.log('Page Info:', { pageType, listName, path });
        }
        return { pageType, listName };
    }

    // Helper function to get product variant (date)
    function getProductVariant($form) {
        let variant = '';
        const $terminSelect = $form.find('select[name="attribute_pa_termin"]');
        const $terminOption = $terminSelect.find('option:selected');
        
        if ($terminSelect.length) {
            // First try to get the formatted text from the selected option
            if ($terminOption.length) {
                variant = $terminOption.text().trim();
            }
            
            // If not found, try the value and format it
            if (!variant) {
                const selectedTermin = $terminSelect.val();
                if (selectedTermin) {
                    variant = selectedTermin.replace(/-/g, '.');
                }
            }
        }

        // If we still don't have a variant, try getting it from the variation description
        if (!variant && $form.find('.woocommerce-variation-description').length) {
            const $variationDescription = $form.find('.woocommerce-variation-description');
            const descText = $variationDescription.text().trim();
            // Look for date pattern in description
            const dateMatch = descText.match(/\d{2}[-.]\d{2}[-.]\d{4}/);
            if (dateMatch) {
                variant = dateMatch[0].replace(/-/g, '.');
            }
        }

        return variant || null;
    }

    // Helper function to calculate ecommerce value from items and quantity
    function calculateEcommerceValue(items, quantity = 1) {
        if (!Array.isArray(items) || items.length === 0) return 0;
        return items[0].price * quantity;
    }

    function getProductData($button) {
        // Get data from PHP
        if (typeof saDataLayer !== 'undefined' && saDataLayer.product) {
            const product = saDataLayer.product;
            
            if (window.DATALAYER_CONFIG?.debug?.enabled) {
                console.log('getProductData - product type:', product.type);
                console.log('getProductData - product:', product);
            }
            
            const baseData = {
                item_id: product.item_id,
                item_name: product.item_name,
                price: product.price,
                currency: product.currency,
                item_category: product.item_category || '',
                item_category2: product.item_category2 || '',
                destination: product.destination || null,
                trip_difficulty: product.trip_difficulty || null,
                product_categories: product.product_categories || [],
                product_tags: product.product_tags || [],
                reservation_deposit: product.reservation_deposit,
                trip_dates: Array.isArray(product.trip_dates) ? product.trip_dates : []
            };
            
            // For variable products, get the selected variation
            if (product.type === 'variable') {
                const $form = $button.closest('form.cart');
                
                if (window.DATALAYER_CONFIG?.debug?.enabled) {
                    console.log('Variable product detected, form found:', $form.length > 0);
                    console.log('Product variations:', product.variations);
                    console.log('Form HTML:', $form.length ? $form.html() : 'No form found');
                    console.log('All form inputs:', $form.length ? $form.find('input').map(function() { return { name: this.name, value: this.value }; }).get() : 'No inputs');
                }
                
                if ($form.length) {
                    const variationId = parseInt($form.find('input[name="variation_id"]').val());
                    
                    if (window.DATALAYER_CONFIG?.debug?.enabled) {
                        console.log('Variation ID from form:', variationId);
                        console.log('Product variations available:', product.variations);
                    }
                    
                    // If variation_id input is not found, try alternative methods
                    let finalVariationId = variationId;
                    if (!finalVariationId) {
                        // Try to get from form data
                        const formData = new FormData($form[0]);
                        const formVariationId = formData.get('variation_id');
                        if (formVariationId) {
                            finalVariationId = parseInt(formVariationId);
                            if (window.DATALAYER_CONFIG?.debug?.enabled) {
                                console.log('Found variation_id in form data:', finalVariationId);
                            }
                        }
                        
                        // Try to get from WooCommerce variation data
                        if (!finalVariationId && typeof wc_add_to_cart_variation_params !== 'undefined') {
                            const wcVariationId = wc_add_to_cart_variation_params.variation_id;
                            if (wcVariationId) {
                                finalVariationId = parseInt(wcVariationId);
                                if (window.DATALAYER_CONFIG?.debug?.enabled) {
                                    console.log('Found variation_id in wc_add_to_cart_variation_params:', finalVariationId);
                                }
                            }
                        }
                        
                        // Try to get from WooCommerce variation form
                        if (!finalVariationId) {
                            const $variationForm = $('.variations_form');
                            if ($variationForm.length) {
                                const wcVariationId = $variationForm.find('input[name="variation_id"]').val();
                                if (wcVariationId) {
                                    finalVariationId = parseInt(wcVariationId);
                                    if (window.DATALAYER_CONFIG?.debug?.enabled) {
                                        console.log('Found variation_id in variations_form:', finalVariationId);
                                    }
                                }
                            }
                        }
                    }
                    
                    if (finalVariationId && product.variations) {
                        const selectedVariation = product.variations.find(v => v.variation_id === finalVariationId);
                        
                        if (window.DATALAYER_CONFIG?.debug?.enabled) {
                            console.log('Selected variation found:', selectedVariation);
                        }
                        
                        if (selectedVariation) {
                            // For selected variation, add variant_id while keeping item_id as parent product ID
                            baseData.variant_id = finalVariationId;
                            // item_id remains the parent product ID (consistent)
                            
                            if (window.DATALAYER_CONFIG?.debug?.enabled) {
                                console.log('Added variant_id to baseData:', baseData.variant_id);
                            }
                            
                            // For selected variation, use only its termin
                            const terminAttr = $form.find('select[name="attribute_pa_termin"]').val();
                            if (terminAttr) {
                                baseData.trip_dates = [terminAttr];
                            }
                        }
                    }
                }
            }
            
            if (window.DATALAYER_CONFIG?.debug?.enabled) {
                console.log('Final baseData:', baseData);
                console.log('Final baseData variant_id:', baseData.variant_id);
            }
            
            return baseData;
        }
        
        console.warn('Product data not available from PHP');
        return null;
    }

    // Helper function to get product categories
    function getProductCategories() {
        if (typeof saDataLayer !== 'undefined' && saDataLayer.product_categories) {
            return saDataLayer.product_categories;
        }
        return [];
    }

    // Helper function to get product tags
    function getProductTags() {
        if (typeof saDataLayer !== 'undefined' && saDataLayer.product_tags) {
            return saDataLayer.product_tags;
        }
        return [];
    }

    // Initialize when DOM is ready
    $(document).ready(function() {
        const pageInfo = getPageInfo();

        // Keep track of viewed sections to prevent duplicates
        const viewedSections = new Set();

        // Track view_item for product pages
        if (pageInfo.pageType === 'product' && window.location.pathname.includes('/wyprawa/')) {
            // Debug check for saDataLayer
            if (typeof saDataLayer === 'undefined') {
                console.warn('saDataLayer is not defined');
            } else if (!saDataLayer.product) {
                console.warn('saDataLayer.product is not defined');
            } else {
                const productData = {
                    item_id: saDataLayer.product.item_id,
                    item_name: saDataLayer.product.item_name,
                    item_category: saDataLayer.product.item_category,
                    price: saDataLayer.product.price,
                    currency: saDataLayer.product.currency,
                    reservation_deposit: saDataLayer.product.reservation_deposit,
                    product_tags: saDataLayer.product.product_tags || [],
                    trip_difficulty: saDataLayer.product.trip_difficulty,
                    destination: saDataLayer.product.destination,
                    trip_dates: saDataLayer.product.trip_dates || [],
                    flight_cost: (saDataLayer.product.flight_cost !== undefined && saDataLayer.product.flight_cost !== '') ? saDataLayer.product.flight_cost : null,
                    flight_currency: saDataLayer.product.flight_currency || saDataLayer.product.currency || 'PLN'
                };
                
                // Push view_item event - only through dataLayerInstance
                dataLayerInstance.trackViewItem(productData);
            }
        } else {
            if (window.DATALAYER_CONFIG?.debug?.enabled) {
                console.log('Not a product page or not a /wyprawa/ URL. Page type:', pageInfo.pageType);
            }
        }

        // Track product detail section views
        const sectionIds = [
            'cennik', 'mapa', 'plan-wyprawy', 'co-zobaczysz', 'cena', 'cena-bonusy',
            'cena-nie-obejmuje', 'liderzy', 'czy-to-dla-ciebie', 'czy-dla-ciebie',
            'dlaczego-warto', 'video', 'faq', 'informacje-zdjecia', 'opis', 'zdjecia', 
            'podobne-wyprawy', 'cta', 'informacje-praktyczne', 'informacje-dodatkowe', 
            'informacje-o-wyprawie', 'galeria', 'program', 'atrakcje', 'zakwaterowanie', 
            'transport', 'wyzywienie', 'ubezpieczenie', 'formalnosci', 'ekwipunek',
            'przygotowanie-kondycyjne', 'pogoda', 'bezpieczenstwo',
            'terminy-ceny', 'terminy', 'ceny', 'bonusy', 'co-warto-wiedziec'
        ];

        // Use a single Intersection Observer instance
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                // Only track on /wyprawa/ URLs
                if (!window.location.pathname.includes('/wyprawa/')) return;
  
                if (entry.isIntersecting && !viewedSections.has(entry.target.id)) {
                    viewedSections.add(entry.target.id);
                    dataLayerInstance.trackProductDetailSection(entry.target.id);
                    // Unobserve after first view
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        // Observe each section only once
        sectionIds.forEach(id => {
            const element = document.getElementById(id);
            if (element && !viewedSections.has(id)) {
                observer.observe(element);
            }
        });

        // Track product info requests
        jQuery('#wlasna-wyprawa, #pytanie-do-lidera').on('click', function(e) {
            // Only track on /wyprawa/ URLs
            if (!window.location.pathname.includes('/wyprawa/')) return;

            const $element = jQuery(this);
            let queryType;

            // For #wlasna-wyprawa, use the .text span's content
            if ($element.attr('id') === 'wlasna-wyprawa') {
                queryType = $element.find('.text').text().trim() || 'Własna wyprawa';
            } 
            // For #pytanie-do-lidera, use its own inner text
            else {
                queryType = $element.text().trim();
            }

            // Only push if we have a query type
            if (queryType) {
                dataLayerInstance.trackProductInfoRequest(queryType);
                if (window.DATALAYER_CONFIG?.debug?.enabled) {
                    console.log('Product info request:', queryType);
                }
            }
        });
    });

    // Track begin checkout
    $(document).on('begin_checkout', function(event, checkoutData) {
        if (!checkoutData) return;
        dataLayerInstance.trackBeginCheckout(checkoutData);
    });

    // Track cart updates
    $(document).on('updated_cart_totals', function() {
        const cartData = (typeof wc_cart_fragments_params !== 'undefined' && wc_cart_fragments_params.cart_data) 
            ? JSON.parse(wc_cart_fragments_params.cart_data) 
            : null;

        if (cartData) {
            const items = cartData.items.map(item => {
                const $cartItem = $(`.cart_item[data-product_id="${item.id}"]`);
                return getProductData($cartItem);
            });

            dataLayerInstance.trackCartUpdate({
                currency: cartData.currency,
                total: formatPrice(cartData.total),
                items: items
            });
        }
    });

    // Track recommended products views
    if ($('#podobne-wyprawy').length) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const items = [];
                    $('#podobne-wyprawy .product').each(function() {
                        const $product = $(this);
                        const productData = getProductData($product, false);
                        productData.item_list_id = 'podobne-wyprawy';
                        productData.item_list_name = 'Podobne wyprawy';
                        items.push(productData);
                    });

                    if (items.length) {
                        dataLayerInstance.trackViewItemList('podobne-wyprawy', 'Podobne wyprawy', items);
                    }
                }
            });
        }, { threshold: 0.5 });

        observer.observe($('#podobne-wyprawy')[0]);
    }

    // --- DRY Helper for E-commerce Events ---
    // Usage: window.dataLayerInstance.trackEcommerceEvent(eventName, {currency, value, items, ...rest})
    dataLayerInstance.trackEcommerceEvent = function(eventName, {currency, value, items, ...rest}) {
        try {
            dataLayerInstance.pushEvent(eventName, {
                ecommerce: {
                    currency,
                    value,
                    items,
                    ...rest
                }
            });
        } catch (error) {
            console.warn(`Error tracking ${eventName}:`, error);
        }
    };

    // --- Centralized Remove From Cart Tracking ---
    dataLayerInstance.trackRemoveFromCart = function($element, quantity) {
        try {
            var cartItemKey = $element.data('cart-key');
            let productData = null;
            if (typeof saDataLayer !== 'undefined' && saDataLayer.cart_items && cartItemKey) {
                productData = saDataLayer.cart_items[cartItemKey] || null;
            }
            if (productData) {
                // Debug logging to verify data structure
                if (window.DATALAYER_CONFIG?.debug?.enabled) {
                    console.log('Remove from Cart - Product Data:', productData);
                }
                
                dataLayerInstance.trackEcommerceEvent('remove_from_cart', {
                    currency: productData.currency,
                    value: productData.price * quantity,
                    items: [{
                        ...productData,
                        quantity: quantity
                    }]
                });
            } else {
                console.warn('No product data found for cartItemKey:', cartItemKey);
            }
        } catch (error) {
            console.warn('Error tracking remove_from_cart:', error);
        }
    };

    // --- Centralized Add To Cart Tracking ---
    dataLayerInstance.trackAddToCart = function($button) {
        try {
            const $form = $button.closest('form.cart');
            const productData = getProductData($button);
            
            // Debug logging to see what getProductData returns
            if (window.DATALAYER_CONFIG?.debug?.enabled) {
                console.log('trackAddToCart - getProductData returned:', productData);
                console.log('trackAddToCart - variant_id in returned data:', productData?.variant_id);
            }
            
            if (productData) {
                let variant = '';
                if ($form.length) {
                    const $terminSelect = $form.find('select[name="attribute_pa_termin"]');
                    if ($terminSelect.length) {
                        variant = $terminSelect.find('option:selected').text().trim();
                    }
                    if (!variant) {
                        const attributes = {};
                        $form.find('.variations select').each(function() {
                            const $select = $(this);
                            const name = $select.attr('name').replace('attribute_', '');
                            const value = $select.val();
                            if (value) {
                                attributes[name] = value;
                            }
                        });
                        if (Object.keys(attributes).length > 0) {
                            variant = Object.entries(attributes)
                                .map(([name, value]) => `${name}: ${value}`)
                                .join(' | ');
                        }
                    }
                }
                if (variant) {
                    productData.variant = variant;
                }
                
                // Debug logging to verify data structure
                if (window.DATALAYER_CONFIG?.debug?.enabled) {
                    console.log('Add to Cart - Product Data:', productData);
                    console.log('Add to Cart - variant_id present:', 'variant_id' in productData);
                    console.log('Add to Cart - variant_id value:', productData.variant_id);
                }
                
                dataLayerInstance.trackEcommerceEvent('add_to_cart', {
                    currency: productData.currency,
                    value: productData.price,
                    items: [{
                        ...productData,
                        variant: productData.variant,
                        variant_id: productData.variant_id, // Explicitly include variant_id
                        quantity: 1
                    }]
                });
            }
        } catch (error) {
            console.warn('Error tracking add_to_cart:', error);
        }
    };

    // --- Centralized Cookie Consent Update Tracking ---
    // Usage: window.dataLayerInstance.trackCookieConsentUpdate(consentData)
    dataLayerInstance.trackCookieConsentUpdate = function(consentData) {
        try {
            dataLayerInstance.pushEvent('cookie_consent_update', {
                cookieConsent: consentData
            });
        } catch (error) {
            console.warn('Error tracking cookie_consent_update:', error);
        }
    };

    // --- Centralized View Item Tracking ---
    dataLayerInstance.trackViewItem = function(productData) {
        try {
            dataLayerInstance.trackEcommerceEvent('view_item', {
                currency: productData.currency,
                value: productData.price,
                items: [productData]
            });
        } catch (error) {
            console.warn('Error tracking view_item:', error);
        }
    };

    // --- Centralized View Item List Tracking ---
    dataLayerInstance.trackViewItemList = function(listId, listName, items) {
        try {
            dataLayerInstance.trackEcommerceEvent('view_item_list', {
                currency: items[0]?.currency || 'PLN',
                value: items.reduce((sum, item) => sum + (parseFloat(item.price) * (parseInt(item.quantity || 1))), 0),
                items: items,
                item_list_id: listId,
                item_list_name: listName
            });
        } catch (error) {
            console.warn('Error tracking view_item_list:', error);
        }
    };

    // --- Centralized Product Info Request Tracking ---
    // Usage: window.dataLayerInstance.trackProductInfoRequest(queryType)
    dataLayerInstance.trackProductInfoRequest = function(queryType) {
        try {
            dataLayerInstance.pushEvent('product_info_request', {
                query_type: queryType
            });
        } catch (error) {
            console.warn('Error tracking product_info_request:', error);
        }
    };

    // --- Centralized Product Detail Section Tracking ---
    // Usage: window.dataLayerInstance.trackProductDetailSection(sectionId)
    dataLayerInstance.trackProductDetailSection = function(sectionId) {
        try {
            dataLayerInstance.pushEvent('product_detail_section', {
                section_id: sectionId
            });
        } catch (error) {
            console.warn('Error tracking product_detail_section:', error);
        }
    };

    // --- Centralized Begin Checkout Tracking ---
    dataLayerInstance.trackBeginCheckout = function(checkoutData) {
        try {
            const totalValue = checkoutData.items.reduce((sum, item) => sum + (parseFloat(item.price) * parseInt(item.quantity || 1)), 0);
            dataLayerInstance.trackEcommerceEvent('begin_checkout', {
                currency: checkoutData.currency,
                value: totalValue,
                items: checkoutData.items
            });
        } catch (error) {
            console.warn('Error tracking begin_checkout:', error);
        }
    };

    // --- Centralized Cart Update Tracking ---
    dataLayerInstance.trackCartUpdate = function(cartData) {
        try {
            dataLayerInstance.trackEcommerceEvent('cart_update', {
                currency: cartData.currency,
                value: cartData.total,
                items: cartData.items
            });
        } catch (error) {
            console.warn('Error tracking cart_update:', error);
        }
    };

})(jQuery); 