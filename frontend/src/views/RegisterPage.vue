<template>
  <div class="register-wrapper">
    <!-- Logo at bottom right corner -->
    <div class="bottom-right-logo">
      <img src="@/assets/logo.png" alt="Logo" class="logo-image" />
    </div>

    <!-- Background layers with slideshow -->
    <div class="bg-layers">
      <div class="bg-base"></div>
      <div class="bg-overlay"></div>
      <div class="bg-particles" id="particles-js"></div>
    </div>

    <!-- Centered registration box with neon border -->
    <div class="register-box">
      <!-- LEFT: Brand Section -->
      <div class="left-panel">
        <div class="brand-content">
          <div class="logo">
            <i class="fas fa-home"></i>
          </div>
          <h1 class="brand-title">ResidenceSora</h1>
          <p class="brand-tagline">Temui Kediaman Baharu Anda dengan Tenang</p>
          <p class="brand-description">
            Sertai komuniti pelajar dan pemilik rumah sekitar UiTM Tapah yang berpuas hati dengan ResidenceSora.
          </p>
          <button @click="goToLogin" class="login-btn">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Log Masuk
          </button>
        </div>
      </div>

      <!-- RIGHT: Registration Form -->
      <div class="right-panel">
        <div class="form-container">
          <!-- Sticky Header -->
          <div class="sticky-header">
            <!-- Role Toggle -->
            <div class="role-toggle">
              <button
                class="toggle-btn"
                :class="{ active: form.role === 'tenant' }"
                @click="setRole('tenant')"
              >
                <i class="fas fa-user"></i>
                Tenant
              </button>
              <button
                class="toggle-btn"
                :class="{ active: form.role === 'landlord' }"
                @click="setRole('landlord')"
              >
                <i class="fas fa-building"></i>
                Landlord
              </button>
            </div>

            <div class="form-header">
              <h2 class="form-heading">
                {{ form.role === 'tenant' ? 'Daftar sebagai Tenant (Penyewa)' : 'Daftar sebagai Landlord (Tuan Rumah)' }}
              </h2>
              <p class="form-subheading">Daftar akaun anda untuk mula menggunakan.</p>
            </div>
          </div>

          <!-- Scrollable Form Content -->
          <div class="form-content">
            <form @submit.prevent="submitRegister" class="register-form">
<!-- Name -->
<div class="input-group">
  <label for="name" class="input-label">Nama Penuh</label>
  <div class="input-wrapper">
    <svg class="input-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#00bfa5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M20 21v-2a4 4 0 0 0-3-3.87"></path>
      <path d="M4 21v-2a4 4 0 0 1 3-3.87"></path>
      <circle cx="12" cy="7" r="4"></circle>
    </svg>
    <input
      id="name"
      v-model="form.name"
      type="text"
      placeholder="Sila masukkan nama penuh anda"
      required
    />
  </div>
</div>

<!-- Email -->
<div class="input-group">
  <label for="email" class="input-label">Alamat E-mel</label>
  <div class="input-wrapper">
    <svg class="input-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#00bfa5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M4 4h16v16H4z"></path>
      <polyline points="22,6 12,13 2,6"></polyline>
    </svg>
    <input
      id="email"
      v-model="form.email"
      type="email"
      placeholder="you@example.com"
      required
    />
  </div>
</div>

<!-- Phone Number -->
<div class="input-group">
  <label for="phone" class="input-label">Nombor Telefon</label>
  <div class="input-wrapper">
    <svg class="input-icon-svg" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#00bfa5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
      <path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 3 5.18 2 2 0 0 1 5 3h3a2 2 0 0 1 2 1.72c.12.83.37 1.64.72 2.41a2 2 0 0 1-.45 2.11l-1.27 1.27a16 16 0 0 0 7.29 7.29l1.27-1.27a2 2 0 0 1 2.11-.45c.77.35 1.58.6 2.41.72A2 2 0 0 1 22 16.92z"/>
    </svg>
    <input
      id="phone"
      v-model="form.phone"
      type="tel"
      placeholder="011-2345678"
      required
    />
  </div>
</div>

<!-- Resident Area -->
<div class="input-group">
  <label for="resident" class="input-label">Taman Perumahan</label>
  <div class="input-wrapper">
    <svg class="input-icon-svg" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#00bfa5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
      <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/>
      <circle cx="12" cy="10" r="3"/>
    </svg>
    <input
      id="resident"
      v-model="form.resident"
      type="text"
      :placeholder="form.role === 'tenant' ? 'e.g. Taman Heliconia' : 'e.g. Perumahan Heliconia'"
      required
    />
  </div>
