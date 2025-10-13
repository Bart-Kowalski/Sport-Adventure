
'use strict';

class Trigger {
	constructor(element, options = {}) {
		this.element = element;
		this.options = options;
		this.init();
	}

	init() {
		this.element.addEventListener('click', () => {
			this.toggleMenu();
		});

		this.element.addEventListener('keydown', (e) => {
			if (e.key === 'Enter') {
				e.preventDefault();
				this.toggleMenu();
			}
		});
	}

	toggleMenu() {
		if (this.isBurgerType()) {
        	this.element.classList.toggle('fr-hamburger--active');
		} else if (this.isButtonType()) {
			this.element.classList.toggle('fr-button-trigger--active');
			this.toggleButtonText();
		}
		this.element.setAttribute('aria-expanded',
			this.element.getAttribute('aria-expanded') === 'true' ? 'false' : 'true'
		);
	}

	toggleButtonText() {
		if (this.options.useActiveText) {
			if (this.element.classList.contains('fr-button-trigger--active')) {
				this.element.querySelector('.fr-button-trigger__text').innerHTML = this.options.buttonActiveText;
			} else {
				this.element.querySelector('.fr-button-trigger__text').innerHTML = this.options.buttonText;
			}
		}

	}

	isBurgerType() {
		return this.element.classList.contains('fr-hamburger');
	}

	isButtonType() {
		return this.element.classList.contains('fr-button-trigger');
	}


}

function trigger_script() {

	const triggers = document.querySelectorAll('.brxe-fr-trigger');
	triggers.forEach((trigger) => {
		let options = {};
		if (trigger.dataset.frTriggerOptions) {
			try {
				options = JSON.parse(trigger.dataset.frTriggerOptions);
			} catch (error) {
				console.error('Error parsing frTriggerOptions', error);
			}
		}
		new Trigger(trigger, options);
	});

}

document.addEventListener('DOMContentLoaded', function (e) {
	bricksIsFrontend && trigger_script();
});
