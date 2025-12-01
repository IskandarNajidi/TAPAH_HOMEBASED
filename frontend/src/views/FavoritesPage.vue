<template>
  <div class="favorites-page">
    <!-- Header Navigation -->
    <div class="header-nav-wrapper">
      <HeaderNav :user="user" />
    </div>

    <!-- Hero Header with Gradient -->
    <header class="hero-header-favorites">
      <div class="hero-background">
        <div class="hero-gradient-overlay"></div>
      </div>
      
      <div class="hero-content">
        <div class="hero-badge">
          <svg class="badge-icon" viewBox="0 0 24 24">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
          </svg>
          <span>My Collection</span>
        </div>
        <h1 class="hero-title">
          Saved Properties
          <span class="favorites-count" v-if="favorites.length > 0">({{ favorites.length }})</span>
        </h1>
        <p class="hero-subtitle">Your handpicked properties - all in one place</p>
      </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
      <div class="content-container">
        
        <!-- Filter & Sort Bar -->
        <div v-if="favorites.length > 0" class="filter-sort-bar">
          <div class="filter-section">
            <button class="filter-btn" :class="{ active: currentFilter === 'all' }" @click="currentFilter = 'all'">
              All ({{ favorites.length }})
            </button>
            <button class="filter-btn" :class="{ active: currentFilter === 'available' }" @click="currentFilter = 'available'">
              Available ({{ availableCount }})
            </button>
          </div>

          <div class="sort-section">
            <label class="sort-label">
              <svg class="icon" viewBox="0 0 24 24">
                <path d="M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z"/>
              </svg>
              Sort:
            </label>
            <select v-model="sortBy" class="sort-select">
              <option value="recent">Recently Added</option>
              <option value="price-low">Price: Low to High</option>
              <option value="price-high">Price: High to Low</option>
              <option value="name">Property Name</option>
            </select>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="state-container">
          <div class="loading-spinner-wrapper">
            <div class="loading-spinner-ring"></div>
            <div class="loading-spinner-ring"></div>
            <div class="loading-spinner-ring"></div>
          </div>
          <p class="loading-text">Loading your favorites...</p>
        </div>

        <!-- Empty State -->
        <div v-else-if="favorites.length === 0" class="empty-state">
          <div class="empty-illustration">
            <svg viewBox="0 0 200 200" class="illustration-svg">
              <circle cx="100" cy="100" r="80" fill="#f0fdfa"/>
              <path fill="#14b8a6" d="M100,50 L120,70 L120,120 L100,140 L80,120 L80,70 Z" opacity="0.3"/>
              <path fill="#0891b2" d="M100 60 L110 70 L110 110 L100 120 L90 110 L90 70 Z"/>
              <!-- Heart -->
              <path fill="#e74c3c" d="M100 85l-3-3c-6-6-15-6-21 0s-6 15 0 21l24 24 24-24c6-6 6-15 0-21s-15-6-21 0l-3 3z" opacity="0.6"/>
            </svg>
          </div>
          <h3 class="empty-title">No Favorites Yet</h3>
          <p class="empty-description">Start exploring and save properties you love by clicking the heart icon</p>
          <router-link to="/property" class="btn-primary btn-large">
            <svg class="icon" viewBox="0 0 24 24">
              <path d="M21 11l-7-7v4C7 9 4 14 3 19c2.5-3.5 6-5.1 11-5.1V18l7-7z"/>
            </svg>
            Browse Properties
          </router-link>
        </div>

        <!-- Favorites Grid -->
        <div v-else class="favorites-grid">
          <div
            v-for="property in sortedFavorites"
            :key="property.id"
            class="favorite-card"
          >
            <!-- Image Section with Carousel -->
            <div class="card-image-section">
              <img 
                :src="getImage(property, imageIndexes[property.id] || 0)" 
                :alt="property.title" 
                class="card-image"
              >
              
              <!-- Availability Badge -->
              <div class="availability-badge" :class="{ available: property.available_rooms > 0 }">
                <span v-if="property.available_rooms > 0">{{ property.available_rooms }} Rooms Available</span>
                <span v-else>Fully Occupied</span>
              </div>

              <!-- Favorite Heart (Always Visible) -->
              <button class="favorite-heart-btn active" @click.stop="removeFavorite(property.id)" title="Remove from favorites">
                <svg class="icon" viewBox="0 0 24 24">
                  <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
              </button>

              <!-- Date Added Badge -->
              <div class="date-added-badge">
                <svg class="icon" viewBox="0 0 24 24">
                  <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                </svg>
                {{ formatDate(property.favorited_at) }}
              </div>

              <!-- Carousel Controls -->
              <div class="carousel-overlay">
                <button 
                  class="carousel-button prev" 
                  @click.stop="prevImage(property.id)"
                  v-if="(property.imagesArray || []).length > 1"
                >
                  <svg class="icon" viewBox="0 0 24 24">
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                  </svg>
                </button>
                <button 
                  class="carousel-button next" 
                  @click.stop="nextImage(property.id)"
                  v-if="(property.imagesArray || []).length > 1"
                >
                  <svg class="icon" viewBox="0 0 24 24">
                    <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                  </svg>
                </button>
              </div>

              <!-- Image Dots -->
              <div class="image-dots" v-if="(property.imagesArray || []).length > 1">
                <span
                  v-for="(img, idx) in property.imagesArray"
                  :key="idx"
                  class="dot"
                  :class="{ active: (imageIndexes[property.id] || 0) === idx }"
                ></span>
              </div>
            </div>

            <!-- Card Content -->
            <div class="card-content">
              <h3 class="property-title">{{ property.title }}</h3>
              
              <p class="property-location">
                <svg class="icon" viewBox="0 0 24 24">
                  <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
                {{ property.address || 'Location not specified' }}
              </p>

              <!-- Features -->
              <div class="property-features">
                <div class="feature">
                  <svg class="icon" viewBox="0 0 24 24">
                    <path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V7H1v13h2v-2h18v2h2V10c0-2.21-1.79-4-4-4z"/>
                  </svg>
                  <span>{{ property.available_rooms || 0 }} Rooms</span>
                </div>
                <div class="feature">
                  <svg class="icon" viewBox="0 0 24 24">
                    <path d="M9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                  </svg>
                  <span>{{ property.total_bathrooms || 0 }} Baths</span>
                </div>
              </div>

              <!-- Price -->
              <div class="price-section">
                <div class="price-main">
                  <span class="price-amount">RM {{ formatNumber(property.payment?.amount || 0) }}</span>
                  <span class="price-period">/ month</span>
                </div>
                <div class="price-deposit">
                  Deposit: <strong>RM {{ formatNumber(property.payment?.deposit || 0) }}</strong>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="card-actions">
                <button class="btn-action btn-contact" @click="contactLandlord(property)">
                  <svg class="icon" viewBox="0 0 24 24">
                    <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM9 11H7V9h2v2zm4 0h-2V9h2v2zm4 0h-2V9h2v2z"/>
                  </svg>
                  WhatsApp
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Recently Removed Section -->
        <div v-if="recentlyRemoved.length > 0" class="recently-removed-section">
          <div class="section-header">
            <h3>Recently Removed</h3>
            <button class="btn-clear-all" @click="clearRecentlyRemoved">Clear All</button>
          </div>
          <div class="removed-items">
            <div v-for="item in recentlyRemoved" :key="item.id" class="removed-item">
              <img :src="getImage(item, 0)" :alt="item.title" class="removed-thumb">
              <div class="removed-info">
                <span class="removed-title">{{ item.title }}</span>
                <span class="removed-time">Removed {{ formatTimeAgo(item.removed_at) }}</span>
              </div>
              <button class="btn-undo" @click="restoreFavorite(item)">
                <svg class="icon" viewBox="0 0 24 24">
                  <path d="M12.5 8c-2.65 0-5.05.99-6.9 2.6L2 7v9h9l-3.62-3.62c1.39-1.16 3.16-1.88 5.12-1.88 3.54 0 6.55 2.31 7.6 5.5l2.37-.78C21.08 11.03 17.15 8 12.5 8z"/>
                </svg>
                Undo
              </button>
            </div>
          </div>
        </div>

      </div>
    </main>

    <!-- Toast Notification -->
    <transition name="toast-fade">
      <div v-if="toast.visible" class="toast-notification" :class="toast.type">
        <div class="toast-icon">
          <svg v-if="toast.type === 'success'" class="icon" viewBox="0 0 24 24">
            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
          </svg>
          <svg v-else-if="toast.type === 'error'" class="icon" viewBox="0 0 24 24">
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
          </svg>
          <svg v-else class="icon" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
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
const user = ref(JSON.parse(localStorage.getItem('user')) || { name: 'Guest' })
const loading = ref(false)
const favorites = ref([])
const imageIndexes = ref({})
const currentFilter = ref('all')
const sortBy = ref('recent')
const recentlyRemoved = ref([])

