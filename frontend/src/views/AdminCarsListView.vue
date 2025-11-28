<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'
import { useRouter } from 'vue-router'

const cars = ref([])
const loading = ref(false)
const router = useRouter()

const loadCars = async () => {
  loading.value = true
  try {
    const res = await api.getCars()
    cars.value = res.data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

onMounted(loadCars)

const editCar = (id) => router.push({ name: 'admin-edit-car', params: { id } })

const deleteCar = async (id, name) => {
  if (!confirm(`Delete "${name}"?`)) return
  try {
    await api.deleteCar(id)
    cars.value = cars.value.filter(c => c.id !== id)
  } catch (e) {
    console.error(e)
    alert('Delete failed')
  }
}
</script>

<template>
  <div class="admin-page">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px">
      <h2>Manage Articles</h2>
      <router-link to="/admin/create" class="btn btn-primary">+ New Article</router-link>
    </div>

    <div v-if="loading" style="text-align:center; padding:40px">Loading...</div>

      <div v-else-if="!cars.length" style="text-align:center; padding:40px; color:var(--muted)">
      No articles yet. <router-link to="/admin/create" style="color:var(--accent)">Write your first article!</router-link>
    </div>

    <div v-else class="cars-table">
      <div class="table-head">
        <div class="col-name">Article</div>
        <div class="col-price">Price</div>
        <div class="col-year">Year</div>
        <div class="col-actions">Actions</div>
      </div>

      <div v-for="car in cars" :key="car.id" class="table-row">
        <div class="col-name">
          <div class="car-name">{{ car.car_name }}</div>
          <div class="car-meta">{{ car.brand }} {{ car.model }}</div>
        </div>
        <div class="col-price">${{ Number(car.price).toLocaleString() }}</div>
        <div class="col-year">{{ car.year }}</div>
        <div class="col-actions">
          <button class="btn btn-sm btn-ghost" @click="editCar(car.id)">Edit</button>
          <button class="btn btn-sm btn-danger" @click="deleteCar(car.id, car.car_name)">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.admin-page {
  max-width: 1000px;
  margin: 0 auto;
  padding: 24px 20px;
}

.cars-table {
  background: var(--surface);
  border-radius: 12px;
  border: 1px solid #f0f4f8;
  overflow: hidden;
  box-shadow: 0 2px 8px var(--shadow);
}

.table-head {
  display: grid;
  grid-template-columns: 2fr 1fr 0.8fr 1.2fr;
  gap: 16px;
  padding: 16px 20px;
  background: var(--surface-alt);
  border-bottom: 1px solid #e2e8f0;
  font-weight: 600;
  font-size: 12px;
  text-transform: uppercase;
  color: var(--muted);
}

.table-row {
  display: grid;
  grid-template-columns: 2fr 1fr 0.8fr 1.2fr;
  gap: 16px;
  padding: 16px 20px;
  border-bottom: 1px solid #f0f4f8;
  align-items: center;
  transition: background 150ms ease;
}

.table-row:hover {
  background: var(--surface-alt);
}

.col-name {
  min-width: 0;
}

.car-name {
  font-weight: 600;
  color: var(--text);
}

.car-meta {
  font-size: 12px;
  color: var(--muted);
}

.col-price {
  font-weight: 600;
  color: var(--success);
}

.col-year {
  color: var(--muted);
}

.col-actions {
  display: flex;
  gap: 8px;
}
</style>
