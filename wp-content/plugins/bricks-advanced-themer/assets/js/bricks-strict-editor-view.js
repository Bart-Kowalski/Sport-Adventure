const ADMINEDITORBRXC = {
    vueState: document.querySelector('.brx-body').__vue_app__.config.globalProperties.$_state,
    vueGlobalProp: document.querySelector('.brx-body').__vue_app__.config.globalProperties,
    helpers: {
        getElementObject: function(id, forceStructure = false){

            const getElementObject = ADMINEDITORBRXC.vueGlobalProp.$_getElementObject;
            const getDynamicElementById = ADMINEDITORBRXC.vueGlobalProp.$_getDynamicElementById;
        
            if (typeof getElementObject === 'function') {
                return getElementObject(id);
            } else if (typeof getDynamicElementById === 'function') {
                const obj = getDynamicElementById(id);
                if(obj && obj.hasOwnProperty('cid')){
                    return ADMINEDITORBRXC.vueGlobalProp.$_getComponentElementById(obj.cid);
                } else {
                    return obj;
                }
            } else {
                console.error("No suitable function available to get element object.");
                return null;
            }
            
        },
        isElementActive: function(){
            if(ADMINEDITORBRXC.vueState.activePanel !== "element" || !typeof ADMINEDITORBRXC.vueState.activeElement === "object") return false;
            return true;
         },
    },
    // appendElements: function(){
    //     const els = document.querySelector('#bricks-panel-inner #bricks-panel-elements');
    //     const defaultPanel = document.querySelector('#bricks-panel-inner #default-panel')

    //     if(!els || defaultPanel) return;

    //     els.innerHTML = '<div id="default-panel" class="no-results"><p>No element selected. Hover the page and click on an selectable element (with a blue outline) to modify the content.</p><a href="https://academy.bricksbuilder.io/article/builder-intro/" target="_blank" class="button">Learn more</a></div>';
    // },
    controlVisibility: function(){
        const self = this;
        if(!self.helpers.isElementActive()) return;
        let style = document.querySelector('#brxcFullAccessElementStyles');

        // Create style
        if(!style){
            style = document.createElement('style');
            style.id = 'brxcFullAccessElementStyles';
        }
        const controls = self.vueState.activeElement.settings.fullAccessDisable || false;
        let css = ''

        if(controls && Array.isArray(controls) && controls.length > 0){
            css = controls.map(key => `[data-controlkey="${key}"]`).join();
            css += `{display:none}`;
        } 
        style.innerHTML = css;
        document.head.appendChild(style);

    },
    setNotes: function(){
        const self = this;
        const structure = document.querySelector('#bricks-structure');
        if (!structure) return;
    
        const els = structure.querySelectorAll('.element');
        if (els.length < 1) return;
    
        // Loop into the Structure elements
        els.forEach(el => {
            const elementObject = self.helpers.getElementObject(el.dataset.id);
            const elementSettings = elementObject?.settings;
            if (elementSettings.hasOwnProperty('editorNotes')) {
                let action = el.querySelector('ul.actions');
                if (!action) {
                    el.querySelector(".title").insertAdjacentHTML("afterend", `<ul class="actions"></ul>`);
                    action = el.querySelector('ul.actions');
                }
                const existing = action.querySelector('.action.notes');
                if (existing) existing.remove();
                
                const liSpan = `<span class="bricks-svg-wrapper"><i class="ti-comment-alt"></i></span>`;
                const li = document.createElement('li');
                li.className = `action notes`;
                li.setAttribute('onmouseenter', `ADMINEDITORBRXC.showNotes(event, '${elementSettings['editorNotes']}')`);
                li.setAttribute('onmouseleave', `ADMINEDITORBRXC.hideNotes()`)
                li.innerHTML = liSpan;
                action.append(li);
            } else {
                const existing = el.querySelector('.action.notes');
                if (existing) existing.remove();
            }
            
        })
    },
    showNotes: function(event,txt){
        const element = event.target;
        const rect = element.getBoundingClientRect();
        const div = document.createElement('div');
        div.className = `brxc-notes`;
        div.innerHTML = `<span>${txt}</span>`;
        div.style.top = `${parseInt(rect.bottom)}px`; 
        div.style.left = `calc(${parseInt(rect.right)}px - 216px)`; 
        document.body.append(div);

    },  
    hideNotes: function(){
        const existingNotes = document.querySelectorAll('.brxc-notes');
        if(existingNotes) existingNotes.forEach(el => el.remove());
    },
    changeIcon: function(){
        const newIconUrl = brxcStrictOptions.change_logo;
        if(!!newIconUrl){
            const toolbarLogo = document.querySelector("#bricks-toolbar .logo img");
            if(!toolbarLogo) return
            toolbarLogo.src = newIconUrl;
        }
    },
    runStateFunctions: function(){
        const self = this;
        //self.appendElements();
        self.controlVisibility();
    },
    runObserver: function() {
        const self = this;
        const panelInner = document.querySelector('#bricks-panel');
        if (!panelInner) return;

        const observer = new MutationObserver(function(mutations) {
            if(self.vueState.brxcRunningObserver === true) return;
            self.vueState.brxcRunningObserver = true;
            self.runStateFunctions();
            
            setTimeout(() => self.vueState.brxcRunningObserver = false, 300)
        });
        observer.observe(panelInner, { subtree: true, childList: true });
    },
    initObservers: function(){
        const self = this;
        self.runObserver();
    },
    setBodyClasses: function(){
        const self = this;
        brxcStrictOptions.builderTweaks.forEach(el => {
            document.body.classList.add(`at-${el}`);
        })
    },
    init: function(){
        const self = this;
        self.setBodyClasses();
        self.initObservers();
        //self.appendElements();
        self.setNotes();
    }
}

window.addEventListener('DOMContentLoaded', () => {
    ADMINEDITORBRXC.changeIcon();
})
window.addEventListener('load', () => {
    setTimeout(() => {
        ADMINEDITORBRXC.init()
    }, 300);
})

