import SharePreview from './components/SharePreview.vue'

window.panel.plugin('presprog/meta', {
  sections: {
    sharePreview: SharePreview,
  },
  use: {
    plugin(Vue) {
      window.__VUE_DEVTOOLS_GLOBAL_HOOK__.Vue = Vue;
    },
  },
})
