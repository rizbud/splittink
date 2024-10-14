<script setup>
import { reactive, ref, computed, defineProps } from "vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";

import { Input, PrimaryButton } from ".";
import { toast } from "../utils";

const props = defineProps({
    group: Object,
    currencies: Array,
});

const participant = ref("");
const isLoading = ref(false);

const form = reactive({
    name: props.group?.name || "",
    description: props.group?.description || "",
    participants: props.group?.participants || [],
    currency_id: props.group?.currency_id || 1,
});

const isEditing = !!props.group;

const isDisabled = computed(() => {
    return !form.name || form.participants.length < 2 || isLoading.value;
});

const addParticipant = () => {
    form.participants.push({ name: participant.value });
    participant.value = "";
};

const removeParticipant = (index) => {
    const confirmRemove = isEditing
        ? confirm(
              `Are you sure you want to remove ${form.participants[index].name}?`
          )
        : true;
    confirmRemove && form.participants.splice(index, 1);
};

const submitForm = async () => {
    try {
        isLoading.value = true;
        const response = isEditing
            ? await axios.put(`/groups/${group.id}`, form)
            : await axios.post("/groups", form);

        if (response.status === 200) {
            toast.success(
                `Group ${isEditing ? "updated" : "created"} successfully.`
            );
            router.get(`/groups/${response.data.slug}`);
        } else {
            throw new Error();
        }
    } catch (error) {
        toast.error(`Failed to ${isEditing ? "update" : "create"} the group.`);
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <form @submit.prevent="submitForm" class="flex flex-col gap-4">
        <Input
            id="name"
            v-model="form.name"
            label="Name"
            placeholder="Enter the name of the group"
            required
        />

        <Input
            id="description"
            kind="textarea"
            v-model="form.description"
            label="Description"
            placeholder="Give a brief description of the group."
        />

        <div class="flex flex-col gap-1">
            <label for="participants" class="block text-sm">
                Participants
            </label>

            <ul
                v-if="form.participants.length"
                class="mt-2 flex flex-col gap-2"
            >
                <li
                    v-for="(participant, index) in form.participants"
                    :key="participant.id || index"
                    class="flex"
                >
                    <input type="hidden" :value="participant.id" />
                    <input
                        type="text"
                        v-model="form.participants[index].name"
                        class="w-full border-l border-t border-b border-gray-300 rounded-l p-2 focus:outline-none focus:ring-0"
                    />
                    <button
                        type="button"
                        class="bg-red-500 text-white rounded-r p-2 w-32 focus:outline-none focus:ring-0"
                        @click="removeParticipant(index)"
                    >
                        Remove
                    </button>
                </li>
            </ul>
            <form @submit.prevent="addParticipant" class="flex">
                <input
                    type="text"
                    v-model="participant"
                    class="w-full border-l border-t border-b border-gray-300 rounded-l p-2 focus:outline-none focus:ring-0"
                    placeholder="John Doe"
                />

                <button
                    type="submit"
                    class="bg-emerald-500 text-white rounded-r p-2 w-32 focus:outline-none focus:ring-0"
                    :class="
                        participant
                            ? 'hover:bg-emerald-600 cursor-pointer'
                            : 'cursor-default disabled:opacity-50'
                    "
                    :disabled="!participant"
                >
                    Add
                </button>
            </form>
        </div>

        <div class="flex flex-col gap-1">
            <label for="currency" class="block text-sm"> Base Currency </label>

            <select
                id="currency"
                v-model="form.currency_id"
                class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-0"
            >
                <option
                    v-for="currency in currencies"
                    :key="currency.id"
                    :value="currency.id"
                    :selected="currency.id === form.currency_id"
                >
                    {{ currency.name }} ({{ currency.code }} -
                    {{ currency.symbol }})
                </option>
            </select>
        </div>

        <PrimaryButton
            type="submit"
            text="Save Changes"
            :disabled="isDisabled"
            :is-loading="isLoading"
        />
    </form>
</template>
