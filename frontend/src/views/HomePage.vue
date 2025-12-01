<template>
  <div class="home-page">
    <!-- ✅ Header navigation -->
    <HeaderNav v-if="user" :user="user" @logout="logout" />

    <main v-if="user" class="main-content">
      <!-- SECTION 1: Enhanced LED Welcome Sign -->
      <div class="user-info">
        <div class="led-band-container">
          <div class="led-scrolling-track">
            <div class="led-scrolling-content">
              <span class="led-text">
                <svg class="house-icon" width="28" height="28" viewBox="0 0 32 32" fill="none">
                  <path d="M16 6L6 14V26H12V20H20V26H26V14L16 6Z" fill="currentColor"/>
                  <circle cx="16" cy="12" r="2" fill="white"/>
                </svg>
                Selamat Datang, {{ user.name }}! 
              </span>
              <span class="led-text">
                <svg class="house-icon" width="28" height="28" viewBox="0 0 32 32" fill="none">
                  <path d="M16 6L6 14V26H12V20H20V26H26V14L16 6Z" fill="currentColor"/>
                  <circle cx="16" cy="12" r="2" fill="white"/>
                </svg>
                Selamat Datang, {{ user.name }}! 
              </span>
              <span class="led-text">
                <svg class="house-icon" width="28" height="28" viewBox="0 0 32 32" fill="none">
                  <path d="M16 6L6 14V26H12V20H20V26H26V14L16 6Z" fill="currentColor"/>
                  <circle cx="16" cy="12" r="2" fill="white"/>
                </svg>
               Selamat Datang, {{ user.name }}! 
              </span>
              <span class="led-text">
                <svg class="house-icon" width="28" height="28" viewBox="0 0 32 32" fill="none">
                  <path d="M16 6L6 14V26H12V20H20V26H26V14L16 6Z" fill="currentColor"/>
                  <circle cx="16" cy="12" r="2" fill="white"/>
                </svg>
                Selamat Datang, {{ user.name }}! 
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION 2: Enhanced Welcome Banner -->
      <section class="welcome-banner">
        <div class="banner-background"></div>
        <div class="banner-overlay"></div>
        <div class="banner-vignette"></div>
        <div class="banner-content">
          <div class="logo-container">
            <div class="logo-glow-ring"></div>
            <svg class="floating-logo" width="100" height="100" viewBox="0 0 100 100" fill="none">
              <circle cx="50" cy="50" r="46" fill="white" stroke="#17a2b8" stroke-width="3"/>
              <path d="M35 40 L50 30 L65 40 V65 H35 V40 Z" fill="#14919b" opacity="0.9"/>
              <rect x="42" y="50" width="16" height="15" fill="#0d7377"/>
              <circle cx="44" cy="45" r="2" fill="white"/>
              <circle cx="56" cy="45" r="2" fill="white"/>
            </svg>
          </div>
          <h2 class="welcome-text fade-in-up">
            <span class="gradient-headline">Welcome to Sora Residence</span>
            <span class="headline-subtitle">where comfort meets community</span>
          </h2>
          <p class="welcome-message fade-in-up-delay">Thank you for being part of our growing home-sharing network.</p>
        </div>
      </section>

<!-- SECTION 3: Highly Attractive Interactive Guide -->
<section class="how-to-use">
  <div class="section-header">
    <h2 class="section-title fade-in">How to Use This Platform</h2>
    <p class="section-subtitle">Your complete step-by-step guide to success</p>
  </div>
  
  <div class="guide-cards">
    <!-- Tenant Guide Card with Original Icon -->
    <div class="guide-card tenant-card">
      <div class="card-icon-wrapper">
        <div class="icon-glow-ring"></div>
        <svg class="card-icon" width="100" height="100" viewBox="0 0 100 100" fill="none">
          <circle cx="50" cy="50" r="48" fill="url(#tenantGradient)" opacity="0.2"/>
          <circle cx="50" cy="50" r="48" stroke="url(#tenantStroke)" stroke-width="3"/>
          <path d="M50 28L62 35V50L50 57L38 50V35L50 28Z" fill="#ffffff" stroke="#e0f7fa" stroke-width="2.5"/>
          <path d="M38 50V65L50 72L62 65V50" stroke="#ffffff" stroke-width="2.5"/>
          <circle cx="50" cy="78" r="8" fill="#ffffff" stroke="#e0f7fa" stroke-width="2.5"/>
          <defs>
            <linearGradient id="tenantGradient" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#14919b"/>
              <stop offset="100%" stop-color="#40e0d0"/>
            </linearGradient>
            <linearGradient id="tenantStroke" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#14919b"/>
              <stop offset="50%" stop-color="#40e0d0"/>
              <stop offset="100%" stop-color="#17a2b8"/>
            </linearGradient>
          </defs>
        </svg>
      </div>
      
      <h3 class="card-title">Tenant Guide</h3>
      <p class="card-subtitle">Discover your perfect home and move in with confidence</p>
      
      <ul class="steps-list">
        <li class="step-item">
          <span class="step-badge">
            <span class="badge-number">1</span>
            <span class="badge-glow"></span>
          </span>
          <span class="step-content">
            <span class="step-icon">🔍</span>
            <span class="step-text"><strong>Browse</strong> available properties</span>
          </span>
        </li>
        <li class="step-item">
          <span class="step-badge">
            <span class="badge-number">2</span>
            <span class="badge-glow"></span>
          </span>
          <span class="step-content">
            <span class="step-icon">📝</span>
            <span class="step-text"><strong>Submit</strong> your rental application</span>
          </span>
        </li>
        <li class="step-item">
          <span class="step-badge">
            <span class="badge-number">3</span>
            <span class="badge-glow"></span>
          </span>
          <span class="step-content">
            <span class="step-icon">🤝</span>
            <span class="step-text"><strong>Connect</strong> with verified landlords</span>
          </span>
        </li>
        <li class="step-item">
          <span class="step-badge">
            <span class="badge-number">4</span>
            <span class="badge-glow"></span>
          </span>
          <span class="step-content">
            <span class="step-icon">🏡</span>
            <span class="step-text"><strong>Move in</strong> to your new home</span>
          </span>
        </li>
      </ul>
    </div>

    <!-- Landlord Guide Card with Original Icon -->
    <div class="guide-card landlord-card">
      <div class="card-icon-wrapper">
        <div class="icon-glow-ring"></div>
        <svg class="card-icon" width="100" height="100" viewBox="0 0 100 100" fill="none">
          <circle cx="50" cy="50" r="48" fill="url(#landlordGradient)" opacity="0.2"/>
          <circle cx="50" cy="50" r="48" stroke="url(#landlordStroke)" stroke-width="3"/>
          <path d="M32 45L50 32L68 45V72H32V45Z" fill="#ffffff" stroke="#e0f7fa" stroke-width="2.5"/>
          <rect x="42" y="55" width="8" height="17" fill="#14919b"/>
          <rect x="55" y="55" width="8" height="17" fill="#14919b"/>
          <circle cx="44" cy="48" r="2.5" fill="#40e0d0"/>
          <circle cx="56" cy="48" r="2.5" fill="#40e0d0"/>
          <defs>
            <linearGradient id="landlordGradient" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#17a2b8"/>
              <stop offset="100%" stop-color="#40e0d0"/>
            </linearGradient>
            <linearGradient id="landlordStroke" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#17a2b8"/>
              <stop offset="50%" stop-color="#40e0d0"/>
              <stop offset="100%" stop-color="#14919b"/>
            </linearGradient>
          </defs>
        </svg>
      </div>
      
      <h3 class="card-title">Landlord Guide</h3>
      <p class="card-subtitle">List properties and grow your rental business effortlessly</p>
      
      <ul class="steps-list">
        <li class="step-item">
          <span class="step-badge">
            <span class="badge-number">1</span>
            <span class="badge-glow"></span>
          </span>
          <span class="step-content">
            <span class="step-icon">📋</span>
            <span class="step-text"><strong>List</strong> your property details</span>
          </span>
        </li>
        <li class="step-item">
          <span class="step-badge">
            <span class="badge-number">2</span>
            <span class="badge-glow"></span>
          </span>
          <span class="step-content">
            <span class="step-icon">✅</span>
            <span class="step-text"><strong>Screen</strong> potential tenants</span>
          </span>
        </li>
        <li class="step-item">
          <span class="step-badge">
            <span class="badge-number">3</span>
            <span class="badge-glow"></span>
          </span>
          <span class="step-content">
            <span class="step-icon">💳</span>
            <span class="step-text"><strong>Manage</strong> bookings & payments</span>
          </span>
        </li>
        <li class="step-item">
          <span class="step-badge">
            <span class="badge-number">4</span>
            <span class="badge-glow"></span>
          </span>
          <span class="step-content">
            <span class="step-icon">📈</span>
            <span class="step-text"><strong>Grow</strong> your rental portfolio</span>
          </span>
        </li>
      </ul>
    </div>
  </div>
