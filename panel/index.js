import SharePreview from './components/SharePreview.vue'

window.panel.plugin('presprog/meta', {
  sections: {
    sharePreview: SharePreview,
  },
})
