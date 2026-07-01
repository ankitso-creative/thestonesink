(function () {
	'use strict';

	function initProductGallery(gallery) {
		var slides = Array.prototype.slice.call(gallery.querySelectorAll('[data-ssc-gallery-slide]'));
		var thumbs = Array.prototype.slice.call(gallery.querySelectorAll('[data-ssc-gallery-thumb]'));
		var prev = gallery.querySelector('[data-ssc-gallery-prev]');
		var next = gallery.querySelector('[data-ssc-gallery-next]');
		var current = 0;

		function show(index) {
			if (!slides.length) {
				return;
			}

			current = (index + slides.length) % slides.length;

			slides.forEach(function (slide, slideIndex) {
				slide.classList.toggle('is-active', slideIndex === current);
			});

			thumbs.forEach(function (thumb, thumbIndex) {
				thumb.classList.toggle('is-active', thumbIndex === current);
			});
		}

		thumbs.forEach(function (thumb) {
			thumb.addEventListener('click', function () {
				show(parseInt(thumb.getAttribute('data-ssc-gallery-thumb'), 10) || 0);
			});
		});

		if (prev) {
			prev.addEventListener('click', function () {
				show(current - 1);
			});
		}

		if (next) {
			next.addEventListener('click', function () {
				show(current + 1);
			});
		}
	}

	function initTabs(root) {
		var buttons = Array.prototype.slice.call(root.querySelectorAll('[data-ssc-single-tab]'));
		var panels = Array.prototype.slice.call(root.querySelectorAll('[data-ssc-single-panel]'));

		buttons.forEach(function (button) {
			button.addEventListener('click', function () {
				var target = button.getAttribute('data-ssc-single-tab');

				buttons.forEach(function (item) {
					item.classList.toggle('is-active', item === button);
				});

				panels.forEach(function (panel) {
					panel.classList.toggle('is-active', panel.getAttribute('data-ssc-single-panel') === target);
				});
			});
		});
	}

	function initRelatedCarousel(related) {
		var list = related.querySelector('ul.products');

		if (!list) {
			return;
		}

		var controls = document.createElement('div');
		var prev = document.createElement('button');
		var next = document.createElement('button');

		controls.className = 'ssc-related-controls';
		prev.type = 'button';
		next.type = 'button';
		prev.textContent = 'Previous';
		next.textContent = 'Next';
		controls.appendChild(prev);
		controls.appendChild(next);
		related.appendChild(controls);

		function distance() {
			var first = list.querySelector('li.product');
			return first ? first.getBoundingClientRect().width + 30 : list.clientWidth;
		}

		prev.addEventListener('click', function () {
			list.scrollBy({ left: -distance(), behavior: 'smooth' });
		});

		next.addEventListener('click', function () {
			list.scrollBy({ left: distance(), behavior: 'smooth' });
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('[data-ssc-product-gallery]').forEach(initProductGallery);
		document.querySelectorAll('.ssc-single-tabs').forEach(initTabs);
		document.querySelectorAll('.ssc-single-product .related.products').forEach(initRelatedCarousel);
	});
}());