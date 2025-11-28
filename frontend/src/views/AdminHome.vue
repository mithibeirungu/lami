<script setup>
import { ref, computed } from 'vue'
import AdminDashboard from './AdminDashboard.vue'
import LoginView from './LoginView.vue'
import AdminRegisterView from './AdminRegisterView.vue'

const raw = localStorage.getItem('user') || 'null'
let parsed = null
try { parsed = JSON.parse(raw) } catch(e) { parsed = null }

const user = ref(parsed)
// reactive check for login changes
window.addEventListener('storage', () => {
  try { user.value = JSON.parse(localStorage.getItem('user') || 'null') } catch(e) { user.value = null }
})

const isAdmin = computed(() => user.value && user.value.type_of_user === 'admin')
const active = ref('login')
</script>

<template>
  <div>
    <div v-if="isAdmin" class="admin-page">
      <div class="admin-bar">
        <div>
          <p class="eyebrow">Control center</p>
          <h2>Admin Panel</h2>
        </div>
        <div class="admin-actions">
          <router-link to="/admin/cars" class="btn btn-ghost">Manage Cars</router-link>
          <router-link to="/admin/dashboard" class="btn btn-primary glow-pill">Dashboard</router-link>
        </div>
      </div>
      <AdminDashboard />
    </div>

    <div v-else class="auth-shell container">
      <div class="auth-card">
        <p class="eyebrow">Admin access</p>
        <h2>Restricted area</h2>
        <p class="lead">Verify your credentials or onboard as an administrator.</p>

        <div class="toggle-group">
          <button class="tab-btn" :class="{ active: active === 'login' }" @click="active = 'login'">Login</button>
          <button class="tab-btn" :class="{ active: active === 'register' }" @click="active = 'register'">
            Register Admin
          </button>
        </div>

        <div v-if="active === 'login'">
          <LoginView redirectTo="/admin" :standalone="false" />
        </div>
        <div v-else>
          <AdminRegisterView redirectTo="/admin" :standalone="false" />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.admin-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 16px;
  margin-bottom: 32px;
  flex-wrap: wrap;
}

.admin-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.toggle-group {
  display: flex;
  gap: 12px;
  margin: 24px 0;
}

.tab-btn {
  flex: 1;
  padding: 10px 14px;
  border-radius: 999px;
  border: 1px solid rgba(255, 255, 255, 0.12);
  background: transparent;
  color: var(--text);
  font-weight: 600;
  cursor: pointer;
  transition: background 150ms ease, color 150ms ease;
}

.tab-btn.active {
  background: linear-gradient(120deg, var(--accent), var(--accent-alt));
  color: #05080f;
  border-color: transparent;
}
</style>
