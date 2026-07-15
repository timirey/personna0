import { Link, createInertiaApp, usePage } from "@inertiajs/vue3";
import createServer from "@inertiajs/vue3/server";
import { renderToString } from "@vue/server-renderer";
import { computed, createBlock, createCommentVNode, createSSRApp, createTextVNode, h, mergeProps, openBlock, reactive, toDisplayString, unref, useSSRContext, watch, withCtx } from "vue";
import { ssrInterpolate, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass, ssrRenderComponent, ssrRenderList, ssrRenderSlot } from "vue/server-renderer";
//#region resources/js/components/LanguageSwitcher.vue
var _sfc_main$2 = {
	__name: "LanguageSwitcher",
	__ssrInlineRender: true,
	setup(__props) {
		const page = usePage();
		const locales = computed(() => page.props.locales);
		const current = computed(() => page.props.locale);
		const alternates = computed(() => page.props.seo.alternates);
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({
				class: "lang-switcher",
				role: "group",
				"aria-label": _ctx.$t("language")
			}, _attrs))}><!--[-->`);
			ssrRenderList(locales.value, (l) => {
				_push(ssrRenderComponent(unref(Link), {
					key: l.code,
					href: alternates.value[l.code],
					rel: "alternate",
					hreflang: l.code,
					"preserve-scroll": "",
					class: { "is-active": l.code === current.value },
					"aria-current": l.code === current.value ? "true" : null
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`${ssrInterpolate(l.code.toUpperCase())}`);
						else return [createTextVNode(toDisplayString(l.code.toUpperCase()), 1)];
					}),
					_: 2
				}, _parent));
			});
			_push(`<!--]--></div>`);
		};
	}
};
var _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/LanguageSwitcher.vue");
	return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
//#endregion
//#region resources/js/lib/toasts.js
var toastState = reactive({
	items: [],
	seq: 0
});
//#endregion
//#region resources/js/components/Toasts.vue
var _sfc_main$1 = {
	__name: "Toasts",
	__ssrInlineRender: true,
	setup(__props) {
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({
				class: "toasts",
				"aria-live": "polite",
				"aria-atomic": "true"
			}, _attrs))}><!--[-->`);
			ssrRenderList(unref(toastState).items, (t) => {
				_push(`<div class="${ssrRenderClass([`toast--${t.type}`, "toast"])}">${ssrInterpolate(t.message)}</div>`);
			});
			_push(`<!--]--></div>`);
		};
	}
};
var _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/Toasts.vue");
	return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