</div>

<!-- Gender -->
<div class="input-group">
  <label for="sex" class="input-label">Jantina</label>
  <div class="input-wrapper">
    <svg class="input-icon-svg" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#00bfa5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
      <circle cx="12" cy="12" r="4"/>
      <path d="M20 4v4h-4M4 20v-4h4"/>
      <path d="M16 8l-8 8"/>
    </svg>
    <select id="sex" v-model="form.sex" required>
      <option disabled value="">Pilih Jantina Anda</option>
      <option value="male">Lelaki</option>
      <option value="female">Perempuan</option>
    </select>
  </div>
</div>

<!-- Password -->
<div class="input-group">
  <label for="password" class="input-label">Kata Laluan</label>
  <div class="input-wrapper">
    <svg class="input-icon-svg" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#00bfa5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
    </svg>
    <input
      id="password"
      v-model="form.password"
      :type="showPassword ? 'text' : 'password'"
      placeholder="Cipta kata laluan yang selamat"
      required
    />
    <button type="button" class="password-toggle" @click="showPassword = !showPassword">
      <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" class="eye-icon" fill="none" stroke="#00bfa5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M1 1l22 22M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a17.7 17.7 0 0 1 3.07-4.14M9.9 9.9a3 3 0 0 0 4.24 4.24"></path>
      </svg>
      <svg v-else xmlns="http://www.w3.org/2000/svg" class="eye-icon" fill="none" stroke="#00bfa5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
        <circle cx="12" cy="12" r="3"/>
      </svg>
    </button>
  </div>
</div>

<!-- Confirm Password -->
<div class="input-group">
  <label for="password_confirmation" class="input-label">Sahkan Kata Laluan</label>
  <div class="input-wrapper">
    <svg class="input-icon-svg" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#00bfa5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
    </svg>
    <input
      id="password_confirmation"
      v-model="form.password_confirmation"
      :type="showConfirmPassword ? 'text' : 'password'"
      placeholder="Sahkan Kata Laluan"
      required
    />
    <button type="button" class="password-toggle" @click="showConfirmPassword = !showConfirmPassword">
      <svg v-if="showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="eye-icon" fill="none" stroke="#00bfa5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M1 1l22 22M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a17.7 17.7 0 0 1 3.07-4.14M9.9 9.9a3 3 0 0 0 4.24 4.24"></path>
      </svg>
      <svg v-else xmlns="http://www.w3.org/2000/svg" class="eye-icon" fill="none" stroke="#00bfa5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
        <circle cx="12" cy="12" r="3"/>
      </svg>
    </button>
  </div>
</div>


              <!-- Terms Agreement -->
              <div class="terms-group">
                <label class="terms-label">
                  <input type="checkbox" v-model="agreeTerms" required />
                  <span class="checkmark"></span> Saya bersetuju untuk meneruskan proses pendaftaran akaun saya di ResidenceSora.
                </label>
              </div>

              <!-- Register Button -->
              <button 
                type="submit" 
                class="register-btn"
                :disabled="loading || !agreeTerms"
              >
                <span class="btn-text">
                  {{ loading ? 'Akaun sedang didaftarkan...' : (form.role === 'tenant' ? 'Daftar sebagai Tenant' : 'Daftar sebagai Landlord') }}
                </span>
                <div class="btn-spinner" v-if="loading"></div>
              </button>
            </form>

            <!-- Login Link -->
            <div class="login-section">
              <p class="login-text">
                Sudah daftar akaun?
                <router-link to="/login" class="login-link">Log masuk di sini</router-link>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import router from '../router'
import { ref, onMounted } from 'vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import registerImg from '../assets/register.png'


