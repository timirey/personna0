import { t as _sfc_main$2 } from "./SeoHead-B64EwHoA.js";
import { Link, usePage } from "@inertiajs/vue3";
import { computed, createBlock, createCommentVNode, createTextVNode, createVNode, mergeProps, openBlock, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { ssrInterpolate, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/components/ProductCard.vue
var _sfc_main$1 = {
	__name: "ProductCard",
	__ssrInlineRender: true,
	props: { product: {
		type: Object,
		required: true
	} },
	setup(__props) {
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<article${ssrRenderAttrs(mergeProps({ class: "card" }, _attrs))}>`);
			_push(ssrRenderComponent(unref(Link), {
				href: __props.product.url,
				class: "card__link"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<div class="card__media"${_scopeId}>`);
						if (__props.product.image) _push(`<img class="product-img"${ssrRenderAttr("src", __props.product.image.card)}${ssrRenderAttr("srcset", `${__props.product.image.thumb} 400w, ${__props.product.image.card} 800w, ${__props.product.image.full} 1600w`)} sizes="(max-width: 760px) 50vw, 360px" width="800" height="1000"${ssrRenderAttr("alt", __props.product.name)} loading="lazy" decoding="async"${_scopeId}>`);
						else _push(`<div class="product-img product-img--ph"${_scopeId}><span${_scopeId}>Personna</span></div>`);
						if (__props.product.soldOut) _push(`<span class="card__badge"${_scopeId}>${ssrInterpolate(_ctx.$t("product.sold_out"))}</span>`);
						else if (__props.product.onSale) _push(`<span class="card__badge card__badge--sale"${_scopeId}>−${ssrInterpolate(__props.product.discountPercent)}%</span>`);
						else _push(`<!---->`);
						_push(`</div><div class="card__body"${_scopeId}><h3 class="card__title"${_scopeId}>${ssrInterpolate(__props.product.name)}</h3><p class="card__price"${_scopeId}><span class="${ssrRenderClass({ "price--sale": __props.product.onSale })}"${_scopeId}>${ssrInterpolate(__props.product.priceFormatted)}</span>`);
						if (__props.product.onSale) _push(`<span class="price--old"${_scopeId}>${ssrInterpolate(__props.product.originalPriceFormatted)}</span>`);
						else _push(`<!---->`);
						_push(`</p></div>`);
					} else return [createVNode("div", { class: "card__media" }, [__props.product.image ? (openBlock(), createBlock("img", {
						key: 0,
						class: "product-img",
						src: __props.product.image.card,
						srcset: `${__props.product.image.thumb} 400w, ${__props.product.image.card} 800w, ${__props.product.image.full} 1600w`,
						sizes: "(max-width: 760px) 50vw, 360px",
						width: "800",
						height: "1000",
						alt: __props.product.name,
						loading: "lazy",
						decoding: "async"
					}, null, 8, [
						"src",
						"srcset",
						"alt"
					])) : (openBlock(), createBlock("div", {
						key: 1,
						class: "product-img product-img--ph"
					}, [createVNode("span", null, "Personna")])), __props.product.soldOut ? (openBlock(), createBlock("span", {
						key: 2,
						class: "card__badge"
					}, toDisplayString(_ctx.$t("product.sold_out")), 1)) : __props.product.onSale ? (openBlock(), createBlock("span", {
						key: 3,
						class: "card__badge card__badge--sale"
					}, "−" + toDisplayString(__props.product.discountPercent) + "%", 1)) : createCommentVNode("", true)]), createVNode("div", { class: "card__body" }, [createVNode("h3", { class: "card__title" }, toDisplayString(__props.product.name), 1), createVNode("p", { class: "card__price" }, [createVNode("span", { class: { "price--sale": __props.product.onSale } }, toDisplayString(__props.product.priceFormatted), 3), __props.product.onSale ? (openBlock(), createBlock("span", {
						key: 0,
						class: "price--old"
					}, toDisplayString(__props.product.originalPriceFormatted), 1)) : createCommentVNode("", true)])])];
				}),
				_: 1
			}, _parent));
			_push(`</article>`);
		};
	}
};
var _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/ProductCard.vue");
	return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
