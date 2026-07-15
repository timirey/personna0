import { t as _sfc_main$1 } from "./SeoHead-B64EwHoA.js";
import { t as _sfc_main$2 } from "./OrderLine-Bl60mYck.js";
import { Link, usePage } from "@inertiajs/vue3";
import { computed, createTextVNode, toDisplayString, unref, useSSRContext, withCtx } from "vue";
import { ssrInterpolate, ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
//#region resources/js/Pages/Success.vue
var _sfc_main = {
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
			_push(ssrRenderComponent(_sfc_main$1, {
				title: __props.title,
				noindex: ""
			}, null, _parent));
			_push(`<div class="wrap page success-page"><div class="success-card"><h1 class="page__title">${ssrInterpolate(_ctx.$t("success.title"))}</h1><p>${ssrInterpolate(_ctx.$t("success.thanks"))}</p><p class="success-ref">${ssrInterpolate(_ctx.$t("success.reference"))}: <strong>${ssrInterpolate(__props.order.reference)}</strong></p><p class="muted">${ssrInterpolate(_ctx.$t("success.contact_soon"))}</p><div class="success-items"><!--[-->`);
			ssrRenderList(__props.order.items, (item, i) => {
				_push(ssrRenderComponent(_sfc_main$2, {
					key: i,
					name: item.name,
					size: item.size,
					qty: item.qty,
					amount: item.lineFormatted,
					"on-sale": item.onSale
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
var _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Success.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export { _sfc_main as default };
