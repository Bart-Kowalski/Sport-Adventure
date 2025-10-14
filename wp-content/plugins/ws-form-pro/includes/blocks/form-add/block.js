// Simplified block.js without editor preview toggle
(function (blocks, element, components, blockEditor, serverSideRender) {
    const { registerBlockType } = blocks;
    const { InspectorControls, useBlockProps } = blockEditor;
    const { PanelBody, TextControl, SelectControl } = components;
    const { useState, useEffect, createElement: el } = element;
    const ServerSideRender = serverSideRender;

    // Build icon
    const icon = el(
        'svg',
        { width: 20, height: 20 },
        el(
            'path',
            { fill: '#002E5D', d: 'M0 0v20h20V0zm8.785 13.555h-.829l-1.11-4.966c-.01-.036-.018-.075-.026-.115l-.233-1.297h-.014l-.104.574-.17.838-1.147 4.966h-.836L2.57 6.224h.703l.999 4.27.466 2.23h.044q.133-.966.43-2.243l.998-4.257h.74l1.006 4.27q.119.48.422 2.23h.044q.022-.223.219-1.121t1.254-5.379h.695zm5.645-.389a2.105 2.105 0 0 1-1.54.524 3.26 3.26 0 0 1-.961-.129 2.463 2.463 0 0 1-.644-.283l.309-.534a1.274 1.274 0 0 0 .416.186 2.78 2.78 0 0 0 .925.152 1.287 1.287 0 0 0 .977-.372 1.377 1.377 0 0 0 .355-.993 1.313 1.313 0 0 0-.255-.821 3.509 3.509 0 0 0-.973-.76 6.51 6.51 0 0 1-1.121-.757 2.121 2.121 0 0 1-.466-.635 1.94 1.94 0 0 1-.167-.838A1.67 1.67 0 0 1 11.87 6.6a2.161 2.161 0 0 1 1.487-.517 2.76 2.76 0 0 1 1.567.446l-.31.534a2.425 2.425 0 0 0-1.287-.372 1.422 1.422 0 0 0-.991.334 1.132 1.132 0 0 0-.37.882 1.298 1.298 0 0 0 .252.814 3.792 3.792 0 0 0 1.065.794 6.594 6.594 0 0 1 1.095.767 1.896 1.896 0 0 1 .44.635 2.076 2.076 0 0 1 .144.8 1.94 1.94 0 0 1-.532 1.45zm2.375.598a.671.671 0 1 1 .672-.671.671.671 0 0 1-.672.67zm0-3.242a.671.671 0 1 1 .672-.671.671.671 0 0 1-.672.67zm0-3.284a.671.671 0 1 1 .672-.672.671.671 0 0 1-.672.672z' }
        )
    );

    registerBlockType(ws_form_block_form_add.name, {

        apiVersion: 3,

        category: ws_form_block_form_add.category,

        description: ws_form_block_form_add.text_description,

        icon: icon,

        keywords: ws_form_block_form_add.keywords,

        title: ws_form_block_form_add.label,

        attributes: {

            form_id: { type: 'string' },
            form_element_id: { type: 'string' }
        },

        edit: function (props) {

            const { attributes, setAttributes, isSelected } = props;
            const [forms, setForms] = useState([]);
            const [loading, setLoading] = useState(true);
            const [error, setError] = useState(null);
            const [svg_icon, setSvgIcon] = useState('');

            // Only show preview.gif in block inserter when form_id is "preview"
            const is_example = attributes.form_id === "preview";

            // Use useBlockProps for proper block wrapper
            const block_props = useBlockProps({
                className: 'wp-block-wsf-block-form-add'
            });

            useEffect(() => {

                // Skip ALL processing for example previews
                if(is_example) {

                    setLoading(false);
                    return;
                }

                // Load SVG logo
                fetch(ws_form_block_form_add.url_block + '/logo.svg')
                    .then(response => response.text())
                    .then(svg => setSvgIcon(svg))
                    .catch(() => {});

                // Use wp.apiFetch instead of fetch for proper authentication
                if(window.wp && window.wp.apiFetch) {

                    window.wp.apiFetch({ 
                        path: '/ws-form/v1/block/forms/',
                        method: 'GET'
                    })
                    .then(setForms)
                    .catch(err => {
                        setError(err.message);
                    })
                    .finally(() => setLoading(false));

                } else {

                    // Fallback to regular fetch with nonce
                    const headers = {};
                    
                    // Add nonce if available
                    if(ws_form_block_form_add.nonce) {

                        headers['X-WP-Nonce'] = ws_form_block_form_add.nonce;

                    } else if(window.wpApiSettings && window.wpApiSettings.nonce) {

                        headers['X-WP-Nonce'] = window.wpApiSettings.nonce;
                    }
                    
                    fetch('/wp-json/ws-form/v1/block/forms/', {

                        method: 'GET',
                        headers: headers,
                        credentials: 'same-origin'
                    })
                    .then(res => {

                        if(!res.ok) {

                            throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                        }

                        return res.json();
                    })
                    .then(setForms)
                    .catch(err => {

                        setError(err.message);
                    })
                    .finally(() => setLoading(false));
                }
            }, [is_example]);

            // If this is an example preview, show the preview GIF
            if(is_example) {
                return element.createElement('div', block_props,
                    element.createElement('div', {
                        className: 'wsf-block-preview-container',
                        style: {
                            textAlign: 'center',
                            padding: '10px',
                            backgroundColor: '#ffffff',
                            borderRadius: '4px'
                        }
                    },
                        element.createElement('img', {
                            src: ws_form_block_form_add.url_block + '/preview.gif',
                            alt: ws_form_block_form_add.text_preview_alt,
                            style: {
                                maxWidth: '100%',
                                height: 'auto',
                                borderRadius: '4px'
                            },
                            onError: (e) => {
                                // Fallback to showing the plugin icon if GIF fails
                                e.target.style.display = 'none';
                            }
                        })
                    )
                );
            }

            if(loading) {

                return element.createElement('div', block_props,

                    element.createElement(

                        'div',
                        {
                            className: 'ws-form-block-placeholder',
                            style: { 
                                padding: '40px 20px', 
                                border: '2px dashed #ccc', 
                                textAlign: 'center',
                                color: '#666',
                                backgroundColor: '#f9f9f9',
                                borderRadius: '4px',
                                cursor: 'pointer',
                                minHeight: '120px',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center'
                            },
                            onClick: (e) => {
                                e.preventDefault();
                            }
                        },

                        element.createElement(

                            'div',
                            {},

                            // SVG Logo loaded from logo.svg file
                            svg_icon ? element.createElement('div', {
                                dangerouslySetInnerHTML: { __html: svg_icon },
                                style: { marginBottom: '12px' }
                            }) : element.createElement('div', {
                                style: { 
                                    width: '48px', 
                                    height: '48px', 
                                    backgroundColor: '#f0f0f0',
                                    margin: '0 auto 12px auto'
                                }
                            }),

                            element.createElement('p', { style: { margin: '0', fontSize: '14px', opacity: '0.8' } }, 
                                ws_form_block_form_add.text_loading
                            )
                        )
                    )
                );
            }

            if(error) {

                return element.createElement('div', 

                    { ...block_props, style: { color: 'red' } }, 
                    error
                );
            }

            return element.createElement('div', block_props,

                // InspectorControls should always be rendered when block is selected
                isSelected && element.createElement(InspectorControls, {},
                    element.createElement(PanelBody, { title: ws_form_block_form_add.text_panel_title, initialOpen: true },
                        element.createElement(components.SelectControl, {
                            label: ws_form_block_form_add.text_label_form_id,
                            value: attributes.form_id || '',
                            options: [
                                { label: ws_form_block_form_add.text_option_placeholder, value: '' },
                                ...forms
                                    .filter(form => form && form.id)
                                    .map((form, index) => {
                                        const form_id = String(form.id || '');
                                        return {
                                            label: form.label || `Form ${form_id || index + 1}`,
                                            value: form_id
                                        };
                                    })
                            ],
                            onChange: (value) => setAttributes({ form_id: value })
                        }),

                        element.createElement(TextControl, {
                            label: ws_form_block_form_add.text_label_form_element_id,
                            value: attributes.form_element_id || '',
                            onChange: (val) => setAttributes({ form_element_id: val }),
                            help: ws_form_block_form_add.text_help_form_element_id
                        }),
                        
                        // Form action buttons - all in one row
                        element.createElement('div', {

                            style: { 
                                marginTop: '16px',
                                display: 'flex', 
                                gap: '8px',
                                flexWrap: 'wrap'
                            }
                        },

                            // Add Form button - always visible
                            element.createElement(components.Button, {

                                variant: 'secondary',
                                onClick: () => {
                                    const add_form_url = `${ws_form_block_form_add.url_admin}?page=ws-form-add`;
                                    window.open(add_form_url, '_blank');
                                },
                                style: { flex: '1', minWidth: '60px', textAlign: 'center', display: 'block' }
                            }, ws_form_block_form_add.text_add_form_button),
                            
                            // Edit Form button - only show when a form is selected
                            attributes.form_id && element.createElement(components.Button, {

                                variant: 'secondary',
                                onClick: () => {
                                    const edit_form_url = `${ws_form_block_form_add.url_admin}?page=ws-form-edit&id=${attributes.form_id}`;
                                    window.open(edit_form_url, '_blank');
                                },
                                style: { flex: '1', minWidth: '60px', textAlign: 'center', display: 'block' }
                            }, ws_form_block_form_add.text_edit_form_button),
                            
                            // Style button - only show when a form is selected
                            attributes.form_id && element.createElement(components.Button, {

                                variant: 'secondary',
                                onClick: () => {
                                    // Generate random string for wsf_rand parameter
                                    const random_string = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 4);
                                    const styler_url = `${ws_form_block_form_add.url_site}?wsf_preview_form_id=${attributes.form_id}&wsf_preview_styler=true&wsf_rand=${random_string}`;
                                    window.open(styler_url, '_blank');
                                },
                                style: { flex: '1', minWidth: '60px', textAlign: 'center', display: 'block' }
                            }, ws_form_block_form_add.text_styler_button)
                        )
                    )
                ),
                // Block content - always show live form when selected
                attributes.form_id ? 
                    element.createElement('div', {

                        className: 'wsf-block-form-container',
                        style: { 
                            position: 'relative',
                            minHeight: '100px',
                            pointerEvents: 'auto'
                        }
                    },
                        element.createElement(ServerSideRender, {

                            block: 'wsf-block/form-add',
                            attributes: attributes,
                            className: 'wsf-server-side-render'
                        })
                    ) :
                    element.createElement('div', { 

                        className: 'ws-form-block-placeholder',
                        style: { 
                            padding: '40px 20px', 
                            border: '2px dashed #ccc', 
                            textAlign: 'center',
                            color: '#666',
                            backgroundColor: '#f9f9f9',
                            borderRadius: '4px',
                            cursor: 'pointer',
                            minHeight: '120px',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center'
                        },
                        onClick: (e) => {
                            e.preventDefault();
                        }
                    }, 
                    element.createElement('div', {},

                        // SVG Logo loaded from logo.svg file
                        svg_icon ? element.createElement('div', {
                            dangerouslySetInnerHTML: { __html: svg_icon },
                            style: { marginBottom: '12px' }
                        }) : element.createElement('div', {
                            style: { 
                                width: '48px', 
                                height: '48px', 
                                backgroundColor: '#f0f0f0',
                                margin: '0 auto 12px auto'
                            }
                        }),
                        element.createElement('p', { style: { margin: '0', fontSize: '14px', opacity: '0.8' } }, 
                            ws_form_block_form_add.text_form_not_selected
                        )
                    )
                )
            );
        },

        save: function () {

            return null;
        }
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.components,
    window.wp.blockEditor,
    window.wp.serverSideRender
);