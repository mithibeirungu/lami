<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'
import { useRouter } from 'vue-router'

const stats = ref({ cars: 0, users: 0, comments: 0, favorites: 0 })
const router = useRouter()
const loading = ref(false)
const refreshing = ref(false)

const load = async () => {
  loading.value = true
  try {
    const res = await api.getAdminDashboard()
    stats.value = res.data
  } catch (e) {
    console.error(e)
    if (e.response && e.response.status === 403) {
      alert('Forbidden. Admins only.')
      router.push('/login')
    }
  } finally {
    loading.value = false
  }
}

const refresh = async () => {
  refreshing.value = true
  await load()
  refreshing.value = false
}

onMounted(load)

// Auto-refresh every 30 seconds
const interval = setInterval(load, 30000)
</script>

<template>
  <div class="dashboard">
    <div class="dashboard-header">
      <h3>Dashboard Stats</h3>
      <button class="btn btn-sm btn-ghost" @click="refresh" :disabled="refreshing">
        {{ refreshing ? 'Refreshing...' : 'Refresh' }}
      </button>
    </div>

    <div v-if="loading" style="text-align:center; padding:40px">Loading stats...</div>

    <div v-else class="stats-grid">
      <div class="stat-card">
        <div class="stat-label">Total Cars</div>
        <div class="stat-value">{{ stats.cars }}</div>
        <div class="stat-icon">🚗</div>
      </div>

      <div class="stat-card">
        <div class="stat-label">Total Users</div>
        <div class="stat-value">{{ stats.users }}</div>
        <div class="stat-icon">👥</div>
      </div>

      <div class="stat-card">
        <div class="stat-label">Total Comments</div>
        <div class="stat-value">{{ stats.comments }}</div>
        <div class="stat-icon">💬</div>
      </div>

      <div class="stat-card">
        <div class="stat-label">Total Favorites</div>
        <div class="stat-value">{{ stats.favorites }}</div>
        <div class="stat-icon">❤️</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.dashboard {
  background: var(--surface);
  padding: 24px;
  border-radius: 12px;
  box-shadow: 0 2px 8px var(--shadow);
}

.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  padding-bottom: 16px;
  border-bottom: 1px solid #f0f4f8;
}

.dashboard-header h3 {
  margin: 0;
  font-size: 18px;
  color: var(--text);
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
}

.stat-card {
  background: var(--surface-alt);
  padding: 20px;
  border-radius: 10px;
  border: 1px solid #e2e8f0;
  text-align: center;
  transition: all 200ms ease;
  position: relative;
}

.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  border-color: var(--accent);
}

.stat-label {
  font-size: 12px;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 8px;
  letter-spacing: 0.5px;
  font-weight: 600;
}

.stat-value {
  font-size: 36px;
  font-weight: 700;
  color: var(--accent);
  margin-bottom: 8px;
}

.stat-icon {
  font-size: 28px;
  opacity: 0.6;
}
</style>
