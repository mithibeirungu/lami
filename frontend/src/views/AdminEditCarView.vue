<script setup>
import { ref, onMounted, reactive } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import api from '../services/api'

const route = useRoute()
const router = useRouter()
const carId = route.params.id

const title = ref('')
const brand_id = ref('')
const model = ref('')
const year = ref(new Date().getFullYear())
const body_type_id = ref('')
const description = ref('')
const thumbnail_image = ref('')
const transmission = ref('')
const fuel_type = ref('')
const engine_type = ref('')
const engine_size = ref('')
const horsepower = ref(0)
const torque = ref(0)
const seating_capacity = ref(5)
const drive_type = ref('')
const price = ref(0)

const brands = ref([])
const bodyTypes = ref([])
const errors = reactive({})
const loading = ref(true)
const submitting = ref(false)

onMounted(async () => {
  try {
    const [carRes, brandsRes, bodyTypesRes] = await Promise.all([
      api.getCar(carId),
      api.getBrands(),
      api.getBodyTypes(),
    ])
    const car = carRes.data
    title.value = car.title
    brand_id.value = car.brand_id
    model.value = car.model
    year.value = car.year
    body_type_id.value = car.body_type_id
    description.value = car.description || ''
    thumbnail_image.value = car.thumbnail_image || ''
    transmission.value = car.transmission || ''
    fuel_type.value = car.fuel_type || ''
    engine_type.value = car.engine_type || ''
    engine_size.value = car.engine_size || ''
    horsepower.value = car.horsepower || 0
    torque.value = car.torque || 0
    seating_capacity.value = car.seating_capacity || 5
    drive_type.value = car.drive_type || ''
    price.value = car.price || 0

    brands.value = brandsRes.data
    bodyTypes.value = bodyTypesRes.data
  } catch (e) {
    console.error(e)
    alert('Failed to load car')
    router.push('/admin/cars')
  } finally {
    loading.value = false
  }
})

const validate = () => {
  const temp = {}
  if (!title.value) temp.title = 'Title is required'
  if (!brand_id.value) temp.brand_id = 'Brand is required'
  if (!model.value) temp.model = 'Model is required'
  if (!year.value || isNaN(year.value)) temp.year = 'Valid year is required'
  if (!body_type_id.value) temp.body_type_id = 'Body type is required'
  for (const k in temp) errors[k] = temp[k]
  return Object.keys(temp).length === 0
}

const submit = async () => {
  for (const k in errors) delete errors[k]
  if (!validate()) return
  submitting.value = true

  const payload = {
    title: title.value,
    brand_id: Number(brand_id.value),
    model: model.value,
    year: Number(year.value),
    body_type_id: Number(body_type_id.value),
    description: description.value,
    thumbnail_image: thumbnail_image.value,
    transmission: transmission.value,
    fuel_type: fuel_type.value,
    engine_type: engine_type.value,
    engine_size: engine_size.value,
    horsepower: Number(horsepower.value) || 0,
    torque: Number(torque.value) || 0,
    seating_capacity: Number(seating_capacity.value) || 5,
    drive_type: drive_type.value,
    price: Number(price.value) || 0,
  }

  try {
    await api.updateCar(carId, payload)
    alert('Car updated successfully!')
    router.push('/admin/cars')
  } catch (e) {
    console.error(e)
    if (e.response && e.response.data) {
      const data = e.response.data
      if (data.errors) {
        for (const k in data.errors) errors[k] = data.errors[k][0] || data.errors[k]
      } else if (data.message) {
        errors.general = data.message
      }
    } else {
      errors.general = 'Update failed'
    }
  } finally {
    submitting.value = false
  }
}

const cancel = () => router.push('/admin/cars')
</script>

<template>
  <div class="admin-page">
    <h2>Edit Car</h2>

    <div v-if="loading" style="text-align:center; padding:40px">Loading...</div>

    <div v-else class="form-card">
      <div class="field">
        <label>Title</label>
        <input v-model="title" />
        <div class="error" v-if="errors.title">{{ errors.title }}</div>
      </div>

      <div class="two-cols">
        <div class="field">
          <label>Brand</label>
          <select v-model="brand_id">
            <option value="">Select brand</option>
            <option v-for="b in brands" :key="b.brand_id" :value="b.brand_id">{{ b.name }}</option>
          </select>
          <div class="error" v-if="errors.brand_id">{{ errors.brand_id }}</div>
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
          <label>Body Type</label>
          <select v-model="body_type_id">
            <option value="">Select body type</option>
            <option v-for="bt in bodyTypes" :key="bt.body_type_id" :value="bt.body_type_id">{{ bt.name }}</option>
          </select>
          <div class="error" v-if="errors.body_type_id">{{ errors.body_type_id }}</div>
        </div>
      </div>

      <div class="two-cols">
        <div class="field">
          <label>Transmission</label>
          <input v-model="transmission" />
        </div>
        <div class="field">
          <label>Fuel Type</label>
          <input v-model="fuel_type" />
        </div>
      </div>

      <div class="two-cols">
        <div class="field">
          <label>Engine Type</label>
          <input v-model="engine_type" />
        </div>
        <div class="field">
          <label>Engine Size</label>
          <input v-model="engine_size" />
        </div>
      </div>

      <div class="two-cols">
        <div class="field">
          <label>Horsepower</label>
          <input type="number" v-model.number="horsepower" />
        </div>
        <div class="field">
          <label>Torque</label>
          <input type="number" v-model.number="torque" />
        </div>
      </div>

      <div class="two-cols">
        <div class="field">
          <label>Seating Capacity</label>
          <input type="number" v-model.number="seating_capacity" />
        </div>
        <div class="field">
          <label>Drive Type</label>
          <input v-model="drive_type" />
        </div>
      </div>

      <div class="field">
        <label>Thumbnail Image URL</label>
        <input v-model="thumbnail_image" />
      </div>

      <div class="field">
        <label>Price</label>
        <input type="number" v-model.number="price" />
      </div>

      <div class="field">
        <label>Description</label>
        <textarea v-model="description"></textarea>
      </div>

      <div v-if="errors.general" class="error">{{ errors.general }}</div>

      <div style="margin-top:12px">
        <button class="btn btn-primary" :disabled="submitting" @click="submit">{{ submitting ? 'Updating...' : 'Update Car' }}</button>
        <button class="btn btn-ghost" @click="cancel" style="margin-left:8px">Cancel</button>
      </div>
    </div>
  </div>
</template>
