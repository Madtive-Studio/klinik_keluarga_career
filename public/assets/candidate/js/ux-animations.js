(function () {
	'use strict';

	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	var STAGGER_MS = 80;
	var MAX_STAGGER_MS = 400;

	var observer = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (!entry.isIntersecting) {
				return;
			}

			entry.target.classList.add('is-visible');
			observer.unobserve(entry.target);
		});
	}, {
		threshold: 0.08,
		rootMargin: '0px 0px -32px 0px',
	});

	function prepareElement(element, options) {
		if (!element) {
			return;
		}

		options = options || {};

		element.classList.add('candidate-animate');

		if (options.modifier) {
			element.classList.add(options.modifier);
		}

		if (typeof options.delay === 'number') {
			element.style.transitionDelay = options.delay + 'ms';
		}

		if (options.instant) {
			requestAnimationFrame(function () {
				element.classList.add('is-visible');
			});
			return;
		}

		observer.observe(element);
	}

	function staggerElements(elements, options) {
		options = options || {};

		Array.prototype.forEach.call(elements, function (element, index) {
			prepareElement(element, {
				modifier: options.modifier,
				delay: Math.min(index * STAGGER_MS, MAX_STAGGER_MS),
			});
		});
	}

	function initCandidateAnimations() {
		staggerElements(document.querySelectorAll('.jobs-list .job-list-box'));

		document.querySelectorAll('.job-detail').forEach(function (element) {
			prepareElement(element, { modifier: 'candidate-animate--scale' });
		});

		document.querySelectorAll('.candidate-empty-state, .document-dropzone, .profile-section-card, .profile-sidebar').forEach(function (element) {
			prepareElement(element, { modifier: 'candidate-animate--scale' });
		});

		document.querySelectorAll('.section h5, .show-results').forEach(function (element) {
			prepareElement(element, { modifier: 'candidate-animate--fade' });
		});

		prepareElement(document.querySelector('.bg-home .title-heading'), {
			modifier: 'candidate-animate--fade',
			instant: true,
			delay: 0,
		});

		prepareElement(document.querySelector('.bg-home .home-registration-form'), {
			modifier: 'candidate-animate--scale',
			instant: true,
			delay: 120,
		});

		document.querySelectorAll('.bg-home .home-registration-form .col-md-5, .bg-home .home-registration-form .col-md-3, .bg-home .home-registration-form .col-md-2').forEach(function (element, index) {
			prepareElement(element, {
				modifier: 'candidate-animate--fade',
				instant: true,
				delay: 180 + (index * 60),
			});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initCandidateAnimations);
	} else {
		initCandidateAnimations();
	}
})();
