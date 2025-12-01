<template>
  <div class="contact-page">
    <!-- Header Navigation -->
    <div class="header-nav-wrapper">
      <HeaderNav :user="user" />
    </div>

    <!-- Hero Section - ALWAYS VISIBLE -->
    <section class="hero-section">
      <div class="hero-background">
        <div class="hero-gradient-overlay"></div>
      </div>
      
      <div class="hero-content">
        <h1 class="hero-title">Trusted Landlords</h1>
        <p class="hero-subtitle">Meet our verified property owners committed to providing quality accommodation</p>
        
        <!-- Stats with Loading Animation -->
        <div class="hero-stats">
          <div class="stat-item">
            <!-- Loading spinner for this stat -->
            <div v-if="loading" class="stat-loading">
              <div class="mini-spinner"></div>
            </div>
            <template v-else>
              <div class="stat-number">{{ landlords.length }}</div>
              <div class="stat-label">Verified Landlords</div>
            </template>
          </div>
          
          <div class="stat-item">
            <!-- Loading spinner for this stat -->
            <div v-if="loading" class="stat-loading">
              <div class="mini-spinner"></div>
            </div>
            <template v-else>
              <div class="stat-number">{{ totalProperties }}</div>
              <div class="stat-label">Total Properties</div>
            </template>
          </div>
          
          <div class="stat-item">
            <!-- Loading spinner for this stat -->
            <div v-if="loading" class="stat-loading">
              <div class="mini-spinner"></div>
            </div>
            <template v-else>
              <div class="stat-number">{{ totalRooms }}</div>
              <div class="stat-label">Available Rooms</div>
            </template>
          </div>
        </div>
      </div>
    </section>

    <!-- Landlords Section -->
    <section class="landlords-section">
      <div class="section-container">
        
        <!-- Section Header -->
        <div class="section-header">
          <h2 class="section-title">Our Trusted Landlords</h2>
          <p class="section-subtitle">Contact any landlord directly to inquire about their properties</p>
        </div>

        <!-- Loading State - ONLY HERE -->
        <div v-if="loading" class="loading-container">
          <div class="loading-spinner-wrapper">
            <div class="loading-spinner-ring"></div>
            <div class="loading-spinner-ring"></div>
            <div class="loading-spinner-ring"></div>
          </div>
          <p class="loading-text">Loading trusted landlords...</p>
        </div>

        <!-- Empty State -->
        <div v-else-if="landlords.length === 0" class="empty-state">
          <div class="empty-illustration">
            <svg viewBox="0 0 200 200" class="illustration-svg">
              <circle cx="100" cy="100" r="80" fill="#E0FFFF"/>
              <path fill="#14b8a6" d="M100,50 L120,70 L120,120 L100,140 L80,120 L80,70 Z" opacity="0.3"/>
              <path fill="#0891b2" d="M100 60 L110 70 L110 110 L100 120 L90 110 L90 70 Z"/>
            </svg>
          </div>
          <h3 class="empty-title">No Landlords Found</h3>
          <p class="empty-description">There are no registered landlords at the moment</p>
        </div>

        <!-- Landlords Grid -->
        <div v-else class="landlords-grid">
          <div
            v-for="landlord in landlords"
            :key="landlord.id"
            class="landlord-card"
          >
            <!-- Landlord Header -->
            <div class="landlord-header">
              <div class="landlord-avatar">
                <img v-if="landlord.avatar" :src="landlord.avatar" :alt="landlord.name" class="avatar-image" />
                <div v-else class="avatar-placeholder">
                  <svg class="avatar-icon" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                  </svg>
                </div>
                <div class="verified-badge" v-if="landlord.verified">
                  <svg class="verified-icon" viewBox="0 0 24 24">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                  </svg>
                </div>
              </div>
              
              <div class="landlord-info">
                <h3 class="landlord-name">{{ landlord.name }}</h3>
                <div class="landlord-meta">
                  <span class="meta-item" v-if="landlord.resident">
                    <svg class="meta-icon" viewBox="0 0 24 24">
                      <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    {{ landlord.resident }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Landlord Stats -->
            <div class="landlord-stats">
              <div class="stat-box">
                <div class="stat-value">{{ landlord.propertyCount || 0 }}</div>
                <div class="stat-label">Properties</div>
              </div>
              <div class="stat-box">
                <div class="stat-value">{{ landlord.totalRooms || 0 }}</div>
                <div class="stat-label">Rooms</div>
              </div>
              <div class="stat-box">
                <div class="stat-value">{{ landlord.activeListings || 0 }}</div>
                <div class="stat-label">Active</div>
              </div>
            </div>

            <!-- Properties Preview -->
            <div class="properties-preview" v-if="landlord.properties && landlord.properties.length > 0">
              <h4 class="preview-title">Recent Properties</h4>
              <div class="properties-list">
                <div
                  v-for="property in landlord.properties.slice(0, 3)"
                  :key="property.id"
                  class="property-preview-item"
                  @click="viewPropertyDetails(property)"
                >
                  <img 
                    :src="getPropertyMainImage(property)" 
                    :alt="property.title"
                    class="property-thumb"
                  />
                  <div class="property-preview-info">
                    <div class="property-preview-title">{{ property.title }}</div>
                    <div class="property-preview-price">RM {{ formatNumber(property.payment?.amount || 0) }}/month</div>
                  </div>
                  <div class="property-preview-badge" v-if="property.available_rooms > 0">
                    {{ property.available_rooms }} available
                  </div>
                </div>
              </div>
              <button 
                v-if="landlord.properties.length > 3"
                class="view-all-btn"
                @click="viewLandlordProfile(landlord)"
              >
                View All {{ landlord.properties.length }} Properties
              </button>
            </div>

            <!-- No Properties Message -->
            <div v-else class="no-properties">
              <svg class="icon" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
              </svg>
              <p>No properties listed yet</p>
            </div>

            <!-- Contact Button -->
            <button class="contact-btn" @click="contactLandlord(landlord)">
              <svg class="icon" viewBox="0 0 24 24">
                <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM9 11H7V9h2v2zm4 0h-2V9h2v2zm4 0h-2V9h2v2z"/>
              </svg>
              Contact via WhatsApp
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Toast Notification -->
    <transition name="toast-fade">
      <div v-if="toast.visible" class="toast-notification" :class="toast.type">
        <div class="toast-icon">
          <svg v-if="toast.type === 'success'" class="icon" viewBox="0 0 24 24">
            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
          </svg>
          <svg v-else class="icon" viewBox="0 0 24 24">
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
          </svg>
        </div>
        <div class="toast-content">
          <p class="toast-message">{{ toast.message }}</p>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import HeaderNav from '../components/HeaderNav.vue'

const router = useRouter()

// State
const user = ref(JSON.parse(localStorage.getItem('user')) || { name: 'Guest' })
const loading = ref(false)
const landlords = ref([])

// Toast
const toast = ref({
  visible: false,
  message: '',
  type: 'success'
})

// Computed
const totalProperties = computed(() => {
  return landlords.value.reduce((sum, l) => sum + (l.propertyCount || 0), 0)
})

const totalRooms = computed(() => {
  return landlords.value.reduce((sum, l) => sum + (l.totalRooms || 0), 0)
})

// Fetch all landlords and their properties
async function fetchLandlords() {
  loading.value = true
  
  try {
    console.log('🔄 Fetching properties...')
    
    // Fetch all properties
    const propertiesRes = await axios.get('/api/properties', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    
    let allProperties = []
    if (Array.isArray(propertiesRes.data)) {
      allProperties = propertiesRes.data
    } else if (propertiesRes.data?.data) {
      allProperties = propertiesRes.data.data
    }
    
    console.log('✅ Properties fetched:', allProperties.length)
    console.log('📋 Sample property:', allProperties[0]) // See property structure
    
    if (allProperties.length === 0) {
      showToast('No properties available', 'error')
      loading.value = false
      return
    }
    
    // Fetch all payments
    console.log('🔄 Fetching payments...')
    const paymentsRes = await axios.get('/api/payments', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    
    let allPayments = []
    if (Array.isArray(paymentsRes.data)) {
      allPayments = paymentsRes.data
    } else if (paymentsRes.data?.data) {
      allPayments = paymentsRes.data.data
    }
    
    console.log('✅ Payments fetched:', allPayments.length)
    
    // Group properties by user_id to create landlords
    const landlordMap = new Map()
    
    allProperties.forEach(property => {
      const userId = property.user_id
      
      if (!userId) {
        console.warn('⚠️ Property without user_id:', property.id)
        return
      }
      
      if (!landlordMap.has(userId)) {
        // Create new landlord entry
        landlordMap.set(userId, {
          id: userId,
          name: `Landlord ${userId}`, // Will be replaced if we find real name
          email: '',
          phone: property.contact || '',
          resident: property.address || 'Location not specified',
          avatar: null,
          verified: true,
          properties: [],
          propertyCount: 0,
          totalRooms: 0,
          activeListings: 0
        })
      }
      
      // Get landlord from map
      const landlord = landlordMap.get(userId)
      
      // Update landlord name from first property (if you stored it)
      // You might have landlord info in the property object
      if (property.user && property.user.name) {
        landlord.name = property.user.name
        landlord.email = property.user.email || ''
        landlord.phone = property.user.phone || property.contact || ''
        landlord.resident = property.user.resident || property.address || ''
      }
      
      // Find payment for this property
      const payment = allPayments.find(p => p.property_id == property.id) || { 
        amount: 0, 
        deposit: 0 
      }
      
      // Process images
      let imgs = property.images
      if (typeof imgs === 'string') {
        try { 
          imgs = JSON.parse(imgs) 
        } catch (e) {
          console.warn('Failed to parse images:', property.id)
        }
      }
      
      let imagesArray = []
      if (Array.isArray(imgs)) {
        imagesArray = imgs.map(i => {
          if (typeof i === 'string') {
            return i.startsWith('http') 
              ? i 
              : `http://127.0.0.1:8000${String(i).replace(/\\/g, '/')}`
          }
          return i
        })
      }
      
      if (imagesArray.length === 0) {
        imagesArray = ['/default-house.jpg']
      }
      
      // Add property to landlord
      landlord.properties.push({
        ...property,
        payment,
        mainImage: imagesArray[0],
        totalImages: imagesArray.length
      })
      
      // Update landlord stats
      landlord.propertyCount = landlord.properties.length
      landlord.totalRooms += property.available_rooms || 0
      if (property.available_rooms > 0) {
        landlord.activeListings++
      }
    })
    
    // Convert map to array
    landlords.value = Array.from(landlordMap.values())
    
    console.log('✅ Landlords created:', landlords.value.length)
    console.log('📋 Sample landlord:', landlords.value[0]) // See landlord structure
    
    // Sort by property count
    landlords.value.sort((a, b) => b.propertyCount - a.propertyCount)
    
    if (landlords.value.length === 0) {
      showToast('No landlords found', 'error')
    } else {
      showToast(`Found ${landlords.value.length} landlords`, 'success')
    }
    
  } catch (err) {
    console.error('❌ Error fetching landlords:', err)
    console.error('Error details:', err.response?.data)
    
    if (err.response?.status === 401) {
      showToast('Please login first', 'error')
      router.push('/login')
    } else {
      showToast('Failed to load landlords', 'error')
    }
  } finally {
    loading.value = false
  }
}

// Get property main image
function getPropertyMainImage(property) {
  return property.mainImage || '/default-house.jpg'
}

// Format number
function formatNumber(num) {
  return num?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") || '0'
}

// View property details - Navigate to HomePage and auto-open modal
function viewPropertyDetails(property) {
  // Store the property ID to open its modal
  localStorage.setItem('openPropertyModal', property.id)
  
  // Navigate to home page
  router.push('/home')
}

// View landlord profile
function viewLandlordProfile(landlord) {
  router.push(`/landlord-profile?landlordId=${landlord.id}`)
}

// Contact landlord via WhatsApp
function contactLandlord(landlord) {
  if (!landlord.phone) {
    showToast('Contact information not available', 'error')
    return
  }
  
  const message = encodeURIComponent(
    `Hi ${landlord.name}! I found your profile on the housing website and I'm interested in your properties. Can we discuss?`
  )
  
  let whatsappLink = landlord.phone
  if (!whatsappLink.startsWith('http')) {
    const cleanNumber = whatsappLink.replace(/[\s\-\(\)]/g, '')
    const phoneNumber = cleanNumber.startsWith('60') 
      ? cleanNumber 
      : '60' + cleanNumber.replace(/^0+/, '')
    whatsappLink = `https://wa.me/${phoneNumber}?text=${message}`
  }
  
  window.open(whatsappLink, '_blank')
  showToast('Opening WhatsApp...', 'success')
}

// Toast notification
function showToast(message, type = 'success') {
  toast.value.message = message
  toast.value.type = type
  toast.value.visible = true
  
  setTimeout(() => {
    toast.value.visible = false
  }, 3000)
}

// Lifecycle
onMounted(() => {
  fetchLandlords()
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

:root {
  --primary-teal: #14b8a6;
  --deep-teal: #0891b2;
  --dark-teal: #0e7490;
  --light-teal: #ecfeff;
  --very-light-teal: #f0fdfa;
  --mint: #99f6e4;
  --light-mint: #ccfbf1;
  --text-dark: #0e7490;
  --text-medium: #0891b2;
  --text-muted: #64748b;
  --white: #FFFFFF;
  --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.04);
  --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.06);
  --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.08);
  --shadow-xl: 0 12px 40px rgba(0, 0, 0, 0.1);
  --shadow-teal: 0 4px 20px rgba(20, 184, 166, 0.15);
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  background-color: var(--very-light-teal);
  color: var(--text-dark);
  line-height: 1.6;
}

.contact-page {
  min-height: 100vh;
  background: var(--very-light-teal);
}

/* Loading State */
.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  padding: 2rem;
}

.loading-spinner-wrapper {
  position: relative;
  width: 120px;
  height: 120px;
  margin-bottom: 2rem;
}

.loading-spinner-ring {
  position: absolute;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  border: 4px solid transparent;
}

.loading-spinner-ring:nth-child(1) {
  border-top-color: #14b8a6;
  border-right-color: #14b8a6;
  animation: spinFast 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  box-shadow: 0 0 20px rgba(20, 184, 166, 0.3);
}

.loading-spinner-ring:nth-child(2) {
  width: 85%;
  height: 85%;
  top: 7.5%;
  left: 7.5%;
  border-bottom-color: #0891b2;
  border-left-color: #0891b2;
  animation: spinMedium 1.5s cubic-bezier(0.5, 0, 0.5, 1) infinite reverse;
  box-shadow: 0 0 15px rgba(8, 145, 178, 0.3);
}

.loading-spinner-ring:nth-child(3) {
  width: 70%;
  height: 70%;
  top: 15%;
  left: 15%;
  border-top-color: #2dd4bf;
  border-right-color: #2dd4bf;
  animation: spinSlow 2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  box-shadow: 0 0 10px rgba(45, 212, 191, 0.3);
}

@keyframes spinFast {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes spinMedium {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes spinSlow {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.loading-text {
  color: var(--text-medium);
  font-size: 1.125rem;
  font-weight: 600;
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.6; }
}

/* Hero Section */
.hero-section {
  position: relative;
  padding: 6rem 2rem 4rem;
  overflow: hidden;
  min-height: 400px;
}

.hero-background {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 0;
}

.hero-gradient-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    135deg,
    rgba(20, 184, 166, 0.95) 0%,
    rgba(8, 145, 178, 0.95) 50%,
    rgba(14, 116, 144, 0.95) 100%
  );
}

.hero-content {
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
  text-align: center;
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 800;
  color: white;
  margin-bottom: 1rem;
  line-height: 1.2;
  text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.hero-subtitle {
  font-size: 1.25rem;
  color: rgba(255, 255, 255, 0.95);
  margin-bottom: 3rem;
  max-width: 700px;
  margin-left: auto;
  margin-right: auto;
}

.hero-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
  max-width: 900px;
  margin: 0 auto;
}

.stat-item {
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  padding: 2rem;
  border-radius: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.stat-number {
  font-size: 3rem;
  font-weight: 800;
  color: white;
  line-height: 1;
  margin-bottom: 0.5rem;
}

.stat-label {
  color: rgba(255, 255, 255, 0.9);
  font-size: 0.938rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Landlords Section */
.landlords-section {
  padding: 4rem 2rem;
  background: var(--white);
}

.section-container {
  max-width: 1400px;
  margin: 0 auto;
}

.section-header {
  text-align: center;
  margin-bottom: 3rem;
}

.section-title {
  font-size: 2.5rem;
  font-weight: 800;
  color: var(--text-dark);
  margin-bottom: 0.75rem;
}

.section-subtitle {
  color: var(--text-muted);
  font-size: 1.125rem;
  max-width: 600px;
  margin: 0 auto;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  background: var(--light-teal);
  border-radius: 20px;
}

.empty-illustration {
  margin-bottom: 2rem;
}

.illustration-svg {
  width: 150px;
  height: 150px;
  margin: 0 auto;
  filter: drop-shadow(0 4px 12px rgba(20, 184, 166, 0.2));
  animation: float 3s ease-in-out infinite;
}

@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-10px);
  }
}

