<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { ref, computed, onMounted, onUnmounted } from 'vue'

const user = ref(null)
const menuOpen = ref(false)

const hydrateUser = () => {
  try {
    user.value = JSON.parse(localStorage.getItem('user') || 'null')
  } catch (e) {
    user.value = null
  }
}

const toggleMenu = () => (menuOpen.value = !menuOpen.value)
const closeMenu = () => (menuOpen.value = false)

const logout = () => {
  localStorage.removeItem('user')
  localStorage.removeItem('user_id')
  localStorage.removeItem('sanctum_token')
  user.value = null
  closeMenu()
}

const isAdmin = computed(() => user.value?.type_of_user === 'admin')

hydrateUser()

onMounted(() => {
  window.addEventListener('storage', hydrateUser)
})

onUnmounted(() => window.removeEventListener('storage', hydrateUser))
</script>

<template>
  <div class="site-shell">
    <header class="neo-header">
      <RouterLink class="brand" to="/" @click="closeMenu">
        <div class="brand-mark">L A M I</div>
        <p class="brand-subtitle">Luxury Auto Market Insights</p>
      </RouterLink>

      <button class="nav-toggle" @click="toggleMenu" aria-label="Toggle navigation">
        <span />
        <span />
        <span />
      </button>

      <nav :class="['nav-links', { open: menuOpen }]">
        <RouterLink to="/" @click="closeMenu">Articles</RouterLink>
        <RouterLink to="/brands" @click="closeMenu">Brands</RouterLink>
        <RouterLink to="/about" @click="closeMenu">About</RouterLink>
        <RouterLink to="/admin/cars" v-if="isAdmin" @click="closeMenu">Manage</RouterLink>
        <RouterLink to="/admin/create" v-if="user" @click="closeMenu">Write</RouterLink>
        <RouterLink to="/admin/dashboard" v-if="isAdmin" @click="closeMenu">Dashboard</RouterLink>
      </nav>

      <div class="nav-cta">
        <RouterLink v-if="!user" to="/login" class="btn btn-ghost" @click="closeMenu">Login</RouterLink>
        <RouterLink v-if="!user" to="/register" class="btn btn-primary glow-pill" @click="closeMenu">Join</RouterLink>

        <div v-if="user" class="user-chip">
          <span class="chip-label">{{ user.full_name || user.username || 'Guest' }}</span>
          <button class="chip-action" @click="logout">Logout</button>
        </div>
      </div>
    </header>

    <div class="page-body">
      <RouterView />
    </div>

    <footer class="neo-footer">
      <p>© {{ new Date().getFullYear() }} LAMI • A blog about remarkable cars.</p>
    </footer>
  </div>
</template>
