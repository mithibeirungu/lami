<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import api from '../services/api'

const route = useRoute()
const car = ref(null)
const commentText = ref('')
const loading = ref(true)
const fallbackImage = 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1400&q=70'

const load = async () => {
  try {
    const res = await api.getCar(route.params.id)
    car.value = res.data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

onMounted(load)

const heroImage = computed(() => {
  if (car.value?.thumbnail_image) return car.value.thumbnail_image
  if (car.value?.images?.[0]?.image_url) return car.value.images[0].image_url
  return fallbackImage
})

const publishDate = computed(() => {
  if (!car.value?.created_at) return ''
  return new Date(car.value.created_at).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
})

const submitComment = async () => {
  const user = JSON.parse(localStorage.getItem('user') || '{}')
  if (!user.user_id) {
    alert('Please login to comment')
    return
  }
  try {
    await api.postComment(route.params.id, { content: commentText.value })
    commentText.value = ''
    load()
  } catch (e) {
    console.error(e)
    alert('Failed to post comment')
  }
}

const toggleFavorite = async () => {
  const user = JSON.parse(localStorage.getItem('user') || '{}')
  if (!user.user_id) {
    alert('Please login to favorite')
    return
  }
  try {
    await api.toggleFavorite(route.params.id, {})
    load()
  } catch (e) {
    console.error(e)
    alert('Failed to toggle favorite')
  }
}
</script>

<template>
  <article class="article-page container" v-if="car">
    <header class="article-header">
      <div class="article-meta">
        <span class="article-tag">{{ car.brand?.name }}</span>
        <span class="article-date">{{ publishDate }}</span>
      </div>
      <h1 class="article-title">{{ car.title }}</h1>
      <p class="article-subtitle">{{ car.brand?.name }} {{ car.model }} • {{ car.year }}</p>
    </header>

    <div class="article-hero-image" :style="{ backgroundImage: `url(${heroImage})` }"></div>

    <div class="article-body">
      <div class="article-specs">
        <div class="spec-item">
          <span class="spec-label">Body Type</span>
          <span class="spec-value">{{ car.bodyType?.name || '—' }}</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Horsepower</span>
          <span class="spec-value">{{ car.horsepower || '—' }} hp</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Torque</span>
          <span class="spec-value">{{ car.torque || '—' }} Nm</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Transmission</span>
          <span class="spec-value">{{ car.transmission || '—' }}</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Fuel Type</span>
          <span class="spec-value">{{ car.fuel_type || '—' }}</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Engine Type</span>
          <span class="spec-value">{{ car.engine_type || '—' }}</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Engine Size</span>
          <span class="spec-value">{{ car.engine_size || '—' }}</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Seating</span>
          <span class="spec-value">{{ car.seating_capacity || '—' }}</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Drive Type</span>
          <span class="spec-value">{{ car.drive_type || '—' }}</span>
        </div>
        <div class="spec-item" v-if="car.price">
          <span class="spec-label">Price</span>
          <span class="spec-value">${{ car.price.toLocaleString() }}</span>
        </div>
      </div>

      <div class="article-content">
        <p class="article-description">{{ car.description || 'No description available.' }}</p>
      </div>

      <div class="article-actions">
        <button class="btn btn-ghost" @click="toggleFavorite">♥ Save to favorites</button>
      </div>
    </div>

    <!-- Images Gallery -->
    <section class="images-gallery" v-if="car.images?.length">
      <h2>Gallery</h2>
      <div class="gallery-grid">
        <img v-for="img in car.images" :key="img.image_id" :src="img.image_url" :alt="img.title" />
      </div>
    </section>

    <section class="comments-section">
      <h2 class="comments-title">Reader Comments</h2>
      <p class="comments-count" v-if="car.comments?.length">{{ car.comments.length }} comment{{ car.comments.length !== 1 ? 's' : '' }}</p>

      <div v-if="car.comments?.length" class="comments-list">
        <div v-for="c in car.comments" :key="c.comment_id" class="comment-item">
          <div class="comment-header">
            <strong class="comment-author">{{ c.user?.username || 'Anonymous' }}</strong>
          </div>
          <p class="comment-text">{{ c.content }}</p>
        </div>
      </div>
      <div v-else class="empty-comments">
        <p>No comments yet. Be the first to share your thoughts!</p>
      </div>

      <form class="comment-form" @submit.prevent="submitComment">
        <h3 class="comment-form-title">Add a Comment</h3>
        <textarea
          v-model="commentText"
          rows="4"
          placeholder="Share your thoughts about this car..."
          class="comment-textarea"
          required
        ></textarea>
        <div class="comment-form-footer">
          <button class="btn btn-primary" type="submit">Post Comment</button>
        </div>
      </form>
    </section>
  </article>

  <div v-else-if="loading" class="empty container" style="padding: 80px 0">Loading car...</div>
  <div v-else class="empty container" style="padding: 80px 0">Car not found.</div>
</template>

<style scoped>
.article-page {
  max-width: 900px;
  padding: 60px 0 80px 0;
}

.article-header {
  margin-bottom: 40px;
  padding-bottom: 24px;
  border-bottom: 1px solid var(--border);
}

.article-meta {
  display: flex;
  gap: 16px;
  align-items: center;
  margin-bottom: 16px;
  font-size: 0.9rem;
}

.article-tag {
  background: var(--accent);
  color: #1a1a1a;
  padding: 6px 12px;
  border-radius: 4px;
  font-weight: 600;
  font-size: 0.85rem;
}

.article-date {
  color: var(--muted);
}

.article-title {
  font-size: clamp(2rem, 5vw, 3rem);
  font-weight: 700;
  margin: 0 0 12px 0;
  line-height: 1.2;
}

.article-subtitle {
  font-size: 1.1rem;
  color: var(--muted);
  margin: 0;
}

.article-hero-image {
  width: 100%;
  height: 500px;
  background-size: cover;
  background-position: center;
  border-radius: 12px;
  margin-bottom: 40px;
}

.article-body {
  margin-bottom: 60px;
}

.article-specs {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
  padding: 24px;
  background: var(--surface);
  border-radius: 12px;
  border: 1px solid var(--border);
}

.spec-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.spec-label {
  font-size: 0.85rem;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.spec-value {
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--text);
}

.article-content {
  margin-bottom: 32px;
}

.article-description {
  font-size: 1.1rem;
  line-height: 1.8;
  color: rgba(255, 255, 255, 0.9);
  white-space: pre-wrap;
}

.article-actions {
  padding-top: 24px;
  border-top: 1px solid var(--border);
}

.images-gallery {
  margin-bottom: 60px;
  padding-bottom: 40px;
  border-bottom: 1px solid var(--border);
}

.images-gallery h2 {
  margin-bottom: 20px;
}

.gallery-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
}

.gallery-grid img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 8px;
}

