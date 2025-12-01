<template>
  <div class="login-wrapper" :class="{ 'dark-mode': darkMode }">
    <!-- Logo at top left corner -->
    <div class="top-left-logo">
      <img src="@/assets/logo.png" alt="Logo" class="logo-image" />
    </div>

    <!-- Background layers -->
    <div class="bg-layers">
      <div class="bg-base"></div>
      <div class="bg-overlay"></div>
    </div>

    <!-- Centered login box -->
    <div class="login-box">
      <!-- LEFT: Slideshow Section -->
      <div class="left-panel">
        <div class="slideshow-container">
          <div class="slideshow">
<div class="slides" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
  <div
    v-for="(img, i) in slideshowImages"
    :key="i"
    class="slide-item"
  >
    <img :src="img" class="slide" alt="Luxury residence" />
    <div class="slide-overlay"></div>
  </div>
</div>

          </div>

          <!-- Brand overlay -->
          <div class="brand-overlay">
            <div class="logo">
              <i class="fas fa-home"></i>
            </div>
            <h1 class="brand-title">SoraResidence</h1>
            <p class="brand-tagline">
              Rumah Sewa Pelajar, Semuanya Di Sini.
            </p>
          </div>

          <!-- Slide indicators -->
          <div class="slide-indicators">
            <button
              v-for="(img, i) in slideshowImages"
              :key="i"
              class="indicator"
              :class="{ active: i === currentSlide }"
              @click="currentSlide = i"
            ></button>
          </div>
        </div>
      </div>

      <!-- RIGHT: Login Form -->
      <div class="right-panel">
        <div class="form-container">
          <div class="form-header">
            <h2 class="form-heading">Selamat Datang</h2>
            <p class="form-subheading">Log masuk ke akaun anda</p>
          </div>

          <!-- ✅ Begin Form -->
          <form @submit.prevent="submitLogin" class="login-form">
            <!-- Email Input with SVG Icon -->
            <div class="input-group">
              <label for="email">Alamat E-mel</label>
              <div class="input-wrapper">
                <!-- 📧 SVG Email Icon -->
                <svg
                  class="input-icon"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  width="20"
                  height="20"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.8"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  aria-hidden="true"
                >
                  <rect x="3" y="5" width="18" height="14" rx="2" ry="2" />
                  <polyline points="3,7 12,13 21,7" />
                </svg>

                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  placeholder="you@example.com"
                  required
                  autocomplete="email"
                  @focus="focusInput"
                  @blur="blurInput"
                />

                <div class="input-focus-ring"></div>
              </div>
            </div>

            <!-- Password Input -->
            <div class="input-group">
              <label for="password">Kata Laluan</label>
              <div class="input-wrapper">
                <!-- 🔒 SVG Lock Icon -->
                <svg
                  class="input-icon"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  width="20"
                  height="20"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.8"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  aria-hidden="true"
                >
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                  <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                </svg>

                <input
                  id="password"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  placeholder="Masukkan kata laluan anda"
                  required
                  autocomplete="current-password"
                  @focus="focusInput"
                  @blur="blurInput"
                />

                <!-- 👁 SVG Eye Toggle Button -->
                <button
                  type="button"
                  class="password-toggle"
                  @click="togglePassword"
                  :title="showPassword ? 'Sembunyikan kata laluan' : 'Lihat kata laluan'"
                >
                  <svg
                    v-if="!showPassword"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="22"
                    height="22"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z" />
                    <circle cx="12" cy="12" r="3" />
                  </svg>

                  <svg
                    v-else
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="22"
                    height="22"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path
                      d="M17.94 17.94A10.97 10.97 0 0 1 12 19c-6 0-10-7-10-7 .9-1.6 2.1-3.1 3.6-4.3"
                    />
                    <path d="M1 1l22 22" />
                    <path
                      d="M9.53 9.53A3 3 0 0 0 12 15a3 3 0 0 0 3-3c0-.5-.13-.98-.36-1.4"
                    />
                  </svg>
                </button>

                <div class="input-focus-ring"></div>
              </div>
              <small class="password-hint">
                Klik ikon mata untuk lihat kata laluan.
              </small>
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              class="login-btn"
              :disabled="loading"
              @mouseenter="hoverButton"
              @mouseleave="leaveButton"
            >
              <span class="btn-text">
                {{ loading ? "Cubaan Log Masuk..." : "Log masuk" }}
              </span>
              <div class="btn-spinner" v-if="loading"></div>
              <div class="btn-glow" v-if="!loading"></div>
            </button>
          </form>
          <!-- ✅ End Form -->

          <div class="divider">
            <span>Atau teruskan dengan</span>
          </div>

          <!-- Only Google Sign-in -->
          <div class="social-auth">
            <button @click="googleSignIn" class="social-btn google-btn">
              <svg
                class="google-logo"
                viewBox="0 0 24 24"
                width="20"
                height="20"
              >
                <path
                  fill="#4285F4"
                  d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                />
                <path
                  fill="#34A853"
                  d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                />
                <path
                  fill="#FBBC05"
                  d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                />
                <path
                  fill="#EA4335"
                  d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                />
              </svg>
              <span class="social-text">Log masuk dengan Google</span>
            </button>
          </div>

          <div class="signup-section">
            <p class="signup-text">
              Tiada akaun?
              <router-link to="/register" class="signup-link">
                Daftar Disini
              </router-link>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import axios from 'axios'