export default {
  setup() {
    const form = ref({
      name: '',
      email: '',
      phone: '',
      resident: '',
      sex: '',
      role: 'tenant',
      password: '',
      password_confirmation: ''
    })

    const loading = ref(false)
    const showPassword = ref(false)
    const showConfirmPassword = ref(false)
    const agreeTerms = ref(false)

    // 🔹 Simple reusable toast function
    const notify = (message, type = 'success') => {
      const opts = { autoClose: 3000, position: 'bottom-right', theme: 'colored' }
      type === 'success' ? toast.success(message, opts) : toast.error(message, opts)
    }

    const setRole = (role) => {
      form.value.role = role
      notify(`Switched to ${role === 'tenant' ? 'Tenant' : 'Landlord'} mode`, 'success')
    }

    // 🔹 Enhanced Register Function
    const submitRegister = async () => {
      loading.value = true
      try {
        await axios.post('/api/register', form.value)
        notify('🎉 Registered successfully! Redirecting to login...', 'success')
        setTimeout(() => router.push('/login'), 1500)
      } catch (err) {
        const msg = err.response?.data?.message ?? 'Registration failed. Please try again.'
        notify(`❌ ${msg}`, 'error')
      } finally {
        loading.value = false
      }
    }

    const goToLogin = () => {
      router.push('/login')
    }

    onMounted(() => {
      if (typeof particlesJS !== 'undefined') {
        particlesJS('particles-js', {
          particles: {
            number: { value: 30, density: { enable: true, value_area: 800 } },
            color: { value: "#ffffff" },
            opacity: { value: 0.5, random: true },
            size: { value: 3, random: true },
            line_linked: { enable: true, distance: 150, color: "#ffffff", opacity: 0.4, width: 1 },
            move: { enable: true, speed: 2, direction: "none", random: true }
          }
        })
      }
    })

    return {
      form,
      loading,
      showPassword,
      showConfirmPassword,
      agreeTerms,
      setRole,
      submitRegister,
      goToLogin,
    }
  },
}
</script>


<style scoped>