.empty-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 0.75rem;
}

.empty-description {
  color: var(--text-muted);
  font-size: 1rem;
}

/* Landlords Grid */
.landlords-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
  gap: 2rem;
}

.landlord-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: var(--shadow-md);
  border: 2px solid var(--light-mint);
  transition: var(--transition);
}

.landlord-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow-xl);
  border-color: var(--mint);
}

/* Landlord Header */
.landlord-header {
  padding: 2rem;
  background: linear-gradient(135deg, var(--very-light-teal), var(--light-mint));
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.landlord-avatar {
  position: relative;
  flex-shrink: 0;
}

.avatar-image,
.avatar-placeholder {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
}

.avatar-placeholder {
  background: linear-gradient(135deg, var(--primary-teal), var(--deep-teal));
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 16px rgba(20, 184, 166, 0.3);
}

.avatar-icon {
  width: 40px;
  height: 40px;
  fill: white;
}

.verified-badge {
  position: absolute;
  bottom: 0;
  right: 0;
  width: 28px;
  height: 28px;
  background: #10b981;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4);
  border: 3px solid white;
}

.verified-icon {
  width: 14px;
  height: 14px;
  fill: white;
}

.landlord-info {
  flex: 1;
}

.landlord-name {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 0.5rem;
}

.landlord-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 0.375rem;
  color: var(--text-muted);
  font-size: 0.875rem;
  font-weight: 500;
}

