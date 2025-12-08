/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      "./public/**/*.php",
      "./src/**/*.php"
    ],
    theme: {
      extend: {
        colors: {
          brand: {
            DEFAULT: "#4C6FFF",
            dark: "#3246A8"
          }
        },
        borderRadius: {
          xl: "0.9rem"
        }
      }
    },
    plugins: []
  };
  