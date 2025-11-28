<script setup>
import { ref, reactive } from 'vue'
import api from '../services/api'
import { useRouter } from 'vue-router'

const car_name = ref('')
const brand = ref('')
const model = ref('')
const year = ref(new Date().getFullYear())
const description = ref('')
const price = ref(0)
const body_type = ref('')
const fuel_type = ref('')
const engine_power = ref(0)
const transmission = ref('')
const top_speed = ref(0)
const acceleration = ref(0.0)
const image_url = ref('')

const router = useRouter()
const errors = reactive({})
const submitting = ref(false)

function clearErrors() {
  for (const k in errors) delete errors[k]
}

const validate = () => {
  clearErrors()
  if (!car_name.value) errors.car_name = 'Name is required'
  if (!brand.value) errors.brand = 'Brand is required'
  if (!model.value) errors.model = 'Model is required'
  if (!year.value || isNaN(year.value)) errors.year = 'Valid year is required'
  return Object.keys(errors).length === 0
}

const submit = async () => {
  if (!validate()) return
  submitting.value = true

  const payload = {
    car_name: car_name.value,
    brand: brand.value,
    model: model.value,
    year: Number(year.value),
    description: description.value,
    price: Number(price.value) || 0.0,
    body_type: body_type.value,
    fuel_type: fuel_type.value,
    engine_power: Number(engine_power.value) || 0,
    transmission: transmission.value,
    top_speed: Number(top_speed.value) || 0,
    acceleration: Number(acceleration.value) || 0.0,
    image_url: image_url.value,
  }

  try {
    await api.createCar(payload)
    router.push('/')
  } catch (e) {
    console.error(e)
    // show server validation errors if available
    if (e.response && e.response.data && typeof e.response.data === 'object') {
      const data = e.response.data
      if (data.errors) {
        for (const k in data.errors) errors[k] = data.errors[k][0]
      } else if (data.message) {
        errors.general = data.message
      } else {
        errors.general = 'Create failed'
      }
    } else {
      errors.general = 'Create failed'
    }
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <div class="form-card">
    <h2>Write New Article</h2>

    <div class="field">
      <label>Name</label>
      <input v-model="car_name" />
      <div class="error" v-if="errors.car_name">{{ errors.car_name }}</div>
    </div>

    <div class="two-cols">
      <div class="field">
        <label>Brand</label>
        <input v-model="brand" />
        <div class="error" v-if="errors.brand">{{ errors.brand }}</div>
      </div>
      <div class="field">
        <label>Model</label>
        <input v-model="model" />
        <div class="error" v-if="errors.model">{{ errors.model }}</div>
      </div>
    </div>

    <div class="two-cols">
      <div class="field">
        <label>Year</label>
        <input type="number" v-model.number="year" />
        <div class="error" v-if="errors.year">{{ errors.year }}</div>
      </div>
      <div class="field">
        <label>Price</label>
        <input type="number" v-model.number="price" />
      </div>
    </div>

    <div class="two-cols">
      <div class="field">
        <label>Body type</label>
        <input v-model="body_type" placeholder="e.g. Sedan, Coupe" />
      </div>
      <div class="field">
        <label>Fuel type</label>
        <input v-model="fuel_type" placeholder="e.g. Petrol, Electric" />
      </div>
    </div>

    <div class="two-cols">
      <div class="field">
        <label>Engine power (hp)</label>
        <input type="number" v-model.number="engine_power" />
      </div>
      <div class="field">
        <label>Transmission</label>
        <input v-model="transmission" />
      </div>
    </div>

    <div class="two-cols">
      <div class="field">
        <label>Top speed (km/h)</label>
        <input type="number" v-model.number="top_speed" />
      </div>
      <div class="field">
        <label>0-100 km/h (s)</label>
        <input type="number" step="0.1" v-model.number="acceleration" />
      </div>
    </div>

    <div class="field">
      <label>Image URL</label>
      <input v-model="image_url" placeholder="https://..." />
    </div>

    <div class="field">
      <label>Description</label>
      <textarea v-model="description"></textarea>
    </div>

    <div v-if="errors.general" class="error">{{ errors.general }}</div>

    <div style="margin-top:12px">
      <button class="btn btn-primary" :disabled="submitting" @click="submit">{{ submitting ? 'Publishing...' : 'Publish Article' }}</button>
      <button class="btn btn-ghost" @click="$router.back()" style="margin-left:8px">Cancel</button>
    </div>
  </div>
</template>
