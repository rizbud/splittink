<script setup>
import { reactive, ref, computed, defineProps } from "vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";

import { Input, NegativeButton, PrimaryButton } from ".";
import { toast } from "../utils";

const props = defineProps({
    group: Object,
    currencies: Array,
    settlements: Array,
});

const participant = ref("");
const isLoading = ref(false);
const removeParticipantIndex = ref(null);

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

const handleRemoveParticipant = () => {
    const participant = form.participants[removeParticipantIndex.value];
    // filter settlements where the participant is involved
    const settlementsWithParticipant = props.settlements.filter(
        (settlement) => {
            return (
                settlement.from.id === participant.id ||
                settlement.to.id === participant.id
            );
        }
    );

    if (settlementsWithParticipant.length) {
        removeParticipantIndex.value = null;
        toast.error(
            "Cannot delete participant because still has outstanding settlements."
        );
        return;
    }

    form.participants.splice(removeParticipantIndex, 1);
    removeParticipantIndex.value = null;
};

const submitForm = async () => {
    try {
        isLoading.value = true;
        const response = isEditing
            ? await axios.put(`/groups/${props.group.id}`, form)
            : await axios.post("/groups", form);

        if (response.status === 200 || response.status === 201) {
            router.get(`/groups/${response.data.slug}`);
            toast.success(
                `Group ${isEditing ? "updated" : "created"} successfully.`
            );
        } else {
            throw new Error();
        }
    } catch (error) {
        const errorMessage =
            error.response?.data?.message ||
            error.message ||
            `Failed to ${isEditing ? "update" : "create"} group.`;
        toast.error(errorMessage);
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <div>
        <form @submit.prevent="submitForm" class="flex flex-col gap-4">
            <Input
                id="name"
                v-model="form.name"
                label="Name"
                placeholder="Enter the name of the group"
                required
                maxlength="100"
            />

            <Input
                id="description"
                kind="textarea"
                v-model="form.description"
                label="Description"
                placeholder="Give a brief description of the group."
                maxlength="255"
            />

            <div class="flex flex-col">
                <label
                    for="participants"
                    class="inline-block text-sm text-slate-600"
                >
                    Participants
                </label>

                <ul
                    v-if="form.participants.length"
                    class="mt-1 mb-2 flex flex-col gap-2"
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
                            :placeholder="`Participant ${index + 1}`"
                            maxlength="100"
                        />
                        <button
                            type="button"
                            class="bg-red-500 text-white rounded-r p-2 w-32 focus:outline-none focus:ring-0"
                            @click="removeParticipantIndex = index"
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
                <label for="currency" class="inline-block text-sm">
                    Base Currency
                </label>

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
                :text="isEditing ? 'Save Changes' : 'Create Group'"
                :disabled="isDisabled"
                :is-loading="isLoading"
            />
        </form>

        <div
            v-if="removeParticipantIndex !== null"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div class="bg-white p-4 rounded shadow-lg">
                <h3 class="font-medium mb-4">
                    Are you sure you want to delete this participant?
                </h3>

                <div class="flex justify-between gap-4">
                    <button
                        type="button"
                        class="w-full text-slate-600 px-4 py-2 focus:outline-none focus:ring-0 hover:underline"
                        @click="removeParticipantIndex = null"
                    >
                        Cancel
                    </button>
                    <NegativeButton
                        type="button"
                        text="Delete"
                        class="w-full hover:opacity-80"
                        @click="
                            () =>
                                handleRemoveParticipant(removeParticipantIndex)
                        "
                    />
                </div>
            </div>
        </div>
    </div>
</template>
