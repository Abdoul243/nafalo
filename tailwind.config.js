import defaultTheme from 'tailwindcss/defaultTheme'

export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    DEFAULT: '#0ea5a4',
                    dark: '#0b7284'
                }
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            }
        }
    },
    plugins: [],
}