import { ref, onMounted, onUnmounted } from 'vue'
import router from '../router'
import CryptoJS from 'crypto-js'

export default {
  setup() {
    const form = ref({ email: '', password: '' })
    const loading = ref(false)
    const showPassword = ref(false)
    // REMOVED: darkMode ref

    // Slideshow images
    const slideshowImages = ref([
      new URL('../assets/saujanaIndah.png', import.meta.url).href,
      new URL('../assets/heliconia.png', import.meta.url).href,
      new URL('../assets/wallagonia.png', import.meta.url).href,
      new URL('../assets/uniresidence.png', import.meta.url).href,
    ])

    const currentSlide = ref(0)
    let slideInterval = null

    const nextSlide = () => {
      currentSlide.value = (currentSlide.value + 1) % slideshowImages.value.length
    }

    // Input focus effects
    const focusInput = (event) => {
      const inputWrapper = event.target.closest('.input-wrapper')
      if (inputWrapper) inputWrapper.classList.add('focused')
    }

    const blurInput = (event) => {
      const inputWrapper = event.target.closest('.input-wrapper')
      if (inputWrapper) inputWrapper.classList.remove('focused')
    }

    // Button hover effects
    const hoverButton = (event) => {
      if (!loading.value) event.target.classList.add('hover-glow')
    }

    const leaveButton = (event) => {
      event.target.classList.remove('hover-glow')
    }

    // REMOVED: toggleTheme function

    // 👁 Password visibility toggle
    const togglePassword = () => {
      showPassword.value = !showPassword.value
    }

    // 🔒 Login submission
    const submitLogin = async () => {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      if (!emailPattern.test(form.value.email.trim())) {
        return showNotification('Alamat e-mel tidak sah.', 'error')
      }
      if (form.value.password.trim().length < 6) {
        return showNotification('Kata laluan mesti sekurang-kurangnya 6 aksara.', 'error')
      }

      loading.value = true
      try {
        const cleanData = {
          email: form.value.email.trim(),
          password: form.value.password.trim(),
        }

        const res = await axios.post('/api/login', cleanData, {
          headers: { 'Content-Type': 'application/json' },
          timeout: 10000,
        })

        const token = res.data.token
        const user = res.data.user

        // Encrypt token before storing
        const secretKey = import.meta.env.VITE_CRYPTO_KEY || 'SORA_DEV_KEY'
        const encryptedToken = CryptoJS.AES.encrypt(token, secretKey).toString()

        sessionStorage.setItem('token', encryptedToken)
        sessionStorage.setItem('user', JSON.stringify(user))

        showNotification('Log masuk berjaya! Mengalihkan...', 'success')
        setTimeout(() => router.push('/home'), 800)
      } catch (err) {
        if (err.code === 'ECONNABORTED') {
          showNotification('Sambungan tamat masa. Sila cuba lagi.', 'error')
        } else {
          const msg = err.response?.data?.message || 'Log masuk gagal. Sila cuba lagi.'
          showNotification(msg, 'error')
        }
      } finally {
        loading.value = false
      }
    }

    // ✅ Toast Notification
    const showNotification = (message, type) => {
      const opts = { autoClose: 3000, position: 'bottom-right' }
      type === 'success' ? toast.success(message, opts) : toast.error(message, opts)
    }

    // 🌐 Google OAuth
    const googleSignIn = () => {
      const backendURL = import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000'
      window.location.href = `${backendURL}/auth/google`
    }

    // Lifecycle
    onMounted(() => {
      slideInterval = setInterval(nextSlide, 4000)
    })

    onUnmounted(() => {
      if (slideInterval) clearInterval(slideInterval)
    })

    return {
      form,
      loading,
      showPassword,
      slideshowImages,
      currentSlide,
      focusInput,
      blurInput,
      hoverButton,
      leaveButton,
      togglePassword,
      submitLogin,
      googleSignIn,
    }
  },
}
</script>

