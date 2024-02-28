<template>
  <k-section v-if="!isLoading" class="k-share-preview-section">

    <div class="k-grid" data-variant="fields">
      <header class="k-section-header">
        <h2 class="k-headline h2">{{ $t('share-preview-section-title') }}</h2>
      </header>

      <k-box theme="warning" icon="alert" v-if="!description || !image">
        {{ $t('share-preview-incomplete-warning') }}
      </k-box>

      <figure class="preview-wrapper">
        <SerpPreview :title="title" :description="description" :url="this.pageUrl" />
        <figcaption class="k-help k-text"><p>{{ $t('share-preview-serp-caption') }}</p></figcaption>
      </figure>

      <figure class="preview-wrapper">
        <SocialPreview :title="title" :description="description" :image="this.image" />
        <figcaption class="k-help k-text"><p>{{ $t('share-preview-social-caption') }}</p></figcaption>
      </figure>

      <figure class="preview-wrapper">
        <MessagingPreview :title="title" :description="description" :image="this.image" />
        <figcaption class="k-help k-text"><p>{{ $t('share-preview-messaging-caption') }}</p></figcaption>
      </figure>

    </div>
  </k-section>

</template>

<script>
import SerpPreview from './SerpPreview.vue'
import SocialPreview from './SocialPreview.vue'
import MessagingPreview from './MessagingPreview.vue'

export default {
  components: { MessagingPreview, SerpPreview, SocialPreview },

  computed: {
    currentContent() {
      return this.$store.getters['content/values']()
    },

    title() {
      let title = (this.currentContent.metatitle) ? this.currentContent.metatitle : this.pageTitle

      if (this.metaOptions.appendSiteTitle) {
        title += this.metaOptions.titleSeparator + this.siteTitle
      }

      return title
    },

    description() {
      if (this.currentContent.metadescription) {
        return this.currentContent.metadescription;
      }

      return this.siteDescription ? this.siteDescription : ''
    },

    image() {
      if (this.currentContent.ogimage.length > 0) {
        return this.currentContent.ogimage[0].url
      }

      if (this.pageImage) {
        return this.pageImage
      }

      return this.siteImage ? this.siteImage : ''
    },
  },

  created() {
    this.$events.on("page.changeTitle", this.fetch)
    this.fetch()
  },

  data() {
    return {
      isLoading:       true,
      siteTitle:       '',
      siteDescription: '',
      siteImage:       '',
      pageTitle:       '',
      pageImage:       '',
      pageUrl:         '',
      metaOptions:     {},
    }
  },

  methods: {
    fetch() {
      return this.load().then(response => {
        this.siteTitle = response.siteTitle
        this.siteDescription = response.siteDescription
        this.siteImage = response.siteImage
        this.pageTitle = response.pageTitle
        this.pageImage = response.pageImage
        this.pageUrl = new URL(response.pageUrl)
        this.metaOptions = response.metaOptions
        this.isLoading = false
      })
    }
  }
}
</script>

<style scoped>
.k-headline {
  font-weight: var(--font-normal);
}
.k-grid {
  --columns: 1;
}
.preview-wrapper {
  --min-preview-height: 5rem;
}
.preview-wrapper h3 {
  margin-bottom: 0.5rem;
}
figcaption {
  margin-top: var(--spacing-2);
  font-size: var(--text-font-size);
  line-height: var(--text-line-height);
  color: var(--color-text-dimmed);
}
</style>