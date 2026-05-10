/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./vendor/livewire/flux-pro/resources/views/**/*.blade.php",
        "./vendor/livewire/flux/resources/views/**/*.blade.php",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
};
