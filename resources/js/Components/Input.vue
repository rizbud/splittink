<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    label: String,
    modelValue: String,
    required: Boolean,
    kind: {
        type: String,
        default: "input",
        validator: (value) => ["input", "textarea"].includes(value),
    },
});

const emits = defineEmits(["update:modelValue"]);

const updateValue = (event) => {
    emits("update:modelValue", event.target.value);
};
</script>

<template>
    <div class="flex flex-col gap-1">
        <label for="name" class="block text-sm">
            {{ props.label }}
            <span v-if="props.required" class="text-red-500">*</span>
        </label>
        <textarea
            v-bind="$attrs"
            v-if="props.kind === 'textarea'"
            :value="props.modelValue"
            @input="updateValue"
            class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-0"
        />
        <input
            v-bind="$attrs"
            v-else
            :value="props.modelValue"
            @input="updateValue"
            class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-0"
        />

        <span class="text-xs text-gray-500 self-end">
            {{ props.modelValue.length }}/{{ $attrs.maxlength }}
        </span>
    </div>
</template>
