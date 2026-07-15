import { mergeProps, useSSRContext } from "vue";
import { ssrInterpolate, ssrRenderAttrs } from "vue/server-renderer";
//#region resources/js/components/OrderLine.vue
var _sfc_main = {
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
		},
		onSale: {
			type: Boolean,
			default: false
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
			if (__props.onSale) _push(`<span class="size-badge size-badge--sale">%</span>`);
			else _push(`<!---->`);
			_push(`</span><span class="summary__amount">${ssrInterpolate(__props.amount)}</span></div>`);
		};
	}
};
var _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
	const ssrContext = useSSRContext();
	(ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/OrderLine.vue");
	return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export { _sfc_main as t };
