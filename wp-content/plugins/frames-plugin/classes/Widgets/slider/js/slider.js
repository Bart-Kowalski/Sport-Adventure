'use strict';

function slider_script(params) {

	let flag_enable_mouse_wheel_release = window.frames_slide_obj?.flag_enable_mouse_wheel_release ?? false;
	if (flag_enable_mouse_wheel_release) {
		console.log('flag mouse_wheel_release is on');
	}

	const sliders = document.querySelectorAll('.fr-slider');
	const inBuilder = document.querySelector('.iframe.mounted');

	const { isWpgb = false } = params || {};
	if (isWpgb) {
		sliders.forEach((slider) => {


			// remove pagination
			const list = slider.querySelector('.splide__list');

			const pagination = slider.querySelector('.splide__pagination');
			if (pagination) pagination.remove();
			if (list.classList.contains('.wpgb-enabled')) {
				const children = list.children;

				for (let i = 0; i < children.length; i++) {
					children[i].remove();
				}

			}
		});
		return;
	}

	const { isBricksFilters = false } = params || {};

	storeID(sliders);
	addSlideClasses(sliders);
	initiateSliders(sliders);
	// switchIds(sliders)
	// setBreakoutOverflow(sliders)
	// paginationRender(sliders)


	function afterSplideMounted() {
		// storeID(sliders)
		switchIds(sliders);
		setBreakoutOverflow(sliders);
		customAriaControls(sliders);
		setListItemRoles(sliders);
	}

	function customAriaControls(sliders) {

		[...sliders]
			.map((slider) => {
				const options = sliderOptions(slider);
				const slides = slider.querySelectorAll('.splide__slide:not(.splide__slide--clone)');
				const paginationList = slider.querySelectorAll('.splide__pagination');

				return { slides, paginationList, options };
			})
			.filter(({ options, paginationList }) => (options.pagination && paginationList))
			.forEach(({ slides, paginationList }) => [...paginationList].flatMap(getFixedAriaIDs).forEach(fixAriaIDs(slides))
			);

		function fixAriaIDs() {
			return ({ child, index }) => {
				const slides = document.querySelectorAll('.splide__slide');
				const slideId = slides[index].id;
				const childBullet = child.querySelector('button');
				childBullet.setAttribute('aria-controls', slideId);
			};
		}

		function getFixedAriaIDs(pagination) {
			return [...pagination.querySelectorAll('li')]
				.map((child, index) => ({ child, index }));
		}

	}

	function setListItemRoles(sliders) {
		sliders.forEach((slider) => {
			const hasPagination = slider.querySelector('.splide__pagination');
			const list = slider.querySelector('.splide__list');
			const slides = slider.querySelectorAll('.splide__slide');

			// Remove any existing roles first
			list.removeAttribute('role');
			slides.forEach(slide => slide.removeAttribute('role'));

			if (hasPagination) {
				// When pagination is visible
				list.setAttribute('role', 'tablist');
				slides.forEach(slide => {
					if (slide.tagName === 'LI') {
						slide.setAttribute('role', 'tab');
					}
				});
			} else {
				// When pagination is hidden
				list.setAttribute('role', 'list');
				slides.forEach(slide => {
					if (slide.tagName === 'LI') {
						slide.setAttribute('role', 'listitem');
					}
				});
			}
		});
	}


	removeExtraPaginationInBuilder(sliders);
	removeExtraPaginationInBricksFilters(sliders);

	function storeID(sliders) {
		sliders.forEach((slider) => {
			const list = slider.querySelector('.splide__list');
			const children = list.children;
			for (let i = 0; i < children.length; i++) {
				// if(!children[i].id){
				// 	children[i].id = `demo-${[i]}`

				// }
				children[i].dataset.slideId = children[i].id;
			}
		});
	}


	function addSlideClasses(sliders) {
		sliders.forEach((slider) => {
			const list = slider.querySelector('.splide__list');
			const children = list.children;


			for (let i = 0; i < children.length; i++) {

				if (children[i].classList.contains('brx-nestable-children-placeholder')) {
					continue;
				}
				if (children[i].classList.contains('splide__slide')) {
				}
				children[i].classList.add('splide__slide');
			}

			if (children.length > 1 && inBuilder) {
				for (let i = 0; i < children.length; i++) {
					if (children[i].classList.contains('brx-nestable-children-placeholder')) {
						children[i].remove();
					}
				}
			}

		});
	}

	function switchIds(sliders) {
		sliders.forEach((slider) => {
			const list = slider.querySelector('.splide__list');
			const children = list.children;
			for (let i = 0; i < children.length; i++) {
				const child = children[i];
				// Jira issue: FC-100
				child.dataset.splideSlide = child.id;
				if(child.dataset.slideId) {
					child.id = child.dataset.slideId;
				} else {
					child.dataset.slideId = child.dataset.splideSlide;
				}
			}
		});
	}

	// function initiateSliders
	function initiateSliders(sliders) {

		const slidersToSync = Array.from(sliders).filter((slider) => {
			return slider.dataset.frSliderSync === 'true';
		});

		const slidersSingles = Array.from(sliders).filter((slider) => {
			return slider.dataset.frSliderSync === 'false';
		});

		const slidersToSyncByID = slidersToSync.reduce((acc, slider) => {
			const id = slider.dataset.frSliderSyncId;
			if (!acc[id]) {
				acc[id] = [];
			}
			acc[id].push(slider);
			return acc;
		}, {});

		const slidersSync = [];

		Object.keys(slidersToSyncByID).forEach((id) => {
			const slidersInstance = [];
			slidersToSyncByID[id].forEach((slider) => {
				const splideInstance = new Splide(slider);
				splideInstance.on('mounted', afterSplideMounted);
				slidersInstance.push(splideInstance);
			});


			const sortedSliderInstances = slidersInstance.sort((a, b) => {
				const aIsNavigation = JSON.parse(a.root.dataset.splide)?.isNavigation ?? false;
				const bIsNavigation = JSON.parse(b.root.dataset.splide)?.isNavigation ?? false;
				return aIsNavigation - bIsNavigation;
			});

			slidersSync.push({ id, slidersInstance: sortedSliderInstances });

		});

		slidersSync.forEach((group) => {
			group.slidersInstance.forEach((slider, index) => {

				slider.on('move', (newIndex) => {
					syncOtherSliders(group.slidersInstance, index, newIndex);
				});


				if (turnAutoScroll(slider.root)) {
					slider.mount(window.splide.Extensions);
				} else {
					slider.mount();
				}

				slider.on('moved', () => {
					updateButtonState(group.id, slider);
				});

				updateButtonState(group.id, slider);

				if (index === 0) {
					customNavButton(group.id, slider, 'next');
					customNavButton(group.id, slider, 'prev');
					progressBar(group.id, slider);
				}

				window.bricksData.splideInstances[group.id] = slider;

			});
		});

		slidersSingles.forEach((slider) => {

			const splide = new Splide(slider);
			splide.on('mounted', afterSplideMounted);
			if (turnAutoScroll(slider)) {
				splide.mount(window.splide.Extensions);
			} else {
				splide.mount();
			}

			window.bricksData.splideInstances[slider.id] = splide;
		});

	}
	// end function initiateSliders

	function syncOtherSliders(sliders, sourceIndex, newIndex) {
		sliders.forEach((otherSlider, otherIndex) => {
			if (otherIndex !== sourceIndex) {
				otherSlider.go(newIndex);
			}
		});
	}



	// breakout

	function setBreakoutOverflow(sliders) {
		sliders.forEach((slider) => {
			const isBreakout = slider.dataset.frSliderBreakout;
			if (isBreakout === 'false') return;
			const list = slider.querySelector('.fr-slider__list');
			const track = slider.querySelector('.fr-slider__track');
			list.style.overflow = 'visible';
			track.style.overflow = 'visible';

		});
	}

	// Auto Scroll

	function turnAutoScroll(slider) {
		const autoScroll = slider.dataset.frSliderAutoScroll;
		if (autoScroll === 'true') {
			return true;
		}
		return false;
	}

	// Custom Controls

	function getSliderSyncID(slider) {
		if (slider.dataset.frSliderSyncId) {
			return slider.dataset.frSliderSyncId;
		} else {
			return null;
		}
	}

	function customNavButton(id, slider, direction) {
		const customNavs = document.querySelectorAll('.fr-slider-custom-navigation');
		customNavs.forEach((nav) => {
			if (nav.dataset.frSliderNavType === 'next' || nav.dataset.frSliderNavType === 'nextPrev' || nav.dataset.frSliderNavType === 'prev') {
				const syncID = nav.dataset.frSliderNavSyncId;
				if (syncID === id) {
					const buttons = nav.querySelectorAll(`.fr-slider__custom-arrow--${direction}`);
					buttons.forEach((button) => {
						button.addEventListener('click', function () {
							const perMove = slider.options.perMove || 1;
							const command = direction === 'next' ? `+${perMove}` : `-${perMove}`;
							slider.go(command);
							updateButtonState(id, slider);  // call updateButtonState after the slide change
						});
					});
				}
			}
		});
	}


	function updateButtonState(id, slider) {
		const customNavs = document.querySelectorAll('.fr-slider-custom-navigation');
		customNavs.forEach((nav) => {
			if (nav.dataset.frSliderNavType === 'next' || nav.dataset.frSliderNavType === 'nextPrev' || nav.dataset.frSliderNavType === 'prev') {
				const syncID = nav.dataset.frSliderNavSyncId;
				if (syncID === id) {
					const buttons = nav.querySelectorAll('.fr-slider__custom-arrow');
					const type = slider.options.type;
					const rewind = slider.options.rewind;
					const getIndex = slider.index;  // get the current slide index
					const slidesCount = slider.length;  // get the total number of slides
					const perPage = slider.options.perPage;  // get the number of slides per page

					if ((getIndex >= slidesCount - perPage) && type !== 'loop' && rewind !== true) {
						buttons[1].setAttribute('disabled', '');
					} else {
						buttons[1].removeAttribute('disabled');
					}

					if (getIndex === 0 && type !== 'loop' && rewind !== true) {
						buttons[0].setAttribute('disabled', '');
					} else {
						buttons[0].removeAttribute('disabled');
					}
				}
			}
		});
	}





	function goToSlide(slider, index) {
		slider.go(index);
	}

	function progressBar(id, slider) {

		const customNavs = document.querySelectorAll('.fr-slider-custom-navigation[data-fr-slider-nav-sync-id="' + id + '"]');
		customNavs.forEach((nav) => {
			if (nav.dataset.frSliderNavType === 'progress') {
				let counter = 0;
				const bar = nav.querySelector('.fr-slider__progress-bar');
				const syncID = nav.dataset.frSliderNavSyncId;
				if (syncID === id) {

					function updateProgress() {
						const end = slider.Components.Controller.getEnd() + 1;
						const rate = Math.min((slider.index + 1) / end, 1);
						bar.style.width = String(100 * rate) + '%';
					}

					updateProgress();
					slider.on('mounted move', updateProgress);


				}

				const buttonsWrapper = nav.querySelector('.fr-slider__progress-buttons');
				const slides = slider.Components.Elements.slides;

				slides.forEach((slide, index) => {

					const button = document.createElement('button');
					button.classList.add('fr-slider__progress-button');
					button.setAttribute('data-index', index);
					buttonsWrapper.appendChild(button);
				});

				const buttons = buttonsWrapper.querySelectorAll('.fr-slider__progress-button');
				buttons.forEach((button) => {
					button.addEventListener('click', function () {
						const index = Number(button.dataset.index);

						slider.go(index);
					});
				});

			}

		});
	}



	function paginationRender(sliders) {

		sliders.forEach((slider) => {
			const paginationList = slider.querySelectorAll('.fr-slider__pagination');
			paginationList.forEach((pagination, index) => {
				if (index === 0) {
					return;
				}
				pagination.remove();
			});

		});
	}


	function sliderOptions(slider) {
		return slider.dataset.splide ? JSON.parse(slider.dataset.splide) : {};
	}


	function removeExtraPaginationInBuilder(sliders) {

		if (inBuilder) {
			sliders.forEach((slider) => {
				const slidesList = slider.querySelector('.splide__list');
				const children = slidesList.children.length;
				const slidesLength = children - 1;
				const paginationList = slider.querySelectorAll('.splide__pagination');

				const options = sliderOptions(slider);

				paginationList.forEach((pagination, index) => {
					if (pagination.children.length > slidesLength) {
						const children = pagination.querySelectorAll('li');
						const childrenLength = children.length;
						children.forEach((child, index) => {
							if (index > slidesLength && options.perPage <= 1) {
								child.remove();
							} else if (index > slidesLength / options.perPage && options.perPage > 1) {
								child.remove();
							}

						});
					}
				});
			});
		}
	}

	function removeExtraPaginationInBricksFilters(sliders) {
		if (!isBricksFilters) return;

		sliders.forEach((slider) => {
			const sliderList = slider.querySelector('.splide__list');
			const slidesList = sliderList.querySelectorAll('.splide__slide:not(.splide__slide--clone)');
			const slidesLength = slidesList.length;
			const paginationList = slider.querySelectorAll('.splide__pagination');

			paginationList.forEach((pagination, index) => {
				const paginationItems = pagination.querySelectorAll('li');

				if (paginationItems.length <= slidesLength) return;

				Array.from(paginationItems).forEach((item, index) => {
					if (index <= slidesLength) return;
					item.remove();
				});

				const sliderId = slider.id;
				refreshSliderInstance(sliderId);

			});
		});
	}

	function refreshSliderInstance(sliderId) {
		const sliderInstance = window.bricksData.splideInstances[sliderId];
		if (!sliderInstance) return;
		sliderInstance.refresh();
	}

}