</section>

<!-- SECTION 4: Enhanced Resident Listing Preview with Glassmorphism Info -->
<section class="resident-listings">
  <h2 class="section-title">Featured Properties</h2>
  <div class="listings-container">
    <div v-for="(property, index) in properties" :key="property.id" 
         class="property-card scroll-reveal" :class="index % 2 === 0 ? 'image-left' : 'image-right'">
      
      <!-- Property Image (unchanged) -->
      <div class="property-image">
        <div class="image-wrapper">
          <div class="property-image-bg" :class="property.image"></div>
        </div>
      </div>
      
      <!-- Updated Property Info with Glassmorphism -->
      <div class="property-info">
        <!-- 1. Elegant Property Title -->
        <h3 class="property-title-elegant">
          <span class="title-text">{{ property.name }}</span>
          <span class="title-glow"></span>
        </h3>

        <!-- 2. Glass Pill Chips -->
        <div class="property-chips">
          <div class="glass-chip built-chip">
            <span class="chip-icon">📅</span>
            <span class="chip-label">Built:</span>
            <span class="chip-value">{{ property.year }}</span>
            <div class="chip-glow"></div>
          </div>

          <div class="glass-chip residents-chip">
            <span class="chip-icon">👥</span>
            <span class="chip-label">Residents:</span>
            <span class="chip-value">{{ property.residents }}</span>
            <div class="chip-glow"></div>
          </div>
        </div>

        <!-- 3. Glass Description Container -->
        <div class="glass-description-container">
          <div class="description-shine"></div>
          <p class="property-description-elegant">{{ property.description }}</p>
          <div class="description-gradient-border"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SECTION 5: Enhanced Why Choose Us -->
<section class="why-choose-us">
  <h2 class="section-title-turquoise">Why Choose Us</h2>
  <div class="features-grid-clean">
    <!-- Secure Payments -->
    <div class="feature-item-clean">
      <div class="feature-illustration">
        <svg width="180" height="180" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M90 30L110 40L130 30L145 40V85C145 115 130 130 90 145C50 130 35 115 35 85V40L50 30L70 40L90 30Z" 
                fill="#14919b" opacity="0.15"/>
          <path d="M90 35L108 44L126 35L140 44V85C140 113 126 127 90 140C54 127 40 113 40 85V44L54 35L72 44L90 35Z" 
                stroke="#14919b" stroke-width="4" stroke-linejoin="round"/>
          <circle cx="90" cy="80" r="25" fill="#14919b" opacity="0.2"/>
          <path d="M75 85L85 95L105 70" stroke="#14919b" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
          <rect x="70" y="105" width="40" height="8" rx="4" fill="#14919b" opacity="0.3"/>
        </svg>
      </div>
      <h3 class="feature-title-turquoise">Secure Payments</h3>
      <p class="feature-description-grey">Encrypted transactions with multiple payment options for your peace of mind</p>
    </div>

    <!-- Verified Listings -->
    <div class="feature-item-clean">
      <div class="feature-illustration">
        <svg width="180" height="180" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M60 50L90 30L120 50V120H60V50Z" fill="#14919b" opacity="0.15"/>
          <path d="M65 55L90 37L115 55V115H65V55Z" stroke="#14919b" stroke-width="4" stroke-linejoin="round"/>
          <rect x="80" y="80" width="20" height="35" fill="#14919b" opacity="0.3"/>
          <circle cx="85" cy="68" r="4" fill="#14919b"/>
          <circle cx="105" cy="68" r="4" fill="#14919b"/>
          <circle cx="140" cy="50" r="25" fill="#14919b"/>
          <path d="M130 50L137 57L155 40" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M45 75H55" stroke="#14919b" stroke-width="3" stroke-linecap="round" opacity="0.4"/>
          <path d="M45 85H52" stroke="#14919b" stroke-width="3" stroke-linecap="round" opacity="0.4"/>
        </svg>
      </div>
      <h3 class="feature-title-turquoise">Verified Listings</h3>
      <p class="feature-description-grey">All properties thoroughly vetted and authenticated by our expert team</p>
    </div>

    <!-- Easy Communication -->
    <div class="feature-item-clean">
      <div class="feature-illustration">
        <svg width="180" height="180" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="40" y="50" width="70" height="55" rx="8" fill="#14919b" opacity="0.15"/>
          <path d="M45 55C45 52.2386 47.2386 50 50 50H105C107.761 50 110 52.2386 110 55V95C110 97.7614 107.761 100 105 100H65L45 115V55Z" 
                stroke="#14919b" stroke-width="4"/>
          <circle cx="70" cy="72" r="5" fill="#14919b"/>
          <circle cx="87" cy="72" r="5" fill="#14919b"/>
          <path d="M75 85H95" stroke="#14919b" stroke-width="4" stroke-linecap="round"/>
          <rect x="70" y="65" width="100" height="55" rx="8" fill="white" stroke="#14919b" stroke-width="4"/>
          <circle cx="95" cy="87" r="5" fill="#14919b"/>
          <circle cx="112" cy="87" r="5" fill="#14919b"/>
          <circle cx="129" cy="87" r="5" fill="#14919b"/>
          <path d="M90 102H130" stroke="#14919b" stroke-width="3" stroke-linecap="round" opacity="0.4"/>
        </svg>
      </div>
      <h3 class="feature-title-turquoise">Easy Communication</h3>
      <p class="feature-description-grey">Built-in messaging system for seamless interaction with landlords</p>
    </div>

    <!-- 24/7 Support -->
    <div class="feature-item-clean">
      <div class="feature-illustration">
        <svg width="180" height="180" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="90" cy="90" r="50" fill="#14919b" opacity="0.15"/>
          <circle cx="90" cy="90" r="45" stroke="#14919b" stroke-width="4"/>
          <path d="M75 70C75 65 80 60 90 60C100 60 105 65 105 70C105 75 100 77 95 80C93 81 92 83 92 85V90" 
                stroke="#14919b" stroke-width="4" stroke-linecap="round"/>
          <circle cx="92" cy="105" r="4" fill="#14919b"/>
          <path d="M90 35V45" stroke="#14919b" stroke-width="4" stroke-linecap="round"/>
          <path d="M90 135V145" stroke="#14919b" stroke-width="4" stroke-linecap="round"/>
          <path d="M135 90H145" stroke="#14919b" stroke-width="4" stroke-linecap="round"/>
          <path d="M35 90H45" stroke="#14919b" stroke-width="4" stroke-linecap="round"/>
          <circle cx="125" cy="55" r="15" fill="#14919b" opacity="0.3"/>
          <circle cx="55" cy="125" r="15" fill="#14919b" opacity="0.3"/>
        </svg>
      </div>
      <h3 class="feature-title-turquoise">24/7 Support</h3>
      <p class="feature-description-grey">Round-the-clock customer service and assistance whenever you need it</p>
    </div>
  </div>
</section>

      <!-- SECTION 6: Enhanced Thank You Banner -->
      <section class="thank-you-banner">
        <div class="wave-pattern"></div>
        <div class="thank-you-overlay"></div>
        <div class="thank-you-content">
          <h2 class="thank-you-text fade-in-scale">
            Thank You for Choosing HomeBased Tapah
          </h2>
          <div class="sparkles-container">
            <div v-for="n in 8" :key="n" class="sparkle" :style="getSparkleStyle(n)"></div>
          </div>
        </div>
      </section>
    </main>

    <p v-else class="loading">Loading your account…</p>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import HeaderNav from '../components/HeaderNav.vue'
