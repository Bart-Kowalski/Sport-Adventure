(function () {
    const toggles = document.querySelectorAll('.brxe-brxc-darkmode-toggle, .brxe-brxc-darkmode-btn, .brxe-brxc-darkmode-btn-nestable, .brxe-brxc-darkmode-toggle-nestable');
    const acssToggles = document.querySelectorAll('[data-acss-color-scheme="toggle"]');
    if (toggles.length < 1) return;
    const html = document.documentElement;
    const isACSSLoaded = typeof acss !== "undefined";
    let darkmodeCookie = isACSSLoaded ? localStorage.getItem("acss-color-scheme") : localStorage.getItem("brxc-theme");

    toggles.forEach(toggle => {
        const checkbox = toggle.querySelector('input.brxc-toggle-checkbox, .brxc-darkmode-btn-nestable__checkbox, .brxc-darkmode-toggle-nestable__checkbox')
        if(!checkbox) return;

        if(darkmodeCookie === 'dark' || (!darkmodeCookie && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            checkbox.checked = true;
        }

        checkbox.addEventListener('change', () => {
            if((isACSSLoaded && html.classList.contains("color-scheme--alt")) || html.dataset.theme === 'dark') {
                html.setAttribute('data-theme','light');
                toggles.forEach(cb => {cb.querySelector('input.brxc-toggle-checkbox, .brxc-darkmode-btn-nestable__checkbox, .brxc-darkmode-toggle-nestable__checkbox').checked = false;});
                localStorage.setItem("brxc-theme", "light");

                // ACSS
                if(isACSSLoaded){
                    html.classList.remove("color-scheme--alt")
                    acssToggles.forEach(cb => {
                        cb.classList.remove('checked');
                        cb.checked = false;
                    })
                    localStorage.setItem("acss-color-scheme", "light")
                }
            } else {
                html.setAttribute('data-theme','dark');
                toggles.forEach(cb => {cb.querySelector('input.brxc-toggle-checkbox, .brxc-darkmode-btn-nestable__checkbox, .brxc-darkmode-toggle-nestable__checkbox').checked = true;});
                localStorage.setItem("brxc-theme", "dark");

                // ACSS
                if(isACSSLoaded){
                    html.classList.add("color-scheme--alt")
                    acssToggles.forEach(cb => {
                        cb.classList.add('checked');
                        cb.checked = true;
                    })
                    localStorage.setItem("acss-color-scheme", "dark")
                }
            }
        })
    })

    acssToggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
            if((isACSSLoaded && html.classList.contains("color-scheme--alt")) || html.dataset.theme === 'dark') {
                html.setAttribute('data-theme','light');
                toggles.forEach(cb => {cb.querySelector('input.brxc-toggle-checkbox, .brxc-darkmode-btn-nestable__checkbox, .brxc-darkmode-toggle-nestable__checkbox').checked = false;});
                localStorage.setItem("brxc-theme", "light");
            } else {
                html.setAttribute('data-theme','dark');
                toggles.forEach(cb => {cb.querySelector('input.brxc-toggle-checkbox, .brxc-darkmode-btn-nestable__checkbox, .brxc-darkmode-toggle-nestable__checkbox').checked = true;});
                localStorage.setItem("brxc-theme", "dark");
            }
        })
    })
})()

window.addEventListener('load', () => {
    const toggles = document.querySelectorAll('.brxe-brxc-darkmode-toggle, .brxe-brxc-darkmode-btn, .brxe-brxc-darkmode-btn-nestable, .brxe-brxc-darkmode-toggle-nestable');
    if (toggles.length < 1) return;

    toggles.forEach(toggle => {
        toggle.classList.remove('no-animation');
        toggle.removeAttribute('data-no-animation');
        const input = toggle.querySelector('input[type="checkbox"]');
        if(input) input.style = "";

    })
})