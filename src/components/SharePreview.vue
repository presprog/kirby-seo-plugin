<template>
  <k-section v-if="!isLoading" class="k-share-preview-section">

    <div class="k-grid" data-variant="fields">
      <header class="k-section-header">
        <h2 class="k-headline h2">{{ $t('share-preview-section-title') }}</h2>
      </header>

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
      let title

      if (this.currentContent.metatitle) {
        title = this.currentContent.metatitle
      } else {
        title = this.pageTitle
      }

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
      let image

      if (this.currentContent.ogimage) {
        image = this.currentContent.ogimage
      } else {
        image = this.siteImage ? this.siteImage : null
      }

      if (!image || Array.isArray(image) && image.length === 0) {
        return ''
      }

      return image[0].url
    }
    },

  created() {
    this.load().then(response => {
      this.siteTitle = response.siteTitle
      this.siteImage = response.siteImage
      this.pageTitle = response.pageTitle
      this.pageUrl = new URL(response.pageUrl)
      this.siteDescription = response.siteDescription
      this.metaOptions = response.metaOptions
      this.isLoading = false
    })
  },

  data() {
    return {
      isLoading:   true,
      siteTitle:   '',
      siteImage:   '',
      pageTitle:   '',
      pageUrl:     '',
      metaOptions: {},
    }
  },
}
</script>

<style scoped>
.k-headline {
  font-weight: var(--font-normal);
}

.k-grid {
  --columns: 1;
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
