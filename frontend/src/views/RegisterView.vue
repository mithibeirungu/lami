<script setup>
import { ref } from 'vue'
import api from '../services/api'
import { useRouter, RouterLink } from 'vue-router'

const props = defineProps({ redirectTo: { type: String, default: '/login' } })

const username = ref('')
const email = ref('')
const full_name = ref('')
const password = ref('')
const router = useRouter()

const submit = async () => {
  try {
    await api.register({
      username: username.value,
      email: email.value,
      full_name: full_name.value,
      password: password.value,
    })
    router.push(props.redirectTo)
  } catch (e) {
    alert('Register failed')
  }
}
</script>

<template>
  <section class="auth-shell container">
    <div class="auth-card">
      <p class="eyebrow">Join the circuit</p>
      <h2>Create your driver profile</h2>
      <p class="lead">Save favorites, add commentary, and list vehicles with concierge support.</p>

      <form class="field-stack" @submit.prevent="submit">
        <div>
          <label class="field-label">Username</label>
          <input class="field-input" v-model="username" required placeholder="lami_driver" />
        </div>
        <div>
          <label class="field-label">Full name</label>
          <input class="field-input" v-model="full_name" required placeholder="Ariana K." />
        </div>
        <div>
          <label class="field-label">Email</label>
          <input class="field-input" type="email" v-model="email" required placeholder="driver@lamigarage.com" />
        </div>
        <div>
          <label class="field-label">Password</label>
          <input class="field-input" type="password" v-model="password" required placeholder="••••••••" />
        </div>
        <button class="btn btn-primary glow-pill" type="submit">Create account</button>
      </form>

      <p class="muted">
        Already in the pit lane?
        <RouterLink to="/login">Sign in</RouterLink>
      </p>
    </div>
  </section>
</template>
