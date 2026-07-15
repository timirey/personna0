import { t as _sfc_main$1 } from "./SeoHead-B64EwHoA.js";
import { Link, usePage } from "@inertiajs/vue3";
import { computed, createBlock, createTextVNode, createVNode, openBlock, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { ssrInterpolate, ssrRenderAttr, ssrRenderClass, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Pages/Cart.vue
var _sfc_main = {
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
			_push(ssrRenderComponent(_sfc_main$1, {
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
					_push(`<span class="cart-row__meta"><span class="${ssrRenderClass({ "price--sale": row.onSale })}">${ssrInterpolate(row.unitFormatted)}</span>`);
					if (row.onSale) _push(`<span class="price--old">${ssrInterpolate(row.originalUnitFormatted)}</span>`);
					else _push(`<!---->`);
					_push(`</span></div><div class="cart-row__qty"><input type="number"${ssrRenderAttr("value", row.qty)} min="0" max="99"${ssrRenderAttr("aria-label", _ctx.$t("product.quantity"))}></div><span class="cart-row__total">${ssrInterpolate(row.lineFormatted)}</span><button class="cart-row__remove" type="button"${ssrRenderAttr("aria-label", _ctx.$t("cart.remove"))}>×</button></div>`);
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
var _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Cart.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export { _sfc_main as default };