import CryptoJS from 'crypto-js'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const user = ref(JSON.parse(sessionStorage.getItem('user')))

// Reactive states for animations
const hoverState = reactive({ tenant: false, landlord: false })
const glowState = reactive({})
const zoomState = reactive({})

// Data for sections
const tenantSteps = ref([
  'Browse available properties',
  'Submit rental application',
  'Connect with landlords',
  'Move in comfortably'
])

const landlordSteps = ref([
  'List your property',
  'Screen potential tenants',
  'Manage bookings & payments',
  'Grow your rental business'
])

const properties = ref([
  {
    id: 1,
    name: 'Skyline Residences',
    year: 2022,
    residents: '85-120',
    description: 'Modern luxury apartments with panoramic city views and premium amenities.',
    image: 'skyline'
  },
  {
    id: 2,
    name: 'Garden Villas',
    year: 2020,
    residents: '45-65',
    description: 'Tranquil suburban living with spacious gardens and community facilities.',
    image: 'garden'
  },
  {
    id: 3,
    name: 'Urban Lofts',
    year: 2023,
    residents: '120-150',
    description: 'Contemporary industrial-style lofts in the heart of the city center.',
    image: 'urban'
  },
  {
    id: 4,
    name: 'Lakeside Manor',
    year: 2021,
    residents: '25-40',
    description: 'Exclusive waterfront properties with private access and premium finishes.',
    image: 'lakeside'
  }
])

const features = ref([
  {
    id: 1,
    title: 'Secure Payments',
    description: 'Encrypted transactions with multiple payment options',
    iconComponent: 'ShieldIcon'
  },
  {
    id: 2,
    title: 'Verified Listings',
    description: 'All properties thoroughly vetted and authenticated',
    iconComponent: 'VerifiedIcon'
  },
  {
    id: 3,
    title: 'Easy Communication',
    description: 'Built-in messaging system for seamless interaction',
    iconComponent: 'ChatIcon'
  },
  {
    id: 4,
    title: '24/7 Support',
    description: 'Round-the-clock customer service and assistance',
    iconComponent: 'SupportIcon'
  }
])

// SVG Icon Components
const ShieldIcon = {
  template: `
    <g>
      <path d="M25 10L30 12L35 10L40 12V20C40 30 35 35 30 40C25 35 20 30 20 20V12L25 10Z" 
            fill="#40E0D0" stroke="#87CEFA" stroke-width="2"/>
      <path d="M28 25L32 29L37 24" stroke="white" stroke-width="3" stroke-linecap="round"/>
    </g>
  `
}

const VerifiedIcon = {
  template: `
    <g>
      <circle cx="25" cy="25" r="20" fill="#87CEFA" stroke="#40E0D0" stroke-width="2"/>
      <path d="M20 25L23 28L30 21" stroke="white" stroke-width="3" stroke-linecap="round"/>
      <circle cx="35" cy="15" r="3" fill="#40E0D0"/>
    </g>
  `
}

const ChatIcon = {
  template: `
    <g>
      <path d="M15 15H35V30H20L15 35V15Z" fill="#40E0D0" stroke="#87CEFA" stroke-width="2"/>
      <circle cx="22" cy="22" r="1.5" fill="white"/>
      <circle cx="28" cy="22" r="1.5" fill="white"/>
      <circle cx="25" cy="26" r="1.5" fill="white"/>
      <path d="M25 30V35" stroke="#87CEFA" stroke-width="2"/>
    </g>
  `
}

const SupportIcon = {
  template: `
    <g>
      <circle cx="25" cy="25" r="20" fill="#87CEFA" stroke="#40E0D0" stroke-width="2"/>
      <circle cx="25" cy="20" r="3" fill="white"/>
      <path d="M20 35C20 30 25 28 30 35" stroke="white" stroke-width="2" stroke-linecap="round"/>
      <path d="M15 15L20 20" stroke="#40E0D0" stroke-width="2"/>
      <path d="M35 15L30 20" stroke="#40E0D0" stroke-width="2"/>
    </g>
  `
}

// Animation methods
const hoverCard = (type) => {
  hoverState[type] = true
}

const resetCard = () => {
  Object.keys(hoverState).forEach(key => {
    hoverState[key] = false
  })
}

const glowFeature = (id) => {
  glowState[id] = true
}

const resetGlow = () => {
  Object.keys(glowState).forEach(key => {
    glowState[key] = false
  })
}

const zoomImage = (id) => {
  zoomState[id] = true
}

const resetImage = () => {
  Object.keys(zoomState).forEach(key => {
    zoomState[key] = false
  })
}

const getSparkleStyle = (n) => {
  const size = Math.random() * 6 + 4
  const duration = Math.random() * 3 + 2
  const delay = Math.random() * 2
  const x = Math.random() * 100
  const y = Math.random() * 100
  
  return {
    width: `${size}px`,
    height: `${size}px`,
    left: `${x}%`,
    top: `${y}%`,
    animationDelay: `${delay}s`,
    animationDuration: `${duration}s`
  }
}

// 🔐 Decrypt token (AES)
const decryptToken = () => {
  const encryptedToken = sessionStorage.getItem('token')
  if (!encryptedToken) return null
  try {
    const secretKey = import.meta.env.VITE_CRYPTO_KEY || 'SORA_DEV_KEY'
    const bytes = CryptoJS.AES.decrypt(encryptedToken, secretKey)
    return bytes.toString(CryptoJS.enc.Utf8)
  } catch (e) {
    console.error('❌ Token decryption failed:', e)
    return null
  }
}

// 🧩 Fetch user info from backend
const fetchUser = async () => {
  try {
    const token = decryptToken()
    if (!token) throw new Error('No valid token found')

    const res = await axios.get('/api/user', {
      headers: { Authorization: `Bearer ${token}` },
    })

    user.value = res.data
    sessionStorage.setItem('user', JSON.stringify(res.data))
    console.log('✅ User loaded successfully:', res.data)
  } catch (err) {
    console.error('⚠️ Failed to fetch user:', err)
    sessionStorage.clear()
    router.push('/login')
  }
}

// 🚪 Logout function
const logout = async () => {
  try {
    const token = decryptToken()
    if (token) {
      await axios.post('/api/logout', {}, {
        headers: { Authorization: `Bearer ${token}` },
      })
    }
  } catch (err) {
    console.warn('⚠️ Logout failed:', err)
  } finally {
    sessionStorage.clear()
    router.push('/login')
  }
}

// 🧠 Lifecycle hook: Handle Google redirect & fetch user
onMounted(async () => {
  const token = route.query.token

  if (token) {
    console.log('📦 Google token detected:', token)

    // ✅ Encrypt and store token securely
    const secretKey = import.meta.env.VITE_CRYPTO_KEY || 'SORA_DEV_KEY'
    const encryptedToken = CryptoJS.AES.encrypt(token, secretKey).toString()
    sessionStorage.setItem('token', encryptedToken)

    // ✅ Fetch user immediately using Google token
    await fetchUser()

    // ✅ Clean URL (remove ?token=...)
    router.replace({ path: '/home', query: {} })
    return
  }

  // If no user info yet, try fetching using stored token
  if (!user.value && sessionStorage.getItem('token')) {
    await fetchUser()
  } else if (!sessionStorage.getItem('token')) {
    console.warn('⚠️ No token found — redirecting to login')
    router.push('/login')
  }
})
</script>






<style scoped>
/* ========================================
   GLOBAL & BASE STYLES
======================================== */
.home-page {
  text-align: center;
  margin-top: 0;
  overflow-x: hidden;
}

.loading {
  text-align: center;
  margin-top: 120px;
  font-size: 1.2rem;
  color: #14919b;
}

/* ========================================
   SECTION 1: LED WELCOME SIGN
======================================== */
.user-info {
  padding: 0;
  margin: 0;
  width: 100%;
  max-width: 100%;
  overflow: hidden;
}

