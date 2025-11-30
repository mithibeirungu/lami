<script setup>
import { ref } from 'vue'
import api from '../services/api'
import { useRouter } from 'vue-router'

const props = defineProps({
  redirectTo: { type: String, default: '/login' },
  standalone: { type: Boolean, default: true },
})

const username = ref('')
const email = ref('')
const full_name = ref('')
const password = ref('')
const admin_secret = ref('')
const router = useRouter()

const submit = async () => {
  try {
    await api.registerAdmin({
      username: username.value,
      email: email.value,
      full_name: full_name.value,
      password: password.value,
      invite_code: admin_secret.value,
    })
    alert('Admin registered')
    router.push(props.redirectTo)
  } catch (e) {
    console.error(e)
    alert('Admin registration failed')
  }
}
</script>

<template>
  <component :is="props.standalone ? 'section' : 'div'" :class="props.standalone ? 'auth-shell container' : 'embedded-auth'">
    <div class="auth-card" :class="{ 'auth-card--minimal': !props.standalone }">
      <p class="eyebrow">LAMI Ops</p>
      <h2>Elevate to admin</h2>
      <p class="lead">Unlock inventory controls and live telemetry for featured drops.</p>

      <form class="field-stack" @submit.prevent="submit">
        <div>
          <label class="field-label">Username</label>
          <input class="field-input" v-model="username" required />
        </div>
        <div>
          <label class="field-label">Full name</label>
          <input class="field-input" v-model="full_name" required />
        </div>
        <div>
          <label class="field-label">Email</label>
          <input class="field-input" type="email" v-model="email" required />
        </div>
        <div>
          <label class="field-label">Password</label>
          <input class="field-input" type="password" v-model="password" required />
        </div>
        <div>
          <label class="field-label">Invite code</label>
          <input class="field-input" v-model="admin_secret" required />
        </div>
        <button class="btn btn-primary glow-pill" type="submit">Register admin</button>
      </form>
    </div>
  </component>
</template>
