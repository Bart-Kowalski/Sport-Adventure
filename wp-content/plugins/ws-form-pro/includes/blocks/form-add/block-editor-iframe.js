// JavaScript that targets both iframe and non-iframe block editor environments
// This file handles WS Form initialization for forms inside and outside of iFrames

// Wait for DOM to be ready
wp.domReady(() => {
    
    // Function to initialize a WS Form element
    function initialize_ws_form(form_element) {

        wsf_form_init(true, true, form_element.parentElement);
    }
    
    // Function to find and target the block editor iframe (API v3)
    function find_block_editor_iframe() {

        // Look for the block editor iframe
        const iframe = document.querySelector('iframe[name="editor-canvas"]') || 
                      document.querySelector('.edit-site-visual-editor__editor-canvas') ||
                      document.querySelector('iframe[title="Editor canvas"]') ||
                      document.querySelector('.block-editor iframe');
        
        return iframe;
    }
    
    // Function to initialize forms within a document (iframe or main document)
    function initialize_forms_in_document(target_document) {

        // Initialize existing forms
        const existing_forms = target_document.querySelectorAll('form.wsf-form');
        existing_forms.forEach(initialize_ws_form);
        
        // Watch for new forms being added
        const observer = new MutationObserver((mutations) => {

            mutations.forEach((mutation) => {

                mutation.addedNodes.forEach((node) => {

                    if (node.nodeType === 1) { // Element node

                        // Check if the node itself is a form.wsf-form
                        if (node.matches && node.matches('form.wsf-form')) {

                            initialize_ws_form(node);
                        }

                        // Check if the node contains form.wsf-form elements
                        if (node.querySelectorAll) {

                            const new_forms = node.querySelectorAll('form.wsf-form');
                            if (new_forms.length > 0) {

                                new_forms.forEach(initialize_ws_form);
                            }
                        }
                    }
                });
            });
        });
        
        // Observe the document's body
        observer.observe(target_document.body, {

            childList: true,
            subtree: true
        });
        
        return observer;
    }
    
    // Function to detect if we're in an iframe-based editor (API v3)
    function is_iframe_editor() {

        return !!find_block_editor_iframe();
    }
    
    // Function to handle iframe-based editor (API v3)
    function handle_iframe_editor() {

        const iframe = find_block_editor_iframe();
        
        if (iframe && iframe.contentDocument && iframe.contentDocument.readyState === 'complete') {

            initialize_forms_in_document(iframe.contentDocument);

        } else if (iframe) {

            // Wait for iframe to load
            iframe.addEventListener('load', () => {

                if (iframe.contentDocument) {

                    initialize_forms_in_document(iframe.contentDocument);
                }
            });
        }
    }
    
    // Function to handle non-iframe editor (API v1/v2)
    function handle_non_iframe_editor() {

        // Check if we're in the block editor context
        const editor_container = document.querySelector('.block-editor-writing-flow') ||
                                document.querySelector('.editor-writing-flow') ||
                                document.querySelector('.wp-block-post-content') ||
                                document.querySelector('[data-type]'); // Generic block container
        
        if (editor_container) {

            initialize_forms_in_document(document);
        }
    }
    
    // Main initialization function
    function initialize_editor() {

        let iframe_check_count = 0;
        const max_iframe_checks = 10; // Prevent infinite checking
        
        function check_editor_type() {

            iframe_check_count++;
            
            if (is_iframe_editor()) {

                // API v3 with iframe
                handle_iframe_editor();
                
                // Also watch for new iframes being added
                const main_observer = new MutationObserver((mutations) => {

                    mutations.forEach((mutation) => {

                        mutation.addedNodes.forEach((node) => {

                            if (node.nodeType === 1 && node.tagName === 'IFRAME') {

                                const iframe = find_block_editor_iframe();

                                if (iframe && iframe === node) {

                                    handle_iframe_editor();
                                }
                            }
                        });
                    });
                });
                
                main_observer.observe(document.body, {

                    childList: true,
                    subtree: true
                });
                
            } else if (iframe_check_count < max_iframe_checks) {

                // Keep checking for iframe for a short period
                setTimeout(check_editor_type, 500);

            } else {

                // No iframe found after checking, assume API v1/v2
                handle_non_iframe_editor();
            }
        }
        
        // Start the check
        check_editor_type();
        
        // Also handle non-iframe case immediately if we detect block editor elements
        const editor_exists = document.querySelector('.block-editor-writing-flow') ||
                             document.querySelector('.editor-writing-flow') ||
                             document.querySelector('.wp-block-post-content');
        
        if (editor_exists && !is_iframe_editor()) {
            handle_non_iframe_editor();
        }
    }
    
    // Start initialization
    initialize_editor();
    
    // Also watch for dynamic changes in the main document that might indicate
    // editor mode changes or late-loading editor elements
    const global_observer = new MutationObserver((mutations) => {

        mutations.forEach((mutation) => {

            mutation.addedNodes.forEach((node) => {

                if (node.nodeType === 1) {

                    // Check for editor containers being added
                    if (node.matches && (

                        node.matches('.block-editor-writing-flow') ||
                        node.matches('.editor-writing-flow') ||
                        node.matches('.wp-block-post-content') ||
                        node.matches('iframe[name="editor-canvas"]')
                    )) {
                        // Re-initialize when editor elements are added
                        setTimeout(initialize_editor, 100);
                    }
                }
            });
        });
    });
    
    global_observer.observe(document.body, {

        childList: true,
        subtree: true
    });
});