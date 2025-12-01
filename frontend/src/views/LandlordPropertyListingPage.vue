
<template>
  <div class="min-h-screen bg-gradient-to-br from-cyan-50 via-teal-50 to-emerald-50 py-8 px-4">
    <!-- Header -->
    <HeaderNav v-if="user" :user="user" />
    <HeaderNav v-else :user="{ name: 'Tetamu', role: 'guest' }" />
    
    <!-- Animated Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
      <div class="glow-orb glow-orb-1"></div>
      <div class="glow-orb glow-orb-2"></div>
      <div class="glow-orb glow-orb-3"></div>
    </div>

    <!-- Toast Notification -->
    <div v-if="toast.show" class="toast-notification" :class="toast.type">
      <div class="toast-icon">
        <span v-if="toast.type === 'success'">✓</span>
        <span v-else-if="toast.type === 'error'">✕</span>
        <span v-else>ℹ</span>
      </div>
      <div class="toast-content">
        <div class="toast-title">{{ toast.title }}</div>
        <div class="toast-message">{{ toast.message }}</div>
      </div>
    </div>

    <!-- Main Dashboard Container -->
    <div class="container-wrapper">
      <div class="dashboard-box">
        <div class="dashboard-flex">
          
          <!-- Left Sidebar - Step Navigation -->
          <div class="sidebar">
            <div class="sidebar-header">
              <h2 class="sidebar-title">Tambah Harta Baru</h2>
              <p class="sidebar-subtitle">Lengkapkan semua langkah untuk menyenaraikan harta anda</p>
            </div>
            
            <nav class="sidebar-nav">
              <button
                v-for="(tab, index) in tabs"
                :key="index"
                @click="goToStep(index + 1)"
                :class="['nav-item', {
                  'nav-item-active': step === index + 1,
                  'nav-item-completed': step > index + 1
                }]"
              >
                <div :class="['nav-icon', {
                  'nav-icon-active': step === index + 1,
                  'nav-icon-completed': step > index + 1
                }]">
                  <span v-if="step > index + 1">✓</span>
                  <span v-else>{{ index + 1 }}</span>
                </div>
                <div class="nav-text">
                  <div class="nav-name">{{ tab }}</div>
                  <div class="nav-step">Langkah {{ index + 1 }} daripada {{ tabs.length }}</div>
                </div>
                <svg v-if="step === index + 1" class="nav-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
              </button>
            </nav>

            <div class="progress-section">
              <div class="progress-card">
                <div class="progress-label">Perkembangan</div>
                <div class="progress-wrapper">
                  <div class="progress-bar-bg">
                    <div class="progress-bar-fill" :style="{ width: `${(step / tabs.length) * 100}%` }"></div>
                  </div>
                  <span class="progress-text">{{ Math.round((step / tabs.length) * 100) }}%</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Content Area -->
          <div class="content-area">
            
            <!-- Step 1 - Basic Info -->
            <div v-if="step === 1" class="content-section">
              <div class="section-header">
                <h2 class="section-title">Maklumat Asas Harta</h2>
                <p class="section-subtitle">Masukkan butiran penting tentang harta anda</p>
              </div>

              <div class="form-grid">
                <div class="form-group full-width">
                  <label class="form-label">Tajuk Harta</label>
                  <input v-model="form.title" class="form-input" placeholder="cth. Apartment Selesa di Tapah" />
                </div>

                <div class="form-group full-width">
                  <label class="form-label">Alamat</label>
                  <input v-model="form.address" class="form-input" placeholder="cth. No. 23, Jalan Tapah Indah" />
                </div>

                <div class="form-group">
                  <label class="form-label">Kawasan Perumahan</label>
                  <input v-model="form.area_name" class="form-input" placeholder="cth. Taman Tapah Indah" />
                </div>

                <div class="form-group">
                  <label class="form-label">Jenis Harta</label>
                  <select v-model="form.property_type" class="form-input">
                    <option value="">Pilih Jenis</option>
                    <option>Terrace</option>
                    <option>Apartment</option>
                    <option>Bungalow</option>
                    <option>Sewa Bilik</option>
                  </select>
                </div>

                <div class="form-group">
                  <label class="form-label">Jumlah Bilik</label>
                  <input v-model="form.total_units" type="number" class="form-input" placeholder="0" />
                </div>

                <div class="form-group">
                  <label class="form-label">Bilik Tersedia</label>
                  <input v-model="form.available_rooms" type="number" class="form-input" placeholder="0" />
                </div>

                <div class="form-group">
                  <label class="form-label">Penyewa Diperlukan</label>
                  <input v-model="form.tenants_needed" type="number" class="form-input" placeholder="0" />
                </div>

                <div class="form-group">
                  <label class="form-label">Penyewa Semasa</label>
                  <input v-model="form.current_tenants" type="number" class="form-input" placeholder="0" />
                </div>

                <div class="form-group">
                  <label class="form-label">Tempoh Kontrak</label>
                  <select v-model="form.contract_duration" class="form-input">
                    <option value="">Pilih Tempoh</option>
                    <option>6 Bulan</option>
                    <option>12 Bulan</option>
                    <option>Fleksibel</option>
                  </select>
                </div>

                <div class="form-group">
                  <label class="form-label">Jantina Pilihan</label>
                  <select v-model="form.gender" class="form-input">
                    <option value="">Pilih Jantina</option>
                    <option>Lelaki</option>
                    <option>Perempuan</option>
                    <option>Mana-mana</option>
                  </select>
                </div>

                <div class="form-group">
                  <label class="form-label">Tahap Perabot</label>
                  <select v-model="form.furnished_level" class="form-input">
                    <option value="">Pilih Tahap</option>
                    <option>Lengkap</option>
                    <option>Separuh</option>
                    <option>Tanpa Perabot</option>
                  </select>
                </div>

                <div class="form-group">
                  <label class="form-label">Jumlah Bilik Air</label>
                  <input v-model="form.total_bathrooms" type="number" class="form-input" placeholder="0" />
                </div>

                <div class="form-group full-width">
                  <label class="form-label">Hubungan WhatsApp <span class="optional-text">(Pilihan)</span></label>
                  <input v-model="form.contact" class="form-input" placeholder="https://wa.me/60123456789" />
                </div>

                <div class="form-group full-width">
                  <label class="form-label">Gambar Harta</label>
                  <input type="file" multiple accept="image/*" @change="handlePropertyImages" class="form-input file-input" />
                  <div v-if="propertyImagesPreview.length" class="image-preview">
                    <img v-for="(img, i) in propertyImagesPreview" :key="i" :src="img" class="preview-img" />
                  </div>
                </div>
              </div>
            </div>

            <!-- Step 2 - Room Details -->
            <div v-if="step === 2" class="content-section">
              <div class="section-header">
                <h2 class="section-title">Butiran Bilik</h2>
                <p class="section-subtitle">Tambah butiran untuk setiap bilik yang tersedia</p>
              </div>

              <div v-if="form.available_rooms <= 0" class="empty-state">
                Sila nyatakan bilangan bilik yang tersedia dalam Langkah 1 dahulu.
              </div>

              <div v-else class="rooms-container">
                <div v-for="(room, index) in form.rooms" :key="room._uid" class="room-card">
                  <h3 class="room-title">Bilik {{ index + 1 }}</h3>
                  <div class="form-grid">
                    <div class="form-group">
                      <label class="form-label">Jenis Bilik</label>
                      <select v-model="room.room_type" class="form-input">
                        <option value="">Pilih</option>
                        <option>Single</option>
                        <option>Master</option>
                        <option>Shared</option>
                        <option>Couple</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label class="form-label">Kapasiti</label>
                      <input v-model="room.capacity" type="number" class="form-input" placeholder="0" />
                    </div>

                    <div class="form-group">
                      <label class="form-label">Ketersediaan Semasa</label>
                      <input v-model="room.current_occupancy" type="number" class="form-input" placeholder="0" />
                    </div>

                    <div class="form-group">
                      <label class="form-label">Jenis Bilik Air</label>
                      <select v-model="room.bathroom_type" class="form-input">
                        <option value="">Pilih Jenis</option>
                        <option>Attached</option>
                        <option>Shared</option>
                      </select>
                    </div>

                    <div class="form-group full-width">
                      <label class="form-label">Jenis Katil</label>
                      <select v-model="room.bed_type" class="form-input">
                        <option value="">Pilih Jenis Katil</option>
                        <option>Single Bed</option>
                        <option>Double Decker</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label class="form-label">Gambar Bilik</label>
                      <input type="file" multiple accept="image/*" @change="handleFileChange($event, room, 'room_images')" class="form-input file-input" />
                      <div v-if="room.room_images_preview.length" class="image-preview">
                        <img v-for="(img, i) in room.room_images_preview" :key="i" :src="img" class="preview-img" />
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="form-label">Gambar Bilik Air</label>
                      <input type="file" multiple accept="image/*" @change="handleFileChange($event, room, 'bathroom_images')" class="form-input file-input" />
                      <div v-if="room.bathroom_images_preview.length" class="image-preview">
                        <img v-for="(img, i) in room.bathroom_images_preview" :key="i" :src="img" class="preview-img" />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Additional Rooms -->
                <div class="additional-section">
                  <h3 class="room-title">Butiran Bilik Tambahan</h3>
                  <div v-for="(extra, index) in additionalRooms" :key="extra._uid" class="room-card">
                    <div class="form-group">
                      <label class="form-label">Nama Bilik</label>
                      <input v-model="extra.name" class="form-input" placeholder="cth. Dapur, Ruang Makan, Ruang Tamu" />
                    </div>
                    <div class="form-group">
                      <label class="form-label">Muat Naik Gambar</label>
                      <input type="file" multiple accept="image/*" @change="handleExtraRoomImages($event, extra)" class="form-input file-input" />
                      <div v-if="extra.images_preview.length" class="image-preview">
                        <img v-for="(img, i) in extra.images_preview" :key="i" :src="img" class="preview-img" />
                      </div>
                    </div>
                  </div>
                  <button @click="addExtraRoom" class="btn-secondary">+ Tambah Bilik Tambahan</button>
                </div>
              </div>
            </div>

            <!-- Step 3 - Payment -->
            <div v-if="step === 3" class="content-section">
              <div class="section-header">
                <h2 class="section-title">Butiran Pembayaran</h2>
                <p class="section-subtitle">Tetapkan harga sewa dan terma pembayaran</p>
              </div>

              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Jenis Pembayaran</label>
                  <select v-model="payment.payment_type" class="form-input">
                    <option value="">Pilih Jenis</option>
                    <option>Per Satu Orang</option>
                    <option>Per Bilik Disewakan</option>
                    <option>Per Sebuah Rumah</option>
                  </select>
                </div>

                <div class="form-group">
                  <label class="form-label">Sewa Bulanan (RM)</label>
                  <input v-model="payment.amount" type="number" class="form-input" placeholder="cth. 500" />
                </div>

                <div class="form-group">
                  <label class="form-label">Bayaran Deposit (RM)</label>
                  <input v-model="payment.deposit" type="number" class="form-input" placeholder="cth. 500" />
                </div>

                <div class="form-group">
                  <label class="form-label">Utiliti Disediakan?</label>
                  <select v-model="payment.utility" class="form-input">
                    <option>Disertakan</option>
                    <option>Tidak Disertakan</option>
                  </select>
                </div>

                <div v-if="payment.utility === 'Tidak Disertakan'" class="form-group">
                  <label class="form-label">Anggaran Kos Utiliti (RM)</label>
                  <input v-model="payment.additional_bill" type="number" class="form-input" placeholder="cth. 100" />
                </div>

                <div class="form-group full-width">
                  <label class="form-label">Nota Pembayaran</label>
                  <textarea v-model="payment.note" rows="4" class="form-input" placeholder="Contoh: Bil utiliti dikongsi sama rata antara penyewa..."></textarea>
                </div>
              </div>
            </div>

            <!-- Step 4 - Amenities -->
            <div v-if="step === 4" class="content-section">
              <div class="section-header">
                <h2 class="section-title">Kemudahan Harta</h2>
                <p class="section-subtitle">Pilih semua kemudahan yang tersedia di harta anda</p>
              </div>

              <div class="amenities-grid">
                <label v-for="a in amenitiesList" :key="a" class="amenity-item">
                  <input type="checkbox" v-model="form.amenities" :value="a" class="amenity-checkbox" />
                  <span class="amenity-label">{{ a }}</span>
                </label>
              </div>

              <!-- Custom Amenities -->
              <div class="custom-amenities-section">
                <h3 class="custom-amenities-title">Kemudahan Tersuai</h3>
                <div class="custom-amenity-input-wrapper">
                  <input 
                    v-model="newAmenity" 
                    @keyup.enter="addCustomAmenity"
                    class="form-input" 
                    placeholder="Tambah kemudahan anda sendiri (cth. Kolam Renang, Gim)" 
                  />
                  <button @click="addCustomAmenity" class="btn-add-amenity">+ Tambah</button>
                </div>
                <div v-if="customAmenities.length" class="custom-amenities-list">
                  <div v-for="(amenity, index) in customAmenities" :key="index" class="custom-amenity-tag">
                    <span>{{ amenity }}</span>
                    <button @click="removeCustomAmenity(index)" class="remove-amenity-btn">×</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Step 5 - Location -->
            <div v-if="step === 5" class="content-section">
              <div class="section-header">
                <h2 class="section-title">Maklumat Lokasi</h2>
                <p class="section-subtitle">Berikan butiran lokasi dan kemudahan berhampiran</p>
              </div>

              <div class="form-grid-location">
                <div class="form-group">
                  <label class="form-label">Jarak dari UiTM Tapah</label>
                  <input v-model="form.distance_ui_tm" class="form-input" placeholder="cth. 2.5 km" />
                </div>

                <div class="form-group">
                  <label class="form-label">Kedai Berhampiran</label>
                  <input v-model="form.nearby_shop" class="form-input" placeholder="cth. 7-Eleven, Kedai Runcit" />
                </div>

                <div class="form-group">
                  <label class="form-label">Kemudahan Awam Berhampiran</label>
                  <input v-model="form.nearby_facility" class="form-input" placeholder="cth. Perhentian Bas, Klinik, Masjid" />
                </div>

                <div class="form-group full-width-location">
                  <label class="form-label">Nota Tambahan</label>
                  <textarea v-model="form.location_advantages" rows="4" class="form-input" placeholder="Tambah sebarang butiran khas tentang kebolehcapaian, keselamatan, atau mercu tanda..."></textarea>
                </div>
              </div>

              <div class="submit-section">
                <button :disabled="loading" @click="submitAllData" class="btn-submit">
                  {{ loading ? 'Menyimpan...' : '✅ Hantar Harta' }}
                </button>
                <p v-if="success" class="success-message">✅ Harta berjaya disimpan!</p>
                <p v-if="error" class="error-message">{{ error }}</p>
              </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
              <button v-if="step > 1" @click="prevStep" class="btn-nav btn-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Kembali
              </button>
              <button v-if="step < tabs.length" @click="nextStep" class="btn-nav btn-next ml-auto">
                Seterusnya
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'
import HeaderNav from '../components/HeaderNav.vue'