//#endregion
//#region resources/js/Layout.vue
var _sfc_main = {
	__name: "Layout",
	__ssrInlineRender: true,
	setup(__props) {
		const page = usePage();
		const shop = computed(() => page.props.shop);
		const urls = computed(() => page.props.urls);
		const cartCount = computed(() => page.props.cartCount);
		const locale = computed(() => page.props.locale);
		const year = (/* @__PURE__ */ new Date()).getFullYear();
		watch(locale, (value) => {
			if (typeof document !== "undefined") document.documentElement.lang = value;
		}, { immediate: true });
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[--><a class="skip-link" href="#main">${ssrInterpolate(_ctx.$t("nav.home"))}</a><header class="site-header"><div class="wrap site-header__inner">`);
			_push(ssrRenderComponent(unref(Link), {
				href: urls.value.catalogue,
				class: "wordmark",
				"aria-label": "Personna"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`Personna`);
					else return [createTextVNode("Personna")];
				}),
				_: 1
			}, _parent));
			_push(`<div class="site-header__actions">`);
			_push(ssrRenderComponent(_sfc_main$2, null, null, _parent));
			_push(ssrRenderComponent(unref(Link), {
				href: urls.value.cart,
				class: "cart-link",
				"aria-label": _ctx.$t("nav.cart")
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`${ssrInterpolate(_ctx.$t("nav.cart"))} `);
						if (cartCount.value > 0) _push(`<span class="cart-count"${_scopeId}>${ssrInterpolate(cartCount.value)}</span>`);
						else _push(`<!---->`);
					} else return [createTextVNode(toDisplayString(_ctx.$t("nav.cart")) + " ", 1), cartCount.value > 0 ? (openBlock(), createBlock("span", {
						key: 0,
						class: "cart-count"
					}, toDisplayString(cartCount.value), 1)) : createCommentVNode("", true)];
				}),
				_: 1
			}, _parent));
			_push(`</div></div></header><main id="main">`);
			ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
			_push(`</main><footer class="site-footer"><div class="wrap site-footer__inner"><div><p class="wordmark wordmark--sm">Personna</p><p class="site-footer__tagline">${ssrInterpolate(_ctx.$t("tagline"))}</p></div><div class="site-footer__contact"><p class="eyebrow">${ssrInterpolate(_ctx.$t("footer.contact"))}</p>`);
			if (shop.value.phone) _push(`<p><a${ssrRenderAttr("href", `tel:${shop.value.phone}`)}>${ssrInterpolate(shop.value.phone)}</a></p>`);
			else _push(`<!---->`);
			_push(`<div class="socials">`);
			if (shop.value.instagram) _push(`<a${ssrRenderAttr("href", shop.value.instagram)} target="_blank" rel="noopener" class="social" aria-label="Instagram"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><circle cx="17.5" cy="6.5" r="1.1" fill="currentColor" stroke="none"></circle></svg><span>Instagram</span></a>`);
			else _push(`<!---->`);
			if (shop.value.telegram) _push(`<a${ssrRenderAttr("href", shop.value.telegram)} target="_blank" rel="noopener" class="social" aria-label="Telegram"><svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M21.94 4.64 18.9 19a1 1 0 0 1-1.61.6l-4.1-3.02-2.03 1.96a.55.55 0 0 1-.94-.35l-.28-4.06 7.4-6.68c.28-.25-.06-.4-.42-.16l-9.14 5.76-3.94-1.23c-.79-.24-.8-.79.18-1.17l15.4-5.94c.66-.25 1.24.16 1.02 1.13z"></path></svg><span>Telegram</span></a>`);
			else _push(`<!---->`);
			_push(`</div></div></div><div class="wrap site-footer__legal"><span>© ${ssrInterpolate(unref(year))} Personna. ${ssrInterpolate(_ctx.$t("footer.rights"))}</span></div></footer>`);
			_push(ssrRenderComponent(_sfc_main$1, null, null, _parent));
			_push(`<!--]-->`);
		};
	}
};
var _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layout.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
//#region resources/js/lib/resolve.js
var pages = /* #__PURE__ */ Object.assign({
	"../Pages/Cart.vue": () => import("./assets/Cart-AEAK6qS3.js"),
	"../Pages/Catalogue.vue": () => import("./assets/Catalogue-x8Dz75ju.js"),
	"../Pages/Checkout.vue": () => import("./assets/Checkout-CWPXJSuX.js"),
	"../Pages/Product.vue": () => import("./assets/Product-CFVFxkFo.js"),
	"../Pages/Success.vue": () => import("./assets/Success-B9B9Qgq5.js")
});
async function resolvePage(name) {
	const importer = pages[`../Pages/${name}.vue`];
	if (!importer) throw new Error(`Inertia page not found: ${name}`);
	const page = await importer();
	page.default.layout = page.default.layout ?? _sfc_main;
	return page;
}
//#endregion
//#region resources/js/lib/i18n.js
function trans(key, replace = {}) {
	const messages = usePage().props.translations ?? {};
	let value = key.split(".").reduce((carry, part) => carry != null ? carry[part] : void 0, messages);
	if (value == null) return key;
	for (const [token, replacement] of Object.entries(replace)) value = value.replaceAll(`:${token}`, replacement);
	return value;
}
var i18n = { install(app) {
	app.config.globalProperties.$t = trans;
} };
//#endregion
//#region resources/js/ssr.js
createServer((page) => createInertiaApp({
	page,
	render: renderToString,
	resolve: resolvePage,
	setup({ App, props, plugin }) {
		return createSSRApp({ render: () => h(App, props) }).use(plugin).use(i18n);
	}
}));
//#endregion
export {};