:root {
  --primary-gradient: linear-gradient(135deg, #00b8a3 0%, #007f73 100%);
  --primary-gradient-hover: linear-gradient(135deg, #00d4b4 0%, #007f73 100%);
  --bg-gradient: linear-gradient(135deg, #052a2d 0%, #0d3b3b 100%);
  --bg-gradient-dark: linear-gradient(135deg, #031a1c 0%, #082a2a 100%);
  --text-primary: #1a1a1a;
  --text-secondary: #666;
  --text-light: rgba(255, 255, 255, 0.9);
  --border-color: #e0e0e0;
  --shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
  --shadow-light: 0 10px 30px rgba(0, 0, 0, 0.08);
  --glass-bg: rgba(255, 255, 255, 0.1);
  --glass-border: rgba(255, 255, 255, 0.1);
  --neon-glow: 0 0 10px #00d4b4, 0 0 20px #00d4b4 inset;
}

/* ===================================================== */
/* BOTTOM RIGHT LOGO */
.bottom-right-logo {
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
/* GLOBAL STYLES - Consistent with Login Page */
.register-wrapper {
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  background: var(--bg-gradient);
  margin: 0;
  transition: background 0.3s ease;
  /* Neon border effect */
  box-shadow: var(--neon-glow);
  border: 0px solid #00d4b4;
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

.bg-particles {
  position: absolute;
  inset: 0;
}

/* Enhanced Registration Box with Neon Border */
.register-box {
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
  border: 1px solid rgba(255, 255, 255, 0.15);
  
  /* Neon Glow Effect */
  box-shadow: 
    0 0 0 1px rgba(0, 212, 180, 0.3),
    0 0 20px rgba(0, 212, 180, 0.4),
    0 0 40px rgba(0, 212, 180, 0.1),
    inset 0 0 20px rgba(255, 255, 255, 0.05);
  
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

/* ===================================================== */
/* LEFT PANEL - Enhanced Gradient */
.left-panel {
  position: relative;
  background: linear-gradient(135deg, rgba(0,77,64,0.8), rgba(0,191,165,0.8)),
              url('@/assets/register.png') center/cover no-repeat;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  padding: 60px 40px;
  overflow: hidden;
  color: white;
}


.left-panel::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
  animation: float 6s ease-in-out infinite;
}

@keyframes float {
  0%, 100% { transform: translate(0, 0) rotate(0deg); }
  50% { transform: translate(-10px, -10px) rotate(1deg); }
}

.brand-content {
  position: relative;
  z-index: 2;
  max-width: 400px;
}

.logo {
  font-size: 4rem;
  color: white;
  margin-bottom: 25px;
  text-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  filter: drop-shadow(0 0 10px rgba(0, 191, 165, 0.5));
}

.brand-title {
  font-size: 2.8rem;
  color: white;
  font-weight: 800;
  margin-bottom: 15px;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
  background: linear-gradient(135deg, #ffffff 0%, #e0f7fa 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
}

.brand-tagline {
  color: rgba(255, 255, 255, 0.95);
  font-size: 1.3rem;
  font-weight: 600;
  margin-bottom: 20px;
  line-height: 1.4;
}

.brand-description {
  color: rgba(255, 255, 255, 0.8);
  font-size: 1rem;
  line-height: 1.6;
  margin-bottom: 40px;
  max-width: 350px;
  margin-left: auto;
  margin-right: auto;
}

.login-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 14px 28px;
  background: rgba(255, 255, 255, 0.15);
  color: white;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 12px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
}

.login-btn:hover {
  background: rgba(255, 255, 255, 0.25);
  border-color: rgba(255, 255, 255, 0.5);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

/* ===================================================== */
/* RIGHT PANEL - Enhanced Form with Fixed Scroll */
.right-panel {
  padding: 0;
  background: rgba(255, 255, 255, 0.95);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.form-container {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
}

/* Sticky Header */
.sticky-header {
  position: sticky;
  top: 0;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  z-index: 100;
  padding: 30px 40px 20px;
  border-bottom: 1px solid #e8e8e8;
}

/* Fixed Role Toggle */
.role-toggle {
  display: flex;
  background: #f8f9fa;
  border-radius: 12px;
  padding: 6px;
  margin-bottom: 20px;
  border: 1px solid #e8e8e8;
  transition: all 0.3s ease;
}

.toggle-btn {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 16px;
  background: transparent;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  color: #666;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.toggle-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
  transition: left 0.5s;
}

.toggle-btn:hover::before {
  left: 100%;
}

.toggle-btn.active {
  background: linear-gradient(135deg, #00bfa5 0%, #004d40 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(0, 191, 165, 0.4);
  transform: translateY(-1px);
}

.toggle-btn:not(.active):hover {
  background: rgba(0, 191, 165, 0.1);
  color: #00bfa5;
  transform: translateY(-1px);
}

/* Form Header */
.form-header {
  margin-bottom: 0;
  text-align: center;
}

.form-heading {
  font-size: 1.8rem;
  color: #004d40;
  font-weight: 700;
  margin-bottom: 6px;
  transition: all 0.3s ease;
}

.form-subheading {
  color: #666;
  font-size: 0.95rem;
  transition: all 0.3s ease;
}

/* Scrollable Form Content */
.form-content {
  flex: 1;
  overflow-y: auto;
  padding: 0 40px 30px;
  scrollbar-width: thin;
  scrollbar-color: #00bfa5 #f1f1f1;
}

.form-content::-webkit-scrollbar {
  width: 6px;
}

.form-content::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.form-content::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #00bfa5 0%, #004d40 100%);
  border-radius: 10px;
}

.form-content::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, #00dfd8 0%, #007c6c 100%);
}

/* Enhanced Form Styles */
.register-form {
  width: 100%;
  padding-top: 10px;
}

.input-group {
  margin-bottom: 20px;
}

.input-label {
  display: block;
  margin-bottom: 6px;
  color: #444;
  font-weight: 600;
  font-size: 0.9rem;
}

.input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.input-icon {
  position: absolute;
  left: 15px;
  color: #00bfa5;
  font-size: 1rem;
  z-index: 2;
  transition: all 0.3s ease;
}

.input-group input,
.input-group select {
  width: 100%;
  padding: 14px 15px 14px 45px;
  border: 2px solid #e8e8e8;
  border-radius: 12px;
  font-size: 0.95rem;
  background: #fafafa;
  font-family: inherit;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.input-group input:focus,
.input-group select:focus {
  outline: none;
  border-color: #00bfa5;
  background: white;
  box-shadow: 
    0 0 0 4px rgba(0, 191, 165, 0.1),
    0 4px 12px rgba(0, 191, 165, 0.1);
  transform: translateY(-1px);
}

.input-wrapper:focus-within .input-icon {
  color: #004d40;
  transform: scale(1.1);
}

.password-toggle {
  position: absolute;
  right: 12px;
  background: none;
  border: none;
  color: #777;
  cursor: pointer;
  font-size: 0.9rem;
  padding: 4px;
  transition: all 0.3s ease;
}

.password-toggle:hover {
  color: #00bfa5;
  transform: scale(1.1);
}

/* Terms Agreement */
.terms-group {
  margin: 25px 0;
}

.terms-label {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  color: #666;
  font-size: 0.9rem;
  line-height: 1.4;
  cursor: pointer;
}

.terms-label input {
  display: none;
}

.checkmark {
  width: 18px;
  height: 18px;
  border: 2px solid #ddd;
  border-radius: 4px;
  background: white;
  position: relative;
  flex-shrink: 0;
  transition: all 0.3s ease;
}

.terms-label input:checked + .checkmark {
  background: #00bfa5;
  border-color: #00bfa5;
  box-shadow: 0 0 10px rgba(0, 191, 165, 0.4);
}

.terms-label input:checked + .checkmark::after {
  content: '✓';
  position: absolute;
  color: white;
  font-size: 12px;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.terms-link {
  color: #00bfa5;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
}

.terms-link:hover {
  color: #004d40;
  text-decoration: underline;
}

/* Enhanced Register Button with Glow Effect */
.register-btn {
  width: 100%;
  padding: 16px;
  background: linear-gradient(135deg, #00bfa5 0%, #004d40 100%);
  color: white;
  border: none;
  border-radius: 12px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  margin-top: 10px;
  position: relative;
  overflow: hidden;
  
  /* Initial glow */
  box-shadow: 
    0 4px 15px rgba(0, 191, 165, 0.4),
    0 0 20px rgba(0, 191, 165, 0.2);
}

.register-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
  transition: left 0.5s;
}

.register-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 
    0 8px 25px rgba(0, 191, 165, 0.6),
    0 0 30px rgba(0, 191, 165, 0.4);
}

.register-btn:hover:not(:disabled)::before {
  left: 100%;
}

.register-btn:active:not(:disabled) {
  transform: translateY(0);
}

.register-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
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

/* Login Section */
.login-section {
  text-align: center;
  margin-top: 25px;
  padding-top: 20px;
  border-top: 1px solid #e8e8e8;
}

.login-text {
  color: #666;
  font-size: 0.95rem;
}

.login-link {
  color: #00bfa5;
  text-decoration: none;
  font-weight: 600;
  margin-left: 0.3rem;
  transition: all 0.3s ease;
}

.login-link:hover {
  color: #004d40;
  text-decoration: underline;
}


.input-icon-svg {
  position: absolute;
  left: 14px;
  width: 20px;
  height: 20px;
  stroke: #00bfa5;
  z-index: 2;
  pointer-events: none;
  transition: 0.3s ease;
}

.eye-icon {
  width: 22px;
  height: 22px;
}

.input-wrapper:focus-within .input-icon-svg {
  stroke: #00796b;
  transform: scale(1.1);
}

.input-icon-svg {
  position: absolute;
  left: 15px;
  width: 20px;
  height: 20px;
  stroke: #00bfa5;
  z-index: 2;
  pointer-events: none;
  transition: 0.3s ease;
}

.input-wrapper:focus-within .input-icon-svg {
  stroke: #00796b;
  transform: scale(1.1);
}

.eye-icon {
  width: 22px;
  height: 22px;
}

.input-group input,
.input-group select {
  padding-left: 45px; /* ensure space for SVG icons */
}



/* ===================================================== */
/* RESPONSIVE DESIGN */
@media (max-width: 1024px) {
  .bottom-right-logo {
    bottom: 30px;
    right: 30px;
  }

  .logo-image {
    height: 90px;
  }

  .register-box {
    grid-template-columns: 1fr;
    height: auto;
    max-width: 500px;
  }

  .left-panel {
    padding: 40px 30px;
    height: 300px;
  }

  .logo {
    font-size: 3rem;
    margin-bottom: 20px;
  }

  .brand-title {
    font-size: 2.2rem;
  }

  .brand-tagline {
    font-size: 1.1rem;
  }

  .sticky-header {
    padding: 25px 30px 15px;
  }

  .form-content {
    padding: 0 30px 25px;
  }
}

@media (max-width: 480px) {
  .bottom-right-logo {
    bottom: 20px;
    right: 20px;
  }

  .logo-image {
    height: 70px;
  }

  .register-wrapper {
    padding: 15px;
  }

  .register-box {
    border-radius: 20px;
  }

  .left-panel {
    padding: 30px 20px;
    height: 250px;
  }

  .logo {
    font-size: 2.5rem;
  }

  .brand-title {
    font-size: 1.8rem;
  }

  .brand-tagline {
    font-size: 1rem;
  }

  .brand-description {
    font-size: 0.9rem;
  }

  .sticky-header {
    padding: 20px 25px 15px;
  }

  .form-content {
    padding: 0 25px 20px;
  }

  .form-heading {
    font-size: 1.6rem;
  }

  .role-toggle {
    flex-direction: column;
    gap: 8px;
  }
}

:deep(.Toastify__toast--success) {
  background: linear-gradient(135deg, #00bfa5, #007f73);
  color: #fff;
  font-weight: 600;
}

:deep(.Toastify__toast--error) {
  background: linear-gradient(135deg, #ff4e4e, #b00020);
  color: #fff;
  font-weight: 600;
}

:deep(.Toastify__progress-bar) {
  background: rgba(255, 255, 255, 0.8);
}

</style>