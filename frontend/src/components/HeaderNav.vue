<template>
  <header class="navbar">
    <div class="logo-container">
      <div class="logo-icon">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
          <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
      </div>
      <div class="logo">SoraResidence</div>
    </div>

    <button class="menu-toggle" @click="toggleMobileMenu">☰</button>

    <nav class="nav-links" :class="{ active: mobileMenuOpen }">
      <!-- Tenant Navigation -->
      <template v-if="user && user.role === 'tenant'">
        <router-link to="/home" class="nav-item">Laman Utama</router-link>
        <router-link to="/property" class="nav-item">Rumah Sewa</router-link>
        <router-link to="/favorites" class="nav-item">Kegemaran</router-link>
        <router-link to="/contact" class="nav-item"
          >Hubungi Tuan Rumah</router-link
        >
        <router-link to="/about" class="nav-item">Tentang Kami</router-link>
        <router-link to="/profile" class="nav-item">Akaun</router-link>
      </template>

      <!-- Landlord Navigation -->
      <template v-else-if="user && user.role === 'landlord'">
        <router-link to="/home" class="nav-item">Laman Utama</router-link>
        <router-link to="/property-edit" class="nav-item"
          >Sunting Rumah Sewa</router-link
        >
        <router-link to="/property-listing" class="nav-item"
          >Senaraikan Rumah</router-link
        >
        <router-link to="/about" class="nav-item">Tentang Kami</router-link>
        <router-link to="/profile" class="nav-item">Akaun</router-link>
      </template>

      <div class="user-info" v-if="user && user.role !== 'guest'">
        <div class="user-role">{{ user.role }}</div>
        <button class="logout-btn" @click="logout">Logout</button>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { useRouter } from "vue-router";
import axios from "axios";
import { defineProps, ref } from "vue";

const props = defineProps({
  user: {
    type: Object,
    default: () => ({ name: "Guest", role: "guest" }),
  },
});

const router = useRouter();
const mobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
};

const logout = async () => {
  try {
    await axios
      .post("/api/logout")
      .catch(() => axios.post("/auth/google/logout"));
  } catch {
    console.warn("Logout request failed, clearing local data anyway.");
  }
  localStorage.removeItem("token");
  localStorage.removeItem("user");
  router.push("/");
  mobileMenuOpen.value = false;
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

/* 🌐 Redesigned Navbar */
.navbar {
  background: linear-gradient(135deg, #00b8a3 0%, #007f73 100%);
  color: #fff;
  padding: 15px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 12px rgba(0, 127, 115, 0.15);
  border-radius: 0px;
  max-width: 2000px;
  margin: 0 auto;
  font-family: "Inter", sans-serif;
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
  content: "";
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

  .user-info {
    margin-top: 10px;
    width: 100%;
    justify-content: center;
  }
}
</style>
