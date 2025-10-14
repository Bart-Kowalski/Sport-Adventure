function brxcDownloadObjectAsJson(exportObj, exportName){
    var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(exportObj));
    var downloadElement = document.createElement('a');
    downloadElement.setAttribute("href", dataStr);
    downloadElement.setAttribute("download", exportName);
    downloadElement.style.display = 'none';
    document.body.appendChild(downloadElement)
    downloadElement.click();
    document.body.removeChild(downloadElement);
}

function brxcHandleResponse(response, obj){
    const modal = document.createElement('div');
    modal.id = "brxcModalResponse";
    const cls = obj.success ? 'success' : 'error';
    modal.setAttribute('class', cls);
    let content = '';
    content += `<div id="brxcModalHeader">${obj.header}</div>`;
    content += `<div id="brxcModalBody"><h3>${obj.text}</h3>`;
    response.forEach(el => {
        content += `<div><span class="dashicons dashicons-yes"></span>${el}</h3></div>`
    })
    content += obj.btn;
    content += '</div>'
    modal.innerHTML = content;
    document.body.appendChild(modal);

}

jQuery(document).ready(function($){

    const form = '<input type="file" id="brxcImportFile" name="filename" accept="application/JSON"><a href="#" id="brxcImportSubmit" class="button button-primary button-large button-disabled">Import Settings</a>';
    $('#brxcImportWrapper').append(form);
    const file = document.querySelector('#brxcImportFile');
    const submit = document.querySelector('#brxcImportSubmit');
    if (file) {
        file.addEventListener('change', () => {
            (file.files.length) ? submit.classList.remove('button-disabled') : submit.classList.add('button-disabled');
        })
    }

    $('#brxcConvertToLogical').click(function(e) {
        e.preventDefault();
        const checkedData = [];
        const options = document.querySelectorAll('[data-name="brxc_convert_to_logical_properties"] li input');
        options.forEach(option => {
            if(option.checked === true) checkedData.push(option.value);
        })

        $.ajax({
            url: exportOptions.ajax_url, 
            method: "POST",
            dataType: "JSON",
            data: {
                action: "convert_to_logical_properties",
                nonce: exportOptions.nonce,
                checked_data: checkedData,
                logical: true,
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    brxcHandleResponse(response.data, {
                        success: true,
                        header: 'Success!',
                        text: 'The following settings have been correctly converted:',
                        btn: '<button class="button button-primary button-large" onclick="window.location.reload(true)">Reload Page</button>',
                    });
                } else {
                    brxcHandleResponse(response.data, {
                        success: true,
                        header: 'Notice',
                        text: 'No settings were converted. If you think this is an error, please contact the support.',
                        btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                brxcHandleResponse(null, {
                    success: false,
                    header: 'Error!',
                    text: `The following error occurred: "${errorThrown}". Please contact support.`,
                    btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                });
            }
        });
    })

    $('#brxcConvertToDirectional').click(function(e) {
        e.preventDefault();
        const checkedData = [];
        const options = document.querySelectorAll('[data-name="brxc_convert_to_logical_properties"] li input');
        options.forEach(option => {
            if(option.checked === true) checkedData.push(option.value);
        })

        $.ajax({
            url: exportOptions.ajax_url, 
            method: "POST",
            dataType: "JSON",
            data: {
                action: "convert_to_logical_properties",
                nonce: exportOptions.nonce,
                checked_data: checkedData,
                logical: false,
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    brxcHandleResponse(response.data, {
                        success: true,
                        header: 'Success!',
                        text: 'The following settings have been correctly converted:',
                        btn: '<button class="button button-primary button-large" onclick="window.location.reload(true)">Reload Page</button>',
                    });
                } else {
                    brxcHandleResponse(response.data, {
                        success: true,
                        header: 'Notice',
                        text: 'No settings were converted. If you think this is an error, please contact the support.',
                        btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                brxcHandleResponse(null, {
                    success: false,
                    header: 'Error!',
                    text: `The following error occurred: "${errorThrown}". Please contact support.`,
                    btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                });
            }
        });
    })

    $('#brxcConvertCSSGridUtilityClasses').click(function(e) {
        e.preventDefault();
        const checkedData = [];
        const options = document.querySelectorAll('[data-name="brxc_convert_to_css_grid_utility_classes"] li input');
        options.forEach(option => {
            if(option.checked === true) checkedData.push(option.value);
        })

        $.ajax({
            url: exportOptions.ajax_url, 
            method: "POST",
            dataType: "JSON",
            data: {
                action: "convert_to_css_grid_utility_classes",
                nonce: exportOptions.nonce,
                checked_data: checkedData,
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    brxcHandleResponse(response.data, {
                        success: true,
                        header: 'Success!',
                        text: 'The following settings have been correctly converted:',
                        btn: '<button class="button button-primary button-large" onclick="window.location.reload(true)">Reload Page</button>',
                    });
                } else {
                    brxcHandleResponse(response.data, {
                        success: true,
                        header: 'Notice',
                        text: 'No settings were converted. If you think this is an error, please contact the support.',
                        btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                brxcHandleResponse(null, {
                    success: false,
                    header: 'Error!',
                    text: `The following error occurred: "${errorThrown}". Please contact support.`,
                    btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                });
            }
        });
    })

    $('#brxcConvertHideRemoveSettings').click(function(e) {
        e.preventDefault();
        const checkedData = [];
        const options = document.querySelectorAll('[data-name="brxc_convert_hide_remove_settings"] li input');
        options.forEach(option => {
            if(option.checked === true) checkedData.push(option.value);
        })

        $.ajax({
            url: exportOptions.ajax_url, 
            method: "POST",
            dataType: "JSON",
            data: {
                action: "convert_hide_remove_settings",
                nonce: exportOptions.nonce,
                checked_data: checkedData,
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    brxcHandleResponse(response.data, {
                        success: true,
                        header: 'Success!',
                        text: 'The following settings have been correctly converted:',
                        btn: '<button class="button button-primary button-large" onclick="window.location.reload(true)">Reload Page</button>',
                    });
                } else {
                    brxcHandleResponse(response.data, {
                        success: true,
                        header: 'Notice',
                        text: 'No settings were converted. If you think this is an error, please contact the support.',
                        btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                brxcHandleResponse(null, {
                    success: false,
                    header: 'Error!',
                    text: `The following error occurred: "${errorThrown}". Please contact support.`,
                    btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                });
            }
        });
    })

    $('#brxcExportSettings').click(function(e) {
        e.preventDefault();
        const checkedData = [];
        const options = document.querySelectorAll('[data-name="brxc_export_data"] li input');
        options.forEach(option => {
            if(option.checked === true) checkedData.push(option.value);
        })

        $.ajax({
            url: exportOptions.ajax_url, 
            method: "POST",
            dataType: "JSON",
            data: {
                action: "export_advanced_options",
                nonce: exportOptions.nonce,
                checked_data: checkedData,
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    if (Object.keys(response.data.json_data).length > 0) {
                        brxcDownloadObjectAsJson(response.data.json_data, 'export_at_theme_settings.json');
                        brxcHandleResponse(response.data.success_data, {
                            success: true,
                            header: 'Success!',
                            text: 'The following settings have been correctly exported:',
                            btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                        });
                    } else {
                        console.log('No AT data found');
                        brxcHandleResponse(response.data.success_data, {
                            success: true,
                            header: 'Notice',
                            text: 'No settings were exported. If you think this is an error, please contact the support.',
                            btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                        });
                    }
                } else {
                    brxcHandleResponse(response.data.success_data, {
                        success: true,
                        header: 'Notice',
                        text: 'No settings were exported. If you think this is an error, please contact the support.',
                        btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                brxcHandleResponse(null, {
                    success: false,
                    header: 'Error!',
                    text: `The following error occurred: "${errorThrown}". Please contact support.`,
                    btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                });
            }
        });
    });

    $('#brxcImportSubmit').click(function(e) {
        e.preventDefault();

        const checkedData = [];
        const options = document.querySelectorAll('[data-name="brxc_import_data"] li input');
        const overwrite = document.querySelectorAll('[data-name="brxc_import_data_overwrite"] input[type="checkbox"]');
        options.forEach(option => {
            if(option.checked === true) checkedData.push(option.value);
        })

        const file = $('#brxcImportFile')[0].files[0];
        if (typeof file === "undefined") {
            alert('No file imported. Please select a JSON export file.');
            return false;
        }

        // Create a FormData object
        const formData = new FormData();
        formData.append('action', 'import_advanced_options');
        formData.append('nonce', exportOptions.nonce);
        formData.append('file', file);
        formData.append('checked_data', JSON.stringify(checkedData));
        formData.append('overwrite', overwrite.checked);
        $.ajax({
            url: exportOptions.ajax_url, 
            method: "POST",
            dataType: "JSON",
            data: formData, // Use formData as data
            processData: false, // Tell jQuery not to process the data
            contentType: false, // Tell jQuery not to set contentType
            success: function(response) {
                console.log(response);
                if (response.success) {
                    brxcHandleResponse(response.data, {
                        success: true,
                        header: 'Success!',
                        text: 'The following settings have been correctly imported:',
                        btn: '<button class="button button-primary button-large" onclick="window.location.reload(true)">Reload Page</button>',
                    });
                } else {
                    brxcHandleResponse(response.data, {
                        success: true,
                        header: 'Notice',
                        text: 'No settings were imported. If you think this is an error, please contact the support.',
                        btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                brxcHandleResponse(null, {
                    success: false,
                    header: 'Error!',
                    text: `The following error occurred: "${errorThrown}". Please contact support.`,
                    btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                });
            }
        });
    });
    $('#brxcResetSettings').click(function(e) {
        e.preventDefault();
        const checkedData = [];
        const options = document.querySelectorAll('[data-name="brxc_reset_data"] li input');
        options.forEach(option => {
            if(option.checked === true) {
                checkedData.push(option.value);
                if(option.value === "at-local-storage"){
                    localStorage.removeItem('brxc-builder-states');
                }
            }
        })

        $.ajax({
            url: exportOptions.ajax_url,
            method: "POST",
            dataType: "json",
            data: {
                action: "reset_advanced_options",
                nonce: exportOptions.nonce,
                checked_data: checkedData,
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    brxcHandleResponse(response.data, {
                        success: true,
                        header: 'Success!',
                        text: 'The following settings have been correctly removed:',
                        btn: '<button class="button button-primary button-large" onclick="window.location.reload(true)">Reload Page</button>',
                    });
                } else {
                    brxcHandleResponse(response.data, {
                        success: true,
                        header: 'Notice',
                        text: 'No settings were removed. If you think this is an error, please contact the support.',
                        btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                brxcHandleResponse(null, {
                    success: false,
                    header: 'Error!',
                    text: `The following error occurred: "${errorThrown}". Please contact support.`,
                    btn: `<button class="button button-primary button-large" onclick="document.querySelector('#brxcModalResponse').remove();">Close</button>`,
                });
            }
        });
    });
})


window.addEventListener('DOMContentLoaded', () => {
    //open tab with URL hash

    const url = new URL(window.location.href); 
    const anchorID = url.hash.substring(1);
    const link = document.querySelector(`[data-key="${anchorID}"]`); 
    if (link) link.click();

    document.querySelectorAll('body.bricks_page_bricks-advanced-themer .acf-repeater .acf-field .acf-input').forEach(function(el) {
        if (el.querySelector('.acf-input-append')) {
            el.classList.add('has-append');
        }
    });

    const adminOption = document.querySelector('#acf-field_63daa58ccc209-field_6388e73289b6a-administrator');
    if (!adminOption) return;
    adminOption.setAttribute('disabled','');
    adminOption.setAttribute('checked','');

    
    
})