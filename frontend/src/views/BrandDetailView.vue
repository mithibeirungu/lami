<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'

const route = useRoute()
const router = useRouter()
const cars = ref([])
const loading = ref(true)

const brandName = computed(() => decodeURIComponent(route.params.brand))

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
  return cars.value.filter(car => 
    car.brand?.trim().toLowerCase() === brandName.value.toLowerCase()
  )
})

const open = (id) => router.push({ name: 'car', params: { id } })
</script>

<template>
  <section class="brand-detail-section container">
    <div class="brand-detail-header">
      <button class="back-button" @click="$router.back()">← Back</button>
      <h1>{{ brandName }}</h1>
      <p class="brand-article-count">
        {{ filteredCars.length }} {{ filteredCars.length === 1 ? 'article' : 'articles' }}
      </p>
    </div>

    <div v-if="loading" class="empty">Loading articles...</div>
    <div v-else-if="!filteredCars.length" class="empty">
      <p>No articles found for {{ brandName }}.</p>
    </div>
    <div v-else class="blog-grid">
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

<style scoped>
.brand-detail-section {
  padding: 60px 0 80px 0;
}

.brand-detail-header {
  margin-bottom: 40px;
  padding-bottom: 24px;
  border-bottom: 1px solid var(--border);
}

.back-button {
  background: transparent;
  border: 1px solid var(--border);
  color: var(--text);
  padding: 8px 16px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 0.9rem;
  margin-bottom: 24px;
  transition: background 0.2s, border-color 0.2s;
}

.back-button:hover {
  background: rgba(255, 255, 255, 0.05);
  border-color: var(--accent);
}

.brand-detail-header h1 {
  font-size: clamp(2rem, 5vw, 3rem);
  font-weight: 700;
  margin: 0 0 12px 0;
}

.brand-article-count {
  color: var(--muted);
  font-size: 1rem;
  margin: 0;
}
</style>

