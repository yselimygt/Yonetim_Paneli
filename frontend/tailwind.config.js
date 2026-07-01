/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#F49F20',     // InnoMIS Orange
        secondary: '#DA2C2E',   // InnoMIS Red
        background: '#0C0A09',  // Stone 950 (Warm Dark)
        surface: '#1C1917',     // Stone 900
        accent: {
          purple: '#8B5CF6',    // Purple (Tech)
          cyan: '#F43F5E',      // Rose 500 (Warm Accent)
          green: '#EAB308'      // Yellow 500
        },
        text: {
          main: '#FAFAFA',      // Neutral 50
          secondary: '#A1A1AA', // Zinc 400
          meta: '#71717A'       // Zinc 500
        }
      },
      fontFamily: {
        sans: ['Manrope', 'sans-serif'],
        display: ['Space Grotesk', 'sans-serif'],
      },
      backgroundImage: {
        'gradient-primary': 'linear-gradient(to right, #f49f20, #da2c2e)',
      },
      animation: {
        'fade-in': 'fadeIn 0.2s ease-out',
        'scale-up': 'scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1)',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        scaleUp: {
          '0%': { transform: 'scale(0.95)', opacity: '0' },
          '100%': { transform: 'scale(1)', opacity: '1' },
        }
      }
    },
  },
  plugins: [],
}