//#endregion
//#region resources/js/Pages/Catalogue.vue
var _sfc_main = {
	__name: "Catalogue",
	__ssrInlineRender: true,
	props: {
		products: {
			type: Object,
			required: true
		},
		categories: {
			type: Array,
			default: () => []
		},
		activeCategory: {
			type: String,
			default: null
		},
		heroImage: {
			type: Object,
			default: null
		},
		title: {
			type: String,
			default: ""
		},
		description: {
			type: String,
			default: ""
		}
	},
	setup(__props) {
		const catalogueUrl = computed(() => usePage().props.urls.catalogue);
		const categoryHref = (slug) => slug ? `${catalogueUrl.value}?category=${slug}` : catalogueUrl.value;
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(_sfc_main$2, {
				title: __props.title,
				description: __props.description,
				"og-image": __props.heroImage?.url,
				"preload-image": __props.heroImage?.url
			}, null, _parent));
			_push(`<section class="${ssrRenderClass([{ "hero--split": __props.heroImage }, "hero"])}"><div class="wrap hero__inner"><div class="hero__text"><p class="eyebrow">Personna</p><h1 class="hero__title">${ssrInterpolate(_ctx.$t("tagline"))}</h1></div>`);
			if (__props.heroImage) _push(`<div class="hero__media"><img${ssrRenderAttr("src", __props.heroImage.url)}${ssrRenderAttr("width", __props.heroImage.width)}${ssrRenderAttr("height", __props.heroImage.height)} alt="Personna" fetchpriority="high" decoding="async"></div>`);
			else _push(`<!---->`);
			_push(`</div></section><section class="wrap catalogue"><h2 class="visually-hidden">${ssrInterpolate(_ctx.$t("catalogue.all"))}</h2>`);
			if (__props.categories.length) {
				_push(`<nav class="filters"${ssrRenderAttr("aria-label", _ctx.$t("catalogue.all"))}>`);
				_push(ssrRenderComponent(unref(Link), {
					href: categoryHref(null),
					"preserve-scroll": "",
					only: ["products", "activeCategory"],
					class: { "is-active": !__props.activeCategory }
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`${ssrInterpolate(_ctx.$t("catalogue.all"))}`);
						else return [createTextVNode(toDisplayString(_ctx.$t("catalogue.all")), 1)];
					}),
					_: 1
				}, _parent));
				_push(`<!--[-->`);
				ssrRenderList(__props.categories, (c) => {
					_push(ssrRenderComponent(unref(Link), {
						key: c.slug,
						href: categoryHref(c.slug),
						"preserve-scroll": "",
						only: ["products", "activeCategory"],
						class: { "is-active": __props.activeCategory === c.slug }
					}, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`${ssrInterpolate(c.name)}`);
							else return [createTextVNode(toDisplayString(c.name), 1)];
						}),
						_: 2
					}, _parent));
				});
				_push(`<!--]--></nav>`);
			} else _push(`<!---->`);
			if (!__props.products.data.length) _push(`<p class="empty">${ssrInterpolate(_ctx.$t("catalogue.empty"))}</p>`);
			else {
				_push(`<!--[--><div class="grid"><!--[-->`);
				ssrRenderList(__props.products.data, (product) => {
					_push(ssrRenderComponent(_sfc_main$1, {
						key: product.id,
						product
					}, null, _parent));
				});
				_push(`<!--]--></div>`);
				if (__props.products.last_page > 1) {
					_push(`<nav class="pager" aria-label="Pagination">`);
					if (__props.products.prev_page_url) _push(ssrRenderComponent(unref(Link), {
						class: "pager__btn",
						href: __props.products.prev_page_url,
						"preserve-scroll": "",
						only: ["products", "activeCategory"],
						rel: "prev"
					}, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`←`);
							else return [createTextVNode("←")];
						}),
						_: 1
					}, _parent));
					else _push(`<span class="pager__btn is-disabled" aria-hidden="true">←</span>`);
					_push(`<span class="pager__info">${ssrInterpolate(__props.products.current_page)} / ${ssrInterpolate(__props.products.last_page)}</span>`);
					if (__props.products.next_page_url) _push(ssrRenderComponent(unref(Link), {
						class: "pager__btn",
						href: __props.products.next_page_url,
						"preserve-scroll": "",
						only: ["products", "activeCategory"],
						rel: "next"
					}, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`→`);
							else return [createTextVNode("→")];
						}),
						_: 1
					}, _parent));
					else _push(`<span class="pager__btn is-disabled" aria-hidden="true">→</span>`);
					_push(`</nav>`);
				} else _push(`<!---->`);
				_push(`<!--]-->`);
			}
			_push(`</section><!--]-->`);
		};
	}
};
var _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Catalogue.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export { _sfc_main as default };
