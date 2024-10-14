<script setup>
import { reactive, ref, computed } from "vue";
import { router, Head, usePage } from "@inertiajs/vue3";
import axios from "axios";
import { GroupForm, Input, PrimaryButton } from "../../Components";

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

const isDisabled = computed(() => {
    return !form.name || form.participants.length < 2 || isLoading.value;
});

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

        <GroupForm :currencies="currencies" />
    </div>
</template>
