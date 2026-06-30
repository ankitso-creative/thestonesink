(function () {
	'use strict';

	var header = document.querySelector('.ssc-header');
	var searchToggles = document.querySelectorAll('.ssc-search-toggle');
	var searchPanel = document.getElementById('sscHeaderSearch');
	var searchInput = document.getElementById('ssc-header-search-field');

	function updateStickyState() {
		if (!header) {
			return;
		}

		header.classList.toggle('is-stuck', window.scrollY > 8);
	}

	function setSearch(open) {
		if (!searchPanel) {
			return;
		}

		searchPanel.hidden = !open;
		searchToggles.forEach(function (toggle) {
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});

		if (open && searchInput) {
			window.setTimeout(function () {
				searchInput.focus();
			}, 80);
		}
	}

	function closeSiblingDropdowns(toggle) {
		var parentMenu = toggle.closest('ul');

		if (!parentMenu) {
			return;
		}

		parentMenu.querySelectorAll(':scope > .ssc-dropdown > .dropdown-menu.show').forEach(function (menu) {
			if (menu.previousElementSibling !== toggle) {
				menu.classList.remove('show');
				menu.previousElementSibling.setAttribute('aria-expanded', 'false');
			}
		});
	}

	window.addEventListener('scroll', updateStickyState, { passive: true });
	updateStickyState();

	searchToggles.forEach(function (toggle) {
		toggle.addEventListener('click', function () {
			setSearch(searchPanel ? searchPanel.hidden : false);
		});
	});

	document.addEventListener('keydown', function (event) {
		if ('Escape' === event.key) {
			setSearch(false);
		}
	});

	document.querySelectorAll('.ssc-dropdown > .dropdown-toggle').forEach(function (toggle) {
		toggle.addEventListener('click', function (event) {
			var submenu = toggle.nextElementSibling;

			if (!submenu || !submenu.classList.contains('dropdown-menu')) {
				return;
			}

			if (window.innerWidth >= 992 || toggle.closest('.ssc-mobile-menu')) {
				event.preventDefault();
				closeSiblingDropdowns(toggle);
				submenu.classList.toggle('show');
				toggle.setAttribute('aria-expanded', submenu.classList.contains('show') ? 'true' : 'false');
			}
		});
	});
}());