<style scoped>
/* ===================================================== */
/* GLOBAL STYLES & VARIABLES */
:root {
  --primary-gradient: linear-gradient(135deg, #00b8a3 0%, #007f73 100%);
  --primary-gradient-hover: linear-gradient(135deg, #00d4b4 0%, #007f73 100%);
  --bg-gradient: linear-gradient(135deg, #052a2d 0%, #0d3b3b 100%);
  --text-primary: #222222;
  --text-secondary: #444444;
  --text-light: rgba(255, 255, 255, 0.9);
  --border-color: #e0e0e0;
  --shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
  --shadow-light: 0 10px 30px rgba(0, 0, 0, 0.08);
  --glass-bg: rgba(255, 255, 255, 0.1);
  --glass-border: rgba(255, 255, 255, 0.1);
}

/* ===================================================== */
/* BOTTOM RIGHT LOGO */
.top-left-logo {
  position: fixed;
  bottom: 5px;
  right: 10px;
  z-index: 1000;
  animation: fadeIn 0.8s ease-out;
}

.logo-image {
  height: 120px;
  width: auto;
  object-fit: contain;
  filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
  transition: transform 0.3s ease, filter 0.3s ease;
}

.logo-image:hover {
  transform: scale(1.05);
  filter: drop-shadow(0 6px 12px rgba(0, 184, 163, 0.4));
}

/* ===================================================== */
/* BASE STYLES - REMOVED OUTER GLOW */
.login-wrapper {
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  background: var(--bg-gradient);
  margin: 0;
}

/* Background Layers */
.bg-layers {
  position: absolute;
  inset: 0;
  z-index: -1;
}

.bg-base {
  position: absolute;
  inset: 0;
  background: url('https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=1950&q=80') center/cover no-repeat;
  filter: blur(12px) brightness(0.3) saturate(1.4);
  transform: scale(1.1);
}

.bg-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, 
    rgba(10, 140, 111, 0.15) 0%, 
    rgba(10, 77, 60, 0.15) 50%, 
    rgba(8, 122, 95, 0.15) 100%);
}

/* ===================================================== */
/* LOGIN BOX - UPDATED GLOW EFFECT */
.login-box {
  display: grid;
  grid-template-columns: 1.2fr 0.8fr;
  width: 95%;
  max-width: 1100px;
  height: 650px;
  background: rgba(255, 255, 255, 0.08);
  border-radius: 24px;
  overflow: hidden;
  position: relative;
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  
  /* Updated Glow Effect - Only around login box */
  box-shadow: 
    0 8px 25px rgba(0, 191, 165, 0.3),
    0 0 0 1px rgba(0, 212, 180, 0.2),
    0 0 15px rgba(0, 212, 180, 0.2);
  
  animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ===================================================== */
/* LEFT PANEL - Slideshow with Enhanced Effects */
.left-panel {
  position: relative;
  background: linear-gradient(135deg, #0a4d3c 0%, #087a5f 100%);
  overflow: hidden;
}

.slideshow-container {
  position: relative;
  width: 100%;
  height: 100%;
}

.slideshow {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;
}

.slides {
  position: relative;
  width: 100%;
  height: 100%;
  display: flex;
  transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.slide-item {
  position: relative;
  flex: 0 0 100%;
  width: 100%;
  height: 100%;
}

.slide {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.slide-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to bottom, 
    transparent 0%, 
    rgba(10, 77, 60, 0.3) 50%, 
    rgba(10, 77, 60, 0.7) 100%);
}

/* Brand overlay on slideshow */
.brand-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  padding: 40px;
  z-index: 10;
  animation: fadeIn 1s ease-out 0.3s both;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.logo {
  font-size: 3.5rem;
  color: white;
  margin-bottom: 20px;
  text-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  animation: bounceIn 1s ease-out 0.5s both;
}

@keyframes bounceIn {
  0% {
    opacity: 0;
    transform: scale(0.3);
  }
  50% {
    opacity: 1;
    transform: scale(1.05);
  }
  70% {
    transform: scale(0.9);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

.brand-title {
  font-size: 2.5rem;
  color: white;
  font-weight: 700;
  margin-bottom: 10px;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.brand-tagline {
  color: rgba(255, 255, 255, 0.9);
  font-size: 1.1rem;
  max-width: 350px;
  line-height: 1.5;
  text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
  animation: fadeIn 1s ease-out 0.8s both;
}

/* Slide Indicators */
.slide-indicators {
  position: absolute;
  bottom: 25px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
  z-index: 20;
}

.indicator {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  border: none;
  background: rgba(255, 255, 255, 0.4);
  cursor: pointer;
  transition: all 0.3s ease;
}

.indicator.active {
  background: rgba(255, 255, 255, 0.9);
  transform: scale(1.2);
}

.indicator:hover {
  background: rgba(255, 255, 255, 0.7);
}

/* ===================================================== */
/* RIGHT PANEL - Pure White Login Form with Dark Text */
.right-panel {
  padding: 2.5rem 3rem;
  background: #ffffff;
  display: flex;
  flex-direction: column;
  justify-content: center;
  overflow: hidden;
  border-radius: 0 16px 16px 0;
}

.form-container {
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.form-header {
  margin-bottom: 2rem;
  text-align: center;
}

.form-heading {
  font-size: 2rem;
  color: #222222;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.form-subheading {
  color: #444444;
  font-size: 1rem;
}

/* Form Styles */
.login-form {
  width: 100%;
}

.input-group {
  margin-bottom: 1rem;
}

.input-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #222222;
  font-weight: 600;
  font-size: 0.95rem;
}

.input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  overflow: visible !important;
  isolation: isolate;
}

.input-icon {
  position: absolute;
  left: 15px;
  color: #00b8a3;
  font-size: 1.1rem;
  z-index: 2;
  transition: color 0.3s ease;
}

.input-group input {
  width: 100%;
  padding: 14px 50px 14px 50px;
  border: 2px solid #e0e0e0;
  border-radius: 12px;
  font-size: 1rem;
  background: #f9f9f9;
  font-family: inherit;
  transition: all 0.3s ease;
  color: #222222;
}

.input-group input:focus {
  outline: none;
  border-color: #00b8a3;
  background: white;
  box-shadow: 0 0 0 4px rgba(0, 184, 163, 0.1);
  padding-right: 50px;
}

.input-focus-ring {
  position: absolute;
  inset: 0;
  border-radius: 12px;
  pointer-events: none;
  opacity: 0;
  transition: all 0.3s ease;
}

.input-wrapper.focused .input-focus-ring {
  opacity: 1;
  box-shadow: 0 0 0 2px rgba(0, 184, 163, 0.3);
}

/* Password Toggle Button */
.password-toggle {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  background: transparent;
  border: none;
  color: #666;
  cursor: pointer;
  font-size: 1.1rem;
  padding: 8px;
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  transition: all 0.3s ease;
}

.password-toggle:hover {
  background: rgba(0, 184, 163, 0.15);
  color: #007f73;
  transform: translateY(-50%) scale(1.1);
}

.password-hint {
  display: block;
  margin-top: 0.5rem;
  color: #666666;
  font-size: 0.8rem;
  opacity: 0.7;
}

/* Login Button */
.login-btn {
  width: 100%;
  padding: 16px;
  background: linear-gradient(135deg, #00bfa5 0%, #007f73 100%);
  color: #ffffff;
  border: none;
  border-radius: 12px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  margin-top: 0.5rem;
  box-shadow: 0 4px 15px rgba(0, 184, 163, 0.3);
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Fix text visibility */
.btn-text {
  position: relative;
  z-index: 3;
  color: #ffffff;
}

/* Adjust glow effect */
.btn-glow {
  position: absolute;
  inset: 0;
  border-radius: 12px;
  background: radial-gradient(circle at center, rgba(0, 184, 163, 0.4), transparent 70%);
  opacity: 0.8;
  filter: blur(8px);
  z-index: 1;
  pointer-events: none;
}

/* Subtle hover animation */
.login-btn:hover:not(:disabled) {
  transform: scale(1.03);
  box-shadow: 0 8px 20px rgba(0, 191, 165, 0.45);
  background: linear-gradient(135deg, #00d4b4 0%, #008f79 100%);
}

.login-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}


.btn-spinner {
  width: 20px;
  height: 20px;
  border: 2px solid transparent;
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
}

@keyframes spin {
  0% { transform: translateY(-50%) rotate(0deg); }
  100% { transform: translateY(-50%) rotate(360deg); }
}

/* Divider */
.divider {
  display: flex;
  align-items: center;
  margin: 1.5rem 0;
  color: #666666;
  font-size: 0.9rem;
}

.divider::before,
.divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: #e0e0e0;
}

.divider span {
  padding: 0 15px;
}

/* Google Sign-in Button */
.social-auth {
  display: flex;
  justify-content: center;
}

.social-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  width: 100%;
  padding: 14px;
  background: white;
  border: 2px solid #e0e0e0;
  border-radius: 12px;
  font-weight: 600;
  color: #222222;
  cursor: pointer;
  font-family: inherit;
  transition: all 0.3s ease;
  box-shadow: var(--shadow-light);
}

.social-btn:hover {
  border-color: #00b8a3;
  background: #f5fffd;
  transform: scale(1.02);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.google-logo {
  width: 20px;
  height: 20px;
}

/* Signup Section */
.signup-section {
  text-align: center;
  margin-top: 1.5rem;
}

.signup-text {
  color: #444444;
  font-size: 0.95rem;
}

.signup-link {
  color: #00b8a3;
  text-decoration: none;
  font-weight: 600;
  margin-left: 0.3rem;
  transition: color 0.3s ease;
}

.signup-link:hover {
  text-decoration: underline;
  color: #007f73;
}

/* ===================================================== */
/* RESPONSIVE DESIGN */
@media (max-width: 1024px) {
  .top-left-logo {
    bottom: 30px;
    right: 30px;
  }

  .logo-image {
    height: 90px;
  }

  .login-box {
    grid-template-columns: 1fr;
    height: auto;
    max-width: 500px;
    margin: 20px;
  }

  .left-panel {
    height: 300px;
  }

  .brand-title {
    font-size: 2.2rem;
  }

  .right-panel {
    padding: 2rem 2.5rem;
    border-radius: 0 0 16px 16px;
  }

  /* Replace slideshow with static image on mobile */
  .slideshow {
    display: none;
  }
  
  .left-panel {
    background: url('https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?ixlib=rb-4.0.3&w=1000&q=80') center/cover no-repeat;
  }
}

@media (max-width: 480px) {
  .top-left-logo {
    bottom: 20px;
    right: 20px;
  }

  .logo-image {
    height: 70px;
  }

  .login-wrapper {
    padding: 10px;
  }

  .login-box {
    border-radius: 16px;
    margin: 10px;
  }

  .left-panel {
    height: 220px;
  }

  .logo {
    font-size: 2.5rem;
  }

  .brand-title {
    font-size: 1.8rem;
  }

  .brand-tagline {
    font-size: 0.95rem;
  }

  .right-panel {
    padding: 1.5rem 2rem;
  }

  .form-heading {
    font-size: 1.7rem;
  }

  .input-group input {
    padding: 14px 46px 14px 46px;
  }

  /* Remove shadows on mobile for better performance */
  .login-box {
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
  }

  .social-btn,
  .login-btn {
    box-shadow: none;
  }

  .social-btn:hover,
  .login-btn:hover:not(:disabled) {
    transform: none;
  }
}

.input-wrapper.focused .input-icon {
  color: #00b8a3;
  transform: scale(1.1);
  transition: all 0.25s ease;
}

.btn-text {
  position: relative;
  z-index: 2;
  color: #fff;
}
.btn-glow {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, #00bfa5 0%, #007f73 100%);
  filter: blur(10px);
  opacity: 0.5;
  border-radius: 12px;
  z-index: 1;
}

</style>
