<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    label: String,
    modelValue: String | Number,
    required: Boolean,
    value: String | Number,
    kind: {
        type: String,
        default: "input",
        validator: (value) => ["input", "textarea"].includes(value),
    },
});

const inputClasses =
    "w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-0 disabled:bg-slate-200 disabled:text-slate-600";

const emits = defineEmits(["update:modelValue"]);

const updateValue = (event) => {
    emits("update:modelValue", event.target.value);
};
</script>

<template>
    <div class="flex flex-col gap-1">
        <label
            :for="$attrs.id"
            class="inline-block text-sm text-slate-600"
            v-if="props.label"
        >
            {{ props.label
            }}<span v-if="props.required" class="text-red-500">*</span>
        </label>
        <textarea
            v-bind="$attrs"
            v-if="props.kind === 'textarea'"
            :value="props.value || props.modelValue"
            @input="updateValue"
            :required="props.required"
            :class="inputClasses"
        />
        <input
            v-bind="$attrs"
            v-else
            :value="props.value || props.modelValue"
            @input="updateValue"
            :required="props.required"
            :class="inputClasses"
        />

        <!-- <span class="text-xs text-slate-400 self-end" v-if="$attrs.maxlength">
            {{ props.modelValue.length }}/{{ $attrs.maxlength }}
        </span> -->
    </div>
</template>
