<script setup>
import { ref, reactive } from 'vue'
import api from '../services/api'
import { useRouter, RouterLink } from 'vue-router'

const username = ref('')
const email = ref('')
const full_name = ref('')
const password = ref('')
const password_confirmation = ref('')
const role = ref('driver')
const router = useRouter()
const errors = reactive({})
const submitting = ref(false)

const clearErrors = () => {
  for (const k in errors) delete errors[k]
}

const validate = () => {
  clearErrors()
  if (!username.value) errors.username = 'Username is required'
  if (!email.value) errors.email = 'Email is required'
  if (!full_name.value) errors.full_name = 'Full name is required'
  if (!password.value) errors.password = 'Password is required'
  if (password.value !== password_confirmation.value) errors.password_confirmation = 'Passwords do not match'
  if (!role.value) errors.role = 'Role is required'
  return Object.keys(errors).length === 0
}

const submit = async () => {
  if (!validate()) return
  submitting.value = true

  try {
    await api.register({
      username: username.value,
      email: email.value,
      full_name: full_name.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
      role: role.value,
    })
    alert('Registration successful! Please login.')
    router.push('/login')
  } catch (e) {
    console.error(e)
    if (e.response?.data?.errors) {
      for (const k in e.response.data.errors) {
        errors[k] = e.response.data.errors[k][0]
      }
    } else {
      errors.general = 'Registration failed'
    }
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <section class="auth-shell container">
    <div class="auth-card">
      <h2>Register</h2>
      <p class="lead">Join LAMI and become part of our community</p>

      <form class="field-stack" @submit.prevent="submit">
        <div class="field">
          <label class="field-label">Username</label>
          <input class="field-input" v-model="username" required />
          <div class="error" v-if="errors.username">{{ errors.username }}</div>
        </div>

        <div class="field">
          <label class="field-label">Full Name</label>
          <input class="field-input" v-model="full_name" required />
          <div class="error" v-if="errors.full_name">{{ errors.full_name }}</div>
        </div>

        <div class="field">
          <label class="field-label">Email</label>
          <input class="field-input" type="email" v-model="email" required />
          <div class="error" v-if="errors.email">{{ errors.email }}</div>
        </div>

        <div class="field">
          <label class="field-label">I am a:</label>
          <select v-model="role" class="field-input">
            <option value="driver">Car Enthusiast (View & Comment)</option>
            <option value="motor_scribe">Motor Scribe (Create Cars)</option>
            <option value="overseer">Overseer (Full Admin)</option>
          </select>
          <div class="error" v-if="errors.role">{{ errors.role }}</div>
        </div>

        <div class="field">
          <label class="field-label">Password</label>
          <input class="field-input" type="password" v-model="password" required />
          <div class="error" v-if="errors.password">{{ errors.password }}</div>
        </div>

        <div class="field">
          <label class="field-label">Confirm Password</label>
          <input class="field-input" type="password" v-model="password_confirmation" required />
          <div class="error" v-if="errors.password_confirmation">{{ errors.password_confirmation }}</div>
        </div>

        <div v-if="errors.general" class="error">{{ errors.general }}</div>

        <button class="btn btn-primary" type="submit" :disabled="submitting">
          {{ submitting ? 'Registering...' : 'Register' }}
        </button>
      </form>

      <p style="text-align: center; margin-top: 20px;">
        Already have an account? <RouterLink to="/login">Login here</RouterLink>
      </p>
    </div>
  </section>
</template>

<style scoped>
.auth-shell {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  padding: 20px 0;
}

.auth-card {
  background: var(--surface);
  padding: 40px;
  border-radius: 12px;
  border: 1px solid var(--border);
  max-width: 400px;
  width: 100%;
}

.auth-card h2 {
  margin-top: 0;
  margin-bottom: 8px;
  font-size: 1.8rem;
}

.lead {
  color: var(--muted);
  margin-bottom: 32px;
}

.field-stack {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.field {
  display: flex;
  flex-direction: column;
}

.field-label {
  font-weight: 600;
  margin-bottom: 6px;
  font-size: 0.9rem;
}

.field-input {
  padding: 10px 12px;
  border: 1px solid var(--border);
  border-radius: 6px;
  background: rgba(8, 12, 28, 0.6);
  color: var(--text);
  font-size: 1rem;
  font-family: inherit;
  transition: border-color 200ms, box-shadow 200ms;
}

.field-input:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(216, 184, 114, 0.1);
}

.error {
  color: var(--danger);
  font-size: 0.85rem;
  margin-top: 4px;
}

.btn {
  margin-top: 8px;
}

a {
  color: var(--accent);
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}
</style>
