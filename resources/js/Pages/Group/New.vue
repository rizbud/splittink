<script setup>
import { reactive, ref } from "vue";
import { router, Head, usePage } from "@inertiajs/vue3";
import axios from "axios";

const appName = import.meta.env.VITE_APP_NAME;

const { currencies } = usePage().props;

const participant = ref("");
const isLoading = ref(false);

const form = reactive({
    name: "",
    description: "",
    participants: [],
    currency_id: 1,
});

const isDisabled =
    !form.name || form.participants.length < 2 || isLoading.value;

const addParticipant = () => {
    form.participants.push(participant.value);
    participant.value = "";
};

const removeParticipant = (index) => {
    form.participants.splice(index, 1);
};

const submitForm = async () => {
    try {
        isLoading.value = true;
        const response = await axios.post("/groups", form);

        if (response.status === 201) {
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
        <title>Create a New Group | {{ appName }}</title>
    </Head>

    <div>
        <h1 class="text-center text-2xl font-semibold mb-6">
            Create a New Group
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
                        :class="{
                            'hover:bg-emerald-600 cursor-pointer':
                                !!participant,
                            'cursor-default': !participant,
                            'disabled:opacity-50': !participant,
                        }"
                        :disabled="!participant"
                    >
                        Add
                    </button>
                </form>

                <ul v-if="form.participants.length" class="mt-2 flex gap-4">
                    <li
                        v-for="(participant, index) in form.participants"
                        :key="participant"
                        class="flex items-center justify-center bg-gray-200 px-3 py-1.5 rounded-full gap-2"
                    >
                        <span>{{ participant }}</span>
                        <button
                            type="button"
                            class="text-red-500"
                            @click="removeParticipant(index)"
                        >
                            <i class="fas fa-times"></i>
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
                <span v-else>Create Group</span>
            </button>
        </form>
    </div>
</template>
