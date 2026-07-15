import "../ssr.js";
import { t as _sfc_main$3 } from "./SeoHead-B64EwHoA.js";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { computed, createTextVNode, h, mergeProps, onMounted, ref, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrLooseEqual, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass, ssrRenderComponent, ssrRenderList, ssrRenderStyle } from "vue/server-renderer";
//#region resources/js/components/JsonLd.vue
var _sfc_main$2 = {
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
var _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/JsonLd.vue");
	return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
//#endregion
//#region resources/js/components/Gallery.vue
var _sfc_main$1 = {
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
					_push(`<div class="gallery__slide"><img${ssrRenderAttr("src", img.large)}${ssrRenderAttr("srcset", `${img.card} 800w, ${img.large} 1080w, ${img.full} 1600w`)} sizes="(max-width: 860px) 100vw, 620px" width="800" height="1000"${ssrRenderAttr("alt", __props.alt)}${ssrRenderAttr("fetchpriority", i === 0 ? "high" : null)}${ssrRenderAttr("loading", i === 0 ? "eager" : "lazy")} decoding="async"></div>`);
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
var _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/Gallery.vue");
	return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
//#endregion
//#region resources/js/Pages/Product.vue
var _sfc_main = {
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
			_push(ssrRenderComponent(_sfc_main$3, {
				title: __props.title,
				description: __props.description,
				"og-image": __props.ogImage,
				"preload-image": __props.product.gallery?.[0]?.large,
				"preload-srcset": __props.product.gallery?.[0] ? `${__props.product.gallery[0].card} 800w, ${__props.product.gallery[0].large} 1080w, ${__props.product.gallery[0].full} 1600w` : null,
				"preload-sizes": "(max-width: 860px) 100vw, 620px"
			}, null, _parent));
			_push(ssrRenderComponent(_sfc_main$2, { data: __props.jsonld }, null, _parent));
			_push(`<div class="wrap product"><nav class="crumbs" aria-label="Breadcrumb">`);
			_push(ssrRenderComponent(unref(Link), { href: catalogueUrl.value }, {
				default: withCtx((_, _push, _parent, _scopeId) => {
					if (_push) _push(`${ssrInterpolate(_ctx.$t("nav.shop"))}`);
					else return [createTextVNode(toDisplayString(_ctx.$t("nav.shop")), 1)];
				}),
				_: 1
			}, _parent));
			_push(`<span aria-hidden="true">/</span><span>${ssrInterpolate(__props.product.name)}</span></nav><div class="product__grid">`);
			_push(ssrRenderComponent(_sfc_main$1, {
				images: __props.product.gallery,
				alt: __props.product.name
			}, null, _parent));
			_push(`<div class="product__info"><h1 class="product__title">${ssrInterpolate(__props.product.name)}</h1><p class="product__price"><span class="${ssrRenderClass({ "price--sale": __props.product.onSale })}">${ssrInterpolate(__props.product.priceFormatted)}</span>`);
			if (__props.product.onSale) _push(`<span class="price--old">${ssrInterpolate(__props.product.originalPriceFormatted)}</span>`);
			else _push(`<!---->`);
			if (__props.product.onSale) _push(`<span class="sale-tag">−${ssrInterpolate(__props.product.discountPercent)}%</span>`);
			else _push(`<!---->`);
			_push(`</p>`);
			if (__props.product.description) _push(`<div class="product__desc" style="${ssrRenderStyle({ "white-space": "pre-line" })}">${ssrInterpolate(__props.product.description)}</div>`);
			else _push(`<!---->`);
			if (__props.product.soldOut) _push(`<p class="soldout">${ssrInterpolate(_ctx.$t("product.sold_out"))}</p>`);
			else {
				_push(`<form class="add-form"><input${ssrRenderAttr("value", unref(form).website)} type="text" class="hp" tabindex="-1" autocomplete="off" aria-label="Leave empty">`);
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
var _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Product.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export { _sfc_main as default };
