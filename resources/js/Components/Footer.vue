<script setup>
import { Link } from "@inertiajs/vue3";

const appName = import.meta.env.VITE_APP_NAME;
const year = new Date().getFullYear();
</script>

<template>
    <footer class="bg-slate-900 text-white text-center p-4">
        <p>
            Copyright &copy; {{ year }}
            <Link href="/" class="text-emerald-500 hover:underline"
                >{{ appName }} </Link
            >. All rights reserved.
        </p>
    </footer>
</template>
