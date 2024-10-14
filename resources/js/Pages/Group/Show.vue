<script setup>
import { usePage, Head, router } from "@inertiajs/vue3";
import { onMounted } from "vue";

const appName = import.meta.env.VITE_APP_NAME;

const { group } = usePage().props;
const participants = group.participants.map((participant) => participant.name);

const saveGroupToLocalStorage = () => {
    const recentGroupsKey = "recentGroups";
    let recentGroups = JSON.parse(localStorage.getItem(recentGroupsKey)) || [];

    // Remove the group if it already exists
    recentGroups = recentGroups.filter((g) => g.slug !== group.slug);

    // Add the group to the end of the list
    recentGroups.push({
        name: group.name,
        slug: group.slug,
        last_visited: new Date().toISOString(),
    });

    // Save back to localStorage
    localStorage.setItem(recentGroupsKey, JSON.stringify(recentGroups));
};

onMounted(() => {
    saveGroupToLocalStorage();
});
</script>

<template>
    <Head>
        <title>{{ group.name }} | {{ appName }}</title>
    </Head>

    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-start gap-4 w-full">
            <div class="flex flex-col break-all">
                <h1 class="text-2xl font-semibold">
                    {{ group.name }}
                </h1>
                <p class="text-sm text-gray-500">
                    {{ group.description }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ participants.join(", ") }}
                </p>
            </div>

            <button @click="router.get(`/groups/${group.slug}/edit`)">
                <i class="fas fa-edit"></i>
            </button>
        </div>

        <div>Testt</div>
    </div>
</template>
