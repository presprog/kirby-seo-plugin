<template>
  <div class="social-preview">
    <k-frame v-if="this.image" :ratio="ratio" cover="true">
      <img :src="this.image">
    </k-frame>
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
    ratio: String,
  },
}
</script>

<style scoped>
.social-preview {
  background-color: white;
  border-radius: var(--input-rounded);
  box-shadow: var(--shadow);
  min-height: var(--min-preview-height);
}
.inner {
  padding: 0.5rem 0.75rem 0.75rem;
}
strong {
  display: block;
  color: #1558d6;
  font-size: 1.25rem;
  line-height: 1.3;
  margin-bottom: 0.25rem;
}
p {
  color: #4d5156;
  font-size: 0.875rem;
  line-height: 1.25;
}
</style>
