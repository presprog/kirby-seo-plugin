import SeoPreview from './components/SeoPreview.vue'

window.panel.plugin('getkirby/pluginkit', {
  sections: {
    seoPreview: SeoPreview,
  },
  use: {
    plugin(Vue) {
      window.__VUE_DEVTOOLS_GLOBAL_HOOK__.Vue = Vue;
    },
  },
})