// Toast
const toast = ref({
  visible: false,
  message: '',
  type: 'success'
})

// Computed
const availableCount = computed(() => {
  return favorites.value.filter(p => p.available_rooms > 0).length
})

const filteredFavorites = computed(() => {
  if (currentFilter.value === 'available') {
    return favorites.value.filter(p => p.available_rooms > 0)
  }
  return favorites.value
})

const sortedFavorites = computed(() => {
  const sorted = [...filteredFavorites.value]
  
  switch (sortBy.value) {
    case 'price-low':
      return sorted.sort((a, b) => (a.payment?.amount || 0) - (b.payment?.amount || 0))
    case 'price-high':
      return sorted.sort((a, b) => (b.payment?.amount || 0) - (a.payment?.amount || 0))
    case 'name':
      return sorted.sort((a, b) => (a.title || '').localeCompare(b.title || ''))
    case 'recent':
    default:
      return sorted.sort((a, b) => new Date(b.favorited_at) - new Date(a.favorited_at))
  }
})

// Fetch favorites from localStorage
async function fetchFavorites() {
  try {
    loading.value = true
    
    // Get saved property IDs from localStorage
    const savedIds = JSON.parse(localStorage.getItem('savedProperties') || '[]')
    
    if (savedIds.length === 0) {
      favorites.value = []
      loading.value = false
      return
    }

    // Fetch all properties
    const propRes = await axios.get('/api/properties')
    let allProperties = Array.isArray(propRes.data) 
      ? propRes.data 
      : (propRes.data?.data || [])
    
    // Filter to only saved properties
    const savedProperties = allProperties.filter(p => savedIds.includes(p.id))
    
    // Fetch details for each saved property
    const propertiesWithDetails = await Promise.all(
      savedProperties.map(async (property) => {
        try {
          // Fetch payment
          const payRes = await axios.get(`/api/payments?property_id=${property.id}`)
          const payment = Array.isArray(payRes.data)
            ? payRes.data.find(x => x.property_id === property.id) || payRes.data[0]
            : payRes.data || { amount: 0, deposit: 0, utility: 'Included' }
          
          // Process images
          let imgs = property.images
          if (typeof imgs === 'string') {
            try { imgs = JSON.parse(imgs) } catch {}
          }
          let imagesArray = Array.isArray(imgs)
            ? imgs.map(i => i.startsWith('http') ? i : `http://127.0.0.1:8000${String(i).replace(/\\/g, '')}`)
            : []

          // Fetch room images
          try {
            const roomsRes = await axios.get(`/api/properties/${property.id}/rooms`)
            const rooms = Array.isArray(roomsRes.data) ? roomsRes.data : []
            
            rooms.forEach(room => {
              if (room.images && Array.isArray(room.images)) {
                room.images.forEach(img => {
                  if (img.image_path) {
                    const url = img.image_path.startsWith('http')
                      ? img.image_path
                      : `http://127.0.0.1:8000${String(img.image_path).replace(/\\/g, '')}`
                    imagesArray.push(url)
                  }
                })
              }
            })
          } catch (err) {
            console.log('Could not fetch room images:', err)
          }

          if (imagesArray.length === 0) {
            imagesArray = ['/default-house.jpg']
          }

          // Add favorited timestamp (use localStorage timestamp if available)
          const savedTimestamps = JSON.parse(localStorage.getItem('favoritedTimestamps') || '{}')
          const favorited_at = savedTimestamps[property.id] || new Date().toISOString()

          return { 
            ...property, 
            imagesArray, 
            payment,
            favorited_at
          }
        } catch (err) {
          console.error(`Error fetching property ${property.id}:`, err)
          return null
        }
      })
    )

    favorites.value = propertiesWithDetails.filter(p => p !== null)
    
    // Initialize carousel indexes
    favorites.value.forEach(p => (imageIndexes.value[p.id] = 0))

  } catch (err) {
    console.error('Error fetching favorites:', err)
    showToast('Failed to load favorites', 'error')
  } finally {
    loading.value = false
  }
}

