(function() {
  "use strict";
  const SerpPreview_vue_vue_type_style_index_0_scoped_72e904d5_lang = "";
  function normalizeComponent(scriptExports, render, staticRenderFns, functionalTemplate, injectStyles, scopeId, moduleIdentifier, shadowMode) {
    var options = typeof scriptExports === "function" ? scriptExports.options : scriptExports;
    if (render) {
      options.render = render;
      options.staticRenderFns = staticRenderFns;
      options._compiled = true;
    }
    if (functionalTemplate) {
      options.functional = true;
    }
    if (scopeId) {
      options._scopeId = "data-v-" + scopeId;
    }
    var hook;
    if (moduleIdentifier) {
      hook = function(context) {
        context = context || // cached call
        this.$vnode && this.$vnode.ssrContext || // stateful
        this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext;
        if (!context && typeof __VUE_SSR_CONTEXT__ !== "undefined") {
          context = __VUE_SSR_CONTEXT__;
        }
        if (injectStyles) {
          injectStyles.call(this, context);
        }
        if (context && context._registeredComponents) {
          context._registeredComponents.add(moduleIdentifier);
        }
      };
      options._ssrRegister = hook;
    } else if (injectStyles) {
      hook = shadowMode ? function() {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        );
      } : injectStyles;
    }
    if (hook) {
      if (options.functional) {
        options._injectStyles = hook;
        var originalRender = options.render;
        options.render = function renderWithStyleInjection(h, context) {
          hook.call(context);
          return originalRender(h, context);
        };
      } else {
        var existing = options.beforeCreate;
        options.beforeCreate = existing ? [].concat(existing, hook) : [hook];
      }
    }
    return {
      exports: scriptExports,
      options
    };
  }
  const _sfc_main$3 = {
    props: {
      title: String,
      description: String,
      url: URL
    },
    computed: {
      base() {
        if (!this.url) {
          return "";
        }
        return this.url.protocol + "://" + this.url.host;
      },
      path() {
        if (!this.url) {
          return "";
        }
        return this.url.pathname.trim().split("/").filter((fragment) => fragment);
      }
    }
  };
  var _sfc_render$3 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("div", { staticClass: "serp-preview" }, [_c("span", { staticClass: "url" }, [_vm._v(" " + _vm._s(_vm.base)), _vm._l(_vm.path, function(fragment) {
      return _c("span", [_vm._v(_vm._s(" › " + fragment))]);
    })], 2), _c("strong", [_vm._v(_vm._s(_vm.title))]), _vm.description ? _c("p", [_vm._v(_vm._s(_vm.description))]) : _vm._e()]);
  };
  var _sfc_staticRenderFns$3 = [];
  _sfc_render$3._withStripped = true;
  var __component__$3 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$3,
    _sfc_render$3,
    _sfc_staticRenderFns$3,
    false,
    null,
    "72e904d5",
    null,
    null
  );
  __component__$3.options.__file = "/home/benedict/dev/icolux/site/plugins/seo/panel/components/SerpPreview.vue";
  const SerpPreview = __component__$3.exports;
  const SocialPreview_vue_vue_type_style_index_0_scoped_61b213b1_lang = "";
  const _sfc_main$2 = {
    props: {
      title: String,
      description: String,
      image: String,
      ratio: String
    }
  };
  var _sfc_render$2 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("div", { staticClass: "social-preview" }, [this.image ? _c("k-frame", { attrs: { "ratio": _vm.ratio, "cover": "true" } }, [_c("img", { attrs: { "src": this.image } })]) : _vm._e(), _c("div", { staticClass: "inner" }, [_c("strong", [_vm._v(_vm._s(_vm.title))]), _vm.description ? _c("p", [_vm._v(_vm._s(_vm.description))]) : _vm._e()])], 1);
  };
  var _sfc_staticRenderFns$2 = [];
  _sfc_render$2._withStripped = true;
  var __component__$2 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$2,
    _sfc_render$2,
    _sfc_staticRenderFns$2,
    false,
    null,
    "61b213b1",
    null,
    null
  );
  __component__$2.options.__file = "/home/benedict/dev/icolux/site/plugins/seo/panel/components/SocialPreview.vue";
  const SocialPreview = __component__$2.exports;
  const MessagingPreview_vue_vue_type_style_index_0_scoped_8709a2b3_lang = "";
  const _sfc_main$1 = {
    props: {
      title: String,
      description: String,
      image: String
    }
  };
  var _sfc_render$1 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("div", { staticClass: "messaging-preview", class: { "with-image": !!this.image } }, [this.image ? _c("img", { attrs: { "src": this.image, "alt": "" } }) : _vm._e(), _c("div", { staticClass: "inner" }, [_c("strong", [_vm._v(_vm._s(_vm.title))]), _vm.description ? _c("p", [_vm._v(_vm._s(_vm.description))]) : _vm._e()])]);
  };
  var _sfc_staticRenderFns$1 = [];
  _sfc_render$1._withStripped = true;
  var __component__$1 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$1,
    _sfc_render$1,
    _sfc_staticRenderFns$1,
    false,
    null,
    "8709a2b3",
    null,
    null
  );
  __component__$1.options.__file = "/home/benedict/dev/icolux/site/plugins/seo/panel/components/MessagingPreview.vue";
  const MessagingPreview = __component__$1.exports;
  const SharePreview_vue_vue_type_style_index_0_scoped_f4ac27a7_lang = "";
  const _sfc_main = {
    components: { MessagingPreview, SerpPreview, SocialPreview },
    computed: {
      currentContent() {
        return this.$store.getters["content/values"]();
      },
      changes() {
        return this.$store.getters["content/changes"]();
      },
      title() {
        let title = this.currentContent.metatitle ? this.currentContent.metatitle : this.pageTitle;
        if (this.metaOptions.appendSiteTitle) {
          title += this.metaOptions.titleSeparator + this.siteTitle;
        }
        return title;
      },
      description() {
        if (this.currentContent.metadescription) {
          return this.currentContent.metadescription;
        }
        return this.siteDescription ? this.siteDescription : "";
      },
      image() {
        if ("ogimage" in this.changes) {
          if (this.changes.ogimage.length > 0) {
            return this.changes.ogimage[0].url;
          }
          return this.fallbackImage;
        }
        if (this.currentContent.ogimage.length > 0) {
          return this.currentContent.ogimage[0].url;
        }
        return this.fallbackImage ?? "";
      },
      fallbackImage() {
        if (this.pageModelImage) {
          return this.pageModelImage;
        }
        return this.siteImage ? this.siteImage : null;
      }
    },
    created() {
      this.$events.on("page.changeTitle", this.fetch);
      this.$events.on("model.update", this.fetch);
      this.fetch();
    },
    data() {
      return {
        isLoading: true,
        siteTitle: "",
        siteDescription: "",
        siteImage: "",
        pageTitle: "",
        pageImage: "",
        pageModelImage: "",
        pageUrl: "",
        imageRatio: "",
        metaOptions: {}
      };
    },
    methods: {
      fetch() {
        return this.load().then((response) => {
          this.siteTitle = response.siteTitle;
          this.siteDescription = response.siteDescription;
          this.siteImage = response.siteImage;
          this.pageTitle = response.pageTitle;
          this.pageImage = response.pageImage;
          this.pageModelImage = response.pageModelImage;
          this.pageUrl = new URL(response.pageUrl);
          this.metaOptions = response.metaOptions;
          this.imageRatio = `${this.metaOptions.ogImageWidth}/${this.metaOptions.ogImageHeight}`;
          this.isLoading = false;
        });
      }
    }
  };
  var _sfc_render = function render() {
    var _vm = this, _c = _vm._self._c;
    return !_vm.isLoading ? _c("k-section", { staticClass: "k-share-preview-section" }, [_c("div", { staticClass: "k-grid", attrs: { "data-variant": "fields" } }, [_c("header", { staticClass: "k-section-header" }, [_c("h2", { staticClass: "k-headline h2" }, [_vm._v(_vm._s(_vm.$t("share-preview-section-title")))])]), !_vm.description || !_vm.image ? _c("k-box", { attrs: { "theme": "warning", "icon": "alert" } }, [_vm._v(" " + _vm._s(_vm.$t("share-preview-incomplete-warning")) + " ")]) : _vm._e(), _c("figure", { staticClass: "preview-wrapper" }, [_c("SerpPreview", { attrs: { "title": _vm.title, "description": _vm.description, "url": this.pageUrl } }), _c("figcaption", { staticClass: "k-help k-text" }, [_c("p", [_vm._v(_vm._s(_vm.$t("share-preview-serp-caption")))])])], 1), _c("figure", { staticClass: "preview-wrapper" }, [_c("SocialPreview", { attrs: { "title": _vm.title, "description": _vm.description, "image": this.image, "ratio": _vm.imageRatio } }), _c("figcaption", { staticClass: "k-help k-text" }, [_c("p", [_vm._v(_vm._s(_vm.$t("share-preview-social-caption")))])])], 1), _c("figure", { staticClass: "preview-wrapper" }, [_c("MessagingPreview", { attrs: { "title": _vm.title, "description": _vm.description, "image": this.image } }), _c("figcaption", { staticClass: "k-help k-text" }, [_c("p", [_vm._v(_vm._s(_vm.$t("share-preview-messaging-caption")))])])], 1)], 1)]) : _vm._e();
  };
  var _sfc_staticRenderFns = [];
  _sfc_render._withStripped = true;
  var __component__ = /* @__PURE__ */ normalizeComponent(
    _sfc_main,
    _sfc_render,
    _sfc_staticRenderFns,
    false,
    null,
    "f4ac27a7",
    null,
    null
  );
  __component__.options.__file = "/home/benedict/dev/icolux/site/plugins/seo/panel/components/SharePreview.vue";
  const SharePreview = __component__.exports;
  window.panel.plugin("presprog/seo", {
    sections: {
      sharePreview: SharePreview
    }
  });
})();
