class FrColorScheme {
	constructor(element, options) {
		this.element = element;
		this.options = options;
		this.isSimple = this.options.mode === 'simple';
		this.isToggle = this.options.mode === 'toggle';
		this.isWebsiteLight = this.options.websiteScheme === 'light' ? true : false;
		this.isAltModeOnInit = document.documentElement.classList.contains('color-scheme--alt');
		this.isLightMode = this.isWebsiteLight && !this.isAltModeOnInit || !this.isWebsiteLight && this.isAltModeOnInit;
		this.init();
	}


	init() {
		if (this.isSimple) this.initSimple();
		if (this.isToggle) this.initToggle();
	}



	initSimple() {

		const moonPathStatic = 'M11.3807 2.01904C9.91573 3.38786 9 5.33708 9 7.50018C9 11.6423 12.3579 15.0002 16.5 15.0002C18.6631 15.0002 20.6123 14.0844 21.9811 12.6195C21.6613 17.8539 17.3149 22.0002 12 22.0002C6.47715 22.0002 2 17.523 2 12.0002C2 6.68532 6.14629 2.33888 11.3807 2.01904Z';
		const sunPathStatic = 'M12 18C8.68629 18 6 15.3137 6 12C6 8.68629 8.68629 6 12 6C15.3137 6 18 8.68629 18 12C18 15.3137 15.3137 18 12 18ZM11 1H13V4H11V1ZM11 20H13V23H11V20ZM3.51472 4.92893L4.92893 3.51472L7.05025 5.63604L5.63604 7.05025L3.51472 4.92893ZM16.9497 18.364L18.364 16.9497L20.4853 19.0711L19.0711 20.4853L16.9497 18.364ZM19.0711 3.51472L20.4853 4.92893L18.364 7.05025L16.9497 5.63604L19.0711 3.51472ZM5.63604 16.9497L7.05025 18.364L4.92893 20.4853L3.51472 19.0711L5.63604 16.9497ZM23 11V13H20V11H23ZM4 11V13H1V11H4Z';
		let morphing = false;

		let moonPath = moonPathStatic;
		let sunPath = sunPathStatic;

		const userMainPath = this.options.mainIconPath;
		const userAltPath = this.options.altIconPath;


		let mainPath = this.isLightMode ? moonPath : sunPath;
		let altPath = !this.isLightMode ? moonPath : sunPath;

		if (userMainPath) {
			mainPath = userMainPath;
			moonPath = userMainPath;
		}

		if (userAltPath) {
			altPath = userAltPath;
			sunPath = userAltPath;
		}





		const path = `<path d="${this.isLightMode ? moonPath : sunPath}"></path>`;
		const svg = this.element.querySelector('svg');
		svg.innerHTML = path;



		this.element.addEventListener('click', () => {

			if (morphing) return;
			morphing = true;

			const interpolator = this.isLightMode && mainPath
				? flubber.interpolate(moonPath, sunPath)
				: flubber.interpolate(sunPath, moonPath);

			const startTime = Date.now();
			const duration = 100;

			function animate() {
				const now = Date.now();
				const elapsed = now - startTime;
				const t = Math.min(1, elapsed / duration);
				const newPath = interpolator(t);
				svg.querySelector('path').setAttribute('d', newPath);

				if (t < 1) {
					requestAnimationFrame(animate);
				} else {
					morphing = false;
					this.isLightMode = !this.isLightMode; // Toggle the state
				}
			}

			animate = animate.bind(this);  // Binding 'this' to retain the context inside the animate function
			requestAnimationFrame(animate);
		});
	}

	initToggle() {

		const checkbox = this.element.querySelector('input[type="checkbox"]');
		checkbox.checked = this.isAltModeOnInit;
		this.isAltModeOnInit ? checkbox.classList.add('checked') : checkbox.classList.remove('checked');

		checkbox.addEventListener('change', () => {
			checkbox.classList.toggle('checked');
		});

		if (this.options.labelType === 'text' ) {
			const mainLabel = this.element.querySelector('.fr-color-scheme__text-label-main');
			const altLabel = this.element.querySelector('.fr-color-scheme__text-label-alt');
			mainLabel.addEventListener('click', () => {
				checkbox.classList.remove('checked');
			});
			altLabel.addEventListener('click', () => {
				checkbox.classList.add('checked');
			});
		}
	}

}


function color_scheme_script() {
	const colorSchemesToggles = document.querySelectorAll('.fr-color-scheme');

	colorSchemesToggles.forEach(item => {
		if (!item.dataset.frColorSchemeOptions) {
			console.warn('Options not provided for the following switch element:', item);
			return;
		}
		const options = JSON.parse(item.dataset.frColorSchemeOptions);
		new FrColorScheme(item, options);
	});
}

document.addEventListener('DOMContentLoaded', function (e) {
	color_scheme_script();
});

