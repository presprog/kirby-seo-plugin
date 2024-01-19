(function(){"use strict";const E="";function _(i,e,t,o,a,l,c,F){var s=typeof i=="function"?i.options:i;e&&(s.render=e,s.staticRenderFns=t,s._compiled=!0),o&&(s.functional=!0),l&&(s._scopeId="data-v-"+l);var n;if(c?(n=function(r){r=r||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext,!r&&typeof __VUE_SSR_CONTEXT__<"u"&&(r=__VUE_SSR_CONTEXT__),a&&a.call(this,r),r&&r._registeredComponents&&r._registeredComponents.add(c)},s._ssrRegister=n):a&&(n=F?function(){a.call(this,(s.functional?this.parent:this).$root.$options.shadowRoot)}:a),n)if(s.functional){s._injectStyles=n;var I=s.render;s.render=function(V,h){return n.call(h),I(V,h)}}else{var p=s.beforeCreate;s.beforeCreate=p?[].concat(p,n):[n]}return{exports:i,options:s}}const u={props:{title:String,description:String,url:URL},computed:{base(){return this.url?this.url.protocol+"://"+this.url.host:""},path(){return this.url?this.url.pathname.trim().split("/").filter(i=>i):""}}};var d=function(){var e=this,t=e._self._c;return t("div",{staticClass:"serp-preview"},[t("span",{staticClass:"url"},[e._v(" "+e._s(e.base)),e._l(e.path,function(o){return t("span",[e._v(e._s(" › "+o))])})],2),t("strong",[e._v(e._s(e.title))]),e.description?t("p",[e._v(e._s(e.description))]):e._e()])},v=[],g=_(u,d,v,!1,null,"4a0ec80c",null,null);const f=g.exports,M="",m={props:{title:String,description:String,image:String}};var w=function(){var e=this,t=e._self._c;return t("div",{staticClass:"social-preview"},[this.image?t("img",{attrs:{src:this.image}}):e._e(),t("div",{staticClass:"inner"},[t("strong",[e._v(e._s(e.title))]),e.description?t("p",[e._v(e._s(e.description))]):e._e()])])},C=[],$=_(m,w,C,!1,null,"52a313fa",null,null);const S=$.exports,x="",T={props:{title:String,description:String,image:String}};var k=function(){var e=this,t=e._self._c;return t("div",{staticClass:"messaging-preview",class:{"with-image":!!this.image}},[this.image?t("img",{attrs:{src:this.image,alt:""}}):e._e(),t("div",{staticClass:"inner"},[t("strong",[e._v(e._s(e.title))]),e.description?t("p",[e._v(e._s(e.description))]):e._e()])])},O=[],P=_(T,k,O,!1,null,"4b3604db",null,null);const R=P.exports,N="",U={components:{MessagingPreview:R,SerpPreview:f,SocialPreview:S},computed:{currentContent(){return this.$store.getters["content/values"]()},title(){let i=this.currentContent.metatitle?this.currentContent.metatitle:this.pageTitle;return this.metaOptions.appendSiteTitle&&(i+=this.metaOptions.titleSeparator+this.siteTitle),i},description(){return this.currentContent.metadescription?this.currentContent.metadescription:this.siteDescription?this.siteDescription:""},image(){return this.currentContent.ogimage.length>0?this.currentContent.ogimage[0].url:this.siteImage?this.siteImage:""}},created(){this.$events.on("page.changeTitle",this.reload),this.fetch()},data(){return{isLoading:!0,siteTitle:"",siteDescription:"",siteImage:"",pageTitle:"",pageUrl:"",metaOptions:{}}},methods:{reload(){return this.fetch()},fetch(){return this.load().then(i=>{this.siteTitle=i.siteTitle,this.siteDescription=i.siteDescription,this.siteImage=i.siteImage,this.pageTitle=i.pageTitle,this.pageUrl=new URL(i.pageUrl),this.metaOptions=i.metaOptions,this.isLoading=!1})}}};var b=function(){var e=this,t=e._self._c;return e.isLoading?e._e():t("k-section",{staticClass:"k-share-preview-section"},[t("div",{staticClass:"k-grid",attrs:{"data-variant":"fields"}},[t("header",{staticClass:"k-section-header"},[t("h2",{staticClass:"k-headline h2"},[e._v(e._s(e.$t("share-preview-section-title")))])]),t("figure",{staticClass:"preview-wrapper"},[t("SerpPreview",{attrs:{title:e.title,description:e.description,url:this.pageUrl}}),t("figcaption",{staticClass:"k-help k-text"},[t("p",[e._v(e._s(e.$t("share-preview-serp-caption")))])])],1),t("figure",{staticClass:"preview-wrapper"},[t("SocialPreview",{attrs:{title:e.title,description:e.description,image:this.image}}),t("figcaption",{staticClass:"k-help k-text"},[t("p",[e._v(e._s(e.$t("share-preview-social-caption")))])])],1),t("figure",{staticClass:"preview-wrapper"},[t("MessagingPreview",{attrs:{title:e.title,description:e.description,image:this.image}}),t("figcaption",{staticClass:"k-help k-text"},[t("p",[e._v(e._s(e.$t("share-preview-messaging-caption")))])])],1)])])},y=[],L=_(U,b,y,!1,null,"1100e782",null,null);const D=L.exports;window.panel.plugin("presprog/meta",{sections:{sharePreview:D},use:{plugin(i){window.__VUE_DEVTOOLS_GLOBAL_HOOK__.Vue=i}}})})();
