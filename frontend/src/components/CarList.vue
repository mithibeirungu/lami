<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../services/api'
import { useRouter } from 'vue-router'

const cars = ref([])
const searchQuery = ref('')
const router = useRouter()
const loading = ref(true)

const load = async () => {
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

onMounted(load)

const filteredCars = computed(() => {
  if (!searchQuery.value.trim()) return cars.value
  
  const query = searchQuery.value.toLowerCase()
  return cars.value.filter(car => {
    return (
      car.car_name?.toLowerCase().includes(query) ||
      car.brand?.toLowerCase().includes(query) ||
      car.model?.toLowerCase().includes(query) ||
      car.description?.toLowerCase().includes(query) ||
      car.body_type?.toLowerCase().includes(query) ||
      car.fuel_type?.toLowerCase().includes(query) ||
      String(car.year)?.includes(query)
    )
  })
})

const open = (id) => router.push({ name: 'car', params: { id } })
</script>

<template>
  <section class="blog-section container">
    <div class="blog-header">
      <h2>All Articles</h2>
      <div class="search-box">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search by name, brand, model, or description..."
          class="search-input"
        />
      </div>
    </div>

    <div v-if="loading" class="empty">Loading articles...</div>
    <div v-else-if="!filteredCars.length" class="empty">
      <p v-if="searchQuery">No cars found matching "{{ searchQuery }}"</p>
      <p v-else>No articles yet.</p>
    </div>

    <div class="blog-grid">
      <article
        v-for="c in filteredCars"
        :key="c.id"
        class="blog-card"
        @click="open(c.id)"
      >
        <div class="blog-image" :style="{ backgroundImage: `url(${c.image_url || 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?auto=format&fit=crop&w=900&q=60'})` }"></div>
        <div class="blog-content">
          <div class="blog-meta">
            <span class="blog-date">{{ new Date(c.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
            <span class="blog-tag">{{ c.brand }}</span>
          </div>
          <h3 class="blog-title">{{ c.car_name }}</h3>
          <p class="blog-subtitle">{{ c.brand }} {{ c.model }} • {{ c.year }}</p>
          <p class="blog-excerpt">
            {{ c.description?.slice(0, 150) }}{{ c.description && c.description.length > 150 ? '...' : '' }}
          </p>
          <div class="blog-footer">
            <span class="blog-spec">{{ c.body_type }}</span>
            <span class="blog-spec">{{ c.engine_power }} hp</span>
            <span class="blog-spec">{{ c.fuel_type }}</span>
          </div>
        </div>
      </article>
    </div>
  </section>
</template>
