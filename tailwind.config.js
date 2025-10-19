/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        // Quantum Brand Colors
        quantum: {
          // Primary - Electric Blue
          50: '#E6F7FF',
          100: '#BAE7FF',
          200: '#91D5FF',
          300: '#69C0FF',
          400: '#40A9FF',
          500: '#00BFFF', // Main
          600: '#0099CC',
          700: '#007399',
          800: '#004D66',
          900: '#002633',
        },
        void: {
          // Secondary - Cosmic Purple
          50: '#F3EBFF',
          100: '#E0CCFF',
          200: '#CEADFF',
          300: '#BB8EFF',
          400: '#A86FFF',
          500: '#9D5CFF', // Main
          600: '#7E4ACC',
          700: '#5E3799',
          800: '#3F2566',
          900: '#1F1233',
        },
        photon: {
          // Accent - Energetic Gold
          50: '#FFFAEB',
          100: '#FFF3C6',
          200: '#FFEB99',
          300: '#FFE066',
          400: '#FFD633',
          500: '#FFD700', // Main
          600: '#CCAC00',
          700: '#998100',
          800: '#665600',
          900: '#332B00',
        },
        // Dark Matter Backgrounds
        space: {
          50: '#E8E8EC',
          100: '#D1D1D9',
          200: '#A3A3B3',
          300: '#75758D',
          400: '#474767',
          500: '#0A0A0F', // Deepest
          600: '#080809',
          700: '#060607',
          800: '#040404',
          900: '#020202',
        },
        matter: {
          DEFAULT: '#15151F',
          light: '#1F1F2E',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
        display: ['Geist', 'Inter', 'sans-serif'],
        mono: ['Geist Mono', 'Consolas', 'monospace'],
      },
      fontSize: {
        'xs': ['0.75rem', { lineHeight: '1rem' }],
        'sm': ['0.875rem', { lineHeight: '1.25rem' }],
        'base': ['1rem', { lineHeight: '1.5rem' }],
        'lg': ['1.125rem', { lineHeight: '1.75rem' }],
        'xl': ['1.25rem', { lineHeight: '1.75rem' }],
        '2xl': ['1.5rem', { lineHeight: '2rem' }],
        '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
        '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
        '5xl': ['3rem', { lineHeight: '1' }],
        '6xl': ['3.75rem', { lineHeight: '1' }],
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
        '128': '32rem',
      },
      borderRadius: {
        'quantum': '12px',
        'quantum-lg': '16px',
        'quantum-xl': '20px',
      },
      boxShadow: {
        'quantum': '0 4px 20px rgba(0, 191, 255, 0.15)',
        'quantum-lg': '0 10px 40px rgba(0, 191, 255, 0.2)',
        'void': '0 4px 20px rgba(157, 92, 255, 0.15)',
        'glow': '0 0 20px rgba(0, 191, 255, 0.4)',
        'glow-purple': '0 0 20px rgba(157, 92, 255, 0.4)',
      },
      animation: {
        'fade-in': 'fadeIn 0.3s ease-in-out',
        'slide-up': 'slideUp 0.3s ease-out',
        'slide-down': 'slideDown 0.3s ease-out',
        'scale-in': 'scaleIn 0.2s ease-out',
        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'glow': 'glow 2s ease-in-out infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(10px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        slideDown: {
          '0%': { transform: 'translateY(-10px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        scaleIn: {
          '0%': { transform: 'scale(0.95)', opacity: '0' },
          '100%': { transform: 'scale(1)', opacity: '1' },
        },
        glow: {
          '0%, 100%': { boxShadow: '0 0 20px rgba(0, 191, 255, 0.2)' },
          '50%': { boxShadow: '0 0 30px rgba(0, 191, 255, 0.4)' },
        },
      },
      backdropBlur: {
        'quantum': '12px',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms')({
      strategy: 'class',
    }),
    require('@tailwindcss/typography'),
  ],
}
