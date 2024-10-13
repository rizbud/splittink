<script setup>
import { reactive, ref } from "vue";
import { router, Head, usePage } from "@inertiajs/vue3";
import axios from "axios";

const appName = import.meta.env.VITE_APP_NAME;

const participant = ref("");
const isLoading = ref(false);

const { group, currencies } = usePage().props;
const participants = group.participants;

const form = reactive({
    name: group.name,
    description: group.description,
    participants,
    currency_id: group.currency_id,
});

const addParticipant = () => {
    form.participants.push({ name: participant.value });
    participant.value = "";
};

const removeParticipant = (index) => {
    const confirmRemove = confirm(
        `Are you sure you want to remove ${form.participants[index].name}?`
    );
    confirmRemove && form.participants.splice(index, 1);
};

const submitForm = async () => {
    try {
        isLoading.value = true;
        const response = await axios.put(`/groups/${group.id}`, form);

        if (response.status === 200) {
            router.get(`/groups/${response.data.slug}`);
        } else {
            throw new Error();
        }
    } catch (error) {
        console.error(error);
        alert("Failed to create the group.");
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Head>
        <title>Edit {{ group.name }} | {{ appName }}</title>
    </Head>

    <div>
        <h1 class="text-center text-2xl font-semibold mb-6">
            Edit {{ group.name }}
        </h1>

        <form @submit.prevent="submitForm" class="flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <label for="name" class="block text-sm">Group Name</label>
                <input
                    type="text"
                    id="name"
                    v-model="form.name"
                    class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-0"
                    placeholder="Trip to Los Santos, Summer Camp, etc."
                    required
                />
            </div>

            <div class="flex flex-col gap-1">
                <label for="description" class="block text-sm"
                    >Description (optional)</label
                >
                <textarea
                    id="description"
                    v-model="form.description"
                    class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-0"
                    placeholder="Give a brief description of the group."
                ></textarea>
            </div>

            <div class="flex flex-col gap-1">
                <label for="participants" class="block text-sm">
                    Participants
                </label>
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
            </div>

            <div class="flex flex-col gap-1">
                <label for="currency" class="block text-sm">
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

            <button
                type="submit"
                class="bg-emerald-500 text-white rounded p-4 mt-2 focus:outline-none focus:ring-0 flex items-center justify-center"
                :class="
                    isDisabled
                        ? 'bg-gray-300 text-gray-500 cursor-default'
                        : 'bg-emerald-500 text-white'
                "
                :disabled="
                    !form.name || form.participants.length < 2 || isLoading
                "
            >
                <div v-if="isLoading" class="animate-spin">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <span v-else>Save Changes</span>
            </button>
        </form>
    </div>
</template>