.led-band-container {
  width: 100%;
  position: relative;
  background: linear-gradient(135deg, #0a5f62 0%, #0d7377 30%, #14919b 70%, #17a2b8 100%);
  box-shadow: 
    0 4px 15px rgba(13, 115, 119, 0.3),
    inset 0 2px 8px rgba(0, 0, 0, 0.15),
    inset 0 -1px 4px rgba(255, 255, 255, 0.1);
  overflow: hidden;
}

.led-band-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 40%;
  background: linear-gradient(180deg, rgba(255, 255, 255, 0.12) 0%, transparent 100%);
  pointer-events: none;
}

.led-scrolling-track {
  width: 100%;
  overflow: hidden;
  position: relative;
  padding: 2.2rem 0;
}

.led-scrolling-content {
  display: inline-flex;
  white-space: nowrap;
  animation: ledScrollSeamless 24s linear infinite;
  will-change: transform;
}

.led-text {
  display: inline-flex;
  align-items: center;
  gap: 0.8rem;
  font-size: 2.3rem;
  font-weight: 700;
  font-family: 'Poppins', sans-serif;
  padding: 0 5rem;
  color: #ffffff;
  text-shadow: 
    0 2px 6px rgba(0, 0, 0, 0.25),
    0 0 15px rgba(255, 255, 255, 0.15);
  letter-spacing: 0.02em;
}

.house-icon {
  width: 28px;
  height: 28px;
  color: #ffffff;
  filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.25));
  flex-shrink: 0;
}

