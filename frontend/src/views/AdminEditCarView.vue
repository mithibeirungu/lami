<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import api from '../services/api'

const route = useRoute()
const router = useRouter()
const carId = route.params.id

const form = ref({
  car_name: '',
  brand: '',
  model: '',
  year: new Date().getFullYear(),
  price: '',
  description: '',
  body_type: '',
  fuel_type: '',
  engine_power: '',
  transmission: '',
  top_speed: '',
  acceleration: '',
  image_url: ''
})

const errors = ref({})
const loading = ref(true)
const submitting = ref(false)

onMounted(async () => {
  try {
    const res = await api.getCar(carId)
    form.value = res.data
  } catch (e) {
    console.error(e)
    alert('Failed to load car')
    router.push('/admin/cars')
  } finally {
    loading.value = false
  }
})

const validateForm = () => {
  errors.value = {}
  if (!form.value.car_name) errors.value.car_name = 'Car name required'
  if (!form.value.brand) errors.value.brand = 'Brand required'
  if (!form.value.model) errors.value.model = 'Model required'
  if (!form.value.year) errors.value.year = 'Year required'
  if (!form.value.price) errors.value.price = 'Price required'
  if (form.value.price && isNaN(Number(form.value.price))) errors.value.price = 'Price must be a number'
  return Object.keys(errors.value).length === 0
}

const submit = async () => {
  if (!validateForm()) return
  submitting.value = true
  try {
    await api.updateCar(carId, form.value)
    alert('Car updated successfully!')
    router.push('/admin/cars')
  } catch (e) {
    console.error(e)
    alert('Update failed: ' + (e.response?.data?.message || e.message))
  } finally {
    submitting.value = false
  }
}

const cancel = () => router.push('/admin/cars')
</script>

<template>
  <div class="admin-page">
    <h2>Edit Article</h2>

    <div v-if="loading" style="text-align:center; padding:40px">Loading...</div>

    <form v-else @submit.prevent="submit" class="car-form">
      <!-- Required Fields -->
      <div class="form-group">
        <label>Car Name *</label>
        <input v-model="form.car_name" type="text" placeholder="e.g., Aurora GT">
        <div v-if="errors.car_name" class="error">{{ errors.car_name }}</div>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
        <div class="form-group">
          <label>Brand *</label>
          <input v-model="form.brand" type="text" placeholder="e.g., Luxor">
          <div v-if="errors.brand" class="error">{{ errors.brand }}</div>
        </div>

        <div class="form-group">
          <label>Model *</label>
          <input v-model="form.model" type="text" placeholder="e.g., Phantom">
          <div v-if="errors.model" class="error">{{ errors.model }}</div>
        </div>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
        <div class="form-group">
          <label>Year *</label>
          <input v-model="form.year" type="number" :min="1900" :max="2100">
          <div v-if="errors.year" class="error">{{ errors.year }}</div>
        </div>

        <div class="form-group">
          <label>Price ($) *</label>
          <input v-model="form.price" type="number" placeholder="0" step="0.01">
          <div v-if="errors.price" class="error">{{ errors.price }}</div>
        </div>
      </div>

      <!-- Optional Fields -->
      <div class="form-group">
        <label>Description</label>
        <textarea v-model="form.description" placeholder="Describe the car..." style="min-height:100px"></textarea>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
        <div class="form-group">
          <label>Body Type</label>
          <input v-model="form.body_type" type="text" placeholder="e.g., Sedan">
        </div>

        <div class="form-group">
          <label>Fuel Type</label>
          <input v-model="form.fuel_type" type="text" placeholder="e.g., Electric">
        </div>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
        <div class="form-group">
          <label>Engine Power (hp)</label>
          <input v-model="form.engine_power" type="number" placeholder="0">
        </div>

        <div class="form-group">
          <label>Transmission</label>
          <input v-model="form.transmission" type="text" placeholder="e.g., Automatic">
        </div>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
        <div class="form-group">
          <label>Top Speed (km/h)</label>
          <input v-model="form.top_speed" type="number" placeholder="0">
        </div>

        <div class="form-group">
          <label>0-100 Acceleration (s)</label>
          <input v-model="form.acceleration" type="number" placeholder="0" step="0.1">
        </div>
      </div>

      <div class="form-group">
        <label>Image URL</label>
        <input v-model="form.image_url" type="url" placeholder="https://example.com/image.jpg">
      </div>

      <!-- Actions -->
      <div class="form-actions">
        <button type="submit" class="btn btn-primary" :disabled="submitting">
          {{ submitting ? 'Updating...' : 'Update Article' }}
        </button>
        <button type="button" class="btn btn-ghost" @click="cancel">Cancel</button>
      </div>
    </form>
  </div>
</template>

<style scoped>
.admin-page {
  max-width: 600px;
  margin: 0 auto;
  padding: 24px 20px;
}

.car-form {
  background: var(--surface);
  padding: 24px;
  border-radius: 12px;
  box-shadow: 0 2px 8px var(--shadow);
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-weight: 600;
  margin-bottom: 6px;
  color: var(--text);
  font-size: 14px;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-size: 14px;
  font-family: inherit;
  background: white;
  color: var(--text);
  transition: border-color 200ms, box-shadow 200ms;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.error {
  color: var(--danger);
  font-size: 12px;
  margin-top: 4px;
}

.form-actions {
  display: flex;
  gap: 12px;
  margin-top: 24px;
  padding-top: 24px;
  border-top: 1px solid #f0f4f8;
}

.btn {
  flex: 1;
}
</style>
