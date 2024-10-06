<script setup>
import { Header, Footer } from "../Components";
</script>

<template>
    <div class="flex flex-col min-h-screen">
        <Header />

        <main class="flex flex-col gap-4 px-6 py-4 grow">
            <slot />
        </main>

        <Footer />
    </div>
</template>
