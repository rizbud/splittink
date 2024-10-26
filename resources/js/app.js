import "./bootstrap";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";

import VueGtag from "vue-gtag";
import Vue3Toastify from "vue3-toastify";

import MainLayout from "./Layouts/MainLayout.vue";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        let page = pages[`./Pages/${name}.vue`];
        page.default.layout = page.default.layout || MainLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(VueGtag, {
                config: {
                    id: import.meta.env.VITE_GA_MEASUREMENT_ID,
                },
            })
            .use(Vue3Toastify, {
                position: "top-right",
                duration: 3000,
                className: "toastify",
                canPause: false,
                canReplay: false,
                closeOnClick: true,
                closeOnEscape: true,
                pauseOnFocusLoss: true,
                pauseOnHover: false,
                maxToasts: 5,
                newestOnTop: true,
            })
            .mount(el);
    },
});
