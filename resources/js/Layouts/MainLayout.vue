<script setup>
import { Head } from "@inertiajs/vue3";
import "vue3-toastify/dist/index.css";
import { Header, Footer } from "../Components";

const appName = import.meta.env.VITE_APP_NAME;
const appUrl = import.meta.env.VITE_APP_URL;
</script>

<template>
    <Head>
        <title>
            {{ appName }} | Easiest way to split bills among the group
        </title>

        <meta
            name="description"
            content="Easiest way to split bills among the group"
        />

        <meta
            property="keywords"
            content="split, bill, group, share, expense, money, easy, fast, free, fair, simple, simplify"
        />

        <meta property="og:site_name" :content="appName" />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="appUrl + $page.url" />
        <meta property="og:image" :content="appUrl + '/splitt.ink.webp'" />
    </Head>

    <div class="flex flex-col min-h-screen">
        <Header />

        <main class="flex flex-col gap-4 px-6 pt-4 pb-10 grow text-slate-900">
            <slot />
        </main>

        <Footer />
    </div>
</template>
