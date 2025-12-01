<template>
  <div class="profile-page">
    <!-- 🌐 Redesigned Header / Navbar -->
    <header class="navbar">
      <div class="logo-container">
        <div class="logo-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
          </svg>
        </div>
        <div class="logo">Tapah HomeBased</div>
      </div>
      
      <button class="menu-toggle" @click="toggleMobileMenu">☰</button>
      
      <nav class="nav-links" :class="{ active: mobileMenuOpen }">
        <router-link to="/home" class="nav-item">Home</router-link>
        <router-link to="/profile" class="nav-item active">Profile</router-link>
        <button class="logout-btn" @click="logout">Logout</button>
      </nav>
    </header>

    <!-- 🧾 Profile Content -->
    <main v-if="user" class="profile-container">
      <!-- Profile Header with Avatar -->
      <div class="profile-header">
        <div class="avatar-container">
          <div class="avatar" :style="{ backgroundImage: `url(${form.avatar || defaultAvatar})` }">
            <div class="edit-overlay" @click="triggerFileInput">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
              </svg>
              <span>Edit Photo</span>
            </div>
          </div>
          <input 
            type="file" 
            ref="fileInput" 
            @change="handleAvatarUpload" 
            accept="image/*" 
            class="file-input"
          />
        </div>
        <h1 class="profile-title">User Profile</h1>
        <p class="profile-subtitle">Manage your account settings and preferences</p>
      </div>

      <form @submit.prevent="updateProfile" class="profile-form">
        <!-- Personal Information Section -->
        <div class="form-section">
          <h2 class="section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            Personal Information
          </h2>
          <div class="form-grid">
            <div class="form-group">
              <label>Full Name</label>
              <input v-model="form.name" type="text" required placeholder="Enter your full name" />
            </div>

            <div class="form-group">
              <label>Phone Number</label>
              <input v-model="form.phone" type="tel" required placeholder="+60 12 345 6789" />
            </div>

            <div class="form-group">
              <label>Resident Area</label>
              <input v-model="form.resident" type="text" required placeholder="Taman / Perumahan" />
            </div>

            <div class="form-group">
              <label>Sex</label>
              <select v-model="form.sex" required>
                <option disabled value="">Select your gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Account Information Section -->
        <div class="form-section">
          <h2 class="section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
            Account Information
          </h2>
          <div class="form-grid">
            <div class="form-group">
              <label>Email Address</label>
              <input v-model="form.email" type="email" :disabled="isGoogleUser" required placeholder="your.email@example.com" />
              <small v-if="isGoogleUser" class="google-note">Connected via Google account</small>
            </div>

            <div class="form-group">
              <label>Account Role</label>
              <div class="role-display">
                <span class="role-badge" :class="form.role">{{ form.role }}</span>
              </div>
              <small>Role cannot be changed</small>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="actions">
          <button type="submit" class="save-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
              <polyline points="17 21 17 13 7 13 7 21"></polyline>
              <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Save Changes
          </button>
          <button type="button" class="delete-btn" @click="deleteAccount">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="3 6 5 6 21 6"></polyline>
              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            </svg>
            Delete Account
          </button>
        </div>
      </form>
    </main>

    <p v-else class="loading">Loading your profile...</p>
  </div>
</template>

<script>
import axios from 'axios'
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