// document.addEventListener("DOMContentLoaded", slider_script);

function wpgb_slider_script() {

	// check for the presence of WP_GRID_Builder
	if (window.WP_Grid_Builder) {
		WP_Grid_Builder.on('init', function (wpgb) {

			// check for wpgb.facets, if no facets dont run the code
			if (wpgb.facets) {
				wpgb.facets.on('appended', function (nodes) {


					let isFacetSlider = false;

					const sliders = document.querySelectorAll('.fr-slider');
					sliders.forEach((slider) => {
						if (slider.querySelector('.wpgb-enabled')) {
							isFacetSlider = true;
							console.log('isFacetSlider', isFacetSlider);
						}
					});

					if (isFacetSlider) {
						slider_script();
					}

				});

				wpgb.facets.on('change', function () {

					let isFacetSlider = false;

					const sliders = document.querySelectorAll('.fr-slider');
					sliders.forEach((slider) => {
						if (slider.querySelector('.wpgb-enabled')) {
							isFacetSlider = true;
							console.log('isFacetSlider', isFacetSlider);
						}
					});

					if (isFacetSlider) {
						slider_script({ isWpgb: true });
					}

					// slider_script({ isWpgb: true })

				});
			}

		});
	}
}

function runSliderScriptsOnBricksFilters() {

	if (typeof window.bricksFilters !== 'function') return;


	const bricksFilterRun = bricksFiltersFn.run;
	bricksFiltersFn.run = function () {

		bricksFilterRun.apply(this, arguments);
		setTimeout(() => {
			slider_script({isBricksFilters: true});
		});
	};
}

document.addEventListener('DOMContentLoaded', function (e) {

	bricksIsFrontend && slider_script();
	bricksIsFrontend && wpgb_slider_script();
	bricksIsFrontend && runSliderScriptsOnBricksFilters();

});


