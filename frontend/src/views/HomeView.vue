<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import api from '../services/api'

const cars = ref([])
const router = useRouter()

const load = async () => {
  try {
    const res = await api.getCars()
    cars.value = res.data
  } catch (e) {
    console.error(e)
  }
}

onMounted(load)

// Featured cars: first 3 (or most recent)
const featuredCars = computed(() => cars.value.slice(0, 3))
const recentCars = computed(() => cars.value.slice(3, 9))

const getCarImage = (car) => {
  if (car.thumbnail_image) return car.thumbnail_image
  if (car.images?.[0]?.image_url) return car.images[0].image_url
  return 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?auto=format&fit=crop&w=900&q=60'
}

const open = (id) => router.push({ name: 'car', params: { id } })
</script>

<template>
  <main>
    <section class="blog-hero container">
      <div class="blog-hero-content">
        <h1>Car Stories & Insights</h1>
        <p class="blog-hero-lead">
          A curated collection of automotive articles, deep dives, and detailed information about remarkable vehicles.
        </p>
      </div>
    </section>

    <section v-if="featuredCars.length" class="featured-section container">
      <div class="section-header">
        <h2>Featured Articles</h2>
        <p class="section-lead">Handpicked stories worth reading</p>
      </div>
      <div class="featured-grid">
        <article
          v-for="c in featuredCars"
          :key="c.car_id"
          class="featured-card"
          @click="open(c.car_id)"
        >
          <div class="featured-image" :style="{ backgroundImage: `url(${getCarImage(c)})` }"></div>
          <div class="featured-content">
            <div class="featured-meta">
              <span class="featured-tag">Featured</span>
              <span class="featured-date">{{ new Date(c.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) }}</span>
            </div>
            <h3 class="featured-title">{{ c.title }}</h3>
            <p class="featured-subtitle">{{ c.brand?.name }} {{ c.model }} • {{ c.year }}</p>
            <p class="featured-excerpt">
              {{ c.description?.slice(0, 200) }}{{ c.description && c.description.length > 200 ? '...' : '' }}
            </p>
            <div class="featured-specs">
              <span>{{ c.bodyType?.name }}</span>
              <span>{{ c.horsepower }} hp</span>
              <span>{{ c.fuel_type }}</span>
            </div>
          </div>
        </article>
      </div>
    </section>

    <section v-if="recentCars.length" class="recent-section container">
      <div class="section-header">
        <h2>Recent Articles</h2>
        <RouterLink to="/articles" class="view-all-link">View all →</RouterLink>
      </div>
      <div class="blog-grid">
        <article
          v-for="c in recentCars"
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

    <section v-if="!cars.length" class="empty-state container">
      <p>No articles yet. Start writing by creating your first car article.</p>
      <RouterLink v-if="$route.meta.requiresAuth" to="/admin/create" class="btn btn-primary">Create Article</RouterLink>
    </section>
  </main>
</template>