.comments-section {
  padding-top: 40px;
  border-top: 1px solid var(--border);
}

.comments-title {
  font-size: 1.8rem;
  font-weight: 600;
  margin-bottom: 8px;
}

.comments-count {
  color: var(--muted);
  margin-bottom: 32px;
}

.comments-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-bottom: 40px;
}

.comment-item {
  padding: 20px;
  background: var(--surface);
  border-radius: 8px;
  border: 1px solid var(--border);
}

.comment-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}

.comment-author {
  color: var(--accent);
  font-size: 0.95rem;
}

.comment-text {
  color: rgba(255, 255, 255, 0.85);
  line-height: 1.6;
  margin: 0;
}

.empty-comments {
  text-align: center;
  padding: 40px 0;
  color: var(--muted);
  margin-bottom: 40px;
}

.comment-form {
  padding: 32px;
  background: var(--surface);
  border-radius: 12px;
  border: 1px solid var(--border);
}

.comment-form-title {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 20px;
}

.comment-textarea {
  width: 100%;
  padding: 14px;
  border-radius: 8px;
  border: 1px solid var(--border);
  background: rgba(8, 12, 28, 0.6);
  color: var(--text);
  font-family: inherit;
  font-size: 1rem;
  resize: vertical;
  margin-bottom: 16px;
}

.comment-textarea:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(216, 184, 114, 0.1);
}

.comment-form-footer {
  display: flex;
  gap: 12px;
  align-items: center;
}

@media (max-width: 768px) {
  .article-hero-image {
    height: 300px;
  }

  .article-specs {
    grid-template-columns: 1fr;
  }

  .gallery-grid {
    grid-template-columns: 1fr;
  }

  .comment-form-footer {
    flex-direction: column;
    align-items: stretch;
  }
}
</style>