// Remove favorite
function removeFavorite(propertyId) {
  // Find the property before removing
  const property = favorites.value.find(p => p.id === propertyId)
  
  // Remove from localStorage
  let savedIds = JSON.parse(localStorage.getItem('savedProperties') || '[]')
  savedIds = savedIds.filter(id => id !== propertyId)
  localStorage.setItem('savedProperties', JSON.stringify(savedIds))
  
  // Remove timestamp
  const timestamps = JSON.parse(localStorage.getItem('favoritedTimestamps') || '{}')
  delete timestamps[propertyId]
  localStorage.setItem('favoritedTimestamps', JSON.stringify(timestamps))

  // Add to recently removed
  if (property) {
    recentlyRemoved.value.unshift({
      ...property,
      removed_at: new Date().toISOString()
    })
    
    // Keep only last 5 removed items
    if (recentlyRemoved.value.length > 5) {
      recentlyRemoved.value = recentlyRemoved.value.slice(0, 5)
    }
  }

  // Remove from favorites array
  favorites.value = favorites.value.filter(p => p.id !== propertyId)

  showToast('Removed from favorites', 'success')
}

// Restore favorite
function restoreFavorite(property) {
  const propertyId = property.id
  
  // Add back to localStorage
  let savedIds = JSON.parse(localStorage.getItem('savedProperties') || '[]')
  if (!savedIds.includes(propertyId)) {
    savedIds.push(propertyId)
    localStorage.setItem('savedProperties', JSON.stringify(savedIds))
    
    // Add timestamp
    const timestamps = JSON.parse(localStorage.getItem('favoritedTimestamps') || '{}')
    timestamps[propertyId] = new Date().toISOString()
    localStorage.setItem('favoritedTimestamps', JSON.stringify(timestamps))
  }

  // Remove from recently removed
  recentlyRemoved.value = recentlyRemoved.value.filter(p => p.id !== propertyId)

  // Re-fetch favorites
  fetchFavorites()

  showToast('Restored to favorites', 'success')
}

