<template>
  <div class="messaging-preview" :class="{ 'with-image': !!this.image }">
    <img v-if="this.image" :src="this.image" alt="">
    <div class="inner">
      <strong>{{ title }}</strong>
      <p v-if="description">{{ description }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    description: String,
    image: String,
  },
}
</script>

<style scoped>
.messaging-preview {
  background-color: white;
  border-radius: var(--input-rounded);
  box-shadow: var(--shadow);
  color: #4d5156;
  min-height: var(--min-preview-height);
}

.messaging-preview.with-image {
  display: grid;
  grid-template-columns: 7rem auto;
}

img {
  display: block;
  width: 100%;
  height: 100%;
  aspect-ratio: 1;
  object-fit: cover;
  object-position: center;
}
.inner {
  padding: 0.5rem 0.75rem 0.75rem;
}
strong {
  display: block;
  font-size: 1.25rem;
  line-height: 1.3;
  margin-bottom: 0.25rem;
}
p {
  font-size: 0.875rem;
  line-height: 1.25;
}
</style>
