import { t as _sfc_main$1 } from "./SeoHead-B64EwHoA.js";
import { t as _sfc_main$2 } from "./OrderLine-Bl60mYck.js";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { computed, createTextVNode, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { ssrIncludeBooleanAttr, ssrInterpolate, ssrRenderAttr, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Pages/Checkout.vue
var _sfc_main = {
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
			_push(ssrRenderComponent(_sfc_main$1, {
				title: __props.title,
				noindex: ""
			}, null, _parent));
			_push(`<div class="wrap page"><h1 class="page__title">${ssrInterpolate(_ctx.$t("checkout.title"))}</h1><div class="checkout"><form class="checkout__form"><input${ssrRenderAttr("value", unref(form).website)} type="text" class="hp" tabindex="-1" autocomplete="off" aria-label="Leave empty">`);
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
				_push(ssrRenderComponent(_sfc_main$2, {
					key: row.lineKey,
					name: row.name,
					size: row.size,
					qty: row.qty,
					amount: row.lineFormatted,
					"on-sale": row.onSale
				}, null, _parent));
			});
			_push(`<!--]--><div class="summary__row summary__row--total"><span>${ssrInterpolate(_ctx.$t("cart.subtotal"))}</span><strong>${ssrInterpolate(__props.totalFormatted)}</strong></div></aside></div></div><!--]-->`);
		};
	}
};
var _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Checkout.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export { _sfc_main as default };
