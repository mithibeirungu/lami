<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { ref, computed, onMounted, onUnmounted } from 'vue'

const user = ref(null)
const menuOpen = ref(false)
const isDark = ref(false)

const hydrateUser = () => {
  try {
    user.value = JSON.parse(localStorage.getItem('user') || 'null')
  } catch (e) {
    user.value = null
  }
}

const initTheme = () => {
  const saved = localStorage.getItem('theme')
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
  isDark.value = saved ? saved === 'dark' : prefersDark
  applyTheme()
}

const applyTheme = () => {
  const root = document.documentElement
  if (isDark.value) {
    root.style.colorScheme = 'dark'
    root.classList.add('dark-mode')
  } else {
    root.style.colorScheme = 'light'
    root.classList.remove('dark-mode')
  }
  localStorage.setItem('theme', isDark.value ? 'dark' : 'light')
}

const toggleTheme = () => {
  isDark.value = !isDark.value
  applyTheme()
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

const isAdmin = computed(() => {
  const role = user.value?.role
  return role === 'overseer' || role === 'motor_scribe'
})

hydrateUser()
initTheme()

onMounted(() => {
  window.addEventListener('storage', hydrateUser)
})

onUnmounted(() => window.removeEventListener('storage', hydrateUser))
</script>

<template>
  <div class="site-shell">
    <header class="neo-header">
      <RouterLink class="brand" to="/" @click="closeMenu">
        <div class="brand-mark">LAMI</div>
      </RouterLink>

      <button class="nav-toggle" @click="toggleMenu" aria-label="Toggle navigation">
        <span />
        <span />
        <span />
      </button>

      <nav :class="['nav-links', { open: menuOpen }]">
        <RouterLink to="/" @click="closeMenu">Home</RouterLink>
        <RouterLink to="/login" v-if="!user" @click="closeMenu">Login</RouterLink>
        <RouterLink to="/register" v-if="!user" @click="closeMenu">Register</RouterLink>
        <RouterLink to="/admin/create" v-if="user" @click="closeMenu">Create</RouterLink>
        <RouterLink to="/admin/dashboard" v-if="isAdmin" @click="closeMenu">Dashboard</RouterLink>
      </nav>

      <div class="nav-cta">
        <!-- Modern animated theme toggle -->
        <button class="theme-switch" @click="toggleTheme" :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'" :class="{ active: !isDark }">
          <span class="switch-track">
            <span class="switch-thumb">
              <!-- Moon icon -->
              <svg v-if="isDark" class="theme-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
              </svg>
              <!-- Sun icon -->
              <svg v-else class="theme-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="5" />
                <line x1="12" y1="1" x2="12" y2="3" />
                <line x1="12" y1="21" x2="12" y2="23" />
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                <line x1="1" y1="12" x2="3" y2="12" />
                <line x1="21" y1="12" x2="23" y2="12" />
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
              </svg>
            </span>
          </span>
        </button>

        <RouterLink v-if="!user" to="/login" class="btn btn-ghost" @click="closeMenu">Login</RouterLink>
        <RouterLink v-if="!user" to="/register" class="btn btn-primary" @click="closeMenu">Register</RouterLink>

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
      <p>© {{ new Date().getFullYear() }} LAMI</p>
    </footer>
  </div>
</template>
