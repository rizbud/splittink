<template>
    <main class="flex flex-col gap-4 justify-center items-center min-h-screen">
        <h1>Counter: {{ counter }}</h1>
        <button class="p-2 bg-green-500 text-white rounded" @click="counter++">
            Increment
        </button>
        <button class="p-2 bg-red-500 text-white rounded" @click="counter = 0">
            Reset
        </button>
    </main>
</template>

<script setup>
import { ref } from "vue";
const counter = ref(0);
</script>
