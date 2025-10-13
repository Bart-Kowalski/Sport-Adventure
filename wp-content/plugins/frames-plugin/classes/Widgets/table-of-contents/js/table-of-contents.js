class TableOfContents {

	constructor(tocElement, tocOptions) {
		this.initProperties(tocElement, tocOptions);
		this.prepareDOM();
		this.setupTableOfContents();
	}

	initProperties(tocElement, tocOptions) {
		this.tocElement = tocElement;
		this.tocOptions = tocOptions;

		this.frTocContentSelector = tocOptions.frTocContentSelector;
		this.frTocShowHeadingsUpTo = tocOptions.frTocHeading;
		this.frToCScrollOffset = parseInt(tocOptions.frTocScrollOffset, 10) || 0;
		this.frTocHeaderSelector = tocOptions.frTocHeaderSelector;
		this.frTocUseBottomOffset = tocOptions.frTocUseBottomOffset || false;
	}

	prepareDOM() {
		this.frTableOfContentList = this.tocElement.querySelector('.fr-toc__list');
		this.frTableOfContentList.removeChild(this.frTableOfContentList.firstElementChild);
		this.frTableOfContentPostContent = document.querySelector(this.frTocContentSelector);
		this.frTableOfContentHeadings = this.frTableOfContentPostContent.querySelectorAll('h2, h3, h4, h5, h6');
	}

	setupTableOfContents() {
		this.createFramesTableOfContentList(this.frTableOfContentHeadings);
		this.frTableOfContentLinks = this.tocElement.querySelectorAll('.fr-toc__list-link');
		this.toggleActiveClassForFramesTOCLink(this.frToCScrollOffset, this.tocItems);
		this.smoothScrollForFramesTOC(this.frToCScrollOffset, this.tocItems);

		const frTableOfContentIsAccordion = this.tocOptions.frTocAccordion;
		if (frTableOfContentIsAccordion !== 'false') {
			this.accordionForFramesTOC();
		}

		this.outputListType(this.tocElement);
		this.outputSublistType(this.tocElement);

		this.useBottomOffset();

	}

	haveAdminBar() {
		const adminBar = document.body.classList.contains('admin-bar');
		if (adminBar) {
			return true;
		}
		return false;
	}

	outputListType(tocElement) {
		const listType = this.tocOptions.frTocListType;
		tocElement.setAttribute('data-fr-toc-list-type', listType);
	}

	outputSublistType(tocElement) {
		const sublistType = this.tocOptions.frTocSubListType;
		tocElement.setAttribute('data-fr-toc-sublist-type', sublistType);
	}

	headingIdGeneration(heading, index) {
		if (!heading.id) {
			const headingText = heading.textContent;
			const textForId = headingText.split(' ').slice(0, 3).join('-').toLowerCase();
			const id = `${textForId}-${index}`;
			return id;
		}
		return heading.id;
	}

	useBottomOffset() {

		const contentElement = document.querySelector(this.frTocContentSelector);

		if (this.frTocUseBottomOffset === 'false') {
			const lastChildData = this.getLastChild(contentElement);
			if (lastChildData.element) {
				lastChildData.element.style.marginBottom = '';
			}
			return;
		}

		const lastHeadingData = this.getLastHeading(contentElement);
		const lastChildData = this.getLastChild(contentElement);

		const viewportHeight = window.innerHeight;
		const headerHeight = this.getHeaderHeight().headerHeight;
		const documentHeight = document.documentElement.scrollHeight;

		const lastHeadingEndPosition = lastHeadingData.offset + lastHeadingData.offsetHeight + lastHeadingData.marginBottom + lastHeadingData.marginTop;

		const bottomOffset = viewportHeight - headerHeight - this.frToCScrollOffset;
		const currentBottomOffset = documentHeight - lastHeadingEndPosition;
		const marginBottomValue = bottomOffset - currentBottomOffset;


		if (marginBottomValue > 0) {
			lastChildData.element.style.marginBottom = `${marginBottomValue}px`;
		}
	}

	getLastHeading(contentElement) {
		const headings = contentElement.querySelectorAll('h2, h3, h4, h5, h6');
		if (headings.length === 0) return null;

		const lastHeadingEl = headings[headings.length - 1];
		const style = window.getComputedStyle(lastHeadingEl);

		const offsetHeight = lastHeadingEl.offsetHeight;
		const offset = lastHeadingEl.getBoundingClientRect().top + window.scrollY;
		const marginBottom = parseInt(style.marginBottom, 10);
		const marginTop = parseInt(style.marginTop, 10);

		return {
			offsetHeight: offsetHeight,
			offset: offset,
			marginBottom: marginBottom,
			marginTop: marginTop
		};
	}

	getLastChild(contentElement) {
		const lastChildEl = contentElement.lastElementChild;

		return {
			element: lastChildEl,
		};
	}

	smoothScrollForFramesTOC(offsetPixels, tocItems) {
		const adminBarHeight = this.haveAdminBar() ? 32 : 0;
		//TODO check this line after christmas, this is a fix, video on linear FRA-60
		const offset = offsetPixels + adminBarHeight; //TODOremove + adminBarHeight

		const { headerHeight } = this.getHeaderHeight();

		tocItems.forEach(({ link, heading }) => {
			const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
			const buffer = 1;

			link.addEventListener('click', (e) => {
				e.preventDefault();
				const targetPosition = heading.getBoundingClientRect().top + window.scrollY;
				const topPosition = targetPosition - offset - headerHeight + buffer; //- adminBarHeight; // TODO same as commented TODO on top of this method
				window.scrollTo({
					top: topPosition,
					behavior: prefersReducedMotion ? 'auto' : 'smooth',
				});

			});
		});
	}

	toggleActiveClassForFramesTOCLink(offsetPixels, tocItems) {
		//TODO check this line after christmas, this is a fix, video on linear FRA-60
		// const adminBarHeight = this.haveAdminBar() ? 32 : 0;
		const offset = offsetPixels;
		const { headerHeight, usesHeader } = this.getHeaderHeight();
		const headings = tocItems.map(({ heading }) => heading);
		const links = tocItems.map(({ link }) => link);

		const checkActive = () => {
			const scrollPosition = window.scrollY + offset + headerHeight; //+ adminBarHeight; // TODO same as commented TODO on top of this method
			let activeIndex = -1;

			for (let i = 0; i < headings.length; i++) {

				let headingPosition;
				if (usesHeader) {
					headingPosition = headings[i].offsetTop;
				} else {
					headingPosition = headings[i].getBoundingClientRect().top + window.scrollY - offset;
				}

				if (scrollPosition >= headingPosition) {
					activeIndex = i;
				} else {
					break;
				}
			}

			links.forEach((link) => link.classList.remove('fr-toc__list-link--active'));

			if (activeIndex !== -1) {
				links[activeIndex].classList.add('fr-toc__list-link--active');
			}
		};

		window.addEventListener('scroll', checkActive);
		checkActive();
	}

	getHeaderHeight() {
		const givenSelector = this.frTocHeaderSelector ? this.frTocHeaderSelector.trim() : null;

		if (givenSelector && !givenSelector.startsWith('#') && !givenSelector.startsWith('.')) {
			console.error(`Provided Selector: ${givenSelector} is not valid, must start with '#' or '.'`);
		}

		const headerElement = givenSelector ? document.querySelector(givenSelector) : null;
		let headerHeight = 100;
		let usesHeader = false;

		if (headerElement) {
			headerHeight = headerElement.offsetHeight;
			usesHeader = true;
		} else if (givenSelector) {
			console.error(`Selector not found: ${givenSelector}`);
		}

		return { headerHeight, usesHeader };
	}

	accordionForFramesTOC() {
		const tocHeader = this.tocElement.querySelector('.fr-toc__header');
		const accordionContents = this.tocElement.querySelectorAll('.fr-toc__body');
		const copyOpenClass = 'fr-toc__body--open';
		let target = tocHeader.nextElementSibling;

		if (tocHeader.getAttribute('aria-expanded') === 'true') {
			target.style.maxHeight = target.scrollHeight + 'px';
		} else {
			target.style.maxHeight = 0;
		}

		tocHeader.onclick = () => {
			let expanded = tocHeader.getAttribute('aria-expanded') === 'true';
			if (expanded) {
				this.closeItem(target, tocHeader, copyOpenClass);
			} else {
				this.openItem(target, tocHeader, copyOpenClass);
			}
		};
	}

	closeItem(target, btn, openClass) {
		btn.setAttribute('aria-expanded', false);
		target.classList.remove(openClass);
		target.style.maxHeight = 0;
	}

	openItem(target, btn, openClass) {
		btn.setAttribute('aria-expanded', true);
		target.classList.add(openClass);
		target.style.maxHeight = target.scrollHeight + 'px';
	}

	createFramesTableOfContentList(headings) {
		const maxHeadingLevel = parseInt(this.frTocShowHeadingsUpTo.substring(1), 10);

		this.frTableOfContentList.innerHTML = '';

		const docFragment = document.createDocumentFragment();
		const stack = [{ list: docFragment, level: 1 }];
		const tocItems = [];

		headings.forEach((heading, index) => {
			const headingLevel = parseInt(heading.tagName.substring(1), 10);

			if (headingLevel <= maxHeadingLevel) {

				const id = this.headingIdGeneration(heading, index);
				heading.id = id;

				while (headingLevel > stack[0].level + 1) {
					const newList = document.createElement('ol');

					newList.classList.add('fr-toc__list');
					if (stack[0].list.lastElementChild) {
						stack[0].list.lastElementChild.appendChild(newList);
					} else {
						stack[0].list.appendChild(newList);
					}
					stack.unshift({ list: newList, level: stack[0].level + 1 });
				}

				while (headingLevel <= stack[0].level && stack.length > 1) {
					stack.shift();
				}

				const listItem = document.createElement('li');
				listItem.classList.add('fr-toc__list-item');

				const link = document.createElement('a');
				link.setAttribute('href', `#${heading.id}`);
				link.classList.add('fr-toc__list-link');
				link.textContent = heading.textContent;

				tocItems.push({ link, heading });

				listItem.appendChild(link);
				stack[0].list.appendChild(listItem);
			}
		});

		this.frTableOfContentList.appendChild(docFragment);
		this.tocItems = tocItems;
	}

}


