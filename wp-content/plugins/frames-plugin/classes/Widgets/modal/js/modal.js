function modal_script() {

	const modals = document.querySelectorAll('.fr-modal');
	const focusableElements = 'a[href], button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])';
	const higherIndexTriggerClass = 'fr-modal__trigger--higher-index';
	let closeSelector;
	let fadeTime;
	let isStartModalVideo;
	let isInQueryBuilder = false;
	let youtubePlayers = {};
	let youtubeApiInitiated = false;
	// TODO @Hakira-Shymuy remove flag after feature finished and tested.
	// let flag_enable_modal_exit_intent =
	// 	window.hasOwnProperty('frames_modal_obj') && window.frames_modal_obj.hasOwnProperty('flag_enable_modal_exit_intent') ?
	// 	window.frames_modal_obj.flag_enable_modal_exit_intent : false
	// 	//console.log('enable trigger page load', flag_enable_modal_exit_intent)
	// let flag_modal_slider_compatibility = window.hasOwnProperty('frames_modal_slider_obj') && window.frames_modal_slider_obj.hasOwnProperty('flag_modal_slider_compatibility') ? window.frames_modal_slider_obj.flag_modal_slider_compatibility : false
	let flag_enable_modal_exit_intent = window.frames_modal_obj?.flag_enable_modal_exit_intent ?? false;
	if (flag_enable_modal_exit_intent) {
		console.log('flag modal_exit_intent is on');
	}
	let flag_modal_slider_compatibility = window.frames_modal_slider_obj?.flag_modal_slider_compatibility ?? false;
	if (flag_modal_slider_compatibility) {
		console.log('flag modal_slider_compatibility is on');
	}

	/**
	 * Helper function
	 * @param {string} data - The JSON string to parse, safely replaces HTML entities
	 * @returns {Object|null} The parsed JSON object, or null if parsin fails
	 */
	function parseJSONData(data) {
		try {
			return JSON.parse(data.replace(/&quot;/g, '"'));
		} catch (e) {
			console.error('Error parsing JSON data:', e);
			return null;
		}
	}

	/**
	 * Get the data from modal attributes for configuration
	 * @param {HTMLElement} modal - The modal element
	 * @returns {Object} Object containing modal data for configuration
	 */
	function getDataAttributesForModal(modal) {
		// Trigger for modal ( Selector )
		let modalTrigger;

		// Trigger for modal ( Actions, Time, Page load )
		let modalTriggerConfig =
			modal.dataset.frModalTriggerConfig ? parseJSONData(modal.dataset.frModalTriggerConfig) : [];
		// Modal shown repeat rules
		let modalRepeatActions =
			modal.dataset.frModalRepeatActions ? parseJSONData(modal.dataset.frModalRepeatActions) : null;

		if (modal.dataset.frModalTrigger) {
			modalTrigger = modal.dataset.frModalTrigger;

		}
		if (modal.dataset.frModalTriggerEdge) {
			edgeToEdge = modal.dataset.frModalTriggerEdge;

		}
		if (modal.dataset.frModalClose) {
			closeSelector = modal.dataset.frModalClose;

		}
		if (modal.dataset.frModalFadeTime) {
			fadeTime = Number(modal.dataset.frModalFadeTime);

		}
		if (modal.dataset.frVideoAutoplay) {
			isStartModalVideo = modal.dataset.frVideoAutoplay === 'true' ? true : false;
		}

		if (modal.dataset.frModalQueryBuilder) {
			isInQueryBuilder = modal.dataset.frModalQueryBuilder === 'true' ? true : false;
		} else {
			isInQueryBuilder = false;
		}


		return { modalTrigger, modalTriggerConfig, modalRepeatActions };
	}

	/**
	 * Initializes modal triggers and maps with the corresponding modal
	 * Processes selector-based and action-based triggers
	 */
	function generateModalTriggerConnection() {

		const modalsByTrigger = new Map();
		const modalsByAction = new Map();

		/**
		 * Iterates over modals that are not party of a Loop
		 * Binds the modals to the respective Triggers either by Selector or Actions
		 */
		modalsNotInQueryBuilder.forEach(modal => {
			const { modalTrigger, modalTriggerConfig, modalRepeatActions } = getDataAttributesForModal(modal);

			//handle modals with Trigger type of Selector
			if (modalTrigger) {
				if (!modalsByTrigger.has(modalTrigger)) {
					modalsByTrigger.set(modalTrigger, new Set());
				}
				modalsByTrigger.get(modalTrigger).add(modal);
			}

			//handle modals with Trigger type of Action
			if (modalTriggerConfig) {
				modalTriggerConfig.forEach(config => {
					const actionType = config.type;
					if (!modalsByAction.has(actionType)) {
						modalsByAction.set(actionType, new Set());
					}

					modalsByAction.get(actionType).add({ modal, modalRepeatActions, config });
				});
			}

			//binds the modal with the action and the repeat type for the action
			modalsByAction.forEach((modalsWithActions, action) => {
				modalsWithActions.forEach(({ modal, modalRepeatActions, config }) => {
					handleAction(action, modal, config, modalRepeatActions);
				});

			});

			//changed the type of loop to be the same as modalByAction, more readable
			modalsByTrigger.forEach((modals, triggerSelector) => {
				const triggers = document.querySelectorAll(triggerSelector);

				triggers.forEach(trigger => {
					const modal = Array.from(modals)[0];
					const modalID = modal.id;
					trigger.setAttribute('href', `#${modalID}`);
					trigger.setAttribute('tabindex', '0');
					trigger.setAttribute('role', 'button');
					openModalWithTrigger(trigger);
					focusFirstElementOnOpen(trigger);
					trapFocusInModal(trigger);
					initModalRelativeToTrigger(trigger);

				});

			});

		});
	}


	function initModalRelativeToTrigger(trigger) {
		let windowWidth = window.innerWidth;
		modalRelativeToTrigger(trigger, windowWidth);

		window.addEventListener('resize', () => {
			windowWidth = window.innerWidth;
			modalRelativeToTrigger(trigger, windowWidth);
		});

		window.addEventListener('orientationchange', () => {
			windowWidth = window.innerWidth;
			modalRelativeToTrigger(trigger, windowWidth);
		});

		window.addEventListener('scroll', () => {
			windowWidth = window.innerWidth;
			modalRelativeToTrigger(trigger, windowWidth);
		});
	}

	function modalRelativeToTrigger(trigger, windowWidth) {

		const modal = document.querySelector(trigger.getAttribute('href'));
		if (!modal) {
			return;
		}
		const container = modal.querySelector('.fr-modal__body');
		if (!container) {
			return;
		}
		let positionRelatedToTrigger = modal.dataset.frModalPositionRelatedToTrigger;
		let placeFromTriggers = modal.dataset.frModalPlaceFromTriggers;
		let xTriggerOffset = modal.dataset.frModalTriggerXoffset;
		let yTriggerOffset = modal.dataset.frModalTriggerYoffset;
		const edgeToEdge = modal.dataset.frModalTriggerEdge === 'true';

		// positionRelatedToTrigger === 'top' || 'bottom'
		// placeFromTriggers === 'left' || 'right' || 'center' || 'full' || 'container'

		if (xTriggerOffset === '0') {
			xTriggerOffset = '0px';
		}
		if (yTriggerOffset === '0') {
			yTriggerOffset = '0px';
		}

		const triggerRect = trigger.getBoundingClientRect();
		const triggerTopSpace = triggerRect.top;
		const triggerLeftSpace = triggerRect.left;
		const triggerWidth = triggerRect.width;
		const triggerHeight = triggerRect.height;
		const containerWidth = container.getBoundingClientRect().width;
		const containerHeight = container.getBoundingClientRect().height;

		let breakpoint;
		if (modal.dataset.frModalCenterOption) {
			centerOption = JSON.parse(modal.dataset.frModalCenterOption);
			breakpoint = centerOption.centerOnBreakpoint;
		}


		if (placeFromTriggers === 'left' && triggerLeftSpace < containerWidth) {
			placeFromTriggers = 'right';
		}

		if (placeFromTriggers === 'right' && windowWidth - (triggerLeftSpace + triggerWidth) < containerWidth) {
			placeFromTriggers = 'left';
		}

		if (positionRelatedToTrigger === 'bottom' && window.innerHeight - triggerTopSpace - triggerHeight < containerHeight) {
			positionRelatedToTrigger = 'top';
		}

		if (positionRelatedToTrigger === 'top' && triggerTopSpace < containerHeight) {
			positionRelatedToTrigger = 'bottom';
		}


		container.style.position = 'fixed';

		if (positionRelatedToTrigger === 'bottom') {
			container.style.top = `calc(${triggerTopSpace + triggerHeight}px + ${yTriggerOffset})`;
			container.style.bottom = '';
		} else if (positionRelatedToTrigger === 'top') {
			container.style.bottom = `calc(${window.innerHeight - triggerTopSpace}px + ${yTriggerOffset})`;
			container.style.top = '';
		}

		function positionModal() {
			let scaleSize = 0;

			if ((placeFromTriggers === 'left' || placeFromTriggers === 'right') && breakpoint && window.innerWidth <= breakpoint) {

				container.style.left = '50%';
				container.style.transform = 'translateX(-50%)';

			} else {
				if (placeFromTriggers === 'left') {
					let leftPosition = triggerLeftSpace - containerWidth - scaleSize;
					if (edgeToEdge) {
						leftPosition += triggerWidth;
					}
					const finalLeftPosition = `calc(${leftPosition}px - ${xTriggerOffset})`;

					container.style.maxWidth = `${triggerLeftSpace}px`;
					container.style.left = finalLeftPosition;

				} else if (placeFromTriggers === 'right') {
					let rightPosition = triggerLeftSpace + triggerWidth;
					const rightSpace = windowWidth - rightPosition;
					if (edgeToEdge) {
						rightPosition -= triggerWidth;
					}
					const finalRightPosition = `calc(${rightPosition}px + ${xTriggerOffset})`;

					container.style.left = finalRightPosition;
					container.style.maxWidth = `${rightSpace}px`;

				} else if (placeFromTriggers === 'center') {
					container.style.left = `calc(${triggerLeftSpace + triggerWidth / 2}px - ${containerWidth / 2}px)`;
				} else if (placeFromTriggers === 'full') {
					container.style.left = '0';
					container.style.maxWidth = '100%';
					container.style.width = '100%';
				} else if (placeFromTriggers === 'container') {
					container.style.left = '50%';
					container.style.transform = 'translateX(-50%)';
					container.style.maxWidth = 'var(--width-vp-max)';
					container.style.width = '100%';
				}
			}
		}
		positionModal();
	}



	function openModalWithTrigger(trigger) {
		if (!trigger.hasEventListener) {
			trigger.addEventListener('click', (e) => {
				const modalId = trigger.getAttribute('href');
				const modal = document.querySelector(modalId);

				if (modal.classList.contains('fr-modal--open') && modal.dataset.frModalSameCloseTrigger === 'true') {
					// If the modal is open and sameCloseTrigger is true, close it
					closeModal(modal, e);
				} else {
					// Otherwise, open it
					openModal(e, trigger);
					initModalRelativeToTrigger(trigger);
				}
			});

			trigger.addEventListener('keydown', (e) => {
				if (e.key === 'Enter') {
					const modalId = trigger.getAttribute('href');
					const modal = document.querySelector(modalId);

					if (modal.classList.contains('fr-modal--open') && modal.dataset.frModalSameCloseTrigger === 'true') {
						// If the modal is open and sameCloseTrigger is true, close it
						closeModal(modal, e);
					} else {
						// Otherwise, open it
						openModal(e, trigger);
						initModalRelativeToTrigger(trigger);
					}
				}
			});

			// Set flag to indicate event listeners are attached
			trigger.hasEventListener = true;
		}

		// Get the modal associated with this trigger
		const modalId = trigger.getAttribute('href');
		const modal = document.querySelector(modalId);

		// Stop click events inside the modal from bubbling up to the trigger
		if (modal) {
			const modalBody = modal.querySelector('.fr-modal__body');

			if (modalBody) {
				modalBody.addEventListener('click', (e) => {
					// add check for bricks nav menu
					if (!e.target.closest('.brxe-nav-menu')) {
						e.stopPropagation();
					}
				});
			}
		}
	}




	function openModal(e, trigger) {

		e.preventDefault();
		const modalId = trigger.getAttribute('href');
		const modal = document.querySelector(modalId);
		// document.body.appendChild(modal);

		if (modal) {
			modal.triggerElement = trigger;
		}
		if (flag_modal_slider_compatibility) {
			slidesCompatibility(modal);
		}


		if (!modal.closest('header')) {
			modal.classList.add('fr-modal--open');

		} else {
			modal.classList.toggle('fr-modal--open');
		}
		modal.setAttribute('aria-hidden', 'false');
		modal.style.display = 'flex';


		if ('ontouchstart' in window) {
			scrollingToggle(modal);
		} else {
			scrollingToggle(modal);
		}

		if (isTriggerAHamburgerWidget(trigger)) {
			trigger.classList.toggle(higherIndexTriggerClass);
		}

		initVideo(modal);
		initAudio(modal);

		focusFirstElementInModal(e, trigger);
	}

	function slidesCompatibility(modal) {

		let isInsideSlider = modal.closest('.splide__slide');

		if (!bricksIsFrontend || !isInsideSlider) return;
		const mainElement = document.querySelector('main');
		if (mainElement) {
			mainElement.appendChild(modal);
		} else {
			document.body.appendChild(modal);
		}
	}

	/**
	 * Toggles the visibility of a action-triggered modal
	 * I choose to create a new function to avoid complexity attached with the openModal function
	 * @param {HTMLElement} modal - The mapped modal to reveal or hide.
	 */
	function revealModal(modal) {
		const isOpen = modal.classList.contains('fr-modal--open');

		if (!isOpen) {
			modal.classList.toggle('fr-modal--open');
			modal.setAttribute('aria-hidden', 'false');
			modal.style.display = 'flex';
		} else {
			modal.classList.remove('fr-modal--open');
			modal.setAttribute('aria-hidden', 'true');
			// modal.style.display = 'none'
		}

		scrollingToggle(modal);
		initVideo(modal);
		initAudio(modal);
	}

	/**
	 * Execute the appropriate action to trigger a modal
	 * @param {string} action - Type of action to handle (e.g, 'mouseLeaveViewport')
	 * @param {HTMLElement} modal - The modal to apply the action
	 * @param {Object} modalRepeatActions - Configuration for how the modal should show again
	 * @param {Object} config - Settings for the Modal type of Action
	 */
	function handleAction(action, modal, config, modalRepeatActions) {
		switch (action) {
			case 'mouseLeaveViewport':
				handleMouseLeaveViewport(modal, modalRepeatActions);
				break;
			case 'onPageLoad':
				handleOnPageLoad(modal, config, modalRepeatActions);
				break;
			case 'onInactivity':
				handleOnInactivity(modal, config, modalRepeatActions);
				break;
			case 'onHover':
				handleOnHover(modal, config, modalRepeatActions);
				break;

			default:
				console.log(`no action defined: ${action}`);
		}
	}

	// TODO: @Hakira-Shymuy handleOnScrollToEl
	if (flag_enable_modal_exit_intent) {
		function handleAction(action, modal, config, modalRepeatActions) {
			switch (action) {
				case 'mouseLeaveViewport':
					handleMouseLeaveViewport(modal, modalRepeatActions);
					break;
				case 'onPageLoad':
					handleOnPageLoad(modal, config, modalRepeatActions);
					break;
				case 'onInactivity':
					handleOnInactivity(modal, config, modalRepeatActions);
					break;
				case 'onHover':
					handleOnHover(modal, config, modalRepeatActions);
					break;
				case 'onScrollToEl':
					handleOnScrollToEl(modal, config, modalRepeatActions);
					break;

				default:
					console.log(`no action defined: ${action}`);
			}
		}
	};

	//TODO: if everything works, remove this it's a duplicate of handleAction
	// function handleAction(action, modal, config, modalRepeatActions) {
	// 	switch (action) {
	// 		case 'mouseLeaveViewport':
	// 			handleMouseLeaveViewport(modal, modalRepeatActions);
	// 			break;
	// 		case 'onPageLoad':
	// 			handleOnPageLoad(modal, config, modalRepeatActions);
	// 			break;
	// 		case 'onInactivity':
	// 			handleOnInactivity(modal, config, modalRepeatActions);
	// 			break;
	// 		case 'onHover':
	// 			handleOnHover(modal, config, modalRepeatActions);
	// 			break;

	// 		default:
	// 			console.log(`no action defined: ${action}`)
	// 	}
	// };

	/**
	 * Reveals a modal when mouse leave viewport
	 * @param {HTMLElement} modal
	 * @param {Object} modalRepeatActions
	 */
	function handleMouseLeaveViewport(modal, modalRepeatActions) {

		const listener = (event) => {
			if (!event.relatedTarget && !event.toElement) {
				const action = modalRepeatActions.repeatActions;
				const shouldReveal = handleRepeatActions(modal, action);

				if (shouldReveal) {
					revealModal(modal);
				}
			}
		};

		if (!modal.hasAttribute('data-mouse-listener-added')) {
			document.body.addEventListener('mouseleave', listener);
			modal.setAttribute('data-mouse-listener-added', 'true');
		}
	}

	/**
	 * Creates a throttled function that only invokes `func` once per every `limit` milliseconds
	 * this helper function is used to control the rate at we call the functions to check for
	 * the activity events, otherwise we can affect the performance of the site
	 *
	 * @param {Function} func - The function to be throttled
	 * @param {number} limit - The time limit in milliseconds for the throttle
	 * @returns {Function} - A throttled version of the input function
	 */
	function throttle(func, limit) {
		// Hold the timeout for the function to be executed
		let lastFunc;
		// Stores the timestamp of the last time that we ran the function
		let lastRan;

		return function () {
			// Context from the function can be an object, window, etc, the args are passed on the function call
			const context = this;
			const args = arguments;

			if (!lastRan) {
				// If the function wasnt called then execute it
				func.apply(context, args);
				lastRan = Date.now();
			} else {
				clearTimeout(lastFunc);

				// Set a new execution scheduled
				lastFunc = setTimeout(function () {
					if ((Date.now() - lastRan) >= limit) {
						func.apply(context, args);
						lastRan = Date.now();
					}
				}, Math.max(limit - (Date.now() - lastRan), 0));
			}
		};
	}

	/**
	 * Reveals a modal with a delay when the page loads
	 * @param {HTMLElement} modal
	 * @param {Object} modalRepeatActions
	 */
	function handleOnPageLoad(modal, config, modalRepeatActions) {

		// was using typeof config.options.afterPageLoad !== 'number') , but we are actually sending a number inside of a string
		if (!config || !config.options || isNaN(Number(config.options.afterPageLoad))) {
			console.error('After Page Load option is not a valid number or does not exist', modal);
			return;
		}

		const delayOption = config.options.afterPageLoad;
		const delay = delayOption * 1000;
		setTimeout(() => {
			const shouldReveal = handleRepeatActions(modal, modalRepeatActions.repeatActions);
			if (shouldReveal) {
				revealModal(modal);
			}
		}, delay);
	}

	/**
 * Reveals a modal when user hovers over specified elements
 * @param {HTMLElement} modal - The modal element to be revealed
 * @param {Object} config - Settings for the Modal type of Action
 * @param {Object} modalRepeatActions - Configuration for how the modal should show again
 */
	function handleOnHover(modal, config, modalRepeatActions) {
		if (!config || !config.options || !config.options.hoverSelector) {
			console.error('Invalid config or hover selector not found in config:', config);
			return;
		}
		const hoverSelector = config.options.hoverSelector;
		const elements = document.querySelectorAll(hoverSelector);
		if (!elements.length) {
			console.warn(`Modal hover trigger: No elements found with selector "${hoverSelector}"`);
			return;
		}
		// Create a function to handle the hover event
		const handleHover = () => {
			if (!modal.classList.contains('fr-modal--open')) {
				const shouldReveal = handleRepeatActions(modal, modalRepeatActions.repeatActions);
				if (shouldReveal) {
					revealModal(modal);
				}
			}
		};
		// Add hover listeners to all matching elements
		elements.forEach(element => {
			element.addEventListener('mouseenter', handleHover);
		});
	}


	/**
	 * Handle On Inactivity Action
	 * uses the throttle function to throttle the addEventListeners to dont spam the Page
	 *
	 * @param {HTMLElement} modal
	 * @param {Object} config - Settings for the Modal type of Action
	 * @param {*} modalRepeatActions
	 */
	function handleOnInactivity(modal, config, modalRepeatActions) {
		if (!config ||
			!config.options ||
			!['mouseInactivity', 'keyboardInactivity', 'scrollInactivity'].some(key => config.options[key]) ||
			!config.options.inactivityTime) {

			console.warn('Inactivity Config does not have any (option) or (time) set', config.options);
			return;
		}

		const inactivityTime = config.options.inactivityTime * 1000;
		let inactivityTimeout;

		const resetInactivityTimeout = () => {
			clearTimeout(inactivityTimeout);

			inactivityTimeout = setTimeout(() => {
				const shouldReveal = handleRepeatActions(modal, modalRepeatActions.repeatActions);

				if (shouldReveal) {
					revealModal(modal);
				}
			}, inactivityTime);
		};

		const throttledResetTimeout = throttle(resetInactivityTimeout, 150);

		if (config.options.mouseInactivity) {
			document.addEventListener('mousemove', throttledResetTimeout);
		}
		if (config.options.keyboardInactivity) {
			document.addEventListener('keydown', throttledResetTimeout);
		}
		if (config.options.scrollInactivity) {
			document.addEventListener('scroll', throttledResetTimeout);
		}

		resetInactivityTimeout();
	}

	if (flag_enable_modal_exit_intent) {
		// TODO @Hakira-Shymuy before release put handleOnScrollToEl under Flag Feature, and exclude from release.
		// TODO: @Hakira-Shymuy not working on sections if it does not have any other container without a Slider, might be Splide.JS
		/**
		 * IF there is another container than not a slider or contains a slider it is working
		 */
		/**
		 * Handle on Scroll To Element
		 * @param {HTMLElement} modal
		 * @param {Object} config
		 * @param {*} modalRepeatActions
		 * @returns
		 */
		function handleOnScrollToEl(modal, config, modalRepeatActions) {
			if (!config || !config.options || typeof config.options.scrollSelector !== 'string') {
				console.error('Invalid config or scroll Selector not found in config:', config);
				return;
			}

			const targetElement = document.querySelector(config.options.scrollSelector);
			console.log('selector', targetElement);

			if (!targetElement) {
				console.error(`Selector not found: ${config.options.scrollSelector}`);
				return;
			}

			if (bricksIsFrontend) {
				console.log('is bricks frontend');
				const observerCallback = (entries, observer) => {
					console.log('observer callback');
					entries.forEach(entry => {
						console.log('entry', entry);
						if (entry.isIntersecting) {
							console.log('is intersecting');
							const shouldReveal = handleRepeatActions(modal, modalRepeatActions.repeatActions);

							if (shouldReveal) {
								console.log('should reveal');
								revealModal(modal);
								observer.unobserve(entry.target);
							}
						}
					});
				};

				const selectorThreshold = config.options.selectorThreshold ? parseInt(config.options.selectorThreshold, 10) / 100 : 0.5;
				const observer = new IntersectionObserver(observerCallback, {
					root: null,
					threshold: selectorThreshold,
				});

				observer.observe(targetElement);
				console.log(observer);
				console.log(targetElement);
			}
		}
	}
	// End Flag


	/**
	 * Determines if a modal should be shown based on repeat action configuration
	 * @param {HTMLElement} modal
	 * @param {string} action - The type of repeat action to handle
	 * @returns {boolean} True if the modal should be shown
	 */
	function handleRepeatActions(modal, action) {
		switch (action) {
			case 'neverShowAgain':
				return handleNeverShowAgain(modal);
			case 'perPageVisit':
				return handleOncePerPageVisit(modal);
			case 'alwaysShow':
				return true;

			default:
				console.log('Unexpected modalRepeatActions value:', modalRepeatActions);
				return false;
		}
	}

	/**
	 * Handles the logic for modals that should never be shown again
	 * @param {HTMLElement} modal
	 * @returns {boolean}
	 */
	function handleNeverShowAgain(modal) {
		const modalID = modal.id;
		const modalKey = `frames_neverShowAgainModal_${modalID}`;
		const neverShow = localStorage.getItem(modalKey) === 'true';

		if (!neverShow) {
			localStorage.setItem(modalKey, 'true');
			return true;
		}
		return false;
	}

	/**
	 * @handleOncePerPageVisit
	 *
	 * Helper for Firefox, Firefox persists the running of javascript through back_forward navigation
	 * performance.navigation is deprecated
	 * https://developer.mozilla.org/en-US/docs/Web/API/Performance/navigation
	 *
	 */
	function clearFramesSessionStorage() {
		for (let i = sessionStorage.length - 1; i >= 0; i--) {
			const key = sessionStorage.key(i);
			if (key.startsWith('framesModal_')) {
				sessionStorage.removeItem(key);
			}
		}
	}

	window.addEventListener('pageshow', (event) => {
		if (event.persisted || window.PerformanceEventTiming.type === 2) {
			clearFramesSessionStorage();
		}
	});

	window.addEventListener('load', () => {
		clearFramesSessionStorage();
	});

	function handleOncePerPageVisit(modal) {
		const modalId = modal.id;
		const modalShownKey = `framesModal_oncePerPageVisitModal_${modalId}`;
		const shownThisVisit = sessionStorage.getItem(modalShownKey) === 'true';

		if (!shownThisVisit) {
			sessionStorage.setItem(modalShownKey, true);
			return true;
		}

		return false;
	}

	// audio
	function initAudio(modal) {
		if (isHtmlAudio(modal)) {
			initHtmlAudio(modal);
		}
		// Later, you can add checks for other audio types here.
	}

	function isHtmlAudio(modal) {
		const audio = modal.querySelector('audio');
		return audio?.src !== undefined;
	}

	function initHtmlAudio(modal) {
		const audio = modal.querySelector('audio');
		console.log('audio', audio);
		if (modal.dataset.frModalVideoAutoplay === 'true') {
			audio.play();
		}
	}

	function stopAudio(modal) {
		stopHtmlAudio(modal);
	}

	function stopHtmlAudio(modal) {
		if (!isHtmlAudio(modal)) return;
		const audio = modal.querySelector('audio');
		if (audio) {
			audio.pause();
		}
	}

	// video
	function initVideo(modal) {

		if (isYoutubeVideo(modal)) {
			initYoutubePlayer(modal);
		} else if (isVimeoVideo(modal)) {
			initVimeoPlayer(modal);
		} else if (isHtmlVideo(modal)) {
			initHtmlVideo(modal);
		}
		// Later, you can add checks for other video types here.
	}

	function isHtmlVideo(modal) {
		if (!modal.querySelector('video')) return false;
		const video = modal.querySelector('video');
		return video && video.src;
	}

	function isVimeoVideo(modal) {
		if (!modal.querySelector('iframe')) return false;
		const iframe = modal.querySelector('iframe');

		return iframe && iframe.src.includes('vimeo.com');
	}

	function initVimeoPlayer(modal) {
		const iframe = modal.querySelector('iframe');
		if (modal.dataset.frModalVideoAutoplay === 'true') {
			const iframeSrc = iframe.src;

			iframe.src = `${iframeSrc}&autoplay=1`;
		}
	}

	function isYoutubeVideo(modal) {
		if (!modal.querySelector('iframe')) return false;
		const iframe = modal.querySelector('iframe');
		let isYoutube = false;
		if (iframe && iframe.src.includes('youtube.com')) {
			isYoutube = true;
		}
		return isYoutube && window.YT && window.YT.Player;
	}


	// Load YouTube API if there are any YouTube videos in the modals

	window.addEventListener('load', () => {
		let modalsWithYoutubeVideoCounter = 0;
		modals.forEach(modal => {
			if (!modal.querySelector('iframe')) return false;
			const iframe = modal.querySelector('iframe');
			if (iframe.src.includes('youtube.com')) {
				loadYoutubeApi();
				modalsWithYoutubeVideoCounter++;
			}
		});
	});

	function initHtmlVideo(modal) {
		const video = modal.querySelector('video');
		if (modal.dataset.frModalVideoAutoplay === 'true') {
			video.play();
		}
	}

	function initYoutubePlayer(modal) {
		const iframe = modal.querySelector('iframe');
		const videoId = iframe.src.split('embed/')[1].split('?')[0];

		if (!iframe.player) {
			iframe.player = new YT.Player(iframe, {
				videoId: videoId,
				events: {
					'onReady': function (event) {
						if (modal.dataset.frModalVideoAutoplay === 'true') {
							event.target.playVideo();
						}
					}
				}
			});
		} else if (modal.dataset.frModalVideoAutoplay === 'true') {
			iframe.player.playVideo();
		}
	}

	function loadYoutubeApi() {
		if (window.YT && window.YT.Player) {
			// YouTube API is already loaded
			return;
		}

		// This function will be called when the script has finished loading
		window.onYouTubeIframeAPIReady = function () {
			// YouTube API is ready to use
			// console.log('YouTube IFrame API is ready');
			// Initialize video players or anything else that depends on the API here
		};

		// Create a new script element for the YouTube IFrame API
		var tag = document.createElement('script');
		tag.src = 'https://www.youtube.com/iframe_api';
		var firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	}


	function stopVideo(modal) {
		stopYoutubeVideo(modal);
		stopVimeoVideo(modal);
		stopHtmlVideo(modal);
	}

	function stopHtmlVideo(modal) {
		if (!isHtmlVideo(modal)) return;
		const video = modal.querySelector('video');
		if (video) {
			video.pause();
		}
	}

	function stopYoutubeVideo(modal) {
		if (!isYoutubeVideo(modal)) return;
		const iframe = modal.querySelector('iframe');
		if (iframe && iframe.player) {
			iframe.player.stopVideo();
		}
	}

	function stopVimeoVideo(modal) {
		if (!isVimeoVideo(modal)) return;
		const iframe = modal.querySelector('iframe');
		const iframeSrc = iframe.src;
		iframe.src = iframeSrc.replace('&autoplay=1', '');
	}

	function closeModalWithHamburgerWidget(modals) {
		modals.forEach(modal => {
			const trigger = document.querySelector(`[href="#${modal.id}"]`);
			if (isTriggerAHamburgerWidget(trigger)) {

				addEventListenerWithEventInfo(trigger, 'click', (e) => {
					if (modal.classList.contains('fr-modal--open') && !trigger.classList.contains(higherIndexTriggerClass)) {
						closeModal(modal, e);
					}
				});

				addEventListenerWithEventInfo(trigger, 'keydown', (e) => {
					if (e.key === 'Enter') {
						if (modal.classList.contains('fr-modal--open') && !trigger.classList.contains(higherIndexTriggerClass)) {
							closeModal(modal, e);
						}
					}
				});
			}
		});
	}


	function closeModalWithCloseIcon(modals) {
		modals.forEach(modal => {
			if (modal.querySelector('.fr-modal__close-icon-wrapper')) {
				const fadeTime = Number(modal.dataset.frModalFadeTime);
				const closeIcon = modal.querySelector('.fr-modal__close-icon-wrapper');

				addEventListenerWithEventInfo(closeIcon, 'click', (e) => {
					closeModal(modal, e);
					removeActiveBurgerClassOnModalClose(modal);
				});

				addEventListenerWithEventInfo(closeIcon, 'keydown', (e) => {
					if (e.key === 'Enter') {
						closeModal(modal, e);
						removeActiveBurgerClassOnModalClose(modal);
					}
				});
			}
		});

	}

	function closeModalWithEscKey(modals) {
		document.addEventListener('keydown', (e) => {
			if (e.key === 'Escape') {
				modals.forEach(modal => {
					const fadeTime = Number(modal.dataset.frModalFadeTime);
					if (modal.classList.contains('fr-modal--open')) {
						closeModal(modal, e);
						removeActiveBurgerClassOnModalClose(modal);
					}
				});
			}


		});
	}

	function closeModalWithACustomSelector(modals) {
		modals.forEach(modal => {
			getDataAttributesForModal(modal);
			if (modal.querySelector(closeSelector)) {
				const closeElement = modal.querySelector(closeSelector);
				if (closeElement) {
					addEventListenerWithEventInfo(closeElement, 'click', (e) => {
						closeModal(modal, e);
						removeActiveBurgerClassOnModalClose(modal);
					});
				}
			}
		});
	}



	function closeModalOnOverlayClick(modals) {
		modals.forEach(modal => {
			let disableCloseOutside = modal.dataset.frModalDisableCloseOutside === 'true';

			if (!disableCloseOutside) {
				const fadeTime = Number(modal.dataset.frModalFadeTime);
				const overlay = modal.querySelector('.fr-modal__overlay');
				overlay.addEventListener('click', (e) => {
					closeModal(modal, e);
					removeActiveBurgerClassOnModalClose(modal);
				});
			}
		});
	}




	/**
	 * wrapper function for the addEventListener to help get the type of interaction
	 *
	 * @param {HTMLElement} element - the DOM element that will receive the event
	 * @param {string} eventType - represents the type of event (click, keydown)
	 * @param {Function} callback - the functions to be called when the event is triggered
	 */
	function addEventListenerWithEventInfo(element, eventType, callback) {
		element.addEventListener(eventType, (event) => {
			event.isKeyboardEvent = (eventType === 'keydown');

			callback(event);
		});
	}


	function closeModal(modal, closeTrigger) {
		const fadeTime = parseInt(modal.dataset.frModalFadeTime);
		const fadeOutTime = parseInt(modal.dataset.frModalFadeOutTime) || fadeTime;
		const currentScrollPosition = window.scrollY;
		let disableCloseOutside = modal.dataset.frModalDisableCloseOutside === 'true';
		let sameCloseTrigger = modal.dataset.frModalSameCloseTrigger === 'true';

		if (closeTrigger && closeTrigger.target.classList.contains('fr-modal__overlay') && disableCloseOutside) {
			return;
		}

		let isTriggerClose = closeTrigger && closeTrigger.target.closest(modal.dataset.frModalTrigger);

		if (!isTriggerClose && sameCloseTrigger && closeTrigger &&
        !closeTrigger.target.classList.contains('fr-modal__close-icon') &&
        !closeTrigger.target.closest(modal.dataset.frModalClose)) {
			return;
		}

		if (closeTrigger) {
			closeTrigger.stopPropagation();
		}

		modal.classList.add('fr-modal--closing');
		modal.classList.remove('fr-modal--open');
		modal.setAttribute('aria-hidden', 'true');

		setTimeout(() => {
			modal.classList.remove('fr-modal--closing');
			scrollingToggle(modal);
			window.scrollTo(0, currentScrollPosition);
		}, fadeOutTime);

		stopVideo(modal);
		stopAudio(modal);

		if (isTriggerClose && sameCloseTrigger) {
			const trigger = closeTrigger.target.closest(modal.dataset.frModalTrigger);
			if (trigger) {
				trigger.classList.remove('fr-modal__trigger--higher-index');
				if (trigger.classList.contains('fr-hamburger--active')) {
					trigger.classList.remove('fr-hamburger--active');
				}
				if (trigger.classList.contains('fr-button-trigger--active')) {
					trigger.classList.remove('fr-button-trigger--active');
				}
			}
		}

		if (closeTrigger.isKeyboardEvent) {
			setFocusBackToTrigger(modal);
		}
	}





	function setFocusBackToTrigger(modal) {

		const modalTrigger = modal.triggerElement;

		if (modalTrigger) {
			const fadeOutTime = parseInt(modal.dataset.frModalFadeOutTime) || 300;
			setTimeout(() => {
				modalTrigger.focus();
			}, fadeOutTime);
		}
	}






	// Global Modal Functions

	function setTransitionTime(modals) {
		modals.forEach(modal => {
			const fadeTime = Number(modal.dataset.frModalFadeTime);
			const fadeOutTime = Number(modal.dataset.frModalFadeOutTime);
			const modalBody = modal.querySelector('.fr-modal__body');

			// modal.style.transition = `all ${fadeTime}ms ease-in-out`;
			// modalBody.style.transition = `all ${fadeTime}ms ease-in-out`;

			modal.style.setProperty('--fade-in-time', `${fadeTime}ms`);
			modal.style.setProperty('--fade-out-time', `${fadeOutTime}ms`);
		});
	}

	// Focus Functions

	function focusFirstElementOnOpen(trigger) {
		// trigger.addEventListener('click', (e) => {
		//   focusFirstElementInModal(e, trigger)
		// });
		trigger.addEventListener('keydown', (e) => {
			if (e.key === 'Enter') {
				e.preventDefault();
				focusFirstElementInModal(e, trigger);
			}
		});
	}

	function focusFirstElementInModal(e, trigger) {
		e.preventDefault();
		const modalId = trigger.getAttribute('href');
		const modal = document.querySelector(modalId);
		const modalBody = modal.querySelector('.fr-modal__body');
		const fadeTime = Number(modal.dataset.frModalFadeTime);

		const setModalFocus = () => {
			const focusableContent = modalBody.querySelectorAll(focusableElements);

			if (focusableContent.length > 0) {
				focusableContent[0].focus();
			} else {
				modalBody.setAttribute('tabindex', '-1');
				modalBody.focus();
			}
		};

		if (fadeTime >= 100) {
			setTimeout(() => {
				setModalFocus();
			}, fadeTime);
		} else {
			setTimeout(() => {
				setModalFocus();
			}, 100);
		}
	}

	function trapFocusInModal(trigger) {
		const modalId = trigger.getAttribute('href');
		const modal = document.querySelector(modalId);
		const modalBody = modal.querySelector('.fr-modal__body');
		const closeButton = modal.querySelector('.fr-modal__close-icon');
		const customCloseButton = modal.dataset.frModalClose ?
			modal.querySelector(modal.dataset.frModalClose) : null;

		function handleFocusTrap(e) {
			// Get all focusable elements, including close buttons
			const focusableContent = [
				...(closeButton ? [closeButton] : []),
				...(customCloseButton ? [customCloseButton] : []),
				...Array.from(modalBody.querySelectorAll(focusableElements))
			].filter(el => el.offsetParent !== null); // Filter out any hidden elements

			if (focusableContent.length === 0) {
				focusableContent.push(modalBody);
				modalBody.setAttribute('tabindex', '-1');
			}

			const firstElement = focusableContent[0];
			const lastElement = focusableContent[focusableContent.length - 1];

			if (e.key === 'Tab') {
				if (e.shiftKey && document.activeElement === firstElement) {
					e.preventDefault();
					lastElement.focus();
				} else if (!e.shiftKey && document.activeElement === lastElement) {
					e.preventDefault();
					firstElement.focus();
				}
			}
		}

		modal.removeEventListener('keydown', handleFocusTrap);
		modal.addEventListener('keydown', handleFocusTrap);
	}






	// Focus Functions Helpers

	function loopThroughFocusElements(firstFocusableElement, lastFocusableElement) {
		firstFocusableElement.addEventListener('keydown', (e) => {
			if (e.key === 'Tab' && e.shiftKey) {
				e.preventDefault();
				lastFocusableElement.focus();
			}
		});

		lastFocusableElement.addEventListener('keydown', (e) => {
			if (e.key === 'Tab' && !e.shiftKey) {
				e.preventDefault();
				firstFocusableElement.focus();
			}
		});
	}

	function preventScrolling() {
		document.body.style.overflow = 'hidden';
	}



	const scrollBarWidth = window.innerWidth - document.documentElement.clientWidth;
	document.documentElement.style.setProperty('--fr-scrollbar-width', `${scrollBarWidth}px`);

	function scrollingToggle(modal) {
		if (modal.dataset.frModalAllowScroll === 'true') return;

		const bodyHeight = document.body.scrollHeight;
		const windowHeight = window.innerHeight;

		if (bodyHeight > windowHeight) {
			if (window.innerWidth > 767 && scrollBarWidth > 0) {
				document.body.classList.toggle('fr-modal-body-padding');
			}
		}

		document.body.classList.toggle('fr-modal-prevent-scroll');
		if (modal.getAttribute('aria-hidden') === 'true' && document.body.classList.contains('fr-modal-prevent-scroll')) {
			document.body.classList.remove('fr-modal-prevent-scroll');
		}
	}

	function removeAllInBuilder() {
		const modals = document.querySelectorAll('.iframe .fr-modal');
		modalsInQueryBuilder.forEach(modal => {
			if (modal !== modals[0]) {
				modal.style.display = 'none';
			}
			modals[0].style.display = 'flex';
		});
	}

	function setModalBodyToOverflowScroll(modal) {
		const modalBody = modal.querySelector('.fr-modal__body');
		if (modal.dataset.frModalScroll === 'true') {
			modalBody.style.overflow = 'scroll';
		}
		else {
			return;
		}
	}


	function removeActiveBurgerClassOnModalClose(modal) {
		const trigger = document.querySelector(`[href="#${modal.id}"]`);
		if (isTriggerAHamburgerWidget(trigger)) {
			if (trigger.classList.contains(higherIndexTriggerClass)) {
				trigger.classList.remove(higherIndexTriggerClass);
				// if trigger containe fr-hamburger--active
				if (trigger.classList.contains('fr-hamburger--active')) {
					trigger.classList.remove('fr-hamburger--active');
				}
				if (trigger.classList.contains('fr-button-trigger--active')) {
					let options = {};
					if (trigger.dataset.frTriggerOptions) {
						try {
							options = JSON.parse(trigger.dataset.frTriggerOptions);
						} catch (error) {
							console.error('Error parsing frTriggerOptions', error);
						}
					}

					if (options.useActiveText) {

						trigger.querySelector('.fr-button-trigger__text').innerHTML = options.buttonText;

					}
					trigger.classList.remove('fr-button-trigger--active');
				}
			}
		}
	}

	function isTriggerAHamburgerWidget(trigger) {

		if (trigger) {
			if (trigger.classList.contains('brxe-fr-trigger')) {
				return true;
			} else {
				return false;
			}
		}
	}

	function showOnlyFirstModalInBuilderInsideQuery() {
		const modals = document.querySelectorAll('.iframe .fr-modal');
		modals.forEach((modal, index) => {

			if (modal.dataset.frModalInsideQuery === 'true' && index !== 0) {
				modal.remove();
			}
		});
	}

	// Logic



	for (let i = 0; i < modals.length; i++) {
		const modal = modals[i];
		modal.classList.remove('fr-modal--hide');
	}

	for (let i = 0; i < modals.length; i++) {
		const modal = modals[i];
		getDataAttributesForModal(modal);
	}


	function generateModalTriggerConnectionInQueryBuilder() {
		const modalsIDs = [];
		modalsInQueryBuilder.forEach((modal, index) => {
			const triggerSelector = modal.dataset.frModalTrigger;
			modalsIDs.push(modal.id);
			const triggers = document.querySelectorAll(triggerSelector);
			triggers.forEach((trigger, index) => {
				let sharedParent = trigger.closest(`.${modal.dataset.frModalQueryId}`);

				if (sharedParent) {
					let innerModal, innerTrigger;
					if (sharedParent === trigger) {
						// Trigger is the sharedParent itself
						innerModal = sharedParent.querySelector('.fr-modal');
						innerTrigger = trigger;
					} else {
						innerModal = sharedParent.querySelector('.fr-modal');
						innerTrigger = sharedParent.querySelector(`${innerModal.dataset.frModalTrigger}`);
					}

					let newID;
					if (document.querySelectorAll(`#${innerModal.id}`).length > 1) {
						newID = `${innerModal.id}-${Math.random().toString(36).substr(2, 5)}`;
					} else {
						newID = innerModal.id;
					}
					innerModal.id = newID;
					innerTrigger.setAttribute('href', `#${innerModal.id}`);
					if (innerModal.id === innerTrigger.getAttribute('href').slice(1)) {
						innerTrigger.setAttribute('tabindex', '0');
						innerTrigger.setAttribute('role', 'button');
						openModalWithTrigger(innerTrigger);
						focusFirstElementOnOpen(innerTrigger);
						trapFocusInModal(innerTrigger);
					}
				}
			});

		});
	}



	const modalsInQueryBuilder = Array.from(modals).filter(modal => modal.dataset.frModalInsideQuery === 'true');
	const modalsNotInQueryBuilder = Array.from(modals).filter(modal => modal.dataset.frModalInsideQuery === 'false');


	generateModalTriggerConnection();
	generateModalTriggerConnectionInQueryBuilder();

	// modals.forEach(modal => {
	//   setModalBodyToOverflowScroll(modal)
	// });



	setTransitionTime(modals);

	// Close Modal Functions
	closeModalWithHamburgerWidget(modals);
	closeModalWithEscKey(modals);
	if (!document.querySelector('.iframe .fr-modal')) {
		closeModalOnOverlayClick(modals);
		closeModalWithACustomSelector(modals);
		closeModalWithCloseIcon(modals);
	}

	// Builder Functions
	showOnlyFirstModalInBuilderInsideQuery();

}


function wpgb_modal_script() {
	window.WP_Grid_Builder && WP_Grid_Builder.on('init', function (wpgb) {

		// console.log(wpgb); // Holds all instances.
		// console.log(wpgb.element); // Holds all instances.

		if (wpgb.element.classList.contains('wp-grid-builder')) return;
		wpgb.facets.on('appended', function (nodes) {

			modal_script();

		});

	});
}

function runModalScriptsOnBricksFilters() {
	if (typeof window.bricksFilters !== 'function') return;


	const bricksFilterRun = bricksFiltersFn.run;
	bricksFiltersFn.run = function () {

		bricksFilterRun.apply();
		modal_script();
	};

}

document.addEventListener('DOMContentLoaded', function (e) {
	bricksIsFrontend && modal_script();
	bricksIsFrontend && wpgb_modal_script();
	bricksIsFrontend && runModalScriptsOnBricksFilters();
});
