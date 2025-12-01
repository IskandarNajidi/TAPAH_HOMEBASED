<template>
  <div class="page">
    <HeaderNav :user="user" />
    <main>
      <h1>About Page</h1>
      <p>This page will display information about the Tapah HomeBased website and its purpose.</p>
    </main>
  </div>
</template>

<script>
import HeaderNav from '../components/HeaderNav.vue'

export default {
  components: { HeaderNav },
  setup() {
    const user = JSON.parse(localStorage.getItem('user'))
    return { user }
  }
}
</script>

<style>
.page main {
  text-align: center;
  margin-top: 100px;
}
</style>
