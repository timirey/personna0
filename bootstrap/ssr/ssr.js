import { Head, Link, createInertiaApp, useForm, usePage } from "@inertiajs/vue3";
import createServer from "@inertiajs/vue3/server";
import { renderToString } from "@vue/server-renderer";
import { Fragment, computed, createBlock, createCommentVNode, createSSRApp, createTextVNode, createVNode, h, mergeProps, onMounted, openBlock, reactive, ref, renderList, toDisplayString, unref, useSSRContext, watch, withCtx } from "vue";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrLooseEqual, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass, ssrRenderComponent, ssrRenderList, ssrRenderSlot, ssrRenderStyle } from "vue/server-renderer";
//#region \0rolldown/runtime.js
var __defProp = Object.defineProperty;
var __exportAll = (all, no_symbols) => {
	let target = {};
	for (var name in all) __defProp(target, name, {
		get: all[name],
		enumerable: true
	});
	if (!no_symbols) __defProp(target, Symbol.toStringTag, { value: "Module" });
	return target;
};
//#endregion
//#region resources/js/components/SeoHead.vue
var _sfc_main$12 = {
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
		return (_ctx, _push, _parent, _attrs) => {
			_push(ssrRenderComponent(unref(Head), mergeProps({ title: fullTitle.value }, _attrs), {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) {
						_push(`<meta head-key="robots" name="robots"${ssrRenderAttr("content", __props.noindex ? "noindex" : "index, follow")}${_scopeId}><meta head-key="description" name="description"${ssrRenderAttr("content", __props.description)}${_scopeId}><link head-key="canonical" rel="canonical"${ssrRenderAttr("href", seo.value.canonical)}${_scopeId}><!--[-->`);
						ssrRenderList(seo.value.alternates, (url, code) => {
							_push(`<link${ssrRenderAttr("head-key", `hreflang-${code}`)} rel="alternate"${ssrRenderAttr("hreflang", code)}${ssrRenderAttr("href", url)}${_scopeId}>`);
						});
						_push(`<!--]--><link head-key="hreflang-x-default" rel="alternate" hreflang="x-default"${ssrRenderAttr("href", seo.value.xDefault)}${_scopeId}><meta head-key="og:type" property="og:type" content="website"${_scopeId}><meta head-key="og:site_name" property="og:site_name" content="Personna"${_scopeId}><meta head-key="og:title" property="og:title"${ssrRenderAttr("content", __props.title || "Personna")}${_scopeId}><meta head-key="og:description" property="og:description"${ssrRenderAttr("content", __props.description)}${_scopeId}><meta head-key="og:url" property="og:url"${ssrRenderAttr("content", seo.value.canonical)}${_scopeId}><meta head-key="og:locale" property="og:locale"${ssrRenderAttr("content", ogLocales[locale.value] || "ro_RO")}${_scopeId}>`);
						if (__props.ogImage) _push(`<meta head-key="og:image" property="og:image"${ssrRenderAttr("content", __props.ogImage)}${_scopeId}>`);
						else _push(`<!---->`);
						_push(`<meta head-key="twitter:card" name="twitter:card" content="summary_large_image"${_scopeId}>`);
					} else return [
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
						__props.ogImage ? (openBlock(), createBlock("meta", {
							key: 0,
							"head-key": "og:image",
							property: "og:image",
							content: __props.ogImage
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
var _sfc_setup$12 = _sfc_main$12.setup;
_sfc_main$12.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/SeoHead.vue");
	return _sfc_setup$12 ? _sfc_setup$12(props, ctx) : void 0;
};
//#endregion
//#region resources/js/Pages/Cart.vue
var Cart_exports = /* @__PURE__ */ __exportAll({ default: () => _sfc_main$11 });
var _sfc_main$11 = {
	__name: "Cart",
	__ssrInlineRender: true,
	props: {
		rows: {
			type: Array,
			default: () => []
		},
		total: {
			type: Number,
			default: 0
		},
		totalFormatted: {
			type: String,
			default: ""
		},
		title: {
			type: String,
			default: ""
		}
	},
	setup(__props) {
		const urls = computed(() => usePage().props.urls);
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(_sfc_main$12, {
				title: __props.title,
				noindex: ""
			}, null, _parent));
			_push(`<div class="wrap page"><h1 class="page__title">${ssrInterpolate(_ctx.$t("cart.title"))}</h1>`);
			if (!__props.rows.length) {
				_push(`<!--[--><p class="empty">${ssrInterpolate(_ctx.$t("cart.empty"))}</p>`);
				_push(ssrRenderComponent(unref(Link), {
					href: urls.value.catalogue,
					class: "btn"
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`${ssrInterpolate(_ctx.$t("cart.continue"))}`);
						else return [createTextVNode(toDisplayString(_ctx.$t("cart.continue")), 1)];
					}),
					_: 1
				}, _parent));
				_push(`<!--]-->`);
			} else {
				_push(`<div class="cart"><div class="cart__lines"><!--[-->`);
				ssrRenderList(__props.rows, (row) => {
					_push(`<div class="cart-row">`);
					_push(ssrRenderComponent(unref(Link), {
						href: row.url,
						class: "cart-row__media"
					}, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) if (row.image) _push(`<img class="product-img"${ssrRenderAttr("src", row.image.thumb)} width="88" height="110"${ssrRenderAttr("alt", row.name)} loading="lazy"${_scopeId}>`);
							else _push(`<div class="product-img product-img--ph"${_scopeId}><span${_scopeId}>P</span></div>`);
							else return [row.image ? (openBlock(), createBlock("img", {
								key: 0,
								class: "product-img",
								src: row.image.thumb,
								width: "88",
								height: "110",
								alt: row.name,
								loading: "lazy"
							}, null, 8, ["src", "alt"])) : (openBlock(), createBlock("div", {
								key: 1,
								class: "product-img product-img--ph"
							}, [createVNode("span", null, "P")]))];
						}),
						_: 2
					}, _parent));
					_push(`<div class="cart-row__info">`);
					_push(ssrRenderComponent(unref(Link), {
						href: row.url,
						class: "cart-row__name"
					}, {
						default: withCtx((_, _push, _parent, _scopeId) => {
							if (_push) _push(`${ssrInterpolate(row.name)}`);
							else return [createTextVNode(toDisplayString(row.name), 1)];
						}),
						_: 2
					}, _parent));
					if (row.size) _push(`<span class="cart-row__meta">${ssrInterpolate(_ctx.$t("cart.size"))}: ${ssrInterpolate(row.size)}</span>`);
					else _push(`<!---->`);
					_push(`<span class="cart-row__meta">${ssrInterpolate(row.unitFormatted)}</span></div><div class="cart-row__qty"><input type="number"${ssrRenderAttr("value", row.qty)} min="0" max="99"${ssrRenderAttr("aria-label", _ctx.$t("product.quantity"))}></div><span class="cart-row__total">${ssrInterpolate(row.lineFormatted)}</span><button class="cart-row__remove" type="button"${ssrRenderAttr("aria-label", _ctx.$t("cart.remove"))}>×</button></div>`);
				});
				_push(`<!--]--></div><aside class="cart__summary"><div class="summary__row summary__row--total"><span>${ssrInterpolate(_ctx.$t("cart.subtotal"))}</span><strong>${ssrInterpolate(__props.totalFormatted)}</strong></div>`);
				_push(ssrRenderComponent(unref(Link), {
					href: urls.value.checkout,
					class: "btn btn--block"
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`${ssrInterpolate(_ctx.$t("cart.checkout"))}`);
						else return [createTextVNode(toDisplayString(_ctx.$t("cart.checkout")), 1)];
					}),
					_: 1
				}, _parent));
				_push(ssrRenderComponent(unref(Link), {
					href: urls.value.catalogue,
					class: "link-quiet"
				}, {
					default: withCtx((_, _push, _parent, _scopeId) => {
						if (_push) _push(`${ssrInterpolate(_ctx.$t("cart.continue"))}`);
						else return [createTextVNode(toDisplayString(_ctx.$t("cart.continue")), 1)];
					}),
					_: 1
				}, _parent));
				_push(`</aside></div>`);
			}
			_push(`</div><!--]-->`);
		};
	}
};
var _sfc_setup$11 = _sfc_main$11.setup;
_sfc_main$11.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Cart.vue");
	return _sfc_setup$11 ? _sfc_setup$11(props, ctx) : void 0;
};
//#endregion
//#region resources/js/components/ProductCard.vue
var _sfc_main$10 = {
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
						else _push(`<!---->`);
						_push(`</div><div class="card__body"${_scopeId}><h3 class="card__title"${_scopeId}>${ssrInterpolate(__props.product.name)}</h3><p class="card__price"${_scopeId}>${ssrInterpolate(__props.product.priceFormatted)}</p></div>`);
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
					}, toDisplayString(_ctx.$t("product.sold_out")), 1)) : createCommentVNode("", true)]), createVNode("div", { class: "card__body" }, [createVNode("h3", { class: "card__title" }, toDisplayString(__props.product.name), 1), createVNode("p", { class: "card__price" }, toDisplayString(__props.product.priceFormatted), 1)])];
				}),
				_: 1
			}, _parent));
			_push(`</article>`);
		};
	}
};
var _sfc_setup$10 = _sfc_main$10.setup;
_sfc_main$10.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/ProductCard.vue");
	return _sfc_setup$10 ? _sfc_setup$10(props, ctx) : void 0;
};
//#endregion
//#region resources/js/Pages/Catalogue.vue
var Catalogue_exports = /* @__PURE__ */ __exportAll({ default: () => _sfc_main$9 });
var _sfc_main$9 = {
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
			type: String,
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
			_push(ssrRenderComponent(_sfc_main$12, {
				title: __props.title,
				description: __props.description
			}, null, _parent));
			_push(`<section class="${ssrRenderClass([{ "hero--split": __props.heroImage }, "hero"])}"><div class="wrap hero__inner"><div class="hero__text"><p class="eyebrow">Personna</p><h1 class="hero__title">${ssrInterpolate(_ctx.$t("tagline"))}</h1></div>`);
			if (__props.heroImage) _push(`<div class="hero__media"><img${ssrRenderAttr("src", __props.heroImage)} alt="Personna" fetchpriority="high" decoding="async"></div>`);
			else _push(`<!---->`);
			_push(`</div></section><section class="wrap catalogue">`);
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
					_push(ssrRenderComponent(_sfc_main$10, {
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
var _sfc_setup$9 = _sfc_main$9.setup;
_sfc_main$9.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Catalogue.vue");
	return _sfc_setup$9 ? _sfc_setup$9(props, ctx) : void 0;
};
//#endregion
//#region resources/js/components/OrderLine.vue
var _sfc_main$8 = {
	__name: "OrderLine",
	__ssrInlineRender: true,
	props: {
		name: {
			type: String,
			required: true
		},
		size: {
			type: String,
			default: null
		},
		qty: {
			type: Number,
			default: 1
		},
		amount: {
			type: String,
			default: ""
		}
	},
	setup(__props) {
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<div${ssrRenderAttrs(mergeProps({ class: "summary__line" }, _attrs))}><span class="summary__item">`);
			if (__props.qty > 1) _push(`<span class="summary__qty">${ssrInterpolate(__props.qty)} ×</span>`);
			else _push(`<!---->`);
			_push(`<span>${ssrInterpolate(__props.name)}</span>`);
			if (__props.size) _push(`<span class="size-badge">${ssrInterpolate(__props.size)}</span>`);
			else _push(`<!---->`);
			_push(`</span><span class="summary__amount">${ssrInterpolate(__props.amount)}</span></div>`);
		};
	}
};
var _sfc_setup$8 = _sfc_main$8.setup;
_sfc_main$8.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/OrderLine.vue");
	return _sfc_setup$8 ? _sfc_setup$8(props, ctx) : void 0;
};
//#endregion
//#region resources/js/Pages/Checkout.vue
var Checkout_exports = /* @__PURE__ */ __exportAll({ default: () => _sfc_main$7 });
var _sfc_main$7 = {
	__name: "Checkout",
	__ssrInlineRender: true,
	props: {
		rows: {
			type: Array,
			default: () => []
		},
		total: {
			type: Number,
			default: 0
		},
		totalFormatted: {
			type: String,
			default: ""
		},
		title: {
			type: String,
			default: ""
		}
	},
	setup(__props) {
		const urls = computed(() => usePage().props.urls);
		const form = useForm({
			customer_name: "",
			customer_phone: "",
			website: ""
		});
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(_sfc_main$12, {
				title: __props.title,
				noindex: ""
			}, null, _parent));
			_push(`<div class="wrap page"><h1 class="page__title">${ssrInterpolate(_ctx.$t("checkout.title"))}</h1><div class="checkout"><form class="checkout__form"><input${ssrRenderAttr("value", unref(form).website)} type="text" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">`);
			if (unref(form).errors.cart) _push(`<p class="error error--block">${ssrInterpolate(unref(form).errors.cart)}</p>`);
			else _push(`<!---->`);
			_push(`<div class="field"><label for="customer_name">${ssrInterpolate(_ctx.$t("checkout.name"))}</label><input id="customer_name"${ssrRenderAttr("value", unref(form).customer_name)} type="text" required maxlength="120" autocomplete="name"${ssrRenderAttr("placeholder", _ctx.$t("checkout.name_ph"))}>`);
			if (unref(form).errors.customer_name) _push(`<p class="error">${ssrInterpolate(unref(form).errors.customer_name)}</p>`);
			else _push(`<!---->`);
			_push(`</div><div class="field"><label for="customer_phone">${ssrInterpolate(_ctx.$t("checkout.phone"))}</label><input id="customer_phone"${ssrRenderAttr("value", unref(form).customer_phone)} type="tel" required maxlength="40" autocomplete="tel"${ssrRenderAttr("placeholder", _ctx.$t("checkout.phone_ph"))}>`);
			if (unref(form).errors.customer_phone) _push(`<p class="error">${ssrInterpolate(unref(form).errors.customer_phone)}</p>`);
			else _push(`<!---->`);
			_push(`</div><button type="submit" class="btn btn--block"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""}>${ssrInterpolate(_ctx.$t("checkout.place_order"))}</button>`);
			_push(ssrRenderComponent(unref(Link), {
				href: urls.value.cart,
				class: "link-quiet"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`${ssrInterpolate(_ctx.$t("checkout.back"))}`);
					else return [createTextVNode(toDisplayString(_ctx.$t("checkout.back")), 1)];
				}),
				_: 1
			}, _parent));
			_push(`</form><aside class="checkout__summary"><h2 class="eyebrow">${ssrInterpolate(_ctx.$t("checkout.summary"))}</h2><!--[-->`);
			ssrRenderList(__props.rows, (row) => {
				_push(ssrRenderComponent(_sfc_main$8, {
					key: row.lineKey,
					name: row.name,
					size: row.size,
					qty: row.qty,
					amount: row.lineFormatted
				}, null, _parent));
			});
			_push(`<!--]--><div class="summary__row summary__row--total"><span>${ssrInterpolate(_ctx.$t("cart.subtotal"))}</span><strong>${ssrInterpolate(__props.totalFormatted)}</strong></div></aside></div></div><!--]-->`);
		};
	}
};
var _sfc_setup$7 = _sfc_main$7.setup;
_sfc_main$7.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Checkout.vue");
	return _sfc_setup$7 ? _sfc_setup$7(props, ctx) : void 0;
};
//#endregion
//#region resources/js/components/JsonLd.vue
var _sfc_main$6 = {
	name: "JsonLd",
	props: { data: {
		type: Array,
		default: () => []
	} },
	render() {
		return this.data.map((item, index) => h("script", {
			key: index,
			type: "application/ld+json",
			innerHTML: JSON.stringify(item).replace(/</g, "\\u003c")
		}));
	}
};
var _sfc_setup$6 = _sfc_main$6.setup;
_sfc_main$6.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/JsonLd.vue");
	return _sfc_setup$6 ? _sfc_setup$6(props, ctx) : void 0;
};
//#endregion
//#region resources/js/components/Gallery.vue
var _sfc_main$5 = {
	__name: "Gallery",
	__ssrInlineRender: true,
	props: {
		images: {
			type: Array,
			default: () => []
		},
		alt: {
			type: String,
			default: ""
		}
	},
	setup(__props) {
		const props = __props;
		const active = ref(0);
		const track = ref(null);
		onMounted(() => {
			if (!track.value || props.images.length < 2) return;
			const slides = Array.from(track.value.children);
			const observer = new IntersectionObserver((entries) => entries.forEach((entry) => {
				if (entry.isIntersecting) active.value = slides.indexOf(entry.target);
			}), {
				root: track.value,
				threshold: .6
			});
			slides.forEach((slide) => observer.observe(slide));
		});
		return (_ctx, _push, _parent, _attrs) => {
			if (__props.images.length) {
				_push(`<div${ssrRenderAttrs(mergeProps({ class: "product__gallery" }, _attrs))}><div class="gallery__track"><!--[-->`);
				ssrRenderList(__props.images, (img, i) => {
					_push(`<div class="gallery__slide"><img${ssrRenderAttr("src", img.full)}${ssrRenderAttr("srcset", `${img.card} 800w, ${img.full} 1600w`)} sizes="(max-width: 860px) 100vw, 620px" width="800" height="1000"${ssrRenderAttr("alt", __props.alt)}${ssrRenderAttr("fetchpriority", i === 0 ? "high" : null)}${ssrRenderAttr("loading", i === 0 ? "eager" : "lazy")} decoding="async"></div>`);
				});
				_push(`<!--]--></div>`);
				if (__props.images.length > 1) {
					_push(`<div class="gallery__dots" aria-hidden="true"><!--[-->`);
					ssrRenderList(__props.images, (img, i) => {
						_push(`<button type="button" class="${ssrRenderClass([{ "is-active": active.value === i }, "gallery__dot"])}"></button>`);
					});
					_push(`<!--]--></div>`);
				} else _push(`<!---->`);
				if (__props.images.length > 1) {
					_push(`<div class="product__thumbs"><!--[-->`);
					ssrRenderList(__props.images, (img, i) => {
						_push(`<img${ssrRenderAttr("src", img.thumb)} width="80" height="100" loading="lazy"${ssrRenderAttr("alt", __props.alt)} class="${ssrRenderClass([{ "is-active": active.value === i }, "product__thumb"])}">`);
					});
					_push(`<!--]--></div>`);
				} else _push(`<!---->`);
				_push(`</div>`);
			} else _push(`<div${ssrRenderAttrs(mergeProps({ class: "product-img product-img--ph" }, _attrs))}><span>Personna</span></div>`);
		};
	}
};
var _sfc_setup$5 = _sfc_main$5.setup;
_sfc_main$5.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/Gallery.vue");
	return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
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
//#region resources/js/lib/toasts.js
var toastState = reactive({
	items: [],
	seq: 0
});
//#endregion
//#region resources/js/Pages/Product.vue
var Product_exports = /* @__PURE__ */ __exportAll({ default: () => _sfc_main$4 });
var _sfc_main$4 = {
	__name: "Product",
	__ssrInlineRender: true,
	props: {
		product: {
			type: Object,
			required: true
		},
		category: {
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
		},
		ogImage: {
			type: String,
			default: null
		},
		jsonld: {
			type: Array,
			default: () => []
		}
	},
	setup(__props) {
		const props = __props;
		const page = usePage();
		const catalogueUrl = computed(() => page.props.urls.catalogue);
		computed(() => page.props.urls.cartAdd);
		const cooling = ref(false);
		const form = useForm({
			product_id: props.product.id,
			size: props.product.sizes?.[0] ?? null,
			qty: 1,
			website: ""
		});
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(_sfc_main$12, {
				title: __props.title,
				description: __props.description,
				"og-image": __props.ogImage
			}, null, _parent));
			_push(ssrRenderComponent(_sfc_main$6, { data: __props.jsonld }, null, _parent));
			_push(`<div class="wrap product"><nav class="crumbs" aria-label="Breadcrumb">`);
			_push(ssrRenderComponent(unref(Link), { href: catalogueUrl.value }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`${ssrInterpolate(_ctx.$t("nav.shop"))}`);
					else return [createTextVNode(toDisplayString(_ctx.$t("nav.shop")), 1)];
				}),
				_: 1
			}, _parent));
			_push(`<span aria-hidden="true">/</span><span>${ssrInterpolate(__props.product.name)}</span></nav><div class="product__grid">`);
			_push(ssrRenderComponent(_sfc_main$5, {
				images: __props.product.gallery,
				alt: __props.product.name
			}, null, _parent));
			_push(`<div class="product__info"><h1 class="product__title">${ssrInterpolate(__props.product.name)}</h1><p class="product__price">${ssrInterpolate(__props.product.priceFormatted)}</p>`);
			if (__props.product.description) _push(`<div class="product__desc" style="${ssrRenderStyle({ "white-space": "pre-line" })}">${ssrInterpolate(__props.product.description)}</div>`);
			else _push(`<!---->`);
			if (__props.product.soldOut) _push(`<p class="soldout">${ssrInterpolate(_ctx.$t("product.sold_out"))}</p>`);
			else {
				_push(`<form class="add-form"><input${ssrRenderAttr("value", unref(form).website)} type="text" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">`);
				if (__props.product.sizes.length) {
					_push(`<fieldset class="sizes"><legend>${ssrInterpolate(_ctx.$t("product.size"))}</legend><div class="sizes__options"><!--[-->`);
					ssrRenderList(__props.product.sizes, (size) => {
						_push(`<label class="size"><input${ssrIncludeBooleanAttr(ssrLooseEqual(unref(form).size, size)) ? " checked" : ""} type="radio"${ssrRenderAttr("value", size)} required><span>${ssrInterpolate(size)}</span></label>`);
					});
					_push(`<!--]--></div>`);
					if (unref(form).errors.size) _push(`<p class="error">${ssrInterpolate(unref(form).errors.size)}</p>`);
					else _push(`<!---->`);
					_push(`</fieldset>`);
				} else _push(`<!---->`);
				_push(`<div class="qty"><label for="qty">${ssrInterpolate(_ctx.$t("product.quantity"))}</label><input id="qty"${ssrRenderAttr("value", unref(form).qty)} type="number" min="1" max="99" inputmode="numeric"></div><button type="submit" class="btn btn--block"${ssrIncludeBooleanAttr(cooling.value || unref(form).processing) ? " disabled" : ""}>${ssrInterpolate(_ctx.$t("product.add_to_cart"))}</button></form>`);
			}
			_push(`</div></div></div><!--]-->`);
		};
	}
};
var _sfc_setup$4 = _sfc_main$4.setup;
_sfc_main$4.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Product.vue");
	return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
