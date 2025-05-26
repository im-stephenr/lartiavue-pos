import "./bootstrap";
import "../css/app.css";
import "vue-toast-notification/dist/theme-sugar.css";
import "vue3-easy-data-table/dist/style.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
// import Layout from "./Layouts/Layout.vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy"; // installed using: composer require tightenco/ziggy
import ToastPlugin from "vue-toast-notification";

createInertiaApp({
    title: (title) => `Lartiavue POS ${title}`, // adding dynamic title to all pages
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        /**
         * adding this code so it will apply the Layout component as parent component to all Pages instead of adding defineOptions({layout:Layout}) to each pages
         * we will no longer need to import the Layout to each pages
         * */
        let page = pages[`./Pages/${name}.vue`];
        // page.default.layout = page.default.layout || DefaultLayout; // should add checking if user is logged in then use DefaultLayout else use GuestLayout
        // console.log("page ", page.props);

        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(ToastPlugin)
            .mount(el);
    },
    // This shows loading at the top of browser
    progress: {
        color: "red",
        includeCSS: true,
        showSpinner: true,
    },
});