function framesTableOfContentsScript() {
	window.Frames = window.Frames || {};
	window.Frames.TableOfContents = TableOfContents;

	//TODO remove this log once we remove the Legacy ToC
	console.log('New ToC Version');

	const tocElement = document.querySelector('.fr-toc');
	if (!tocElement) {
		console.error('Table of Contents element not found');
		return;
	}

	if (bricksIsFrontend) {
		initializeFrontendToC(tocElement);
		return;
	}

	initializeEditorToC(tocElement);
}

/**
 * Starts the ToC for the frontend
 * @param {*} tocElement
 */
function initializeFrontendToC(tocElement) {
	const tocOptions = parseTocOptions(tocElement);
	new window.Frames.TableOfContents(tocElement, tocOptions);
}

/**
 * Starts the ToC for the editor
 * @param {*} tocElement
 */
function initializeEditorToC(tocElement){
	let currentEditorTocInstance = null;
	const rebuildEditorToC = () => {
		const tocOptions = parseTocOptions(tocElement);
		if (currentEditorTocInstance) {
			currentEditorTocInstance.destroy?.();
		}
		currentEditorTocInstance = new window.Frames.TableOfContents(tocElement, tocOptions);
	};

	const tocOptions = parseTocOptions(tocElement);
	headingsObserver(tocOptions.frTocContentSelector, () => {
		rebuildEditorToC();
	});

	attributesObserver(tocElement, 'data-fr-toc-options', () => {
		rebuildEditorToC();
	});
}


