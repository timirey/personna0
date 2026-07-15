<script>
import { h } from 'vue';

// Render function (not template): Vue's template compiler strips <script> tags,
// so we build script vnodes directly with innerHTML — SSR renders these into the
// served HTML for crawlers. "<" is escaped so product text can't break out.
export default {
    name: 'JsonLd',
    props: {
        data: { type: Array, default: () => [] },
    },
    render() {
        return this.data.map((item, index) =>
            h('script', {
                key: index,
                type: 'application/ld+json',
                innerHTML: JSON.stringify(item).replace(/</g, '\\u003c'),
            }),
        );
    },
};
</script>
