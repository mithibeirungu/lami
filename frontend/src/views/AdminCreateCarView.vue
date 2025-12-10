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
  <div class="create-car-container">
    <div class="form-header">
      <h1>Create New Car Listing</h1>
      <p class="form-subtitle">Add a new vehicle to the marketplace</p>
    </div>

    <div class="form-wrapper">
      <!-- Basic Information Section -->
      <div class="form-section">
        <div class="section-header">
          <h3>Basic Information</h3>
          <span class="section-badge">Required</span>
        </div>

        <div class="field">
          <label>Title *</label>
          <input v-model="title" placeholder="e.g. 2024 Tesla Model S Plaid" class="field-input" />
          <div class="error" v-if="errors.title">{{ errors.title }}</div>
        </div>

        <div class="two-cols">
          <div class="field">
            <label>Brand *</label>
            <select v-model="brand_id" class="field-input">
              <option value="">Select a brand</option>
              <option v-for="b in brands" :key="b.brand_id" :value="b.brand_id">{{ b.name }}</option>
            </select>
            <div class="error" v-if="errors.brand_id">{{ errors.brand_id }}</div>
          </div>
          <div class="field">
            <label>Model *</label>
            <input v-model="model" placeholder="e.g. Model S" class="field-input" />
            <div class="error" v-if="errors.model">{{ errors.model }}</div>
          </div>
        </div>

        <div class="two-cols">
          <div class="field">
            <label>Year *</label>
            <input type="number" v-model.number="year" class="field-input" />
            <div class="error" v-if="errors.year">{{ errors.year }}</div>
          </div>
          <div class="field">
            <label>Body Type *</label>
            <select v-model="body_type_id" class="field-input">
              <option value="">Select body type</option>
              <option v-for="bt in bodyTypes" :key="bt.body_type_id" :value="bt.body_type_id">{{ bt.name }}</option>
            </select>
            <div class="error" v-if="errors.body_type_id">{{ errors.body_type_id }}</div>
          </div>
        </div>
      </div>

      <!-- Technical Specifications Section -->
      <div class="form-section">
        <div class="section-header">
          <h3>Technical Specifications</h3>
          <span class="section-badge">Optional</span>
        </div>

        <div class="two-cols">
          <div class="field">
            <label>Engine Type</label>
            <input v-model="engine_type" placeholder="e.g. V6, Inline 4, Electric Motor" class="field-input" />
          </div>
          <div class="field">
            <label>Engine Size</label>
            <input v-model="engine_size" placeholder="e.g. 3.0L, 2.5L" class="field-input" />
          </div>
        </div>

        <div class="two-cols">
          <div class="field">
            <label>Transmission</label>
            <input v-model="transmission" placeholder="e.g. Manual, Automatic, CVT" class="field-input" />
          </div>
          <div class="field">
            <label>Fuel Type</label>
            <input v-model="fuel_type" placeholder="e.g. Petrol, Diesel, Electric" class="field-input" />
          </div>
        </div>

        <div class="two-cols">
          <div class="field">
            <label>Horsepower</label>
            <input type="number" v-model.number="horsepower" placeholder="e.g. 350" class="field-input" />
          </div>
          <div class="field">
            <label>Torque (Nm)</label>
            <input type="number" v-model.number="torque" placeholder="e.g. 450" class="field-input" />
          </div>
        </div>

        <div class="two-cols">
          <div class="field">
            <label>Drive Type</label>
            <input v-model="drive_type" placeholder="e.g. RWD, AWD, FWD" class="field-input" />
          </div>
          <div class="field">
            <label>Seating Capacity</label>
            <input type="number" v-model.number="seating_capacity" placeholder="e.g. 5" class="field-input" />
          </div>
        </div>
      </div>

      <!-- Pricing & Images Section -->
      <div class="form-section">
        <div class="section-header">
          <h3>Pricing & Media</h3>
          <span class="section-badge">Optional</span>
        </div>

        <div class="field">
          <label>Price</label>
          <div class="input-prefix">
            <span class="prefix">$</span>
            <input type="number" v-model.number="price" placeholder="0.00" class="field-input" />
          </div>
        </div>

        <div class="field">
          <label>Thumbnail Image URL</label>
          <input v-model="thumbnail_image" placeholder="https://example.com/image.jpg" class="field-input" />
          <p class="field-hint">Provide a high-quality image URL for the car thumbnail</p>
        </div>
      </div>

      <!-- Description Section -->
      <div class="form-section">
        <div class="section-header">
          <h3>Description</h3>
          <span class="section-badge">Optional</span>
        </div>

        <div class="field">
          <label>Car Description</label>
          <textarea v-model="description" placeholder="Describe the car, its features, condition, and any special details..." class="field-input textarea-input"></textarea>
          <p class="field-hint">{{ description.length }} / 1000 characters</p>
        </div>
      </div>

      <!-- Errors Section -->
      <div v-if="errors.general" class="error-banner">
        <span class="error-icon">⚠️</span>
        {{ errors.general }}
      </div>

      <!-- Form Actions -->
      <div class="form-actions">
        <button class="btn btn-primary btn-lg" :disabled="submitting" @click="submit">
          <span v-if="!submitting">✓ Create Car Listing</span>
          <span v-else>Creating...</span>
        </button>
        <button class="btn btn-ghost btn-lg" @click="$router.back()">Cancel</button>
      </div>
    </div>
  </div>
</template>
