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
      car.title?.toLowerCase().includes(query) ||
      car.brand?.name?.toLowerCase().includes(query) ||
      car.model?.toLowerCase().includes(query) ||
      car.description?.toLowerCase().includes(query) ||
      car.bodyType?.name?.toLowerCase().includes(query) ||
      car.fuel_type?.toLowerCase().includes(query) ||
      String(car.year)?.includes(query)
    )
  })
})

const getCarImage = (car) => {
  if (car.thumbnail_image) return car.thumbnail_image
  if (car.images?.[0]?.image_url) return car.images[0].image_url
  return 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?auto=format&fit=crop&w=900&q=60'
}

const open = (id) => router.push({ name: 'car', params: { id } })
</script>

<template>
  <section class="blog-section container">
    <div class="blog-header">
      <h2>All Cars</h2>
      <div class="search-box">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search by title, brand, model, or description..."
          class="search-input"
        />
      </div>
    </div>

    <div v-if="loading" class="empty">Loading cars...</div>
    <div v-else-if="!filteredCars.length" class="empty">
      <p v-if="searchQuery">No cars found matching "{{ searchQuery }}"</p>
      <p v-else>No cars yet.</p>
    </div>

    <div class="blog-grid">
      <article
        v-for="c in filteredCars"
        :key="c.car_id"
        class="blog-card"
        @click="open(c.car_id)"
      >
        <div class="blog-image" :style="{ backgroundImage: `url(${getCarImage(c)})` }"></div>
        <div class="blog-content">
          <div class="blog-meta">
            <span class="blog-date">{{ new Date(c.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
            <span class="blog-tag">{{ c.brand?.name }}</span>
          </div>
          <h3 class="blog-title">{{ c.title }}</h3>
          <p class="blog-subtitle">{{ c.brand?.name }} {{ c.model }} • {{ c.year }}</p>
          <p class="blog-excerpt">
            {{ c.description?.slice(0, 150) }}{{ c.description && c.description.length > 150 ? '...' : '' }}
          </p>
          <div class="blog-footer">
            <span class="blog-spec">{{ c.bodyType?.name }}</span>
            <span class="blog-spec">{{ c.horsepower }} hp</span>
            <span class="blog-spec">{{ c.fuel_type }}</span>
          </div>
        </div>
      </article>
    </div>
  </section>
</template>

<style scoped>
.blog-section {
  padding: 80px 0;
}

.blog-header {
  margin-bottom: 60px;
  text-align: center;
}

.blog-header h2 {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 32px;
}

.search-box {
  display: flex;
  justify-content: center;
}

.search-input {
  width: 100%;
  max-width: 500px;
  padding: 12px 20px;
  border-radius: 8px;
  border: 1px solid var(--border);
  background: var(--surface);
  color: var(--text);
  font-size: 1rem;
  transition: border-color 200ms, box-shadow 200ms;
}

.search-input:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(216, 184, 114, 0.1);
}

.empty {
  text-align: center;
  padding: 80px 0;
  color: var(--muted);
}

.blog-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 32px;
}

.blog-card {
  cursor: pointer;
  border-radius: 12px;
  overflow: hidden;
  background: var(--surface);
  border: 1px solid var(--border);
  transition: all 250ms cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  flex-direction: column;
}

.blog-card:hover {
  border-color: var(--accent);
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
  transform: translateY(-4px);
}

.blog-image {
  width: 100%;
  height: 220px;
  background-size: cover;
  background-position: center;
}

.blog-content {
  padding: 24px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.blog-meta {
  display: flex;
  gap: 8px;
  align-items: center;
  margin-bottom: 12px;
  font-size: 0.85rem;
}

.blog-tag {
  background: var(--accent);
  color: #1a1a1a;
  padding: 4px 10px;
  border-radius: 4px;
  font-weight: 600;
}

.blog-date {
  color: var(--muted);
}

.blog-title {
  font-size: 1.3rem;
  font-weight: 700;
  margin: 0 0 8px 0;
  line-height: 1.4;
}

.blog-subtitle {
  font-size: 0.95rem;
  color: var(--muted);
  margin: 0 0 12px 0;
}

.blog-excerpt {
  font-size: 0.95rem;
  color: rgba(255, 255, 255, 0.75);
  margin: 0 0 16px 0;
  flex: 1;
  line-height: 1.6;
}

.blog-footer {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  padding-top: 12px;
  border-top: 1px solid var(--border);
}

.blog-spec {
  font-size: 0.85rem;
  padding: 4px 8px;
  background: rgba(216, 184, 114, 0.1);
  border-radius: 4px;
  color: var(--accent);
}

@media (max-width: 768px) {
  .blog-header h2 {
    font-size: 2rem;
  }

  .blog-grid {
    grid-template-columns: 1fr;
  }
}
</style>
