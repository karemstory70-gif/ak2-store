(() => {
    const preferenceKey = 'ak2-theme';
    const root = document.documentElement;
    const colorMeta = document.querySelector('meta[name="theme-color"]');
    const systemPreference = window.matchMedia?.('(prefers-color-scheme: light)');

    let savedPreference = null;

    try {
        const storedPreference = window.localStorage.getItem(preferenceKey);

        if (storedPreference === 'light' || storedPreference === 'dark') {
            savedPreference = storedPreference;
        }
    } catch {
        // The theme still works when browser storage is unavailable.
    }

    if (savedPreference === null) {
        try {
            const cookiePreference = document.cookie
                .split(';')
                .map((cookie) => cookie.trim())
                .find((cookie) => cookie.startsWith(`${preferenceKey}=`))
                ?.split('=')[1];

            if (cookiePreference === 'light' || cookiePreference === 'dark') {
                savedPreference = cookiePreference;
            }
        } catch {
            // A blocked cookie does not prevent in-page theme switching.
        }
    }

    const getSystemTheme = () => systemPreference?.matches ? 'light' : 'dark';

    const syncThemeControls = () => {
        const isDark = root.dataset.theme !== 'light';
        const nextThemeLabel = isDark ? 'Switch to light mode' : 'Switch to dark mode';

        document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
            button.setAttribute('aria-label', nextThemeLabel);
            button.setAttribute('title', nextThemeLabel);
        });

        if (colorMeta) {
            colorMeta.setAttribute('content', isDark ? '#080D18' : '#F5F6F8');
        }
    };

    const applyTheme = (theme, persist = false) => {
        root.dataset.theme = theme;

        if (persist) {
            savedPreference = theme;

            try {
                window.localStorage.setItem(preferenceKey, theme);
            } catch {
                // Keep the selected theme for this page even without storage.
            }

            try {
                document.cookie = `${preferenceKey}=${theme}; Max-Age=31536000; Path=/; SameSite=Lax`;
            } catch {
                // Keep the selected theme for this page even without cookies.
            }
        }

        syncThemeControls();
    };

    applyTheme(savedPreference ?? getSystemTheme());

    document.addEventListener('DOMContentLoaded', () => {
        syncThemeControls();

        document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                applyTheme(root.dataset.theme === 'light' ? 'dark' : 'light', true);
            });
        });
    });

    systemPreference?.addEventListener('change', () => {
        if (savedPreference === null) {
            applyTheme(getSystemTheme());
        }
    });
})();