.meta-icon {
  width: 16px;
  height: 16px;
  fill: var(--primary-teal);
}

/* Landlord Stats */
.landlord-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1px;
  background: var(--light-mint);
  padding: 0;
}

.stat-box {
  background: white;
  padding: 1.5rem 1rem;
  text-align: center;
}

.stat-value {
  font-size: 2rem;
  font-weight: 800;
  color: var(--deep-teal);
  line-height: 1;
  margin-bottom: 0.5rem;
}

.stat-label {
  color: var(--text-muted);
  font-size: 0.813rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

/* Properties Preview */
.properties-preview {
  padding: 1.5rem;
  border-top: 2px solid var(--light-teal);
}

.preview-title {
  font-size: 1rem;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 1rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.properties-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  margin-bottom: 1rem;
}

.property-preview-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 0.75rem;
  background: var(--light-teal);
  border-radius: 12px;
  cursor: pointer;
  transition: var(--transition);
  border: 2px solid transparent;
}

.property-preview-item:hover {
  background: var(--mint);
  border-color: var(--primary-teal);
  transform: translateX(4px);
}

.property-thumb {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 8px;
  flex-shrink: 0;
}

.property-preview-info {
  flex: 1;
  min-width: 0;
}

.property-preview-title {
  font-size: 0.938rem;
  font-weight: 600;
  color: var(--text-dark);
  margin-bottom: 0.25rem;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.property-preview-price {
  font-size: 0.875rem;
  font-weight: 700;
  color: var(--deep-teal);
}

.property-preview-badge {
  background: linear-gradient(135deg, var(--primary-teal), var(--deep-teal));
  color: white;
  padding: 0.375rem 0.75rem;
  border-radius: 50px;
  font-size: 0.75rem;
  font-weight: 700;
  white-space: nowrap;
}

.view-all-btn {
  width: 100%;
  padding: 0.875rem;
  background: var(--very-light-teal);
  border: 2px solid var(--mint);
  border-radius: 10px;
  color: var(--deep-teal);
  font-weight: 700;
  font-size: 0.875rem;
  cursor: pointer;
  transition: var(--transition);
  font-family: inherit;
}

.view-all-btn:hover {
  background: var(--mint);
  border-color: var(--primary-teal);
}

/* No Properties */
.no-properties {
  padding: 2rem;
  text-align: center;
  color: var(--text-muted);
}

.no-properties .icon {
  width: 48px;
  height: 48px;
  fill: var(--text-muted);
  opacity: 0.5;
  margin-bottom: 1rem;
}

.no-properties p {
  font-size: 0.938rem;
  font-weight: 500;
}

/* Contact Button */
.contact-btn {
  width: 100%;
  padding: 1.25rem;
  background: linear-gradient(135deg, #25D366, #128C7E);
  color: white;
  border: none;
  border-radius: 0 0 18px 18px;
  font-weight: 700;
  font-size: 1rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  transition: var(--transition);
  box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);
  font-family: inherit;
}

.contact-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(37, 211, 102, 0.35);
}

