<script setup>
import { Link } from "@inertiajs/vue3";

const appName = import.meta.env.VITE_APP_NAME;
</script>

<template>
    <header class="bg-emerald-600 text-white text-center p-4 sticky top-0 z-10">
        <Link href="/" class="text-2xl font-semibold italic">
            {{ appName }}
        </Link>
    </header>
</template>