export default {
  name: 'ProfilePage',
  setup() {
    const router = useRouter()
    const user = ref(JSON.parse(localStorage.getItem('user')))
    const fileInput = ref(null)
    const form = ref({
      name: '',
      email: '',
      phone: '',
      resident: '',
      sex: '',
      role: '',
      avatar: ''
    })

    const defaultAvatar = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjFGNUZBIi8+CjxwYXRoIGQ9Ik0xMDAgMTE1QzExNy42NzEgMTE1IDEzMiAxMDAuNjcxIDEzMiA4M0MxMzIgNjUuMzI4OSAxMTcuNjcxIDUxIDEwMCA1MUM4Mi4zMjg5IDUxIDY4IDY1LjMyODkgNjggODNDNjggMTAwLjY3MSA4Mi4zMjg5IDExNSAxMDAgMTE1WiIgZmlsbD0iIzAwQjg5QzMiLz4KPHBhdGggZD0iTTY4IDEzMUM2OCAxMjUuNDc3IDcyLjQ3NzIgMTIxIDc4IDEyMUgxMjJDMTI3LjUyMyAxMjEgMTMyIDEyNS40NzcgMTMyIDEzMVYxNTZINjhWMTMxWiIgZmlsbD0iIzAwQjg5QzMiLz4KPC9zdmc+'

    const isGoogleUser = ref(false)
    const mobileMenuOpen = ref(false)

    const toggleMobileMenu = () => {
      mobileMenuOpen.value = !mobileMenuOpen.value
    }

    const triggerFileInput = () => {
      fileInput.value.click()
    }

    const handleAvatarUpload = (event) => {
      const file = event.target.files[0]
      if (file) {
        const reader = new FileReader()
        reader.onload = (e) => {
          form.value.avatar = e.target.result
          // In a real app, you would upload this to your server
          console.log('Avatar updated (would upload to server)')
        }
        reader.readAsDataURL(file)
      }
    }

    const fetchUser = async () => {
      try {
        const res = await axios.get('/api/user')
        user.value = res.data
        localStorage.setItem('user', JSON.stringify(res.data))
        Object.assign(form.value, res.data)
        isGoogleUser.value = !!res.data.google_id
      } catch (err) {
        console.error('Failed to fetch user:', err)
      }
    }

    const updateProfile = async () => {
      try {
        await axios.put('/api/user/update', form.value)
        alert('Profile updated successfully!')
        await fetchUser()
      } catch (err) {
        alert(err.response?.data?.message ?? 'Update failed')
      }
    }

    const deleteAccount = async () => {
      if (!confirm('Are you sure you want to delete your account? This action cannot be undone.')) return
      try {
        await axios.delete('/api/user/delete')
        alert('Account deleted successfully.')
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        router.push('/')
      } catch (err) {
        alert(err.response?.data?.message ?? 'Account deletion failed')
      }
    }

    const logout = async () => {
      try {
        await axios.post('/api/logout').catch(() => axios.post('/auth/google/logout'))
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        router.push('/')
      } catch (err) {
        alert('Logout failed')
      }
    }

    onMounted(fetchUser)

    return { 
      user, 
      form, 
      updateProfile, 
      deleteAccount, 
      logout, 
      isGoogleUser,
      mobileMenuOpen,
      toggleMobileMenu,
      fileInput,
      triggerFileInput,
      handleAvatarUpload,
      defaultAvatar
    }
  },
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

/* 🌐 Redesigned Navbar */
.navbar {
  background: linear-gradient(135deg, #00b8a3 0%, #007f73 100%);
  color: #fff;
  padding: 12px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 12px rgba(0, 127, 115, 0.15);
  border-radius: 12px;
  max-width: 1200px;
  margin: 0 auto;
  font-family: 'Inter', sans-serif;
}

.logo-container {
  display: flex;
  align-items: center;
  gap: 10px;
}

.logo-icon {
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 6px;
  padding: 4px;
}

.logo {
  font-size: 20px;
  font-weight: 700;
  letter-spacing: -0.5px;
}

.nav-links {
  display: flex;
  gap: 28px;
  align-items: center;
}

.nav-item {
  color: #fff;
  text-decoration: none;
  font-weight: 500;
  font-size: 15px;
  position: relative;
  padding: 8px 0;
  transition: all 0.3s ease;
}

.nav-item::after {
  content: '';
  position: absolute;
  width: 0;
  height: 2px;
  bottom: 0;
  left: 50%;
  background-color: white;
  transition: all 0.3s ease;
  transform: translateX(-50%);
}

.nav-item:hover::after {
  width: 100%;
}

.nav-item:hover {
  text-shadow: 0 0 8px rgba(255, 255, 255, 0.5);
}

.router-link-active {
  font-weight: 600;
}

.router-link-active::after {
  width: 100%;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-left: 10px;
}

.user-role {
  background: rgba(255, 255, 255, 0.2);
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.logout-btn {
  background-color: #ff6b6b;
  color: white;
  border: none;
  padding: 8px 18px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  font-size: 14px;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(255, 107, 107, 0.3);
}

.logout-btn:hover {
  background-color: #ff5252;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
}


/* Mobile responsive styles */
.menu-toggle {
  display: none;
  background: none;
  border: none;
  color: white;
  font-size: 24px;
  cursor: pointer;
}

@media (max-width: 768px) {
  .navbar {
    padding: 12px 20px;
    flex-wrap: wrap;
    margin: 15px;
  }
  
  .menu-toggle {
    display: block;
  }
  
  .nav-links {
    display: none;
    width: 100%;
    flex-direction: column;
    gap: 15px;
    padding-top: 20px;
  }
  
  .nav-links.active {
    display: flex;
  }
}

/* 🧾 Profile Layout */
.profile-page {
  background-color: #f8fafc;
  min-height: 100vh;
  padding: 0 20px 40px;
  font-family: 'Inter', sans-serif;
}

.profile-container {
  max-width: 700px;
  margin: 40px auto;
  background: white;
  padding: 40px;
  border-radius: 16px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
}

/* Profile Header */
.profile-header {
  text-align: center;
  margin-bottom: 40px;
  padding-bottom: 30px;
  border-bottom: 1px solid #f1f5f9;
}

.avatar-container {
  display: inline-block;
  position: relative;
  margin-bottom: 20px;
}

.avatar {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background-size: cover;
  background-position: center;
  background-color: #f1f5f9;
  position: relative;
  overflow: hidden;
  border: 4px solid white;
  box-shadow: 0 8px 20px rgba(0, 184, 163, 0.2);
}

.edit-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
  color: white;
  padding: 10px 0 5px;
  text-align: center;
  cursor: pointer;
  opacity: 0;
  transition: opacity 0.3s ease;
  font-size: 12px;
  font-weight: 500;
}

.avatar:hover .edit-overlay {
  opacity: 1;
}

.edit-overlay svg {
  display: block;
  margin: 0 auto 4px;
}

.file-input {
  display: none;
}

.profile-title {
  font-size: 28px;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 8px;
}

.profile-subtitle {
  font-size: 16px;
  color: #64748b;
  font-weight: 400;
}

/* Form Sections */
.form-section {
  margin-bottom: 40px;
}

.section-title {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 18px;
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 24px;
  padding-bottom: 12px;
  border-bottom: 1px solid #f1f5f9;
}

.section-title svg {
  color: #00b8a3;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-weight: 500;
  color: #374151;
  margin-bottom: 8px;
  font-size: 14px;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  font-size: 15px;
  transition: all 0.3s ease;
  background-color: #fff;
  font-family: 'Inter', sans-serif;
}

.form-group input:focus,
.form-group select:focus {
  outline: none;
  border-color: #00b8a3;
  box-shadow: 0 0 0 3px rgba(0, 184, 163, 0.1);
}

.form-group input:disabled {
  background-color: #f8fafc;
  color: #94a3b8;
  cursor: not-allowed;
}

.role-display {
  padding: 12px 0;
}

.role-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 600;
  text-transform: capitalize;
}

.role-badge.tenant {
  background: rgba(0, 184, 163, 0.1);
  color: #007f73;
}

.role-badge.landlord {
  background: rgba(59, 130, 246, 0.1);
  color: #1d4ed8;
}

small {
  display: block;
  margin-top: 6px;
  color: #64748b;
  font-size: 12px;
}

.google-note {
  color: #00b8a3;
  font-weight: 500;
}

/* Action Buttons */
.actions {
  display: flex;
  gap: 16px;
  margin-top: 40px;
  padding-top: 30px;
  border-top: 1px solid #f1f5f9;
}

.save-btn, .delete-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 14px 24px;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  font-size: 15px;
  cursor: pointer;
  transition: all 0.3s ease;
  flex: 1;
  font-family: 'Inter', sans-serif;
}

.save-btn {
  background: linear-gradient(135deg, #00b8a3 0%, #007f73 100%);
  color: white;
  box-shadow: 0 4px 12px rgba(0, 184, 163, 0.3);
}

.save-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 184, 163, 0.4);
}

.delete-btn {
  background-color: white;
  color: #ef4444;
  border: 1px solid #fecaca;
  box-shadow: 0 2px 6px rgba(254, 202, 202, 0.3);
}

.delete-btn:hover {
  background-color: #fef2f2;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(254, 202, 202, 0.4);
}

.loading {
  text-align: center;
  margin-top: 100px;
  font-size: 18px;
  color: #64748b;
}

/* Responsive Design */
@media (max-width: 768px) {
  .profile-container {
    margin: 20px 15px;
    padding: 25px;
  }
  
  .form-grid {
    grid-template-columns: 1fr;
  }
  
  .actions {
    flex-direction: column;
  }
  
  .avatar {
    width: 100px;
    height: 100px;
  }
}

@media (max-width: 480px) {
  .profile-container {
    padding: 20px;
  }
  
  .profile-title {
    font-size: 24px;
  }
  
  .section-title {
    font-size: 16px;
  }
}
</style>