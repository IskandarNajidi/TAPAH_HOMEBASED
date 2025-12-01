import { createRouter, createWebHistory } from 'vue-router'

// ✅ Normal imports for all pages
import Login from '../views/LoginPage.vue'
import Home from '../views/HomePage.vue'
import Register from '../views/RegisterPage.vue'
import Profile from '../views/ProfilePage.vue'
import TenantProperty from '../views/TenantPropertyPage.vue'
import Favorites from '../views/FavoritesPage.vue'
import Contact from '../views/ContactPage.vue'
import About from '../views/AboutPage.vue'
import LandlordPropertyEdit from '../views/LandlordPropertyEditPage.vue'
import LandlordPropertyListing from '../views/LandlordPropertyListingPage.vue'

// ✅ Define routes
const routes = [
  { path: '/', component: Login },
  { path: '/login', component: Login },
  { path: '/home', component: Home },
  { path: '/register', component: Register },
  { path: '/profile', component: Profile },
  { path: '/property', component: TenantProperty },
  { path: '/favorites', component: Favorites },
  { path: '/contact', component: Contact },
  { path: '/about', component: About },
  { path: '/property-edit', component: LandlordPropertyEdit },
  { path: '/property-listing', component: LandlordPropertyListing },
]

// ✅ Export router
export default createRouter({
  history: createWebHistory(),
  routes
})

