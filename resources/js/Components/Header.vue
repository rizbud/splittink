<script setup>
import { Link } from "@inertiajs/vue3";

const appName = import.meta.env.VITE_APP_NAME;
</script>

<template>
    <header
        class="bg-emerald-600 text-white text-center px-6 py-3 sticky top-0 z-10 flex justify-between items-center"
    >
        <Link href="/" class="text-2xl font-bold italic hover:opacity-80">
            {{ appName }}
        </Link>

        <Link href="/new" class="text-sm">
            <button
                class="bg-white text-emerald-600 px-3 py-1.5 rounded focus:outline-none focus:ring-0"
            >
                Create
            </button>
        </Link>
    </header>
</template>
