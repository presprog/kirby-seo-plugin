<template>
  <k-field
    :help="help"
    :input="false"
    :label="label"
    :name="name"
    :type="type"
    class="k-share-preview-field"
  >

    <div v-if="!isLoading" class="previews">

      <k-box theme="warning" icon="alert" v-if="!description || !image">
        {{ $t('share-preview-incomplete-warning') }}
      </k-box>

      <figure class="preview-wrapper">
        <SerpPreview :title="title" :description="description" :url="this.pageUrl" />
        <figcaption class="k-help k-text"><p>{{ $t('share-preview-serp-caption') }}</p></figcaption>
      </figure>

      <figure class="preview-wrapper">
        <SocialPreview :title="title" :description="description" :image="this.image" :ratio="imageRatio" />
        <figcaption class="k-help k-text"><p>{{ $t('share-preview-social-caption') }}</p></figcaption>
      </figure>

      <figure class="preview-wrapper">
        <MessagingPreview :title="title" :description="description" :image="this.image" />
        <figcaption class="k-help k-text"><p>{{ $t('share-preview-messaging-caption') }}</p></figcaption>
      </figure>

    </div>
  </k-field>

</template>

<script>
import SerpPreview from './SerpPreview.vue'
import SocialPreview from './SocialPreview.vue'
import MessagingPreview from './MessagingPreview.vue'

export default {
  inheritAttrs: false,
  components: { MessagingPreview, SerpPreview, SocialPreview },

  props: {
    help: String,
    label: String,
    name: String,
    type: String,
    endpoints: Object,
  },

  computed: {
    currentContent() {
      return this.$panel.content.version('changes')
    },

    changes() {
      return this.$panel.content.diff()
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
      // Changes have been made to the open graph image
      if ('ogimage' in this.changes) {
        // New image has been set
        if (this.changes.ogimage.length > 0) {
          return this.changes.ogimage[0].url
        }

        // Existing image has been removed
        return this.fallbackImage
      }

      if (this.currentContent.ogimage.length > 0) {
        return this.currentContent.ogimage[0].url
      }

      return this.fallbackImage ?? ''
    },

    fallbackImage() {
      if (this.pageModelImage) {
        return this.pageModelImage
      }

      return this.siteImage ? this.siteImage : null
    },
  },

  created() {
    this.$panel.events.on("page.changeTitle", this.fetch)
    this.$panel.events.on("model.update", this.fetch)
    this.fetch()
  },

  beforeUnmount() {
    this.$panel.events.off("page.changeTitle", this.fetch)
    this.$panel.events.off("model.update", this.fetch)
  },

  data() {
    return {
      isLoading:       true,
      siteTitle:       '',
      siteDescription: '',
      siteImage:       '',
      pageTitle:       '',
      pageImage:       '',
      pageModelImage:  '',
      pageUrl:         '',
      imageRatio:      '',
      metaOptions:     {},
    }
  },

  methods: {
    fetch() {
      return this.$api.get(this.endpoints.field).then(response => {
        this.siteTitle = response.siteTitle
        this.siteDescription = response.siteDescription
        this.siteImage = response.siteImage
        this.pageTitle = response.pageTitle
        this.pageImage = response.pageImage
        this.pageModelImage = response.pageModelImage
        this.pageUrl = new URL(response.pageUrl)
        this.metaOptions = response.metaOptions
        this.imageRatio = `${this.metaOptions.ogImageWidth}/${this.metaOptions.ogImageHeight}`
        this.isLoading = false
      })
    }
  }
}
</script>

<style scoped>
.previews {
  display: grid;
  gap: var(--spacing-6);
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
