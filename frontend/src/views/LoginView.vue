<script setup>
import { ref } from 'vue'
import api from '../services/api'
import { useRouter, RouterLink } from 'vue-router'

const props = defineProps({
  redirectTo: { type: String, default: '/' },
  standalone: { type: Boolean, default: true }
})

const email = ref('')
const password = ref('')
const router = useRouter()

const submit = async () => {
  try {
    const res = await api.login({ email: email.value, password: password.value })
    const user = res.data.user
    localStorage.setItem('user_id', user.id)
    localStorage.setItem('user', JSON.stringify(user))
    if (res.data.sanctum_token) localStorage.setItem('sanctum_token', res.data.sanctum_token)
    router.push(props.redirectTo)
  } catch (e) {
    alert('Login failed')
  }
}
</script>

<template>
  <component :is="props.standalone ? 'section' : 'div'" :class="props.standalone ? 'auth-shell container' : 'embedded-auth'">
    <div class="auth-card" :class="{ 'auth-card--minimal': !props.standalone }">
      <p class="eyebrow">Welcome back</p>
      <h2>Sign in to the garage</h2>
      <p class="lead">Access exclusive drops, manage listings, and join the nightly conversations.</p>

      <form class="field-stack" @submit.prevent="submit">
        <div>
          <label class="field-label">Email</label>
          <input class="field-input" type="email" v-model="email" placeholder="you@lamigarage.com" required />
        </div>
        <div>
          <label class="field-label">Password</label>
          <input class="field-input" type="password" v-model="password" placeholder="••••••••" required />
        </div>
        <button class="btn btn-primary glow-pill" type="submit">Enter showroom</button>
      </form>

      <p class="muted">
        No account yet?
        <RouterLink to="/register">Create one</RouterLink>
      </p>
    </div>
  </component>
</template>