// 🎉 Toast Notification System
const toast = ref({
  show: false,
  type: 'success',
  title: '',
  message: ''
})

let toastTimeout = null

function showToast(type, title, message) {
  if (toastTimeout) {
    clearTimeout(toastTimeout)
  }
  toast.value = {
    show: true,
    type,
    title,
    message
  }
  toastTimeout = setTimeout(() => {
    toast.value.show = false
  }, 3000)
}

function hideToast() {
  if (toastTimeout) {
    clearTimeout(toastTimeout)
  }
  toast.value.show = false
}

// 🧭 Axios Auth Header
axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`

// 🧑‍💼 User Info
const user = ref(null)
onMounted(() => {
  const userData = localStorage.getItem('user')
  user.value = userData ? JSON.parse(userData) : { name: 'Guest', role: 'guest' }
})

// 🧭 Step Navigation
const step = ref(1)
const tabs = ['Basic Info', 'Rooms Detail', 'Payment', 'Amenities', 'Location']

// 🧾 Property Form
const form = ref({
  title: '',
  address: '',
  property_type: '',
  area_name: '',
  contact: '',
  total_units: 0,
  available_rooms: 0,
  tenants_needed: '',
  current_tenants: '',
  contract_duration: '',
  gender: '',
  furnished_level: '',
  total_bathrooms: '',
  amenities: [],
  location_advantages: '',
  nearby_shop: '',
  nearby_facility: '',
  distance_ui_tm: '',
  rooms: []
})

// 💳 Payment Section
const payment = ref({
  payment_type: '',
  amount: '',
  deposit: '',
  utility: 'Disertakan',
  additional_bill: 0,
  note: ''
})

// 🖼️ Property Images
const propertyImages = ref([])
const propertyImagesPreview = ref([])

function handlePropertyImages(e) {
  const files = Array.from(e.target.files)
  propertyImages.value = files
  propertyImagesPreview.value = files.map(f => URL.createObjectURL(f))
}

// 🧩 Additional Room Details
const additionalRooms = ref([])
function addExtraRoom() {
  additionalRooms.value.push({
    _uid: Date.now() + Math.random(),
    name: '',
    images: [],
    images_preview: []
  })
}
function handleExtraRoomImages(e, extra) {
  const files = Array.from(e.target.files)
  extra.images = files
  extra.images_preview = files.map(f => URL.createObjectURL(f))
}

// 🏘️ Auto-generate Rooms
watch(() => form.value.available_rooms, (val) => {
  const num = Number(val) || 0
  form.value.rooms = Array.from({ length: num }, () => ({
    _uid: Date.now() + Math.random(),
    room_type: '',
    capacity: '',
    current_occupancy: '',
    bathroom_type: '',
    bed_type: '',
    room_images: [],
    bathroom_images: [],
    room_images_preview: [],
    bathroom_images_preview: []
  }))
})

// 📸 Handle Room Image Upload
function handleFileChange(e, room, key) {
  const files = Array.from(e.target.files)
  room[key] = files
  room[`${key}_preview`] = files.map(f => URL.createObjectURL(f))
}

// 🧰 Amenities List
const amenitiesList = [
  'WiFi', 'Washing Machine', 'Refrigerator', 'Water Heater', 'Air Conditioning',
  'Fan', 'Bed / Mattress', 'Wardrobe', 'Study Table', 'Kitchen Access', 'Parking', 'Security (Gated/CCTV)'
]

// Custom Amenities
const customAmenities = ref([])
const newAmenity = ref('')

function addCustomAmenity() {
  if (newAmenity.value.trim()) {
    customAmenities.value.push(newAmenity.value.trim())
    form.value.amenities.push(newAmenity.value.trim())
    newAmenity.value = ''
    showToast('success', 'Amenity Added', 'Custom amenity has been added successfully!')
  }
}

function removeCustomAmenity(index) {
  const amenity = customAmenities.value[index]
  customAmenities.value.splice(index, 1)
  const amenityIndex = form.value.amenities.indexOf(amenity)
  if (amenityIndex > -1) {
    form.value.amenities.splice(amenityIndex, 1)
  }
  showToast('info', 'Amenity Removed', 'Custom amenity has been removed')
}

// ⚙️ Navigation
function nextStep() { 
  if (step.value < tabs.length) { 
    step.value++
    scrollToTop()
    showToast('info', 'Step Changed', `Moved to ${tabs[step.value - 1]}`)
  }
}

function prevStep() { 
  if (step.value > 1) { 
    step.value--
    scrollToTop()
    showToast('info', 'Step Changed', `Back to ${tabs[step.value - 1]}`)
  }
}

function goToStep(num) { 
  step.value = num
  scrollToTop()
  showToast('info', 'Step Changed', `Navigated to ${tabs[num - 1]}`)
}

function scrollToTop() { window.scrollTo({ top: 0, behavior: 'smooth' }) }

// 🧠 Form State
const loading = ref(false)
const success = ref(false)
const error = ref(null)

// 🚀 Submit All Data
async function submitAllData() {
  loading.value = true
  success.value = false
  error.value = null

  try {
    const propertyData = new FormData()
    for (const key in form.value) {
      if (key === 'rooms') continue
      const value = form.value[key]
      if (Array.isArray(value) || typeof value === 'object') {
        propertyData.append(key, JSON.stringify(value))
      } else {
        propertyData.append(key, value ?? '')
      }
    }
    for (const file of propertyImages.value) propertyData.append('images[]', file)

    const propertyRes = await axios.post('/api/properties', propertyData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    const propertyId = propertyRes.data.property.id

    for (const room of form.value.rooms) {
      if (!room.room_type && !room.capacity) continue
      const roomData = new FormData()
      roomData.append('property_id', propertyId)
      roomData.append('room_type', room.room_type || '')
      roomData.append('capacity', room.capacity || 0)
      roomData.append('current_occupancy', room.current_occupancy || 0)
      roomData.append('bathroom_type', room.bathroom_type || '')
      roomData.append('bed_type', room.bed_type || '')
      if (room.room_images?.length)
        for (const file of room.room_images)
          roomData.append('room_images[]', file)
      if (room.bathroom_images?.length)
        for (const file of room.bathroom_images)
          roomData.append('bathroom_images[]', file)
      await axios.post(`/api/properties/${propertyId}/rooms`, roomData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
    }

    for (const extra of additionalRooms.value) {
      if (!extra.name.trim()) continue
      const extraData = new FormData()
      extraData.append('name', extra.name)
      for (const file of extra.images)
        extraData.append('images[]', file)
      await axios.post(`/api/properties/${propertyId}/additional-rooms`, extraData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
    }

    if (payment.value.payment_type) {
      await axios.post('/api/payments', {
        property_id: propertyId,
        payment_type: payment.value.payment_type,
        amount: Number(payment.value.amount) || 0,
        deposit: Number(payment.value.deposit) || 0,
        utility: payment.value.utility,
        additional_bill: Number(payment.value.additional_bill) || 0,
        note: payment.value.note || null,
        status: 'paid'
      })
    }

    success.value = true
    showToast('success', 'Success!', 'Property has been saved successfully!')
  } catch (err) {
    console.error('❌ Submit Error:', err)
    if (err.response?.data?.errors) {
      error.value = Object.values(err.response.data.errors).flat().join(' | ')
    } else {
      error.value = err.response?.data?.message || '❌ Failed to save property details.'
    }
    showToast('error', 'Error!', error.value)
  } finally {
    loading.value = false
  }
}
</script>


<style scoped>
/* Container Wrapper - No Horizontal Scroll */
.container-wrapper {
  max-width: 100%;
  width: 100%;
  margin: 0 auto;
  padding: 0;
  overflow-x: hidden;
}

/* Dashboard Container with Glow */
.dashboard-box {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 1.5rem;
  box-shadow: 
    0 0 60px rgba(6, 182, 212, 0.15),
    0 20px 25px -5px rgba(0, 0, 0, 0.1),
    0 10px 10px -5px rgba(0, 0, 0, 0.04),
    inset 0 1px 0 rgba(255, 255, 255, 0.6);
  border: 1px solid rgba(6, 182, 212, 0.2);
  overflow: hidden;
  position: relative;
  margin: 0 1rem;
}

.dashboard-box::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #06b6d4, #14b8a6, #2dd4bf, #06b6d4);
  background-size: 200% 100%;
  animation: shimmer 3s linear infinite;
}

@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

.dashboard-flex {
  display: flex;
  min-height: auto;
}

/* Sidebar */
.sidebar {
  width: 280px;
  min-width: 280px;
  max-width: 280px;
  background: linear-gradient(180deg, #0e7490 0%, #0891b2 50%, #06b6d4 100%);
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  position: relative;
  overflow: hidden;
  flex-shrink: 0;
}

.sidebar::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(45, 212, 191, 0.3) 0%, transparent 70%);
  animation: rotate 20s linear infinite;
}

@keyframes rotate {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.sidebar-header {
  margin-bottom: 2rem;
  position: relative;
  z-index: 1;
}

.sidebar-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: white;
  margin-bottom: 0.5rem;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.sidebar-subtitle {
  font-size: 0.875rem;
  color: rgba(255, 255, 255, 0.8);
  text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  flex: 1;
  position: relative;
  z-index: 1;
}

.nav-item {
  width: 100%;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  border-radius: 0.75rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
  border: none;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  color: rgba(255, 255, 255, 0.8);
  position: relative;
  overflow: hidden;
}

.nav-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s;
}

.nav-item:hover::before {
  left: 100%;
}

.nav-item:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: translateX(5px);
  box-shadow: 0 4px 15px rgba(45, 212, 191, 0.3);
}

.nav-item-active {
  background: linear-gradient(135deg, #2dd4bf, #14b8a6) !important;
  color: white !important;
  box-shadow: 
    0 10px 25px rgba(45, 212, 191, 0.4),
    0 0 30px rgba(45, 212, 191, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.3) !important;
  transform: translateX(8px) !important;
}

.nav-item-completed {
  background: rgba(45, 212, 191, 0.2);
  color: white;
  border: 1px solid rgba(45, 212, 191, 0.4);
}

.nav-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
  border-radius: 0.5rem;
  background: rgba(255, 255, 255, 0.2);
  font-size: 0.875rem;
  transition: all 0.3s;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  flex-shrink: 0;
}

.nav-icon-active {
  background: rgba(255, 255, 255, 0.95);
  color: #0891b2;
  box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
  font-weight: 700;
}

.nav-icon-completed {
  background: rgba(45, 212, 191, 0.9);
  color: white;
  box-shadow: 0 0 15px rgba(45, 212, 191, 0.6);
}

.nav-text {
  flex: 1;
  text-align: left;
  min-width: 0;
}

.nav-name {
  font-size: 0.875rem;
  font-weight: 500;
}

.nav-step {
  font-size: 0.75rem;
  opacity: 0.7;
}

.nav-chevron {
  flex-shrink: 0;
}

.progress-section {
  margin-top: 2rem;
  padding-top: 1.5rem;
  border-top: 1px solid rgba(255, 255, 255, 0.2);
  position: relative;
  z-index: 1;
}

.progress-card {
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  border-radius: 0.75rem;
  padding: 1rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.progress-label {
  font-size: 0.75rem;
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: 0.5rem;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.progress-wrapper {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.progress-bar-bg {
  flex: 1;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 9999px;
  height: 0.6rem;
  overflow: hidden;
  box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.progress-bar-fill {
  background: linear-gradient(90deg, #2dd4bf, #14b8a6, #0891b2);
  background-size: 200% 100%;
  height: 100%;
  border-radius: 9999px;
  transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 0 15px rgba(45, 212, 191, 0.6);
  animation: progressShine 2s linear infinite;
}

@keyframes progressShine {
  0% {
    background-position: 200% 0;
  }
  100% {
    background-position: -200% 0;
  }
}

.progress-text {
  font-size: 0.875rem;
  color: white;
  font-weight: 500;
}

/* Content Area */
.content-area {
  flex: 1;
  padding: 2.5rem;
  background: linear-gradient(135deg, #ecfeff 0%, #f0fdfa 50%, #ffffff 100%);
  overflow-y: auto;
  overflow-x: hidden;
  min-width: 0;
}

.content-section {
  animation: fadeIn 0.3s ease-in-out;
  max-width: 100%;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.section-header {
  margin-bottom: 2rem;
}

.section-title {
  font-size: 1.5rem;
  font-weight: 700;
  background: linear-gradient(135deg, #0891b2, #14b8a6, #2dd4bf);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 0.5rem;
  position: relative;
  display: inline-block;
}

.section-title::after {
  content: '';
  position: absolute;
  bottom: -4px;
  left: 0;
  width: 60px;
  height: 3px;
  background: linear-gradient(90deg, #14b8a6, #2dd4bf);
  border-radius: 2px;
  box-shadow: 0 2px 10px rgba(20, 184, 166, 0.4);
}

.section-subtitle {
  color: rgb(100, 116, 139);
  font-size: 0.95rem;
}

/* Form Elements */
.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
  max-width: 100%;
}

.form-grid-location {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
  max-width: 100%;
}

.form-group {
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.full-width {
  grid-column: 1 / -1;
}

.full-width-location {
  grid-column: 1 / -1;
}

.optional-text {
  font-size: 0.8rem;
  color: #5eead4;
  font-weight: 400;
}

.form-label {
  display: block;
  font-weight: 600;
  color: #0e7490;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
  letter-spacing: 0.01em;
}

.form-input {
  width: 100%;
  padding: 0.75rem 1rem;
  background: rgba(255, 255, 255, 0.9);
  border: 2px solid #99f6e4;
  border-radius: 0.75rem;
  color: #0e7490;
  font-size: 0.95rem;
  font-weight: 500;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 2px 8px rgba(20, 184, 166, 0.08);
  box-sizing: border-box;
}

.form-input:focus {
  border-color: #14b8a6;
  background: white;
  box-shadow: 
    0 0 0 4px rgba(20, 184, 166, 0.15),
    0 4px 20px rgba(20, 184, 166, 0.2);
  outline: none;
  transform: translateY(-2px);
}

.form-input:hover {
  border-color: #5eead4;
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.12);
}

.form-input::placeholder {
  color: rgb(148, 163, 184);
}

.file-input {
  padding: 0.5rem;
  cursor: pointer;
}

/* Image Previews */
.image-preview {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-top: 0.75rem;
}

.preview-img {
  width: 5rem;
  height: 5rem;
  object-fit: cover;
  border-radius: 0.75rem;
  border: 3px solid #99f6e4;
  transition: all 0.3s;
  box-shadow: 0 4px 10px rgba(20, 184, 166, 0.15);
}

.preview-img:hover {
  border-color: #5eead4;
  transform: scale(1.05);
  box-shadow: 0 8px 20px rgba(20, 184, 166, 0.25);
}

/* Rooms Section */
.empty-state {
  text-align: center;
  padding: 3rem 1rem;
  color: #0891b2;
  font-style: italic;
  background: linear-gradient(135deg, #f0fdfa, #ffffff);
  border-radius: 1rem;
  border: 3px dashed #99f6e4;
  box-shadow: 0 4px 15px rgba(20, 184, 166, 0.08);
}

.rooms-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.room-card {
  background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 100%);
  border: 2px solid #99f6e4;
  border-radius: 1rem;
  padding: 1.5rem;
  box-shadow: 0 4px 15px rgba(20, 184, 166, 0.08);
  transition: all 0.3s;
  position: relative;
  overflow: hidden;
}

.room-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, #14b8a6, #2dd4bf, #5eead4);
  opacity: 0;
  transition: opacity 0.3s;
}

.room-card:hover {
  border-color: #5eead4;
  box-shadow: 0 8px 30px rgba(20, 184, 166, 0.15);
  transform: translateY(-2px);
}

.room-card:hover::before {
  opacity: 1;
}

.room-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #0e7490;
  margin-bottom: 1rem;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid #99f6e4;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.room-title::before {
  content: '✨';
  font-size: 1rem;
}

.additional-section {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 2px solid rgb(226, 232, 240);
}

/* Amenities Grid */
.amenities-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  max-width: 100%;
}

.amenity-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem;
  background: linear-gradient(135deg, #ffffff 0%, #f0fdfa 100%);
  border: 2px solid #ccfbf1;
  border-radius: 0.75rem;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.amenity-item::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(45, 212, 191, 0.3), transparent);
  transform: translate(-50%, -50%);
  transition: width 0.4s, height 0.4s;
}

.amenity-item:hover {
  background: linear-gradient(135deg, #ccfbf1 0%, #99f6e4 100%);
  border-color: #5eead4;
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(20, 184, 166, 0.2);
}

.amenity-item:hover::before {
  width: 200px;
  height: 200px;
}

.amenity-checkbox {
  width: 1.25rem;
  height: 1.25rem;
  accent-color: #14b8a6;
  cursor: pointer;
  filter: drop-shadow(0 2px 4px rgba(20, 184, 166, 0.3));
  flex-shrink: 0;
}

.amenity-label {
  font-size: 0.9rem;
  color: #0e7490;
  font-weight: 500;
  position: relative;
  z-index: 1;
}

/* Custom Amenities */
.custom-amenities-section {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 2px solid #ccfbf1;
}

.custom-amenities-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #0e7490;
  margin-bottom: 1rem;
}

.custom-amenity-input-wrapper {
  display: flex;
  gap: 1rem;
  margin-bottom: 1rem;
}

.custom-amenity-input-wrapper .form-input {
  flex: 1;
  min-width: 0;
}

.btn-add-amenity {
  background: linear-gradient(135deg, #14b8a6, #0891b2);
  color: white;
  font-weight: 600;
  padding: 0.75rem 1.5rem;
  border-radius: 0.75rem;
  border: none;
  cursor: pointer;
  transition: all 0.3s;
  box-shadow: 0 4px 15px rgba(20, 184, 166, 0.3);
  white-space: nowrap;
  flex-shrink: 0;
}

.btn-add-amenity:hover {
  background: linear-gradient(135deg, #0891b2, #06b6d4);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(20, 184, 166, 0.4);
}

.custom-amenities-list {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
}

.custom-amenity-tag {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: linear-gradient(135deg, #14b8a6, #2dd4bf);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  box-shadow: 0 2px 8px rgba(20, 184, 166, 0.3);
}

.remove-amenity-btn {
  background: rgba(255, 255, 255, 0.3);
  border: none;
  color: white;
  width: 1.5rem;
  height: 1.5rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 1.25rem;
  font-weight: 700;
  line-height: 1;
  transition: all 0.2s;
  flex-shrink: 0;
}

.remove-amenity-btn:hover {
  background: rgba(255, 255, 255, 0.5);
  transform: scale(1.1);
}

/* Submit Section */
.submit-section {
  text-align: center;
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 2px solid rgb(226, 232, 240);
}

.btn-submit {
  background: linear-gradient(135deg, #14b8a6, #0891b2);
  color: white;
  font-weight: 600;
  padding: 1rem 3rem;
  border-radius: 0.75rem;
  border: none;
  cursor: pointer;
  font-size: 1rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 
    0 10px 25px rgba(20, 184, 166, 0.3),
    0 0 30px rgba(20, 184, 166, 0.2);
  position: relative;
  overflow: hidden;
}

.btn-submit::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
  transition: left 0.5s;
}

.btn-submit:hover::before {
  left: 100%;
}

.btn-submit:hover {
  background: linear-gradient(135deg, #0891b2, #06b6d4);
  transform: translateY(-3px);
  box-shadow: 
    0 15px 35px rgba(20, 184, 166, 0.4),
    0 0 40px rgba(20, 184, 166, 0.3);
}

.btn-submit:active {
  transform: translateY(-1px);
}

.btn-submit:disabled {
  background: rgb(148, 163, 184);
  cursor: not-allowed;
  transform: none;
}

.success-message {
  color: #14b8a6;
  font-weight: 600;
  margin-top: 1rem;
  text-shadow: 0 0 10px rgba(20, 184, 166, 0.3);
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}

.error-message {
  color: rgb(220, 38, 38);
  font-weight: 500;
  margin-top: 1rem;
}

/* Navigation Buttons */
.nav-buttons {
  display: flex;
  justify-content: space-between;
  margin-top: 2rem;
  padding-top: 1.5rem;
  border-top: 2px solid rgb(226, 232, 240);
}

.btn-nav {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  border: none;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 0.95rem;
}

.btn-back {
  background: linear-gradient(135deg, #f0fdfa, #ccfbf1);
  color: #0e7490;
  border: 2px solid #99f6e4;
  font-weight: 600;
}

.btn-back:hover {
  background: linear-gradient(135deg, #ccfbf1, #99f6e4);
  border-color: #5eead4;
  box-shadow: 0 4px 15px rgba(20, 184, 166, 0.15);
}

.btn-next {
  background: linear-gradient(135deg, #14b8a6, #0891b2);
  color: white;
  box-shadow: 0 4px 15px rgba(20, 184, 166, 0.3);
  position: relative;
  overflow: hidden;
}

.btn-next::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.2);
  transform: translate(-50%, -50%);
  transition: width 0.4s, height 0.4s;
}

.btn-next:hover::before {
  width: 300px;
  height: 300px;
}

.btn-next:hover {
  background: linear-gradient(135deg, #0891b2, #06b6d4);
  box-shadow: 0 8px 25px rgba(20, 184, 166, 0.4);
  transform: translateY(-2px);
}

.btn-secondary {
  background: linear-gradient(135deg, #f0fdfa, #ccfbf1);
  color: #0e7490;
  font-weight: 600;
  padding: 0.75rem 1.5rem;
  border-radius: 0.625rem;
  border: 2px solid #99f6e4;
  cursor: pointer;
  transition: all 0.3s;
  margin-top: 1rem;
  box-shadow: 0 2px 8px rgba(20, 184, 166, 0.1);
}

.btn-secondary:hover {
  background: linear-gradient(135deg, #ccfbf1, #99f6e4);
  border-color: #5eead4;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(20, 184, 166, 0.2);
}

.ml-auto {
  margin-left: auto;
}

/* Toast Notification */
.toast-notification {
  position: fixed;
  top: rem;
  right: 2rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem 1.5rem;
  background: white;
  border-radius: 0.75rem;
  box-shadow: 
    0 20px 25px -5px rgba(0, 0, 0, 0.1),
    0 10px 10px -5px rgba(0, 0, 0, 0.04),
    0 0 30px rgba(20, 184, 166, 0.2);
  border-left: 4px solid #14b8a6;
  z-index: 9999;
  animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  min-width: 320px;
  max-width: 420px;
}

@keyframes slideIn {
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
  border-left-color: #14b8a6;
}

.toast-notification.success .toast-icon {
  background: linear-gradient(135deg, #14b8a6, #2dd4bf);
}

.toast-notification.error {
  border-left-color: #ef4444;
}

.toast-notification.error .toast-icon {
  background: linear-gradient(135deg, #ef4444, #f87171);
}

.toast-notification.info {
  border-left-color: #06b6d4;
}

.toast-notification.info .toast-icon {
  background: linear-gradient(135deg, #06b6d4, #22d3ee);
}

.toast-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  background: linear-gradient(135deg, #14b8a6, #2dd4bf);
  color: white;
  font-size: 1.25rem;
  font-weight: 700;
  flex-shrink: 0;
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
}

.toast-content {
  flex: 1;
  min-width: 0;
}

.toast-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: #0e7490;
  margin-bottom: 0.25rem;
}

.toast-message {
  font-size: 0.85rem;
  color: #64748b;
  line-height: 1.4;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .dashboard-flex {
    flex-direction: column;
  }

  .sidebar {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    padding: 1.25rem;
  }

  .content-area {
    max-width: 100%;
    padding: 2rem;
  }

  .sidebar-nav {
    flex-direction: row;
    flex-wrap: wrap;
  }

  .form-grid,
  .form-grid-location {
    grid-template-columns: 1fr;
  }

  .amenities-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 640px) {
  .container-wrapper {
    padding: 0;
  }

  .dashboard-box {
    margin: 0 0.5rem;
    border-radius: 1rem;
  }

  .content-area {
    padding: 1.5rem;
  }

  .section-title {
    font-size: 1.25rem;
  }

  .amenities-grid {
    grid-template-columns: 1fr;
  }

  .custom-amenity-input-wrapper {
    flex-direction: column;
  }

  .btn-add-amenity {
    width: 100%;
  }

  .toast-notification {
    top: 1rem;
    right: 1rem;
    left: 1rem;
    min-width: auto;
  }

  .nav-buttons {
    flex-direction: column;
    gap: 0.75rem;
  }

  .btn-nav {
    width: 100%;
    justify-content: center;
  }

  .ml-auto {
    margin-left: 0;
  }
}
</style>