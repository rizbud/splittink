<template>
    <button @click="counter++">Clicked {{ counter }} times</button>
</template>

<script setup>
import { ref } from "vue";
const counter = ref(0);
</script>
