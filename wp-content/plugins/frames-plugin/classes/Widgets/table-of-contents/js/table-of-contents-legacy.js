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

		if (this.frTocUseBottomOffset === 'true') {
			this.useBottomOffset();
		}

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

window.Frames = window.Frames || {};
window.Frames.TableOfContents = TableOfContents;

function table_of_contents_script() {

	const newTocEnabled = window.frames_toc_obj && window.frames_toc_obj.flag_enable_new_toc === 'true';

	if (newTocEnabled) {
		console.log('Refactored ToC');

		const tocElement = document.querySelector('.fr-toc');
		if (!tocElement) {
			console.error('Table of Contents element not found');
			return;
		}

		const tocOptions = JSON.parse(tocElement.dataset.frTocOptions);

		const tableOfContents = new window.Frames.TableOfContents(tocElement, tocOptions);


		//!! DONT GO BELLOW
	} else {
		//!! Important, release 1.4.20 contains the exact legacy code match
		console.log('Legacy ToC');


		const frTableOfContentWrapper = document.querySelector('.fr-toc');
		const frTocContentSelector =
			frTableOfContentWrapper.dataset.frTocContentSelector;
		const frTocShowHeadingsUpTo = frTableOfContentWrapper.dataset.frTocHeading;
		const frToCScrollOffset = parseInt(
			frTableOfContentWrapper.dataset.frTocScrollOffset,
			10
		);
		const frTableOfContentList =
			frTableOfContentWrapper.querySelector('.fr-toc__list');
		frTableOfContentList.removeChild(frTableOfContentList.firstElementChild);
		const frTableOfContentPostContent =
			document.querySelector(frTocContentSelector);
		const frTableOfContentHeadings =
			frTableOfContentPostContent.querySelectorAll('h2, h3, h4, h5, h6');

		createFramesTableOfContentList(frTableOfContentHeadings);

		const frTableOfContentLinks =
			document.querySelectorAll('.fr-toc__list-link');

		if (frToCScrollOffset) {
			toggleActiveClassForFramesTOCLink(
				frToCScrollOffset,
				frTableOfContentLinks
			);
			smoothScrollForFramesTOC(frToCScrollOffset, frTableOfContentLinks);
		}

		const frTableOfContentIsAccordion =
			frTableOfContentWrapper.dataset.frTocAccordion;

		if (frTableOfContentIsAccordion !== 'false') {
			accordionForFramesTOC();
		}

		function smoothScrollForFramesTOC(offsetPixels, links) {
			links.forEach((link) => {
				link.addEventListener('click', (e) => {
					e.preventDefault();
					const href = link.getAttribute('href');
					const offsetTop = document.querySelector(href).offsetTop;
					scroll({
						top: offsetTop - 100,
						behavior: 'smooth',
					});
				});
			});
		}

		function toggleActiveClassForFramesTOCLink(offsetPixels, links) {
			links.forEach((link) => {
				const href = link.getAttribute('href');
				const targetHeading = document.querySelector(href);
				if (
					targetHeading.getBoundingClientRect().top < offsetPixels + 1 &&
					targetHeading.getBoundingClientRect().top >
					-targetHeading.getBoundingClientRect().height + offsetPixels
				) {
					link.classList.add('fr-toc__list-link--active');
				}
				window.addEventListener('scroll', () => {
					if (
						targetHeading.getBoundingClientRect().top <
						offsetPixels + 1
					) {
						links.forEach((link) =>
							link.classList.remove('fr-toc__list-link--active')
						);
						link.classList.add('fr-toc__list-link--active');
					}
				});
			});
		}

		function accordionForFramesTOC() {
			const tocHeader = document.querySelector('.fr-toc__header');
			const accordionContents = document.querySelectorAll('.fr-toc__body');
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
					closeItem(target, tocHeader);
				} else {
					openItem(target, tocHeader);
				}
			};

			function closeItem(target, btn) {
				btn.setAttribute('aria-expanded', false);
				target.classList.remove(copyOpenClass);
				target.style.maxHeight = 0;
			}
			function openItem(target, btn) {
				btn.setAttribute('aria-expanded', true);
				target.classList.add(copyOpenClass);
				target.style.maxHeight = target.scrollHeight + 'px';
			}
		}

		function createFramesTableOfContentList(headings) {
			headings.forEach((heading, index) => {
				const headingId = `fr-toc-content__heading-${index}`;
				heading.id = headingId;
				const headingText = heading.textContent;
				const headingLevel = heading.tagName;
				const listItem = document.createElement('li');
				listItem.classList.add('fr-toc__list-item');
				listItem.innerHTML = `<a href="#${headingId}" class="fr-toc__list-link">${headingText}</a>`;

				if (
					headingLevel === 'H2' &&
					(frTocShowHeadingsUpTo === 'h6' ||
						frTocShowHeadingsUpTo === 'h5' ||
						frTocShowHeadingsUpTo === 'h4' ||
						frTocShowHeadingsUpTo === 'h3' ||
						frTocShowHeadingsUpTo === 'h2')
				) {
					frTableOfContentList.appendChild(listItem);
				}

				if (
					headingLevel === 'H3' &&
					(frTocShowHeadingsUpTo === 'h6' ||
						frTocShowHeadingsUpTo === 'h5' ||
						frTocShowHeadingsUpTo === 'h4' ||
						frTocShowHeadingsUpTo === 'h3')
				) {
					const lastItem = frTableOfContentList.lastElementChild;
					if (!lastItem.querySelector('ol')) {
						lastItem.innerHTML += '<ol class="fr-toc__list"></ol>';
					}
					lastItem.querySelector('ol').appendChild(listItem);
				}

				if (
					headingLevel === 'H4' &&
					(frTocShowHeadingsUpTo === 'h6' ||
						frTocShowHeadingsUpTo === 'h5' ||
						frTocShowHeadingsUpTo === 'h4')
				) {
					const lastItem = frTableOfContentList.lastElementChild;
					const lastSubItem =
						lastItem.querySelector('ol').lastElementChild;
					if (!lastSubItem.querySelector('ol')) {
						lastSubItem.innerHTML += '<ol class="fr-toc__list"></ol>';
					}
					lastSubItem.querySelector('ol').appendChild(listItem);
				}

				if (
					headingLevel === 'H5' &&
					(frTocShowHeadingsUpTo === 'h6' ||
						frTocShowHeadingsUpTo === 'h5')
				) {
					const lastItem = frTableOfContentList.lastElementChild;
					const lastSubItem =
						lastItem.querySelector('ol').lastElementChild;
					const lastSubSubItem =
						lastSubItem.querySelector('ol').lastElementChild;
					if (!lastSubSubItem.querySelector('ol')) {
						lastSubSubItem.innerHTML +=
							'<ol class="fr-toc__list"></ol>';
					}
					lastSubSubItem.querySelector('ol').appendChild(listItem);
				}

				if (headingLevel === 'H6' && frTocShowHeadingsUpTo === 'h6') {
					const lastItem = frTableOfContentList.lastElementChild;
					const lastSubItem =
						lastItem.querySelector('ol').lastElementChild;
					const lastSubSubItem =
						lastSubItem.querySelector('ol').lastElementChild;
					const lastSubSubSubItem =
						lastSubSubItem.querySelector('ol').lastElementChild;
					if (!lastSubSubSubItem.querySelector('ol')) {
						lastSubSubSubItem.innerHTML +=
							'<ol class="fr-toc__list"></ol>';
					}
					lastSubSubSubItem.querySelector('ol').appendChild(listItem);
				}
			});
		}
	}
}

document.addEventListener('DOMContentLoaded', function (e) {
	bricksIsFrontend && table_of_contents_script();
});