// Clear recently removed
function clearRecentlyRemoved() {
  recentlyRemoved.value = []
  showToast('Cleared recently removed', 'success')
}

// View property
function viewProperty(property) {
  // Navigate to browse page and open modal
  router.push(`/tenant/browse?property=${property.id}`)
}

// Contact landlord
function contactLandlord(property) {
  if (!property.contact) {
    showToast('Contact information not available', 'error')
    return
  }

  const message = encodeURIComponent(
    `Hi! I'm interested in ${property.title} at ${property.address}. Is it still available?`
  )

  let whatsappLink = property.contact
  
  // If contact is just a phone number, convert to WhatsApp link
  if (!whatsappLink.startsWith('http')) {
    const cleanNumber = whatsappLink.replace(/[\s\-\(\)]/g, '')
    const phoneNumber = cleanNumber.startsWith('60') 
      ? cleanNumber 
      : '60' + cleanNumber.replace(/^0+/, '')
    whatsappLink = `https://wa.me/${phoneNumber}`
  }

  // Add message if not already in link
  if (!whatsappLink.includes('?text=')) {
    whatsappLink += `?text=${message}`
  }

  window.open(whatsappLink, '_blank')
  showToast('Opening WhatsApp...', 'success')
}

// Carousel functions
function nextImage(id) {
  const prop = favorites.value.find(p => p.id === id)
  if (!prop) return
  imageIndexes.value[id] = (imageIndexes.value[id] + 1) % (prop.imagesArray?.length || 1)
}

function prevImage(id) {
  const prop = favorites.value.find(p => p.id === id)
  if (!prop) return
  const len = prop.imagesArray?.length || 1
  imageIndexes.value[id] = (imageIndexes.value[id] - 1 + len) % len
}

function getImage(property, index) {
  return property.imagesArray?.[index] || '/default-house.jpg'
}

// Format helpers
function formatNumber(num) {
  return num?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") || '0'
}

