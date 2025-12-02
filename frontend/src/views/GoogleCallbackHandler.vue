<script setup>
import { useRoute, useRouter } from 'vue-router'
import CryptoJS from 'crypto-js'

const route = useRoute()
const router = useRouter()

// Read token from URL query
const token = route.query.token

if (token) {
  const secretKey = import.meta.env.VITE_CRYPTO_KEY || 'SORA_DEV_KEY'
  const encryptedToken = CryptoJS.AES.encrypt(token, secretKey).toString()

  sessionStorage.setItem('token', encryptedToken)
  router.push('/home')
} else {
  router.push('/login')
}
</script>

<template>
  <div>Signing in with Google...</div>S
</template>