@keyframes ledScrollSeamless {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

.led-scrolling-track::before,
.led-scrolling-track::after {
  content: '';
  position: absolute;
  top: 0;
  bottom: 0;
  width: 100px;
  pointer-events: none;
  z-index: 2;
}

.led-scrolling-track::before {
  left: 0;
  background: linear-gradient(90deg, #0a5f62 0%, rgba(10, 95, 98, 0) 100%);
}

.led-scrolling-track::after {
  right: 0;
  background: linear-gradient(270deg, #17a2b8 0%, rgba(23, 162, 184, 0) 100%);
}






/* ========================================
   SECTION 2: WELCOME BANNER (HERO)
======================================== */
.welcome-banner {
  position: relative;
  height: 100vh;
  min-height: 600px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.banner-background {
  position: absolute;
  inset: 0;
  background:
    /* 💎 Softer glass effect */
    linear-gradient(
      to bottom,
      rgba(0, 0, 0, 0.25) 0%,      /* soft dark top */
      rgba(0, 0, 0, 0.15) 40%,     /* gentle transition */
      rgba(0, 0, 0, 0.25) 100%     /* soft dark bottom */
    ),
    /* ❗ More subtle turquoise tint */
    linear-gradient(
      135deg,
      rgba(20, 145, 155, 0.18),    /* soft turquoise */
      rgba(23, 162, 184, 0.18)
    ),
    url('@/assets/tapahbackground.png');

  background-size: cover;
  background-position: center;
  background-attachment: scroll; /* Changed from fixed to scroll */
  z-index: 0;
}

.banner-overlay {
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
  z-index: 1;
}

.banner-vignette {
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse at center, transparent 0%, rgba(0, 0, 0, 0.3) 100%);
  z-index: 2;
}

.banner-content {
  position: relative;
  z-index: 10;
  text-align: center;
  color: white;
  max-width: 900px;
  padding: 3rem;
  animation: fadeInContent 1.2s ease-out;
}

@keyframes fadeInContent {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

.logo-container {
  position: relative;
  display: inline-block;
  margin-bottom: 2.5rem;
}

.logo-glow-ring {
  position: absolute;
  inset: -12px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(23, 162, 184, 0.4), transparent 70%);
  animation: pulseGlow 3s ease-in-out infinite;
}

@keyframes pulseGlow {
  0%, 100% { transform: scale(1); opacity: 0.4; }
  50% { transform: scale(1.1); opacity: 0.7; }
}

.floating-logo {
  position: relative;
  animation: floatSmooth 4s ease-in-out infinite;
  filter: drop-shadow(0 8px 25px rgba(23, 162, 184, 0.4));
}

@keyframes floatSmooth {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-12px) rotate(2deg); }
}

.welcome-text {
  font-size: 3.5rem;
  font-weight: 800;
  margin-bottom: 2rem;
  font-family: 'Poppins', sans-serif;
  line-height: 1.2;
}

.gradient-headline {
  display: block;
  background: linear-gradient(135deg, #ffffff 0%, #e0f2fe 50%, #bae6fd 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 4px 20px rgba(255, 255, 255, 0.3);
  margin-bottom: 0.5rem;
}

.headline-subtitle {
  display: block;
  font-size: 1.8rem;
  font-weight: 400;
  opacity: 0.95;
  font-style: italic;
}

.welcome-message {
  font-size: 1.35rem;
  opacity: 0.9;
  font-family: 'Nunito', sans-serif;
  line-height: 1.6;
  max-width: 600px;
  margin: 0 auto;
}

.fade-in-up {
  animation: fadeInUp 1s ease-out 0.3s both;
}

.fade-in-up-delay {
  animation: fadeInUp 1s ease-out 0.6s both;
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}




/* ========================================
   SECTION 3: HIGHLY ATTRACTIVE TENANT & LANDLORD GUIDE
======================================== */
.how-to-use {
  padding: 0;
  margin: 0;
  position: relative;
  overflow: hidden;
  background: #f0f9fa; /* Solid background for cards area */
}

.how-to-use::before,
.how-to-use::after {
  display: none;
}

/* ========================================
   SECTION HEADER - MATCHING SECTION 4 BACKGROUND STYLE
======================================== */
.section-header {
  text-align: center;
  position: relative;
  z-index: 2;
  padding: 5rem 2rem 4rem;
  margin-bottom: 0;
  overflow: hidden;
  background:
    /* Same gradient overlay as Section 4 */
    linear-gradient(
      135deg,
      rgba(255, 255, 255, 0.65) 0%,
      rgba(234, 251, 255, 0.60) 30%,
      rgba(240, 252, 255, 0.58) 70%,
      rgba(255, 255, 255, 0.63) 100%
    ),
    /* Same background image as Section 4 */
    url('@/assets/handshake.png') center/cover fixed;
  background-attachment: fixed;
  background-size: cover;
  background-position: center;
}

.section-header::before,
.section-header::after {
  display: none;
}

/* Main section title */
.section-header .section-title {
  font-size: 3.5rem;
  font-weight: 900;
  padding: 0;
  margin: 0 0 1.25rem;
  color: #14919b;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  text-align: center;
  letter-spacing: -0.04em;
  position: relative;
  z-index: 2;
}

.section-header .section-title::after {
  content: '';
  position: absolute;
  bottom: -15px;
  left: 50%;
  transform: translateX(-50%);
  width: 120px;
  height: 5px;
  background: linear-gradient(90deg, #14919b 0%, #40e0d0 50%, #14919b 100%);
  border-radius: 3px;
}

.section-subtitle {
  font-size: 1.25rem;
  color: #64748b;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  font-weight: 500;
  margin-bottom: 0;
  letter-spacing: -0.01em;
  line-height: 1.7;
}

.title-divider {
  display: none;
}

/* ========================================
   TWO COLUMN LAYOUT WITH SOLID BACKGROUND
======================================== */
.guide-cards {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 6rem;
  max-width: 1600px;
  margin: 0 auto;
  padding: 5rem 5rem 8rem;
  align-items: start;
  position: relative;
  z-index: 2;
  background: transparent; /* Inherits from parent .how-to-use */
}

.guide-cards::before {
  display: none;
}

.cards-divider {
  display: none;
}

/* ========================================
   GUIDE COLUMN - CENTERED WITH STYLIZED HEADER GRAPHIC
======================================== */
.tenant-card,
.landlord-card {
  position: relative;
  background: transparent;
  backdrop-filter: none;
  -webkit-backdrop-filter: none;
  border: none;
  border-radius: 0;
  padding: 0;
  text-align: center;
  transition: none;
  overflow: visible;
  box-shadow: none;
}

.tenant-card .card-glass-layer,
.landlord-card .card-glass-layer,
.tenant-card .card-glow-border,
.landlord-card .card-glow-border {
  display: none;
}

.tenant-card:hover,
.landlord-card:hover {
  transform: none;
}

/* ========================================
   PROMINENT STYLIZED HEADER GRAPHIC
======================================== */
.tenant-card .card-icon-wrapper,
.landlord-card .card-icon-wrapper {
  position: relative;
  display: inline-block;
  margin-bottom: 2rem;
  z-index: 1;
  background: linear-gradient(135deg, #14919b 0%, #40e0d0 100%);
  border-radius: 24px;
  padding: 2rem;
  box-shadow: 
    0 10px 40px rgba(20, 145, 155, 0.25),
    0 4px 12px rgba(20, 145, 155, 0.15);
}

.tenant-card .icon-glow-ring,
.landlord-card .icon-glow-ring {
  position: absolute;
  inset: -20px;
  background: radial-gradient(circle, rgba(20, 145, 155, 0.2), transparent 70%);
  border-radius: 30px;
  animation: iconPulse 4s ease-in-out infinite;
  z-index: -1;
}

@keyframes iconPulse {
  0%, 100% { transform: scale(1); opacity: 0.5; }
  50% { transform: scale(1.15); opacity: 0.8; }
}

.card-icon {
  position: relative;
  display: block;
  width: 100px;
  height: 100px;
  filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.15));
  transition: transform 0.5s ease;
}

.guide-card:hover .card-icon {
  transform: scale(1.1) translateY(-5px);
}

/* ========================================
   LARGE BOLD TITLE WITH BENEFIT SUB-HEADING
======================================== */
.card-title {
  position: relative;
  z-index: 1;
  font-size: 2.75rem;
  font-weight: 900;
  margin-bottom: 1rem;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  letter-spacing: -0.04em;
  display: block;
  color: #1e293b;
  text-align: center;
}

.title-icon {
  display: none;
}

/* Benefit-driven sub-heading */
.card-subtitle {
  font-size: 1.15rem;
  font-weight: 500;
  color: #64748b;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  margin-bottom: 3rem;
  line-height: 1.65;
  letter-spacing: -0.01em;
}

/* ========================================
   STEPS LIST - MAXIMUM WHITE SPACE
======================================== */
.steps-list {
  position: relative;
  z-index: 1;
  list-style: none;
  padding: 0;
  text-align: left;
  display: flex;
  flex-direction: column;
  gap: 2.25rem;
  max-width: 100%;
  margin: 0;
}

/* ========================================
   INTERACTIVE STEP CARDS WITH ARROWHEAD
======================================== */
.step-item {
  display: flex;
  align-items: center;
  gap: 1.75rem;
  padding: 2.25rem 2.5rem 2.25rem 2rem;
  background: #ffffff;
  backdrop-filter: none;
  border: 2px solid rgba(20, 145, 155, 0.1);
  border-radius: 18px;
  position: relative;
  overflow: visible;
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 
    0 2px 12px rgba(20, 145, 155, 0.08),
    0 1px 4px rgba(0, 0, 0, 0.04);
}

.step-item::before {
  display: none;
}

/* Smooth hover effect */
.step-item:hover {
  background: linear-gradient(135deg, #ffffff 0%, #f0f9fa 100%);
  border-color: #14919b;
  transform: translateX(-8px);
  box-shadow: 
    0 12px 32px rgba(20, 145, 155, 0.2),
    0 6px 12px rgba(20, 145, 155, 0.12),
    4px 0 0 0 #14919b inset;
}

/* Arrowhead on right side */
.step-item::after {
  content: '›';
  position: absolute;
  right: 2rem;
  top: 50%;
  transform: translateY(-50%);
  font-size: 2rem;
  font-weight: 700;
  color: #cbd5e1;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.step-item:hover::after {
  color: #14919b;
  right: 1.75rem;
  transform: translateY(-50%) scale(1.3);
}

/* ========================================
   TURQUOISE CIRCLES WITH UNIQUE DESCRIPTIVE ICONS
======================================== */
.step-badge {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 3.5rem;
  height: 3.5rem;
  flex-shrink: 0;
}

.badge-number {
  position: absolute;
  top: -8px;
  right: -8px;
  z-index: 3;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 1.5rem;
  height: 1.5rem;
  color: #ffffff;
  border-radius: 50%;
  font-weight: 800;
  font-size: 0.7rem;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  box-shadow: 0 2px 6px rgba(20, 145, 155, 0.3);
  background: #14919b;
  border: 2px solid #ffffff;
}

.step-item:hover .badge-number {
  transform: scale(1.15);
  box-shadow: 0 4px 12px rgba(20, 145, 155, 0.4);
}

/* Badge glow on hover */
.badge-glow {
  position: absolute;
  inset: -8px;
  border-radius: 50%;
  opacity: 0;
  transition: opacity 0.4s ease;
  filter: blur(12px);
  background: radial-gradient(circle, rgba(20, 145, 155, 0.5), transparent 70%);
  z-index: 0;
}

.step-item:hover .badge-glow {
  opacity: 1;
}

/* ========================================
   UNIQUE STEP ICONS IN TURQUOISE CIRCLES
======================================== */
.step-content {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  flex: 1;
  padding-right: 3rem;
}

/* Large turquoise circle for icon */
.step-icon {
  position: relative;
  z-index: 2;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 3.5rem;
  height: 3.5rem;
  font-size: 1.75rem;
  flex-shrink: 0;
  background: linear-gradient(135deg, #14919b 0%, #40e0d0 100%);
  border-radius: 50%;
  box-shadow: 
    0 4px 16px rgba(20, 145, 155, 0.25),
    0 2px 6px rgba(20, 145, 155, 0.15);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  filter: drop-shadow(0 2px 8px rgba(20, 145, 155, 0.2));
}

.step-item:hover .step-icon {
  transform: scale(1.1) rotate(8deg);
  box-shadow: 
    0 6px 24px rgba(20, 145, 155, 0.35),
    0 3px 10px rgba(20, 145, 155, 0.25);
}

/* Professional sans-serif typography */
.step-text {
  font-family: 'Inter', 'Segoe UI', sans-serif;
  color: #334155;
  font-size: 1.15rem;
  font-weight: 600;
  line-height: 1.6;
  letter-spacing: -0.01em;
}

/* Vibrant turquoise highlighted action verbs */
.step-text strong {
  font-weight: 800;
  color: #14919b;
  background: linear-gradient(135deg, #14919b 0%, #40e0d0 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* ========================================
   RESPONSIVE DESIGN
======================================== */
@media (max-width: 1400px) {
  .guide-cards {
    gap: 5rem;
    padding: 4.5rem 4rem 7rem;
  }
}

@media (max-width: 1200px) {
  .guide-cards {
    gap: 4.5rem;
    padding: 4rem 3.5rem 6rem;
  }
  
  .card-icon {
    width: 90px;
    height: 90px;
  }
}

@media (max-width: 1024px) {
  .guide-cards {
    grid-template-columns: 1fr;
    gap: 6rem;
    padding: 3.5rem 3rem 5rem;
  }
  
  .section-header {
    padding: 4rem 2rem 1.75rem;
  }
  
  .section-header .section-title {
    font-size: 3rem;
  }
  
  .card-title {
    font-size: 2.5rem;
  }
}

@media (max-width: 768px) {
  .section-header {
    padding: 3.5rem 1.5rem 1.5rem;
  }
  
  .section-header .section-title {
    font-size: 2.6rem;
  }
  
  .section-subtitle {
    font-size: 1.15rem;
  }
  
  .guide-cards {
    padding: 3rem 1.75rem 4rem;
    gap: 5rem;
  }
  
  .tenant-card .card-icon-wrapper,
  .landlord-card .card-icon-wrapper {
    padding: 1.75rem;
  }
  
  .card-icon {
    width: 80px;
    height: 80px;
  }
  
  .card-title {
    font-size: 2.3rem;
    margin-bottom: 0.9rem;
  }
  
  .card-subtitle {
    font-size: 1.08rem;
    margin-bottom: 2.5rem;
  }
  
  .step-item {
    padding: 2rem 2.25rem 2rem 1.75rem;
    gap: 1.5rem;
  }
  
  .step-item::after {
    right: 1.75rem;
    font-size: 1.75rem;
  }
  
  .step-item:hover::after {
    right: 1.5rem;
  }
  
  .step-badge {
    width: 3.25rem;
    height: 3.25rem;
  }
  
  .badge-number {
    width: 1.4rem;
    height: 1.4rem;
    font-size: 0.65rem;
  }
  
  .step-icon {
    width: 3.25rem;
    height: 3.25rem;
    font-size: 1.6rem;
  }
  
  .step-content {
    padding-right: 2.75rem;
  }
  
  .step-text {
    font-size: 1.08rem;
  }
}

@media (max-width: 480px) {
  .section-header {
    padding: 3rem 1rem 1.25rem;
  }
  
  .section-header .section-title {
    font-size: 2.2rem;
  }
  
  .section-subtitle {
    font-size: 1.05rem;
  }
  
  .guide-cards {
    padding: 2.5rem 1.25rem 3rem;
    gap: 4.5rem;
  }
  
  .tenant-card .card-icon-wrapper,
  .landlord-card .card-icon-wrapper {
    padding: 1.5rem;
    border-radius: 20px;
  }
  
  .card-icon {
    width: 70px;
    height: 70px;
  }
  
  .card-title {
    font-size: 2rem;
    margin-bottom: 0.8rem;
  }
  
  .card-subtitle {
    font-size: 1rem;
    margin-bottom: 2.25rem;
  }
  
  .steps-list {
    gap: 2rem;
  }
  
  .step-item {
    padding: 1.75rem 2rem 1.75rem 1.5rem;
    gap: 1.25rem;
  }
  
  .step-item:hover {
    transform: translateX(-6px);
  }
  
  .step-item::after {
    right: 1.5rem;
    font-size: 1.6rem;
  }
  
  .step-item:hover::after {
    right: 1.25rem;
  }
  
  .step-badge {
    width: 3rem;
    height: 3rem;
  }
  
  .badge-number {
    width: 1.3rem;
    height: 1.3rem;
    font-size: 0.6rem;
  }
  
  .step-icon {
    width: 3rem;
    height: 3rem;
    font-size: 1.5rem;
  }
  
  .step-content {
    padding-right: 2.5rem;
  }
  
  .step-text {
    font-size: 1.02rem;
    line-height: 1.55;
  }
}

/* ========================================
   ENGAGING ANIMATIONS
======================================== */
.fade-in {
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

/* Staggered animation for step cards */
.step-item:nth-child(1) { animation: slideInLeft 0.6s ease-out 0.1s both; }
.step-item:nth-child(2) { animation: slideInLeft 0.6s ease-out 0.2s both; }
.step-item:nth-child(3) { animation: slideInLeft 0.6s ease-out 0.3s both; }
.step-item:nth-child(4) { animation: slideInLeft 0.6s ease-out 0.4s both; }

@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Crisp professional text rendering */
.card-title,
.card-subtitle,
.step-text,
.section-title {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-rendering: optimizeLegibility;
}

/* ========================================
   SECTION 4: FEATURED PROPERTIES - REDUCED OVERLAY
======================================== */
.resident-listings {
  text-align: center;
  position: relative;
  z-index: 2;
  padding: 5rem 2rem 4rem;
  margin-bottom: 0;
  overflow: hidden;
  background:
    /* Lighter overlay - more background visible */
    linear-gradient(
      135deg,
      rgba(255, 255, 255, 0.65) 0%,
      rgba(234, 251, 255, 0.60) 30%,
      rgba(240, 252, 255, 0.58) 70%,
      rgba(255, 255, 255, 0.63) 100%
    ),
    /* Background image */
    url('@/assets/residentback.png') center/cover fixed;
  background-attachment: fixed;
  background-size: cover;
  background-position: center;
}

/* Remove backdrop blur */
.resident-listings::before {
  display: none;
}

/* Clean section title - MATCHING SECTION 3 */
.resident-listings .section-title {
  font-size: 3rem;
  font-weight: 700;
  padding: 4rem 2rem 3rem;
  margin: 0;
  color: #14919b;
  font-family: 'Poppins', sans-serif;
  text-align: center;
  letter-spacing: -0.02em;
  position: relative;
  z-index: 2;
}

/* Simple underline - MATCHING SECTION 3 */
.resident-listings .section-title::after {
  content: '';
  position: absolute;
  bottom: 0.5rem;
  left: 50%;
  transform: translateX(-50%);
  width: 120px;
  height: 3px;
  background: #14919b;
  border-radius: 2px;
  opacity: 0.3;
}

/* ========================================
   RESPONSIVE DESIGN
======================================== */
@media (max-width: 1024px) {
  .guide-cards {
    grid-template-columns: 1fr;
    gap: 0;
  }
  
  .cards-divider {
    display: none;
  }
  
  .tenant-card,
  .landlord-card {
    padding: 4rem 3rem;
  }
  
  .section-header {
    padding: 4rem 2rem 3rem;
  }
  
  .section-header .section-title {
    font-size: 2.6rem;
  }
  
  .resident-listings .section-title {
    font-size: 2.6rem;
  }
}

@media (max-width: 768px) {
  .section-header {
    padding: 3.5rem 1.5rem 2.5rem;
  }
  
  .section-header .section-title {
    font-size: 2.2rem;
  }
  
  .section-subtitle {
    font-size: 1.1rem;
  }
  
  .tenant-card,
  .landlord-card {
    padding: 3.5rem 2rem;
  }
  
  .card-title {
    font-size: 1.75rem;
  }
  
  .resident-listings .section-title {
    font-size: 2.2rem;
    padding: 3rem 1.5rem 2rem;
  }
}

@media (max-width: 480px) {
  .section-header {
    padding: 3rem 1rem 2rem;
  }
  
  .section-header .section-title {
    font-size: 1.9rem;
  }
  
  .section-subtitle {
    font-size: 1rem;
  }
  
  .tenant-card,
  .landlord-card {
    padding: 3rem 1.5rem;
  }
  
  .card-title {
    font-size: 1.6rem;
    gap: 0.5rem;
  }
  
  .title-icon {
    font-size: 1.5rem;
  }
  
  .card-icon {
    width: 70px;
    height: 70px;
  }
  
  .step-item {
    padding: 1rem 1.25rem;
    gap: 0.75rem;
  }
  
  .step-badge {
    width: 2.25rem;
    height: 2.25rem;
  }
  
  .badge-number {
    font-size: 0.95rem;
  }
  
  .step-icon {
    font-size: 1.15rem;
  }
  
  .step-text {
    font-size: 1rem;
  }
  
  .resident-listings .section-title {
    font-size: 1.9rem;
    padding: 2.5rem 1rem 1.5rem;
  }
}

/* ========================================
   PREMIUM POLISH & ANIMATIONS
======================================== */
.fade-in {
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

/* Smooth performance */
.tenant-card,
.landlord-card,
.step-item {
  will-change: transform;
}

/* Crisp text rendering */
.card-title,
.step-text,
.section-title {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}





/* ========================================
   SECTION 4: FEATURED PROPERTIES - PROFESSIONAL MINIMALIST DESIGN
======================================== */
.resident-listings {
  padding: 0;
  margin: 0;
  position: relative;
  overflow: hidden;
  background:
    linear-gradient(
      135deg,
      rgba(255, 255, 255, 0.65) 0%,
      rgba(234, 251, 255, 0.60) 30%,
      rgba(240, 252, 255, 0.58) 70%,
      rgba(255, 255, 255, 0.63) 100%
    ),
    url('@/assets/residentback.png') center/cover fixed;
  background-attachment: fixed;
  background-size: cover;
  background-position: center;
}

.resident-listings::before {
  display: none;
}

/* Section title - MATCHING SECTION 3 */
.resident-listings .section-title {
  font-size: 3rem;
  font-weight: 700;
  padding: 5rem 2rem 4rem;
  margin: 0;
  color: #14919b;
  font-family: 'Poppins', sans-serif;
  text-align: center;
  letter-spacing: -0.02em;
  position: relative;
  z-index: 2;
}

.resident-listings .section-title::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 120px;
  height: 3px;
  background: #14919b;
  border-radius: 2px;
  opacity: 0.3;
}

/* ========================================
   PROPERTY CONTAINER
======================================== */
.listings-container {
  max-width: 100%;
  width: 100%;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 0;
  position: relative;
  z-index: 2;
}

/* ========================================
   PROPERTY CARD - MINIMALIST PROFESSIONAL DESIGN
======================================== */
.property-card {
  display: flex;
  position: relative;
  width: 100%;
  min-height: 450px;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  border: none;
  border-top: 1px solid rgba(0, 0, 0, 0.06);
  border-bottom: 1px solid rgba(0, 0, 0, 0.06);
  overflow: hidden;
  transition: all 0.4s ease;
  opacity: 0;
  transform: translateX(-30px);
  margin: 0;
}

.property-card:first-child {
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

.property-card:last-child {
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
  padding-bottom: 2rem;
}

.property-card::before,
.property-card::after {
  display: none;
}

.property-card .card-edge-glow {
  display: none;
}

.property-card.scroll-reveal {
  animation: cardSlideIn 0.6s ease-out forwards;
}

@keyframes cardSlideIn {
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.property-card:hover {
  background: rgba(255, 255, 255, 0.98);
  box-shadow: 
    0 2px 8px rgba(0, 0, 0, 0.04),
    0 8px 24px rgba(0, 0, 0, 0.06);
}

.property-card.image-left {
  flex-direction: row;
}

.property-card.image-right {
  flex-direction: row-reverse;
}

/* ========================================
   PROPERTY IMAGE
======================================== */
.property-image {
  flex: 0 0 50%;
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: stretch;
  z-index: 2;
}

.image-wrapper {
  width: 100%;
  height: 100%;
  position: relative;
  display: flex;
}

.image-wrapper::before,
.image-wrapper::after {
  display: none;
}

.property-image-bg {
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  transition: transform 0.6s ease;
  filter: brightness(0.98) contrast(1.02) saturate(0.95);
}

.property-card:hover .property-image-bg {
  transform: scale(1.03);
  filter: brightness(1) contrast(1.04) saturate(0.98);
}

.property-image-bg.skyline {
  background-image: url('https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1600&q=90');
}

.property-image-bg.garden {
  background-image: url('https://images.unsplash.com/photo-1558036117-15d82a90b9b1?w=1600&q=90');
}

.property-image-bg.urban {
  background-image: url('https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=1600&q=90');
}

.property-image-bg.lakeside {
  background-image: url('https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1600&q=90');
}

/* ========================================
   PROPERTY INFO - PROFESSIONAL MINIMALIST LAYOUT
======================================== */
.property-info {
  flex: 1;
  padding: 4rem 4.5rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  font-family: 'Nunito', sans-serif;
  position: relative;
  z-index: 2;
  background: #ffffff;
}

/* ========================================
   ELEGANT SERIF TITLE
======================================== */
.property-title-elegant {
  position: relative;
  margin: 0 0 2rem;
  padding: 0;
  font-family: 'Playfair Display', 'Georgia', serif;
}

.title-text {
  display: block;
  font-size: 3rem;
  font-weight: 700;
  letter-spacing: -0.03em;
  line-height: 1.1;
  color: #2d3748;
  margin: 0;
}

.title-glow {
  display: none;
}

.title-text::before {
  display: none;
}

/* ========================================
   PLAIN SOPHISTICATED DESCRIPTION (NO BOX)
======================================== */
.property-description-elegant {
  margin: 0 0 2.5rem;
  padding: 0;
  font-family: 'Lato', 'Helvetica Neue', sans-serif;
  font-size: 1.1rem;
  font-weight: 400;
  line-height: 1.8;
  color: #4a5568;
  text-align: left;
  letter-spacing: 0.01em;
  max-width: 600px;
}

/* Hide the glass description container */
.glass-description-container {
  background: transparent;
  border: none;
  box-shadow: none;
  padding: 0;
  border-radius: 0;
}

.description-shine,
.description-gradient-border {
  display: none;
}

.glass-description-container:hover {
  box-shadow: none;
  border-color: transparent;
}

/* ========================================
   DE-EMPHASIZED PROPERTY DETAILS (Small & Subtle)
======================================== */
.property-chips {
  display: flex;
  gap: 2rem;
  margin-top: auto;
  padding-top: 1.5rem;
  border-top: 1px solid rgba(0, 0, 0, 0.08);
  flex-wrap: wrap;
}

.glass-chip {
  position: relative;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0;
  background: transparent;
  backdrop-filter: none;
  -webkit-backdrop-filter: none;
  border: none;
  border-radius: 0;
  box-shadow: none;
  transition: none;
}

.glass-chip::before,
.glass-chip::after {
  display: none;
}

.glass-chip:hover {
  transform: none;
  background: transparent;
  border-color: transparent;
  box-shadow: none;
}

.chip-glow {
  display: none;
}

/* Small dark icons */
.chip-icon {
  font-size: 1rem;
  opacity: 0.5;
  filter: grayscale(100%);
}

/* De-emphasized labels */
.chip-label {
  font-family: 'Lato', sans-serif;
  font-size: 0.8rem;
  font-weight: 500;
  color: #718096;
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

/* Simple values with brass accent */
.chip-value {
  font-family: 'Lato', sans-serif;
  font-size: 0.85rem;
  font-weight: 600;
  color: #a0826d;
}

/* ========================================
   RESPONSIVE DESIGN
======================================== */
@media (max-width: 1200px) {
  .property-card {
    min-height: 420px;
  }

  .property-info {
    padding: 3.5rem 4rem;
  }

  .title-text {
    font-size: 2.6rem;
  }

  .property-description-elegant {
    font-size: 1.05rem;
  }
}

@media (max-width: 1024px) {
  .resident-listings .section-title {
    font-size: 2.6rem;
    padding: 4rem 2rem 3rem;
  }

  .property-card {
    min-height: 400px;
  }

  .property-image {
    flex: 0 0 45%;
  }

  .property-info {
    padding: 3rem 3.5rem;
  }

  .title-text {
    font-size: 2.4rem;
  }

  .property-description-elegant {
    font-size: 1rem;
    margin-bottom: 2rem;
  }
}

@media (max-width: 768px) {
  .resident-listings .section-title {
    font-size: 2.2rem;
    padding: 3.5rem 1.5rem 2.5rem;
  }

  .property-card {
    flex-direction: column !important;
    min-height: auto;
  }

  .property-image {
    flex: 0 0 300px;
    width: 100%;
  }

  .image-wrapper {
    height: 300px;
  }

  .property-info {
    padding: 3rem 2.5rem;
    background: #ffffff;
  }

  .property-title-elegant {
    margin-bottom: 1.5rem;
  }

  .title-text {
    font-size: 2.2rem;
  }

  .property-description-elegant {
    font-size: 1rem;
    line-height: 1.7;
    margin-bottom: 2rem;
  }

  .property-chips {
    gap: 1.5rem;
    padding-top: 1.25rem;
  }

  .chip-label {
    font-size: 0.75rem;
  }

  .chip-value {
    font-size: 0.8rem;
  }
}

@media (max-width: 480px) {
  .resident-listings .section-title {
    font-size: 1.9rem;
    padding: 3rem 1rem 2rem;
  }

  .property-card {
    min-height: auto;
  }

  .property-image {
    flex: 0 0 260px;
  }

  .image-wrapper {
    height: 260px;
  }

  .property-info {
    padding: 2.5rem 2rem;
  }

  .property-title-elegant {
    margin-bottom: 1.25rem;
  }

  .title-text {
    font-size: 2rem;
  }

  .property-description-elegant {
    font-size: 0.95rem;
    line-height: 1.65;
    margin-bottom: 1.75rem;
  }

  .property-chips {
    gap: 1.25rem;
    flex-direction: column;
    align-items: flex-start;
  }

  .chip-icon {
    font-size: 0.9rem;
  }

  .chip-label {
    font-size: 0.7rem;
  }

  .chip-value {
    font-size: 0.75rem;
  }
}

/* ========================================
   SMOOTH ANIMATIONS
======================================== */
.property-info {
  animation: simpleFadeIn 0.5s ease-out;
}

@keyframes simpleFadeIn {
  from {
    opacity: 0;
    transform: translateY(15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Performance optimization */
.property-card,
.property-image-bg {
  will-change: transform;
}

/* Crisp professional typography */
.property-title-elegant,
.property-description-elegant,
.glass-chip {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-rendering: optimizeLegibility;
}





/* ========================================
   SECTION 5: WHY CHOOSE US - CLEAN ICONOGRAPHIC DESIGN
======================================== */
.why-choose-us {
  padding: 7rem 2rem 8rem;
  background: #ffffff;
  position: relative;
  overflow: hidden;
}

/* Large Centered Turquoise Title */
.section-title-turquoise {
  font-size: 3.5rem;
  font-weight: 800;
  text-align: center;
  color: #14919b;
  font-family: 'Poppins', 'Inter', sans-serif;
  letter-spacing: -0.03em;
  margin: 0 0 5rem;
  position: relative;
}

.section-title-turquoise::after {
  content: '';
  position: absolute;
  bottom: -1rem;
  left: 50%;
  transform: translateX(-50%);
  width: 100px;
  height: 4px;
  background: #14919b;
  border-radius: 2px;
}

/* Four Column Grid - No Cards */
.features-grid-clean {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 4rem;
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 2rem;
}

/* Clean Feature Item - No Borders or Boxes */
.feature-item-clean {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 0;
  background: transparent;
  border: none;
  box-shadow: none;
  transition: transform 0.3s ease;
}

.feature-item-clean:hover {
  transform: translateY(-8px);
}

/* Large Turquoise Illustrations */
.feature-illustration {
  margin-bottom: 2rem;
  transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  filter: drop-shadow(0 4px 12px rgba(20, 145, 155, 0.15));
}

.feature-item-clean:hover .feature-illustration {
  transform: scale(1.08);
  filter: drop-shadow(0 8px 20px rgba(20, 145, 155, 0.25));
}

/* Turquoise Feature Titles */
.feature-title-turquoise {
  font-size: 1.5rem;
  font-weight: 700;
  color: #14919b;
  font-family: 'Poppins', 'Inter', sans-serif;
  margin: 0 0 1rem;
  letter-spacing: -0.01em;
  line-height: 1.3;
}

/* Dark Grey Descriptions */
.feature-description-grey {
  font-size: 1.05rem;
  font-weight: 400;
  color: #4a5568;
  font-family: 'Nunito', 'Lato', sans-serif;
  line-height: 1.7;
  letter-spacing: 0.01em;
  margin: 0;
  max-width: 280px;
}

/* ========================================
   RESPONSIVE DESIGN
======================================== */
@media (max-width: 1200px) {
  .features-grid-clean {
    grid-template-columns: repeat(2, 1fr);
    gap: 4rem 3rem;
  }
}

@media (max-width: 768px) {
  .why-choose-us {
    padding: 5rem 1.5rem 6rem;
  }

  .section-title-turquoise {
    font-size: 2.8rem;
    margin-bottom: 4rem;
  }

  .features-grid-clean {
    grid-template-columns: 1fr;
    gap: 4.5rem;
    padding: 0 1rem;
  }

  .feature-illustration svg {
    width: 160px;
    height: 160px;
  }

  .feature-title-turquoise {
    font-size: 1.4rem;
  }

  .feature-description-grey {
    font-size: 1rem;
  }
}

@media (max-width: 480px) {
  .why-choose-us {
    padding: 4rem 1rem 5rem;
  }

  .section-title-turquoise {
    font-size: 2.3rem;
    margin-bottom: 3.5rem;
  }

  .features-grid-clean {
    gap: 4rem;
  }

  .feature-illustration {
    margin-bottom: 1.5rem;
  }

  .feature-illustration svg {
    width: 140px;
    height: 140px;
  }

  .feature-title-turquoise {
    font-size: 1.3rem;
    margin-bottom: 0.8rem;
  }

  .feature-description-grey {
    font-size: 0.98rem;
    line-height: 1.65;
  }
}

/* Smooth animations and performance */
.feature-item-clean,
.feature-illustration {
  will-change: transform;
}

/* Crisp text rendering */
.section-title-turquoise,
.feature-title-turquoise,
.feature-description-grey {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-rendering: optimizeLegibility;
}



/* ========================================
   SECTION 6: THANK YOU BANNER
======================================== */
.thank-you-banner {
  padding: 7rem 2rem;
  background: linear-gradient(135deg, #14919b 0%, #17a2b8 50%, #7dd3c0 100%);
  position: relative;
  overflow: hidden;
}

.wave-pattern {
  position: absolute;
  inset: 0;
  background-image: 
    radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 80% 50%, rgba(255, 255, 255, 0.08) 0%, transparent 50%),
    radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.12) 0%, transparent 50%);
  animation: waveMove 15s ease-in-out infinite;
}

@keyframes waveMove {
  0%, 100% { transform: translateX(0) translateY(0); }
  50% { transform: translateX(30px) translateY(-20px); }
}

.thank-you-overlay {
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse at center, transparent 0%, rgba(0, 0, 0, 0.1) 100%);
}

.thank-you-content {
  position: relative;
  z-index: 2;
  max-width: 800px;
  margin: 0 auto;
}

.thank-you-text {
  font-size: 2.8rem;
  font-weight: 700;
  color: #ffffff;
  font-family: 'Poppins', sans-serif;
  text-shadow: 
    0 4px 12px rgba(0, 0, 0, 0.2),
    0 2px 4px rgba(0, 0, 0, 0.15);
  line-height: 1.3;
}

.fade-in-scale {
  animation: fadeInScale 1s ease-out;
}

@keyframes fadeInScale {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.sparkles-container {
  position: absolute;
  inset: 0;
  pointer-events: none;
}

.sparkle {
  position: absolute;
  background: radial-gradient(circle, rgba(255, 255, 255, 0.9), transparent 70%);
  border-radius: 50%;
  animation: sparkleFloat infinite linear;
  opacity: 0;
}

@keyframes sparkleFloat {
  0% {
    opacity: 0;
    transform: translateY(0) scale(0);
  }
  15% {
    opacity: 0.8;
    transform: translateY(-20px) scale(1);
  }
  85% {
    opacity: 0.6;
  }
  100% {
    opacity: 0;
    transform: translateY(-120px) scale(0.5);
  }
}

/* ========================================
   RESPONSIVE DESIGN
======================================== */
@media (max-width: 768px) {
  .led-scrolling-track {
    padding: 1.8rem 0;
  }
  
  .led-text {
    font-size: 1.9rem;
    padding: 0 3.5rem;
  }
  
  .house-icon {
    width: 24px;
    height: 24px;
  }
  
  .welcome-banner {
    height: 70vh;
    min-height: 500px;
  }
  
  .welcome-text {
    font-size: 2.5rem;
  }
  
  .headline-subtitle {
    font-size: 1.4rem;
  }
  
  .section-title {
    font-size: 2.2rem;
  }
  
  .guide-cards {
    grid-template-columns: 1fr;
  }
  
  .features-grid {
    grid-template-columns: 1fr;
  }
  
  .property-card {
    flex-direction: column !important;
  }
  
  .property-image {
    flex: 0 0 250px;
  }
  
  .thank-you-text {
    font-size: 2.2rem;
  }
}

@media (max-width: 480px) {
  .led-scrolling-track {
    padding: 1.5rem 0;
  }
  
  .led-text {
    font-size: 1.6rem;
    padding: 0 2.5rem;
  }
  
  .house-icon {
    width: 22px;
    height: 22px;
  }
  
  .welcome-banner {
    height: 60vh;
    min-height: 450px;
  }
  
  .welcome-text {
    font-size: 2rem;
  }
  
  .headline-subtitle {
    font-size: 1.2rem;
  }
  
  .welcome-message {
    font-size: 1.1rem;
  }
  
  .section-title {
    font-size: 1.9rem;
  }
  
  .guide-card {
    padding: 2rem;
  }
  
  .property-info {
    padding: 2rem;
  }
  
  .thank-you-text {
    font-size: 1.8rem;
  }
}
</style>