import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import CryptoJS from 'crypto-js'
import './style.css' // optional if you use Tailwind or custom CSS

// ======================================================
// ✅ Secure Axios Global Configuration
// ======================================================

// Use environment variable for base URL (Vite auto-detects .env)
axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL || 'https://tapahhomebased-production.up.railway.app/api'

// Sanctum (token-based), so no need for credentials or CSRF cookie
axios.defaults.withCredentials = false

// Automatically attach decrypted Bearer token
axios.interceptors.request.use((config) => {
  const encryptedToken = sessionStorage.getItem('token')
  if (encryptedToken) {
    try {
      const bytes = CryptoJS.AES.decrypt(encryptedToken, import.meta.env.VITE_CRYPTO_KEY || 'SORA_DEV_KEY')
      const token = bytes.toString(CryptoJS.enc.Utf8)
      if (token) {
        config.headers.Authorization = `Bearer ${token}`
      }
    } catch (err) {
      console.error('Token decryption failed:', err)
    }
  }
  return config
}, (error) => {
  return Promise.reject(error)
})

// Handle 401 Unauthorized globally
axios.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      sessionStorage.clear()
      console.warn('Session expired. Logging out...')
      window.location.href = '/login'
    }
    return Promise.reject(error)
  }
)

// ======================================================
// ✅ Create Vue App
// ======================================================
const app = createApp(App)
app.use(router)
app.mount('#app')
