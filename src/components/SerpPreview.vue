<template>
  <div class="serp-preview">
    <span class="url">
      {{ base }}<span v-for="fragment of path">{{ ' › ' + fragment }}</span>
    </span>
    <strong>{{ this.title }}</strong>
    <p>{{ this.description }}</p>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    description: String,
    url: URL,
  },

  computed: {
    base() {
      if (!this.url) {
        return ''
      }

      return this.url.protocol + '://' + this.url.host
    },
    path() {
      if (!this.url) {
        return ''
      }

      return this.url.pathname.trim().split('/').filter(fragment => fragment)
    },
  },
}
</script>

<style scoped>
.serp-preview {
  padding: 0.75rem;
  background-color: white;
  border-radius: var(--input-rounded);
  box-shadow: var(--shadow);
}
.url {
  display: block;
  color: #3c4043;
  font-size: 0.75rem;
  line-height: 1.33;
  margin-bottom: 0.25rem;
}
.url span {
  color: #676e73;
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
