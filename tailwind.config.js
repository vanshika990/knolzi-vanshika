// tailwind.config.js
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './resources/**/*.css',
    ],
    theme: {
        extend: {
            colors: {
                // Theme colors mapped to CSS variables
                primary: {
                    DEFAULT: 'var(--color-primary)',
                    light: 'var(--color-primary-light)',
                    dark: 'var(--color-primary-dark)',
                },
                secondary: {
                    DEFAULT: 'var(--color-secondary)',
                    light: 'var(--color-secondary-light)',
                    dark: 'var(--color-secondary-dark)',
                },
                dark: {
                    DEFAULT: 'var(--color-dark)',
                    light: 'var(--color-dark-light)',
                    lighter: 'var(--color-dark-lighter)',
                },
                light: {
                    DEFAULT: 'var(--color-light)',
                    dark: 'var(--color-light-dark)',
                    darker: 'var(--color-light-darker)',
                },
                button: {
                    DEFAULT: 'var(--color-button)',
                    hover: 'var(--color-button-hover)',
                    primary: 'var(--color-button-primary)',
                    'primary-hover': 'var(--color-button-primary-hover)',
                },
                bg: {
                    primary: 'var(--color-bg-primary)',
                    secondary: 'var(--color-bg-secondary)',
                    light: 'var(--color-bg-light)',
                    dark: 'var(--color-bg-dark)',
                },
                text: {
                    primary: 'var(--color-text-primary)',
                    secondary: 'var(--color-text-secondary)',
                    light: 'var(--color-text-light)',
                    white: 'var(--color-text-white)',
                },
                success: 'var(--color-success)',
                warning: 'var(--color-warning)',
                error: 'var(--color-error)',
                info: 'var(--color-info)',
                border: {
                    DEFAULT: 'var(--color-border)',
                    light: 'var(--color-border-light)',
                    dark: 'var(--color-border-dark)',
                },
                shadow: {
                    DEFAULT: 'var(--color-shadow)',
                    light: 'var(--color-shadow-light)',
                    dark: 'var(--color-shadow-dark)',
                },
            },
            fontFamily: {
                sans: 'var(--font-sans)',
                mono: 'var(--font-mono)',
            },
            fontSize: {
                xs: 'var(--text-xs)',
                sm: 'var(--text-sm)',
                base: 'var(--text-base)',
                lg: 'var(--text-lg)',
                xl: 'var(--text-xl)',
                '2xl': 'var(--text-2xl)',
                '3xl': 'var(--text-3xl)',
                '4xl': 'var(--text-4xl)',
                '5xl': 'var(--text-5xl)',
                '6xl': 'var(--text-6xl)',
            },
            fontWeight: {
                light: 'var(--font-light)',
                normal: 'var(--font-normal)',
                medium: 'var(--font-medium)',
                semibold: 'var(--font-semibold)',
                bold: 'var(--font-bold)',
                extrabold: 'var(--font-extrabold)',
            },
            lineHeight: {
                tight: 'var(--leading-tight)',
                normal: 'var(--leading-normal)',
                relaxed: 'var(--leading-relaxed)',
            },
            spacing: {
                xs: 'var(--spacing-xs)',
                sm: 'var(--spacing-sm)',
                md: 'var(--spacing-md)',
                lg: 'var(--spacing-lg)',
                xl: 'var(--spacing-xl)',
                '2xl': 'var(--spacing-2xl)',
            },
            borderRadius: {
                sm: 'var(--radius-sm)',
                md: 'var(--radius-md)',
                lg: 'var(--radius-lg)',
                xl: 'var(--radius-xl)',
                '2xl': 'var(--radius-2xl)',
                full: 'var(--radius-full)',
            },
            transitionDuration: {
                fast: 'var(--transition-fast)',
                normal: 'var(--transition-normal)',
                slow: 'var(--transition-slow)',
            },
            zIndex: {
                dropdown: 'var(--z-dropdown)',
                sticky: 'var(--z-sticky)',
                fixed: 'var(--z-fixed)',
                'modal-backdrop': 'var(--z-modal-backdrop)',
                modal: 'var(--z-modal)',
                popover: 'var(--z-popover)',
                tooltip: 'var(--z-tooltip)',
            },
            backgroundImage: {
                'gradient-primary': 'var(--gradient-primary)',
                'gradient-secondary': 'var(--gradient-secondary)',
                'gradient-light': 'var(--gradient-light)',
            },
            keyframes: {
                'fade-in': {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'scale-in': {
                    '0%': { transform: 'scale(0.95)', opacity: '0' },
                    '100%': { transform: 'scale(1)', opacity: '1' },
                },
            },
            animation: {
                'fade-in': 'fade-in 0.6s ease-out',
                'scale-in': 'scale-in 0.3s ease-out',
            },
        },
        screens: {
            'xs': '475px',
            'sm': '640px',
            'md': '768px',
            'lg': '1024px',
            'xl': '1280px',
            '2xl': '1536px',
        },
    },
    plugins: [],
};
