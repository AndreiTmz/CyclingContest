(() => {
    const tabs = Array.from(document.querySelectorAll('[data-preview-tab]'));

    if (tabs.length === 0) {
        return;
    }

    const menuTargets = {
        registration: 'register',
        competitors: 'competitors',
        results: 'results',
        routes: 'media'
    };

    const activateTab = (activeTab, moveFocus = false) => {
        const activeMenuTarget = menuTargets[activeTab.dataset.previewTab];

        tabs.forEach((tab) => {
            const isActive = tab === activeTab;
            const panel = document.getElementById(tab.getAttribute('aria-controls'));

            tab.setAttribute('aria-selected', String(isActive));
            tab.tabIndex = isActive ? 0 : -1;
            tab.classList.toggle('is-selected', isActive);
            panel.hidden = !isActive;
        });

        document.querySelectorAll('[data-preview-menu]').forEach((menuItem) => {
            menuItem.classList.toggle('is-current', menuItem.dataset.previewMenu === activeMenuTarget);
        });

        if (moveFocus) {
            activeTab.focus();
        }
    };

    tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => activateTab(tab));
        tab.addEventListener('keydown', (event) => {
            let nextIndex = index;

            if (event.key === 'ArrowRight') {
                nextIndex = (index + 1) % tabs.length;
            } else if (event.key === 'ArrowLeft') {
                nextIndex = (index - 1 + tabs.length) % tabs.length;
            } else if (event.key === 'Home') {
                nextIndex = 0;
            } else if (event.key === 'End') {
                nextIndex = tabs.length - 1;
            } else {
                return;
            }

            event.preventDefault();
            activateTab(tabs[nextIndex], true);
        });
    });
})();