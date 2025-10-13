
function accordion_script(wpgbAccordion = null) {

	const accordionInstances = new WeakMap();

	class Accordion {
		constructor(element, options) {
			this.element = element;
			this.options = options;
			this.isScrolling = false;
			this.scrollTimeout = null;
			this.initialHashHandled = false;

			// new order
			if (!this.validateStructure()) {
				return;
			}

			this.removePlaceholder();
			// end new order

			this.removePlaceholderChild();
			this.accordionItems = this.element.querySelectorAll(':scope > .fr-accordion__item');
			this.lastOpenedItem = null;
			this.init();
			accordionInstances.set(this.element, this);

			// Handle initial hash on page load
			if (this.options.scrollToHash && location.hash) {
				this.handleInitialHash();
			}
		}

		init() {
			this.setEventListeners();
			this.handleFirstItemOpened();
			this.handleAllItemsExpanded();
			this.handleExpandedClass();
			this.handleScrollToHash();
			this.handleShowDuration();
			this.addTabindex();
			this.addIdToBody();
			this.addAriaControls();
			this.addIdToHeader();
			this.addAriaLabelledBy();
			this.addFaqSchema();
			this.addRoleButton();

			this.ITEMS(item => {
				if (this.HEADER(item).getAttribute('aria-expanded') !== 'true') {
					this.updateFocusableTabIndex(item, false);
				}
			});

			this.ITEMS(item => {
				const expandedCurrentLink = this.options.expandedCurrentLink;
				if (this.hasCurrentPageLink(item) && expandedCurrentLink === false) {
					this.expandItem(item);
				}
			});

		}

		BODY(item) {
			//return item.querySelector('.fr-accordion__body') || item.children[1];
			const body = item.querySelector('.fr-accordion__body');
			if (!body && item.children.length > 1 && item.children[1].tagName !== 'STYLE') {
				return item.children[1];
			}
			return body;
		}

		HEADER(item) {
			//return item.querySelector('.fr-accordion__header') || item.children[0];
			const header = item.querySelector('.fr-accordion__header');
			if (!header && item.children.length > 0 && item.children[0].tagName !== 'STYLE') {
				return item.children[0];
			}
			return header;
		}

		ITEMS(init) {
			this.accordionItems.forEach(item => {
				if (item.tagName !== 'STYLE' &&
					!item.classList.contains('brx-nestable-children-placeholder') &&
					!(item.tagName === 'DIV' && item.classList.contains('brx-query-trail'))) {
					init(item);
				}
			});
		}


		removePlaceholder() {
			const placeholder = this.element.querySelector('.brx-nestable-children-placeholder');
			const accordionChildren = this.element.querySelectorAll('.fr-accordion__item');

			if (placeholder) {
				placeholder.remove();
			}

			// If there are no children of accordion, add the placeholder back
			if (accordionChildren.length === 0) {
				const newPlaceholder = document.createElement('li');
				newPlaceholder.className = 'brx-nestable-children-placeholder fr-accordion__item';
				this.element.appendChild(newPlaceholder);
			}
		}


		setEventListeners() {
			this.ITEMS((item) => this.attachHeaderListeners(item));
		}

		attachHeaderListeners(item) {
			const header = this.HEADER(item);
			header.addEventListener('click', () => {
				this.headerInteraction(item);
			});
			header.addEventListener('keydown', (e) => {
				if (e.key === 'Enter') {
					this.headerInteraction(item);
				}
			});
			header.addEventListener('keyup', (e) => {
				if (e.key === ' ') {
					this.headerInteraction(item);
				}
			});
		}

		generateUniqueId(item) {
			if (!this.usedIds) {
				this.usedIds = new Set();
			}

			const header = this.HEADER(item);

			// If there's already an ID, respect it and add to used IDs set
			if (header.id) {
				this.usedIds.add(header.id);
				return header.id;
			}

			// Get the title text from the header
			const title = header.querySelector('.fr-accordion__title');

			if (title) {
				const titleText = title.textContent.trim();
				let textForId = titleText
					.split(' ')
					.slice(0, 3)
					.join('-')
					.toLowerCase()
					.replace(/[^a-z0-9-]/g, '-')
					.replace(/-+/g, '-')
					.replace(/^-|-$/g, '');

				// If this ID is already used, append a number
				if (this.usedIds.has(textForId)) {
					let counter = 2;
					let newId = `${textForId}-${counter}`;

					// Keep incrementing counter until we find an unused ID
					while (this.usedIds.has(newId)) {
						counter++;
						newId = `${textForId}-${counter}`;
					}
					textForId = newId;
				}

				this.usedIds.add(textForId);
				return textForId;
			}

			// Fallback only if no title found
			const fallbackId = `accordion-item-${Math.random().toString(36).substr(2, 9)}`;
			this.usedIds.add(fallbackId);
			return fallbackId;
		}
		headerInteraction(item) {
			if (window.innerWidth <= this.options.scrollToHeadingOn) {
        		this.scrollToItemHeader(item);
    		}

			if (this.options.closePreviousItem && this.options.allItemsExpanded) {
				this.ITEMS((otherItem) => {
					if (otherItem !== item) {
						this.collapseItem(otherItem);
					}
				});
			} else if (this.options.closePreviousItem && this.lastOpenedItem && this.lastOpenedItem !== item) {
				this.collapseItem(this.lastOpenedItem);
			}
			this.toggleItem(item);
			this.lastOpenedItem = this.HEADER(item).getAttribute('aria-expanded') === 'true' ? item : null;


		}

		scrollToItemHeader(item) {

			if (!this.options.scrollToHeading) return;

			setTimeout(() => {
				this.HEADER(item).scrollIntoView({
					behavior: 'smooth',
					block: 'start',
					inline: 'nearest'
				});
			}, this.options.showDuration);

		}



		toggleItem(item) {
			if (this.HEADER(item).getAttribute('aria-expanded') === 'true') {
				this.collapseItem(item);
			} else {
				this.expandItem(item);
			}
		}

		// fr-accordion__icon flip icon on expand and collapse
		flipIcon(item) {
			const icon = this.HEADER(item).querySelector('.fr-accordion__icon');
			if (icon) {
				// add transition property with time of showDuration
				icon.style.transition = `transform ${this.options.showDuration}ms ease-in-out`;
				icon.classList.toggle('fr-accordion__icon--flipped');
			}
		}


		handleFirstItemOpened() {
			if (!this.options.firstItemOpened) return;
			this.expandItem(this.accordionItems[0], true);
			this.lastOpenedItem = this.accordionItems[0];
		}

		handleAllItemsExpanded() {
			if (!this.options.allItemsExpanded) return;
			this.ITEMS((item) => this.expandItem(item, true));
		}

		handleExpandedClass() {
			if (!this.options.expandedClass) return;
			this.ITEMS((item) => {
				if (item.classList.contains('fr-accordion__item--expanded')) {
					this.expandItem(item);
				}
			});
		}

		handleScrollToHash() {
			if (!this.options.scrollToHash) return;

			document.addEventListener('click', (e) => {
				const link = e.target.closest('a[href^="#"]');
				if (!link) return;

				const targetHeader = this.element.querySelector(link.hash);
				if (!targetHeader?.classList.contains('fr-accordion__header')) return;

				const targetItem = targetHeader.closest('.fr-accordion__item');
				if (targetItem) {
					e.preventDefault();
					this.scrollToItemAndExpand(targetItem);
					history.pushState(null, '', link.hash);
				}
			});

			// Handle browser back/forward
			window.addEventListener('popstate', (e) => {
				if (location.hash) {
					const targetHeader = this.element.querySelector(location.hash);
					if (!targetHeader?.classList.contains('fr-accordion__header')) return;

					const targetItem = targetHeader.closest('.fr-accordion__item');
					if (targetItem) {
						this.scrollToItemAndExpand(targetItem);
					}
				}
			});
		}

		handleInitialHash() {
			if (this.initialHashHandled) return;

			const hash = location.hash;
			const targetHeader = this.element.querySelector(hash);

			if (!targetHeader?.classList.contains('fr-accordion__header')) return;

			const targetItem = targetHeader.closest('.fr-accordion__item');
    	if (!targetItem) return;

			// Only execute when page is ready
			if (document.readyState === 'complete') {
				this.scrollToInitialHash(targetItem);
			} else {
				window.addEventListener('load', () => this.scrollToInitialHash(targetItem));
			}

			this.initialHashHandled = true;
		}

		scrollToInitialHash(targetItem) {
			// Handle closing previous items first
			if (this.options.closePreviousItem) {
				this.ITEMS((item) => {
					if (item !== targetItem && this.HEADER(item).getAttribute('aria-expanded') === 'true') {
						this.collapseItem(item);
					}
				});
			}

			// Expand target and update state
			this.expandItem(targetItem);
			this.lastOpenedItem = targetItem;

			// Wait for expansion animation
			setTimeout(() => {
				const header = this.HEADER(targetItem);
				const totalOffset = parseInt(this.options.scrollOffset) || 1;

				// Calculate final position and scroll
				const headerPosition = header.getBoundingClientRect().top + window.scrollY - totalOffset;
				window.scrollTo({
					top: Math.max(0, headerPosition),
					behavior: 'instant'
				});
			}, this.options.showDuration);
		}


		scrollToItemAndExpand(item) {
			// closing previous item before expanding: if we're collapsing items, wait for the full animation duration before scrolling
			let needsDelay = false;

			if (this.options.closePreviousItem) {
				if (this.options.allItemsExpanded) {
					this.ITEMS((otherItem) => {
						if (otherItem !== item) {
							this.collapseItem(otherItem);
							needsDelay = true;
						}
					});
				} else if (this.lastOpenedItem && this.lastOpenedItem !== item) {
					this.collapseItem(this.lastOpenedItem);
					needsDelay = true;
				}
			}

			// Clear any existing timeout
			if (this.scrollTimeout) {
				clearTimeout(this.scrollTimeout);
			}

			this.expandItem(item);
			this.lastOpenedItem = item;

			// Wait for collapse animation to complete before scrolling
			// 	setTimeout(() => {
			// 		const header = this.HEADER(item);
			// 		const defaultOffset = 1;
			// 		const customOffset = parseInt(this.options.scrollOffset) || 0;
			// 		const totalOffset = customOffset || defaultOffset;
			// 		const headerPosition = header.getBoundingClientRect().top + window.scrollY - totalOffset;

			// 		window.scrollTo({
			// 			top: Math.max(0, headerPosition),
			// 			behavior: 'smooth'
			// 		});
			// 	}, this.options.showDuration);
			// }
			requestAnimationFrame(() => {
				const header = this.HEADER(item);
				const defaultOffset = 1;
				const customOffset = parseInt(this.options.scrollOffset) || 0;
				const totalOffset = customOffset || defaultOffset;
				const headerPosition = header.getBoundingClientRect().top + window.scrollY - totalOffset;

				window.scrollTo({
					top: Math.max(0, headerPosition),
					behavior: 'smooth'
				});
			});
		}

		expandItem(item, isAutomatic = false) {
			const body = this.BODY(item);
			const header = this.HEADER(item);

			// Check if item is already expanded
			const isAlreadyExpanded = header.getAttribute('aria-expanded') === 'true';
			if (isAlreadyExpanded) {
				return; // Exit if already expanded - no need to expand or flip icon
			}

			body.style.height = body.scrollHeight + 'px';
			header.setAttribute('aria-expanded', 'true');

			if (this.options.scrollToHash && header.id && !isAutomatic) {
				const newUrl = `${window.location.pathname}${window.location.search}#${header.id}`;
				history.replaceState(null, '', newUrl);
			}

			// Dispatch the 'accordionItemExpanded' event
			const expandEvent = new CustomEvent('accordionItemExpanded', { detail: { item: item } });
			this.element.dispatchEvent(expandEvent);
			this.flipIcon(item);
			this.updateFocusableTabIndex(item, true);
		}

		collapseItem(item) {
			const body = this.BODY(item);
			const header = this.HEADER(item);

			// Check if item is already collapsed
			const isAlreadyCollapsed = header.getAttribute('aria-expanded') === 'false';
			if (isAlreadyCollapsed) {
				return; // Exit if already collapsed - no need to collapse or flip icon
			}

			body.style.height = body.offsetHeight + 'px';
			setTimeout(() => {
				body.style.height = 0;
			}, 0);
			header.setAttribute('aria-expanded', 'false');

			if (this.options.scrollToHash && header.id && location.hash === `#${header.id}`) {
				history.replaceState(null, '', window.location.pathname + window.location.search);
			}

			// Dispatch the 'accordionItemCollapsed' event
			const collapseEvent = new CustomEvent('accordionItemCollapsed', { detail: { item: item } });
			this.element.dispatchEvent(collapseEvent);
			this.flipIcon(item);
			this.updateFocusableTabIndex(item, false);
		}

		handleShowDuration() {
			if (!this.options.showDuration) return;
			this.ITEMS((item) => {
				this.BODY(item).style.transitionDuration = this.options.showDuration + 'ms';
				// for header transition
				this.HEADER(item).style.transitionDuration = this.options.showDuration + 'ms';
				// for __title in header
				this.HEADER(item).querySelector('.fr-accordion__title').style.transitionDuration = this.options.showDuration + 'ms';
			});
		}

		addTabindex() {
			this.ITEMS((item) => {
				this.HEADER(item).setAttribute('tabindex', 0);
			});
		}

		addIdToBody() {
			this.ITEMS((item) => {
				if (!this.BODY(item).id) {
					this.BODY(item).id = 'fr-accordion__body-' + Math.random().toString(36).substr(2, 9);
				}
			});
		}

		//TODO andrea remove the random ID generation
		// addIdToHeader() {
		// 	this.ITEMS((item) => {
		// 		if (!this.HEADER(item).id) {
		// 			this.HEADER(item).id = 'fr-accordion__header-' + Math.random().toString(36).substr(2, 9);
		// 		}
		// 	});
		// }

		addIdToHeader() {
			this.ITEMS((item) => {
				const header = this.HEADER(item);
				if (!header.id) {
					header.id = this.generateUniqueId(item);
				}
			});
		}

		addAriaControls() {
			this.ITEMS((item) => {
				this.HEADER(item).setAttribute('aria-controls', this.BODY(item).id);
			});
		}


		addAriaLabelledBy() {
			this.ITEMS((item) => {
				this.BODY(item).setAttribute('aria-labelledby', this.HEADER(item).id);
			});
		}

		addRoleRegion() {
			this.ITEMS((item) => {
				this.BODY(item).setAttribute('role', 'region');
			});
		}

		addRoleButton() {
			this.ITEMS((item) => {
				this.HEADER(item).setAttribute('role', 'button');
			});
		}

		addFaqSchema() {
			if (this.options.faqSchema) {

				const faqData = [];

				this.ITEMS((item) => {
					const question = this.HEADER(item).textContent.trim();
					const answer = this.BODY(item).textContent.trim();

					faqData.push({
						'@type': 'Question',
						'name': question,
						'acceptedAnswer': {
							'@type': 'Answer',
							'text': answer,
						},
					});
				});

				const faqSchema = {
					'@context': 'https://schema.org',
					'@type': 'FAQPage',
					'mainEntity': faqData,
				};

				const script = document.createElement('script');
				script.type = 'application/ld+json';
				script.innerHTML = JSON.stringify(faqSchema);
				document.head.appendChild(script);
			}
		}


		validateStructure() {
			if (this.element.children.length === 0) {
				console.warn('The fr-accordion has no children.', this.element);
				return false;
			}

			// Validation
			const validChildren = {
				'UL': (child) => child.tagName === 'LI' || child.tagName === 'STYLE' || (child.tagName === 'DIV' && child.classList.contains('brx-query-trail')),
				'OL': (child) => child.tagName === 'LI' || child.tagName === 'STYLE' || (child.tagName === 'DIV' && child.classList.contains('brx-query-trail'))
			};

			for (const child of this.element.children) {

				if (child.tagName === 'STYLE'
					|| child.classList.contains('brxe-pagination')
					|| Array.from(child.classList).some(className => className.startsWith('brxe-filter-'))) {

					console.log('found wpgb or bricks element, continuing', child);
					continue;
				}

				if (!child.classList.contains('fr-accordion__item')) {
					child.classList.add('fr-accordion__item');
				}

				const body = child.querySelector('.fr-accordion__body');
				const header = child.querySelector('.fr-accordion__header');
				if (!header || !body) {
					console.warn('Accordion items do not have a Header or a Body element', child);
					return false;
				}

				if (child.children.length > 2) {
					console.warn('Remove extra children elements from accordion item:', child);
					return false;
				}

				if (child.children.length < 2 && child.children.length > 0) {
					console.warn('Add a header and a body to accordion item:', child);
					return false;
				}

				// if element tag is ul or or and direct children are not li elements
				// add warning "add li elements to ul or ol"
				if (this.element.tagName === 'UL' || this.element.tagName === 'OL') {
					if (!validChildren[this.element.tagName](child)) {
						console.warn(`Direct child of the "ul" or "ol" element should have a tag of "li" instead of "${child.tagName}"`, child);
					}
				}

			}
			return true;
		}

		removePlaceholderChild() {
			if (this.element.querySelectorAll('.brx-nestable-children-placeholder')) {
				this.element.querySelectorAll('.brx-nestable-children-placeholder').forEach(el => {
					el.remove();
				});
			}
		}

		updateFocusableTabIndex(item, isOpened) {
			const focusableSelectors = 'a[href], button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], select';
			const focusables = item.querySelectorAll(focusableSelectors);
			focusables.forEach(el => {
				el.setAttribute('tabindex', isOpened ? '0' : '-1');
			});
		}

		hasCurrentPageLink(item) {
			return this.BODY(item).querySelector('a[aria-current="page"]');
		}


		// end of class Accordion
	}

	function framesGetAccordionInstance(element) {
		return accordionInstances.get(element);
	}

	window.getAccordionInstance = framesGetAccordionInstance;

	// * Differentiate between a normal accordion and a wpgb accordion so we can call the appropriate one to re-init
	// const accordions = document.querySelectorAll('.fr-accordion');
	// accordions.forEach(accordion => {

	// 	if (!accordion.dataset.frAccordionOptions) {
	// 		console.warn('Options not provided for the following fr-accordion element:', accordion);
	// 		return;
	// 	}

	// 	const options = JSON.parse(accordion.dataset.frAccordionOptions);
	// 	new Accordion(accordion, options);
	// });
	if (wpgbAccordion) {
		if (!wpgbAccordion.dataset.frAccordionOptions) {
			console.warn('Options not provided for the following fr-accordion element:', wpgbAccordion);
			return;
		}

		const options = JSON.parse(wpgbAccordion.dataset.frAccordionOptions);
		new Accordion(wpgbAccordion, options);
	} else {
		const accordions = document.querySelectorAll('.fr-accordion');
		accordions.forEach(accordion => {
			if (!accordion.dataset.frAccordionOptions) {
				console.warn('Options not provided for the following fr-accordion element:', accordion);
				return;
			}

			const options = JSON.parse(accordion.dataset.frAccordionOptions);
			new Accordion(accordion, options);
		});
	}



	function accordion_script_builder() {

		if (!document.querySelector('.brx-body.iframe.mounted')) return;

		const accordions = document.querySelectorAll('.fr-accordion');
		accordions.forEach(accordion => {
			const items = Array.from(accordion.children);
			items.forEach(item => {
				const header = item.children[0];
				const body = item.children[1];
				// toggle height auto on body on header click
				header.addEventListener('click', () => {
					body.style.height = body.style.height === 'auto' ? '0px' : 'auto';
					// toggle aria-expanded on header
					header.setAttribute('aria-expanded', header.getAttribute('aria-expanded') === 'true' ? 'false' : 'true');
				});
			});
		});
	}

	accordion_script_builder();

}


