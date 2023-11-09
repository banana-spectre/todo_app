/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        rammetto : ['"Rammetto One"', 'sans-serif'],
        roboto_medium : ['Roboto', 'sans-serif'],
      }
    },
  },
  plugins: [
    require("@tailwindcss/forms")
  ],
}