//#endregion
//#region resources/js/Pages/Success.vue
var Success_exports = /* @__PURE__ */ __exportAll({ default: () => _sfc_main$3 });
var _sfc_main$3 = {
	__name: "Success",
	__ssrInlineRender: true,
	props: {
		order: {
			type: Object,
			required: true
		},
		title: {
			type: String,
			default: ""
		}
	},
	setup(__props) {
		const catalogueUrl = computed(() => usePage().props.urls.catalogue);
		return (_ctx, _push, _parent, _attrs) => {
			_push(`<!--[-->`);
			_push(ssrRenderComponent(_sfc_main$12, {
				title: __props.title,
				noindex: ""
			}, null, _parent));
			_push(`<div class="wrap page success-page"><div class="success-card"><h1 class="page__title">${ssrInterpolate(_ctx.$t("success.title"))}</h1><p>${ssrInterpolate(_ctx.$t("success.thanks"))}</p><p class="success-ref">${ssrInterpolate(_ctx.$t("success.reference"))}: <strong>${ssrInterpolate(__props.order.reference)}</strong></p><p class="muted">${ssrInterpolate(_ctx.$t("success.contact_soon"))}</p><div class="success-items"><!--[-->`);
			ssrRenderList(__props.order.items, (item, i) => {
				_push(ssrRenderComponent(_sfc_main$8, {
					key: i,
					name: item.name,
					size: item.size,
					qty: item.qty,
					amount: item.lineFormatted
				}, null, _parent));
			});
			_push(`<!--]--><div class="summary__row summary__row--total"><span>${ssrInterpolate(_ctx.$t("cart.subtotal"))}</span><strong>${ssrInterpolate(__props.order.totalFormatted)}</strong></div></div>`);
			_push(ssrRenderComponent(unref(Link), {
				href: catalogueUrl.value,
				class: "btn"
			}, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`${ssrInterpolate(_ctx.$t("success.back"))}`);
					else return [createTextVNode(toDisplayString(_ctx.$t("success.back")), 1)];
				}),
				_: 1
			}, _parent));
			_push(`</div></div><!--]-->`);
		};
	}
};
var _sfc_setup$3 = _sfc_main$3.setup;
_sfc_main$3.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Success.vue");
	return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
//#endregion
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
	"../Pages/Cart.vue": Cart_exports,
	"../Pages/Catalogue.vue": Catalogue_exports,
	"../Pages/Checkout.vue": Checkout_exports,
	"../Pages/Product.vue": Product_exports,
	"../Pages/Success.vue": Success_exports
});
function resolvePage(name) {
	const page = pages[`../Pages/${name}.vue`];
	if (!page) throw new Error(`Inertia page not found: ${name}`);
	page.default.layout = page.default.layout ?? _sfc_main;
	return page;
}
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
