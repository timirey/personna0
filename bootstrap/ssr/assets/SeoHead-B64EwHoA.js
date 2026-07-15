import { Head, usePage } from "@inertiajs/vue3";
import { Fragment, computed, createBlock, createCommentVNode, createVNode, mergeProps, openBlock, renderList, unref, useSSRContext, withCtx } from "vue";
import { ssrRenderAttr, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/components/SeoHead.vue
var _sfc_main = {
	__name: "SeoHead",
	__ssrInlineRender: true,
	props: {
		title: {
			type: String,
			default: ""
		},
		description: {
			type: String,
			default: ""
		},
		ogImage: {
			type: String,
			default: null
		},
		preloadImage: {
			type: String,
			default: null
		},
		preloadSrcset: {
			type: String,
			default: null
		},
		preloadSizes: {
			type: String,
			default: null
		},
		noindex: {
			type: Boolean,
			default: false
		}
	},
	setup(__props) {
		const props = __props;
		const page = usePage();
		const seo = computed(() => page.props.seo);
		const locale = computed(() => page.props.locale);
		const ogLocales = {
			ro: "ro_RO",
			ru: "ru_RU",
			en: "en_US"
		};
		const fullTitle = computed(() => props.title ? `${props.title} — Personna` : "Personna");
		const origin = computed(() => {
			try {
				return new URL(seo.value.canonical).origin;
			} catch {
				return "";
			}
		});
		const absolute = (url) => !url || url.startsWith("http") ? url : origin.value + url;
		const ogImageAbs = computed(() => absolute(props.ogImage));
		return (_ctx, _push, _parent, _attrs) => {
			_push(ssrRenderComponent(unref(Head), mergeProps({ title: fullTitle.value }, _attrs), {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						if (__props.preloadImage) _push(`<link head-key="preload-lcp" rel="preload" as="image"${ssrRenderAttr("href", __props.preloadImage)}${ssrRenderAttr("imagesrcset", __props.preloadSrcset)}${ssrRenderAttr("imagesizes", __props.preloadSizes)} fetchpriority="high"${_scopeId}>`);
						else _push(`<!---->`);
						_push(`<meta head-key="robots" name="robots"${ssrRenderAttr("content", __props.noindex ? "noindex" : "index, follow")}${_scopeId}><meta head-key="description" name="description"${ssrRenderAttr("content", __props.description)}${_scopeId}><link head-key="canonical" rel="canonical"${ssrRenderAttr("href", seo.value.canonical)}${_scopeId}><!--[-->`);
						ssrRenderList(seo.value.alternates, (url, code) => {
							_push(`<link${ssrRenderAttr("head-key", `hreflang-${code}`)} rel="alternate"${ssrRenderAttr("hreflang", code)}${ssrRenderAttr("href", url)}${_scopeId}>`);
						});
						_push(`<!--]--><link head-key="hreflang-x-default" rel="alternate" hreflang="x-default"${ssrRenderAttr("href", seo.value.xDefault)}${_scopeId}><meta head-key="og:type" property="og:type" content="website"${_scopeId}><meta head-key="og:site_name" property="og:site_name" content="Personna"${_scopeId}><meta head-key="og:title" property="og:title"${ssrRenderAttr("content", __props.title || "Personna")}${_scopeId}><meta head-key="og:description" property="og:description"${ssrRenderAttr("content", __props.description)}${_scopeId}><meta head-key="og:url" property="og:url"${ssrRenderAttr("content", seo.value.canonical)}${_scopeId}><meta head-key="og:locale" property="og:locale"${ssrRenderAttr("content", ogLocales[locale.value] || "ro_RO")}${_scopeId}>`);
						if (ogImageAbs.value) _push(`<meta head-key="og:image" property="og:image"${ssrRenderAttr("content", ogImageAbs.value)}${_scopeId}>`);
						else _push(`<!---->`);
						_push(`<meta head-key="twitter:card" name="twitter:card" content="summary_large_image"${_scopeId}>`);
					} else return [
						__props.preloadImage ? (openBlock(), createBlock("link", {
							key: 0,
							"head-key": "preload-lcp",
							rel: "preload",
							as: "image",
							href: __props.preloadImage,
							imagesrcset: __props.preloadSrcset,
							imagesizes: __props.preloadSizes,
							fetchpriority: "high"
						}, null, 8, [
							"href",
							"imagesrcset",
							"imagesizes"
						])) : createCommentVNode("", true),
						createVNode("meta", {
							"head-key": "robots",
							name: "robots",
							content: __props.noindex ? "noindex" : "index, follow"
						}, null, 8, ["content"]),
						createVNode("meta", {
							"head-key": "description",
							name: "description",
							content: __props.description
						}, null, 8, ["content"]),
						createVNode("link", {
							"head-key": "canonical",
							rel: "canonical",
							href: seo.value.canonical
						}, null, 8, ["href"]),
						(openBlock(true), createBlock(Fragment, null, renderList(seo.value.alternates, (url, code) => {
							return openBlock(), createBlock("link", {
								key: code,
								"head-key": `hreflang-${code}`,
								rel: "alternate",
								hreflang: code,
								href: url
							}, null, 8, [
								"head-key",
								"hreflang",
								"href"
							]);
						}), 128)),
						createVNode("link", {
							"head-key": "hreflang-x-default",
							rel: "alternate",
							hreflang: "x-default",
							href: seo.value.xDefault
						}, null, 8, ["href"]),
						createVNode("meta", {
							"head-key": "og:type",
							property: "og:type",
							content: "website"
						}),
						createVNode("meta", {
							"head-key": "og:site_name",
							property: "og:site_name",
							content: "Personna"
						}),
						createVNode("meta", {
							"head-key": "og:title",
							property: "og:title",
							content: __props.title || "Personna"
						}, null, 8, ["content"]),
						createVNode("meta", {
							"head-key": "og:description",
							property: "og:description",
							content: __props.description
						}, null, 8, ["content"]),
						createVNode("meta", {
							"head-key": "og:url",
							property: "og:url",
							content: seo.value.canonical
						}, null, 8, ["content"]),
						createVNode("meta", {
							"head-key": "og:locale",
							property: "og:locale",
							content: ogLocales[locale.value] || "ro_RO"
						}, null, 8, ["content"]),
						ogImageAbs.value ? (openBlock(), createBlock("meta", {
							key: 1,
							"head-key": "og:image",
							property: "og:image",
							content: ogImageAbs.value
						}, null, 8, ["content"])) : createCommentVNode("", true),
						createVNode("meta", {
							"head-key": "twitter:card",
							name: "twitter:card",
							content: "summary_large_image"
						})
					];
				}),
				_: 1
			}, _parent));
		};
	}
};
var _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/SeoHead.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export { _sfc_main as t };
