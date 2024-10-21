<script setup>
import { Link } from "@inertiajs/vue3";

const appName = import.meta.env.VITE_APP_NAME;
const year = new Date().getFullYear();
</script>

<template>
    <footer class="bg-slate-900 text-white p-4 text-sm text-center">
        <div class="flex gap-1 mb-4 justify-center flex-wrap">
            <Link href="/about-us" class="text-emerald-500 hover:underline"
                >About Us</Link
            >
            |
            <Link
                href="/privacy-policy"
                class="text-emerald-500 hover:underline"
                >Privacy Policy</Link
            >
            |
            <Link
                href="/terms-of-service"
                class="text-emerald-500 hover:underline"
                >Terms of Service</Link
            >
            |
            <a
                href="https://forms.gle/Xm4mRs6w1JmF77mS6"
                target="_blank"
                rel="noopener noreferrer nofollow"
                class="text-emerald-500 hover:underline"
                >Contact Us</a
            >
        </div>

        <p>
            Copyright &copy; {{ year }}
            <Link href="/" class="text-emerald-500 hover:underline">
                {{ appName }}</Link
            >. All rights reserved.
        </p>
    </footer>
</template>
