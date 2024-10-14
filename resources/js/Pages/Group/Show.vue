<script setup>
import { usePage, Head, router, Link } from "@inertiajs/vue3";
import { onMounted } from "vue";
import { formatDate } from "../../utils";

const appName = import.meta.env.VITE_APP_NAME;

const { group } = usePage().props;
const participants = group.participants.map((participant) => participant.name);
const bills = group.bills;

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

            <button
                @click="router.get(`/groups/${group.slug}/edit`)"
                class="rounded w-8 h-8 bg-slate-200 text-slate-900"
            >
                <i class="fas fa-pencil"></i>
            </button>
        </div>

        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Bills and Payments</h2>

                <Link
                    :href="`/groups/${group.slug}/bills/create`"
                    class="text-emerald-500 font-medium"
                >
                    <i class="fas fa-plus-circle"></i>
                    Add Bill
                </Link>
            </div>

            <ul class="flex flex-col gap-4" v-if="bills.length">
                <li
                    v-for="bill in bills"
                    :key="bill.id"
                    class="flex items-center justify-between gap-4"
                >
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-500">
                            Created at {{ formatDate(bill.created_at) }}
                        </span>
                        <p class="text-sm text-gray-500">{{ bill.amount }}</p>
                    </div>

                    <div class="flex gap-4">
                        <button
                            @click="
                                router.get(
                                    `/groups/${group.slug}/bills/${bill.id}`
                                )
                            "
                            class="rounded w-8 h-8 bg-slate-200 text-slate-900"
                        >
                            <i class="fas fa-eye"></i>
                        </button>
                        <button
                            @click="
                                router.get(
                                    `/groups/${group.slug}/bills/${bill.id}/edit`
                                )
                            "
                            class="rounded w-8 h-8 bg-slate-200 text-slate-900"
                        >
                            <i class="fas fa-pencil"></i>
                        </button>
                    </div>
                </li>
            </ul>

            <div v-else class="flex flex-col items-center gap-2">
                <p class="text-gray-500">No bills and payments found.</p>
                <Link
                    :href="`/groups/${group.slug}/bills/create`"
                    class="text-emerald-600"
                >
                    <button
                        class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-0"
                    >
                        Add a new bill
                    </button>
                </Link>
            </div>
        </div>
    </div>
</template>