function wpgb_accordion_script() {
	window.WP_Grid_Builder && WP_Grid_Builder.on('init', function (wpgb) {

		console.dir(wpgb); // Holds all instances.
		console.log('console logging WPGB:', wpgb);

		wpgb.facets.on('appended', function (nodes) {

			// We create a set to store Unique wpgb Accordions
			// if wpgbAccordion then we add to the Set the wpgbAccordions found
			const wpgbAccordionsSet = new Set();
			nodes.forEach(node => {
				const wpgbAccordion = node.closest('.fr-accordion.wpgb-enabled');
				if (wpgbAccordion) {
					wpgbAccordionsSet.add(wpgbAccordion);
				}
			});

			// For each accordion on the set we init them on facets appended ( appended works when filtering / stopping filtering)
			wpgbAccordionsSet.forEach(wpgbAccordion => {
				const instance = window.getAccordionInstance(wpgbAccordion);
				if (instance) {
					accordion_script(wpgbAccordion);
				}
			});

		});

	});
}

function runAccordionScriptsOnBricksFilters() {
	if (typeof window.bricksFilters !== 'function') return;

	const bricksFilterRun = bricksFiltersFn.run;
	bricksFiltersFn.run = function () {
		bricksFilterRun.apply();
		document.querySelectorAll('.fr-accordion').forEach(accordion => {
			clearEventListeners(accordion);
			accordion_script(accordion);
		});

	};
}

function clearEventListeners(accordion){
	if (!bricksIsFrontend) return;
	const accordionHeaders = accordion.querySelectorAll('.fr-accordion__header');
	accordionHeaders.forEach(header => {
		const newHeader = header.cloneNode(true);
		header.parentNode.replaceChild(newHeader, header);
	});
}


document.addEventListener('DOMContentLoaded', function (e) {
	bricksIsFrontend && accordion_script();
	bricksIsFrontend && wpgb_accordion_script();
	bricksIsFrontend && runAccordionScriptsOnBricksFilters();

});