.contact-btn .icon {
  width: 22px;
  height: 22px;
  fill: white;
}

/* Toast Notification */
.toast-notification {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem 1.5rem;
  background: white;
  border-radius: 12px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  z-index: 3000;
  min-width: 320px;
  animation: slideInToast 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes slideInToast {
  from {
    transform: translateX(400px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.toast-notification.success {
  border-left: 4px solid var(--primary-teal);
}

.toast-notification.error {
  border-left: 4px solid #ef4444;
}

.toast-icon {
  width: 24px;
  height: 24px;
  flex-shrink: 0;
}

.toast-icon .icon {
  width: 100%;
  height: 100%;
}

.toast-notification.success .toast-icon .icon {
  fill: var(--primary-teal);
}

.toast-notification.error .toast-icon .icon {
  fill: #ef4444;
}

.toast-message {
  color: var(--text-dark);
  font-weight: 600;
  font-size: 0.938rem;
}

.toast-fade-enter-active,
.toast-fade-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.toast-fade-enter-from,
.toast-fade-leave-to {
  opacity: 0;
  transform: translateX(100px);
}

.icon {
  fill: currentColor;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .hero-section {
    padding: 5rem 1.5rem 3rem;
  }

  .hero-stats {
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
  }

  .landlords-grid {
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  }
}

@media (max-width: 768px) {
  .hero-section {
    padding: 4rem 1.5rem 2rem;
  }

  .hero-title {
    font-size: 2.5rem;
  }

  .hero-subtitle {
    font-size: 1.125rem;
  }

  .hero-stats {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .section-title {
    font-size: 2rem;
  }

  .landlords-grid {
    grid-template-columns: 1fr;
  }

  .landlord-header {
    flex-direction: column;
    text-align: center;
  }

  .toast-notification {
    bottom: 1rem;
    right: 1rem;
    left: 1rem;
    min-width: auto;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 2rem;
  }

  .stat-number {
    font-size: 2.5rem;
  }

  .landlord-stats {
    grid-template-columns: 1fr;
  }

  .stat-box {
    padding: 1.25rem 1rem;
  }

  .property-preview-item {
    flex-direction: column;
    text-align: center;
  }

  .property-thumb {
    width: 100%;
    height: 120px;
  }
}

/* Mini Spinner for Stats Boxes */
.stat-loading {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 80px;
}

.mini-spinner {
  width: 40px;
  height: 40px;
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Adjust Loading Container to NOT be full screen */
.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 4rem 2rem;
  min-height: 400px; /* Changed from 100vh */
}

</style>