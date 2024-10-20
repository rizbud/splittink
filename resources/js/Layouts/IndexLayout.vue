<script setup>
import { Head } from "@inertiajs/vue3";
import "vue3-toastify/dist/index.css";
import { Header, Footer } from "../Components";

const appName = import.meta.env.VITE_APP_NAME;
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
    </Head>

    <div class="flex flex-col min-h-screen">
        <Header />

        <main class="flex flex-col grow text-slate-900">
            <slot />
        </main>

        <Footer />
    </div>
</template>