/**
 * Helper function
 * Parses the ToC options from the `data-fr-toc-options` attributes
 * @returns {Object}
 */
function parseTocOptions(tocElement) {
	try {
		return JSON.parse(tocElement.dataset.frTocOptions);
	} catch (error) {
		console.error('Failed to parse ToC options:', error);
		return {};
	}
}


/**
 * Editor Only
 * Observes changes to headings from frTocContentSelector
 */
function headingsObserver(selector, callback) {
	const observer = new MutationObserver(() => {
		const contentElement = document.querySelector(selector);
		if (contentElement) {
			const headings = contentElement.querySelectorAll('h2, h3, h4, h5, h6');
			if (headings.length > 0) {
				observer.disconnect();
				clearTimeout(headingsTimeout);
				callback();
			}
		}
	});

	observer.observe(document.body, { childList: true, subtree: true });

	headingsTimeout = setTimeout(() => {
		console.warn(`Stopping observer after 10 seconds. No headings found in ${selector}.`);
		observer.disconnect();
	}, 10000);
}

/**
 * Editor Only
 * Observes changes to the `data-fr-toc-options` attribute
 */
function attributesObserver(element, attributeName, callback) {
	const observer = new MutationObserver((mutationsList) => {
		for (const mutation of mutationsList) {
			if (mutation.type === 'attributes' && mutation.attributeName === attributeName) {
				callback();
			}
		}
	});
	observer.observe(element, { attributes: true });
}


document.addEventListener('DOMContentLoaded', () => {
	if (bricksIsFrontend) {
		framesTableOfContentsScript();
		return;
	}
	setTimeout(framesTableOfContentsScript, 500);
});
