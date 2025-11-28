<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'

const cars = ref([])
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

// Get unique brands with car counts
const brands = computed(() => {
  const brandMap = new Map()
  cars.value.forEach(car => {
    if (car.brand) {
      const brand = car.brand.trim()
      if (brandMap.has(brand)) {
        brandMap.set(brand, brandMap.get(brand) + 1)
      } else {
        brandMap.set(brand, 1)
      }
    }
  })
  
  return Array.from(brandMap.entries())
    .map(([name, count]) => ({ name, count }))
    .sort((a, b) => a.name.localeCompare(b.name))
})

const goToBrand = (brandName) => {
  router.push({ name: 'brand-detail', params: { brand: encodeURIComponent(brandName) } })
}

// Helper function to get brand logo URL (using a placeholder service)
const getBrandLogo = (brandName) => {
  // You can replace this with actual logo URLs or a logo API
  // For now, we'll use a placeholder that shows the brand initial
  return null
}
</script>

<template>
  <section class="brands-section container">
    <div class="brands-header">
      <h1>Browse by Brand</h1>
      <p class="brands-lead">Explore articles organized by car manufacturer</p>
    </div>

    <div v-if="loading" class="empty">Loading brands...</div>
    <div v-else-if="!brands.length" class="empty">
      <p>No brands available yet.</p>
    </div>
    <div v-else class="brands-grid">
      <article
        v-for="brand in brands"
        :key="brand.name"
        class="brand-card"
        @click="goToBrand(brand.name)"
      >
        <div class="brand-logo">
          <div class="brand-initial">{{ brand.name.charAt(0).toUpperCase() }}</div>
        </div>
        <div class="brand-info">
          <h3 class="brand-name">{{ brand.name }}</h3>
          <p class="brand-count">{{ brand.count }} {{ brand.count === 1 ? 'article' : 'articles' }}</p>
        </div>
      </article>
    </div>
  </section>
</template>

<style scoped>
.brands-section {
  padding: 80px 0 100px 0;
}

.brands-header {
  text-align: center;
  margin-bottom: 60px;
  padding-bottom: 40px;
  border-bottom: 1px solid var(--border);
}

.brands-header h1 {
  font-size: clamp(2.5rem, 5vw, 3.5rem);
  font-weight: 700;
  margin-bottom: 16px;
}

.brands-lead {
  font-size: 1.1rem;
  color: var(--muted);
  max-width: 600px;
  margin: 0 auto;
}

.brands-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 24px;
}

.brand-card {
  background: var(--surface);
  border-radius: 12px;
  padding: 32px 24px;
  border: 1px solid var(--border);
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.brand-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
  border-color: var(--accent);
}

.brand-logo {
  width: 100px;
  height: 100px;
  border-radius: 16px;
  background: linear-gradient(135deg, rgba(216, 184, 114, 0.15), rgba(216, 184, 114, 0.05));
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
  border: 2px solid var(--border);
  transition: transform 0.2s ease, border-color 0.2s ease;
}

.brand-card:hover .brand-logo {
  transform: scale(1.05);
  border-color: var(--accent);
}

.brand-initial {
  font-size: 3rem;
  font-weight: 700;
  color: var(--accent);
  letter-spacing: 0;
  text-shadow: 0 2px 8px rgba(216, 184, 114, 0.3);
}

.brand-info {
  width: 100%;
}

.brand-name {
  font-size: 1.2rem;
  font-weight: 600;
  margin: 0 0 8px 0;
  color: var(--text);
}

.brand-count {
  font-size: 0.9rem;
  color: var(--muted);
  margin: 0;
}

@media (max-width: 768px) {
  .brands-grid {
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 16px;
  }

  .brand-card {
    padding: 24px 16px;
  }

  .brand-logo {
    width: 70px;
    height: 70px;
    margin-bottom: 16px;
  }

  .brand-initial {
    font-size: 2.2rem;
  }
}
</style>

