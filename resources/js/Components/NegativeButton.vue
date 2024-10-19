<script setup>
import { defineProps } from "vue";

const props = defineProps({
    text: String,
    disabled: Boolean,
    isLoading: Boolean,
});
</script>

<template>
    <button
        v-bind="$attrs"
        class="rounded p-4 mt-2 focus:outline-none focus:ring-0 flex items-center justify-center"
        :class="
            props.disabled || props.isLoading
                ? 'bg-gray-300 text-slate-600 cursor-default'
                : 'bg-red-500 text-white'
        "
        :disabled="props.disabled || props.isLoading"
    >
        <div v-if="props.isLoading" class="animate-spin">
            <i class="fas fa-spinner fa-spin"></i>
        </div>
        <span v-else>{{ props.text }}</span>
    </button>
</template>
