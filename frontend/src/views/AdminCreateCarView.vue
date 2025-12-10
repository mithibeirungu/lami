<script setup>
import { ref, reactive, onMounted } from 'vue'
import api from '../services/api'
import { useRouter } from 'vue-router'

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
const router = useRouter()
const errors = reactive({})
const submitting = ref(false)

onMounted(async () => {
  try {
    const [brandsRes, bodyTypesRes] = await Promise.all([
      api.getBrands(),
      api.getBodyTypes(),
    ])
    brands.value = brandsRes.data
    bodyTypes.value = bodyTypesRes.data
  } catch (e) {
    console.error('Failed to load brands or body types:', e)
  }
})

function clearErrors() {
  for (const k in errors) delete errors[k]
}

const validate = () => {
  clearErrors()
  if (!title.value) errors.title = 'Title is required'
  if (!brand_id.value) errors.brand_id = 'Brand is required'
  if (!model.value) errors.model = 'Model is required'
  if (!year.value || isNaN(year.value)) errors.year = 'Valid year is required'
  if (!body_type_id.value) errors.body_type_id = 'Body type is required'
  return Object.keys(errors).length === 0
}

const submit = async () => {
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
    await api.createCar(payload)
    router.push('/')
  } catch (e) {
    console.error(e)
    if (e.response && e.response.data && typeof e.response.data === 'object') {
      const data = e.response.data
      if (data.errors) {
        for (const k in data.errors) errors[k] = data.errors[k][0] || data.errors[k]
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
    <h2>Create New Car</h2>

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
        <input v-model="transmission" placeholder="e.g. Manual, Automatic" />
      </div>
      <div class="field">
        <label>Fuel Type</label>
        <input v-model="fuel_type" placeholder="e.g. Petrol, Electric" />
      </div>
    </div>

    <div class="two-cols">
      <div class="field">
        <label>Engine Type</label>
        <input v-model="engine_type" placeholder="e.g. V6, Inline 4" />
      </div>
      <div class="field">
        <label>Engine Size</label>
        <input v-model="engine_size" placeholder="e.g. 3.0L" />
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
        <input v-model="drive_type" placeholder="e.g. RWD, AWD" />
      </div>
    </div>

    <div class="field">
      <label>Thumbnail Image URL</label>
      <input v-model="thumbnail_image" placeholder="https://..." />
    </div>

    <div class="two-cols">
      <div class="field">
        <label>Price</label>
        <input type="number" v-model.number="price" />
      </div>
    </div>

    <div class="field">
      <label>Description</label>
      <textarea v-model="description"></textarea>
    </div>

    <div v-if="errors.general" class="error">{{ errors.general }}</div>

    <div style="margin-top:12px">
      <button class="btn btn-primary" :disabled="submitting" @click="submit">{{ submitting ? 'Creating...' : 'Create Car' }}</button>
      <button class="btn btn-ghost" @click="$router.back()" style="margin-left:8px">Cancel</button>
    </div>
  </div>
</template>