function formatDate(dateString) {
  const date = new Date(dateString)
  const now = new Date()
  const diffDays = Math.floor((now - date) / (1000 * 60 * 60 * 24))
  
  if (diffDays === 0) return 'Today'
  if (diffDays === 1) return 'Yesterday'
  if (diffDays < 7) return `${diffDays} days ago`
  if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`
  if (diffDays < 365) return `${Math.floor(diffDays / 30)} months ago`
  return date.toLocaleDateString()
}

function formatTimeAgo(dateString) {
  const date = new Date(dateString)
  const now = new Date()
  const diffMinutes = Math.floor((now - date) / (1000 * 60))
  
  if (diffMinutes < 1) return 'just now'
  if (diffMinutes < 60) return `${diffMinutes}m ago`
  if (diffMinutes < 1440) return `${Math.floor(diffMinutes / 60)}h ago`
  return `${Math.floor(diffMinutes / 1440)}d ago`
}

// Toast
function showToast(message, type = 'success') {
  toast.value.message = message
  toast.value.type = type
  toast.value.visible = true
  
  setTimeout(() => {
    toast.value.visible = false
  }, 3000)
}

onMounted(fetchFavorites)
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
  --background: #FAFCFC;
  --text-dark: #0e7490;
  --text-medium: #0891b2;
  --text-muted: #64748b;
  --white: #FFFFFF;
  --error: #ef4444;
  --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.04);
  --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.06);
  --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.08);
  --shadow-xl: 0 12px 40px rgba(0, 0, 0, 0.1);
  --shadow-teal: 0 4px 20px rgba(20, 184, 166, 0.15);
  --glow-teal: 0 0 20px rgba(20, 184, 166, 0.3);
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
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

.favorites-page {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: var(--very-light-teal);
}

/* Hero Header */
.hero-header-favorites {
  position: relative;
  padding: 3.5rem 2rem 4.5rem;
  overflow: hidden;
  min-height: 280px;
  display: flex;
  align-items: center;
  justify-content: center;
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
    rgba(239, 68, 68, 0.4) 0%,
    rgba(236, 72, 153, 0.45) 25%,
    rgba(168, 85, 247, 0.5) 50%,
    rgba(59, 130, 246, 0.45) 75%,
    rgba(20, 184, 166, 0.4) 100%
  );
  backdrop-filter: blur(12px) saturate(150%);
  -webkit-backdrop-filter: blur(12px) saturate(150%);
  animation: loveFlow 15s ease-in-out infinite alternate;
  background-size: 400% 400%;
}

@keyframes loveFlow {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.hero-content {
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
  text-align: center;
  width: 100%;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: rgba(255, 255, 255, 0.25);
  backdrop-filter: blur(10px);
  padding: 0.5rem 1rem;
  border-radius: 50px;
  color: white;
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
  border: 1px solid rgba(255, 255, 255, 0.3);
}

.badge-icon {
  width: 18px;
  height: 18px;
  fill: white;
  animation: heartbeat 1.5s ease-in-out infinite;
}

@keyframes heartbeat {
  0%, 100% {
    transform: scale(1);
  }
  25% {
    transform: scale(1.15);
  }
  50% {
    transform: scale(1);
  }
}

.hero-title {
  font-size: 3rem;
  font-weight: 800;
  color: white;
  margin-bottom: 0.75rem;
  letter-spacing: -0.02em;
  line-height: 1.1;
  text-shadow: 0 2px 20px rgba(0, 0, 0, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.favorites-count {
  background: rgba(255, 255, 255, 0.2);
  padding: 0.25rem 0.875rem;
  border-radius: 50px;
  font-size: 1.5rem;
  font-weight: 700;
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.hero-subtitle {
  font-size: 1.25rem;
  color: rgba(255, 255, 255, 0.95);
  font-weight: 500;
  text-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
}

/* Main Content */
.main-content {
  flex: 1;
  padding: 0 2rem 4rem;
  margin-top: -2rem;
  position: relative;
  z-index: 2;
}

.content-container {
  max-width: 1400px;
  margin: 0 auto;
  width: 100%;
}

/* Filter & Sort Bar */
.filter-sort-bar {
  background: white;
  border-radius: 16px;
  padding: 1.5rem;
  margin-bottom: 2rem;
  box-shadow: var(--shadow-md);
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 2rem;
  flex-wrap: wrap;
}

.filter-section {
  display: flex;
  gap: 0.75rem;
}

.filter-btn {
  padding: 0.625rem 1.25rem;
  background: var(--light-teal);
  border: 2px solid var(--mint);
  border-radius: 10px;
  color: var(--text-dark);
  font-weight: 600;
  font-size: 0.875rem;
  cursor: pointer;
  transition: var(--transition);
  font-family: inherit;
}

.filter-btn:hover {
  background: var(--mint);
  transform: translateY(-2px);
}

.filter-btn.active {
  background: linear-gradient(135deg, var(--primary-teal), var(--deep-teal));
  border-color: var(--primary-teal);
  color: white;
  box-shadow: var(--shadow-teal);
}

.sort-section {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.sort-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  color: var(--text-dark);
  font-size: 0.938rem;
}

.sort-label .icon {
  width: 18px;
  height: 18px;
  fill: var(--primary-teal);
}

.sort-select {
  padding: 0.625rem 1rem;
  background: var(--light-teal);
  border: 2px solid var(--mint);
  border-radius: 10px;
  color: var(--text-dark);
  font-weight: 500;
  font-size: 0.875rem;
  cursor: pointer;
  transition: var(--transition);
  font-family: inherit;
  min-width: 180px;
}

.sort-select:focus {
  border-color: var(--primary-teal);
  outline: none;
  box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1);
}

/* State Container */
.state-container {
  text-align: center;
  padding: 4rem 2rem;
  background: var(--white);
  border-radius: 20px;
  box-shadow: var(--shadow-md);
}

.loading-spinner-wrapper {
  position: relative;
  width: 120px;
  height: 120px;
  margin: 0 auto 2rem;
}

.loading-spinner-ring {
  position: absolute;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  border: 4px solid transparent;
}

.loading-spinner-ring:nth-child(1) {
  border-top-color: #ef4444;
  border-right-color: #ef4444;
  animation: spinFast 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
}

.loading-spinner-ring:nth-child(2) {
  width: 85%;
  height: 85%;
  top: 7.5%;
  left: 7.5%;
  border-bottom-color: #ec4899;
  border-left-color: #ec4899;
  animation: spinMedium 1.5s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  animation-direction: reverse;
  box-shadow: 0 0 15px rgba(236, 72, 153, 0.3);
}

.loading-spinner-ring:nth-child(3) {
  width: 70%;
  height: 70%;
  top: 15%;
  left: 15%;
  border-top-color: #a855f7;
  border-right-color: #a855f7;
  animation: spinSlow 2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  box-shadow: 0 0 10px rgba(168, 85, 247, 0.3);
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
  color: #ec4899;
  font-size: 1.125rem;
  font-weight: 600;
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.6; }
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  background: var(--white);
  border-radius: 20px;
  box-shadow: var(--shadow-md);
}

.empty-illustration {
  margin-bottom: 2rem;
}

.illustration-svg {
  width: 150px;
  height: 150px;
  margin: 0 auto;
  filter: drop-shadow(0 4px 12px rgba(239, 68, 68, 0.2));
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
  margin-bottom: 2rem;
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
}

.btn-primary {
  background: linear-gradient(135deg, #ec4899, #d946ef, #a855f7);
  color: white;
  border: none;
  padding: 0.875rem 1.75rem;
  border-radius: 12px;
  font-weight: 600;
  font-size: 0.938rem;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: var(--transition);
  box-shadow: 0 4px 20px rgba(236, 72, 153, 0.3);
  text-decoration: none;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(236, 72, 153, 0.4);
  background: linear-gradient(135deg, #d946ef, #c026d3, #9333ea);
}

.btn-primary.btn-large {
  padding: 1rem 2rem;
  font-size: 1rem;
}

.icon {
  width: 20px;
  height: 20px;
  fill: currentColor;
}

/* Favorites Grid */
.favorites-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
  gap: 2rem;
}

/* Favorite Card */
.favorite-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: var(--shadow-md);
  transition: var(--transition);
  border: 2px solid var(--light-mint);
}

.favorite-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-xl), var(--glow-teal);
  border-color: var(--mint);
}

.card-image-section {
  position: relative;
  height: 260px;
  overflow: hidden;
  background: var(--light-teal);
}

.card-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.favorite-card:hover .card-image {
  transform: scale(1.05);
}

.availability-badge {
  position: absolute;
  top: 1rem;
  left: 1rem;
  padding: 0.5rem 0.875rem;
  border-radius: 50px;
  font-size: 0.813rem;
  font-weight: 600;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  background: rgba(100, 116, 139, 0.9);
  color: white;
  z-index: 2;
}

.availability-badge.available {
  background: rgba(20, 184, 166, 0.95);
  border-color: rgba(255, 255, 255, 0.3);
  box-shadow: 0 2px 8px rgba(20, 184, 166, 0.3);
}

.favorite-heart-btn {
  position: absolute;
  top: 1rem;
  right: 1rem;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.95);
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: var(--transition);
  z-index: 3;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.favorite-heart-btn.active {
  background: var(--error);
  animation: heartPulse 0.4s ease;
}

@keyframes heartPulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.2);
  }
}

.favorite-heart-btn .icon {
  width: 22px;
  height: 22px;
  fill: #cbd5e1;
  transition: fill 0.3s;
}

.favorite-heart-btn.active .icon {
  fill: white;
}

.favorite-heart-btn:hover {
  transform: scale(1.1);
}

.date-added-badge {
  position: absolute;
  bottom: 1rem;
  left: 1rem;
  padding: 0.375rem 0.75rem;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 600;
  backdrop-filter: blur(10px);
  background: rgba(255, 255, 255, 0.9);
  color: var(--text-dark);
  display: flex;
  align-items: center;
  gap: 0.375rem;
  z-index: 2;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.date-added-badge .icon {
  width: 14px;
  height: 14px;
  fill: var(--primary-teal);
}

.carousel-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 0.75rem;
  opacity: 0;
  transition: opacity 0.3s;
}

.favorite-card:hover .carousel-overlay {
  opacity: 1;
}

.carousel-button {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.95);
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: var(--transition);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.carousel-button:hover {
  background: white;
  transform: scale(1.1);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.carousel-button .icon {
  width: 20px;
  height: 20px;
  fill: var(--text-dark);
}

.image-dots {
  position: absolute;
  bottom: 1rem;
  right: 1rem;
  display: flex;
  gap: 0.5rem;
  z-index: 2;
}

.dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.5);
  transition: all 0.3s;
  cursor: pointer;
}

.dot.active {
  background: white;
  transform: scale(1.2);
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.card-content {
  padding: 1.75rem;
}

.property-title {
  font-size: 1.375rem;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 0.75rem;
  line-height: 1.3;
}

.property-location {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--text-muted);
  font-size: 0.875rem;
  margin-bottom: 1.25rem;
  line-height: 1.4;
}

.property-location .icon {
  width: 16px;
  height: 16px;
  fill: var(--text-muted);
  flex-shrink: 0;
}

.property-features {
  display: flex;
  gap: 1.25rem;
  margin-bottom: 1.5rem;
  padding-bottom: 1.25rem;
  border-bottom: 2px solid var(--light-teal);
}

.feature {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.938rem;
  color: var(--text-medium);
  font-weight: 600;
}

.feature .icon {
  width: 18px;
  height: 18px;
  fill: var(--primary-teal);
  flex-shrink: 0;
}

.price-section {
  margin-bottom: 1.5rem;
  background: linear-gradient(135deg, rgba(20, 184, 166, 0.08), rgba(8, 145, 178, 0.05));
  padding: 1rem;
  border-radius: 12px;
  border: 2px solid var(--light-mint);
}

.price-main {
  display: flex;
  align-items: baseline;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
}

.price-amount {
  font-size: 2rem;
  font-weight: 800;
  color: var(--deep-teal);
  line-height: 1;
}

.price-period {
  font-size: 1rem;
  color: var(--text-muted);
  font-weight: 500;
}

.price-deposit {
  font-size: 0.875rem;
  color: var(--text-muted);
}

.price-deposit strong {
  color: var(--text-dark);
  font-weight: 700;
}

.card-actions {
  display: flex;
  gap: 0.75rem;
}

.btn-action {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.875rem 1rem;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  font-size: 0.875rem;
  cursor: pointer;
  transition: var(--transition);
  font-family: inherit;
}

.btn-view {
  background: linear-gradient(135deg, var(--primary-teal), var(--deep-teal));
  color: white;
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.25);
}

.btn-view:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(20, 184, 166, 0.35);
}

.btn-contact {
  background: linear-gradient(135deg, #25D366, #128C7E);
  color: white;
  box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);
}

.btn-contact:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(37, 211, 102, 0.35);
}

.btn-action .icon {
  width: 18px;
  height: 18px;
  fill: white;
}

/* Recently Removed Section */
.recently-removed-section {
  margin-top: 3rem;
  padding: 2rem;
  background: white;
  border-radius: 16px;
  box-shadow: var(--shadow-md);
  border: 2px dashed var(--mint);
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 2px solid var(--light-teal);
}

.section-header h3 {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--text-dark);
}

.btn-clear-all {
  padding: 0.5rem 1rem;
  background: var(--light-teal);
  border: 1px solid var(--mint);
  border-radius: 8px;
  color: var(--text-dark);
  font-weight: 600;
  font-size: 0.813rem;
  cursor: pointer;
  transition: var(--transition);
  font-family: inherit;
}

.btn-clear-all:hover {
  background: var(--mint);
}

.removed-items {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.removed-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: var(--light-teal);
  border-radius: 12px;
  border: 1px solid var(--mint);
  transition: var(--transition);
}

.removed-item:hover {
  background: var(--mint);
}

.removed-thumb {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 8px;
  flex-shrink: 0;
}

.removed-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.removed-title {
  font-weight: 600;
  color: var(--text-dark);
  font-size: 0.938rem;
}

.removed-time {
  font-size: 0.813rem;
  color: var(--text-muted);
}

.btn-undo {
  display: flex;
  align-items: center;
  gap: 0.375rem;
  padding: 0.5rem 1rem;
  background: var(--primary-teal);
  border: none;
  border-radius: 8px;
  color: white;
  font-weight: 600;
  font-size: 0.813rem;
  cursor: pointer;
  transition: var(--transition);
  font-family: inherit;
  flex-shrink: 0;
}

.btn-undo:hover {
  background: var(--deep-teal);
  transform: scale(1.05);
}

.btn-undo .icon {
  width: 16px;
  height: 16px;
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
  border-radius: 0.75rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  z-index: 3000;
  min-width: 320px;
  max-width: 420px;
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
  border-left: 4px solid #14b8a6;
}

.toast-notification.error {
  border-left: 4px solid #ef4444;
}

.toast-notification.info {
  border-left: 4px solid #3b82f6;
}

.toast-icon {
  width: 24px;
  height: 24px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.toast-icon .icon {
  width: 20px;
  height: 20px;
}

.toast-notification.success .toast-icon .icon {
  fill: #14b8a6;
}

.toast-notification.error .toast-icon .icon {
  fill: #ef4444;
}

.toast-notification.info .toast-icon .icon {
  fill: #3b82f6;
}

.toast-content {
  flex: 1;
}

.toast-message {
  color: var(--text-dark);
  font-weight: 600;
  font-size: 0.938rem;
  line-height: 1.4;
}

.toast-fade-enter-active,
.toast-fade-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.toast-fade-enter-from {
  opacity: 0;
  transform: translateX(100px);
}

.toast-fade-leave-to {
  opacity: 0;
  transform: translateX(100px);
}

/* Responsive Design */
@media (max-width: 1200px) {
  .favorites-grid {
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  }
}

@media (max-width: 768px) {
  .hero-header-favorites {
    padding: 3rem 1.5rem 4rem;
  }
  
  .hero-title {
    font-size: 2rem;
  }
  
  .favorites-count {
    font-size: 1.25rem;
  }
  
  .hero-subtitle {
    font-size: 1rem;
  }
  
  .main-content {
    padding: 0 1.5rem 3rem;
  }
  
  .filter-sort-bar {
    flex-direction: column;
    align-items: stretch;
  }
  
  .filter-section {
    justify-content: center;
  }
  
  .sort-section {
    justify-content: space-between;
  }
  
  .favorites-grid {
    grid-template-columns: 1fr;
  }
  
  .card-actions {
    flex-direction: column;
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
    font-size: 1.75rem;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .property-features {
    flex-direction: column;
    gap: 0.75rem;
  }
  
  .price-amount {
    font-size: 1.75rem;
  }
  
  .filter-section {
    flex-direction: column;
    width: 100%;
  }
  
  .filter-btn {
    width: 100%;
  }
  
  .sort-select {
    width: 100%;
  }
}
</style>