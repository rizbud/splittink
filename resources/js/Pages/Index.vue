<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, onMounted, defineOptions } from "vue";
import IndexLayout from "../Layouts/IndexLayout.vue";
import { formatDate } from "../utils";

const recentGroups = ref([]);

const loadRecentGroups = () => {
    const recentGroupsKey = "recentGroups";
    const list = JSON.parse(localStorage.getItem(recentGroupsKey)) || [];
    recentGroups.value = list.sort((a, b) => {
        return new Date(b.last_visited) - new Date(a.last_visited);
    });
};

onMounted(() => {
    loadRecentGroups();
});

defineOptions({
    layout: IndexLayout,
});
</script>

<template>
    <div class="bg-emerald-600 text-white px-6 pt-2 pb-4 flex flex-col gap-4">
        <div>
            <h1 class="text-2xl font-semibold">Split Bills with Ease</h1>
            <p class="text-sm">
                Easiest way to split bills among the group. Our app makes it
                simple to split bills and share expenses with friends, family,
                or colleagues.
            </p>
        </div>

        <Link href="/new">
            <button
                class="bg-white text-emerald-600 px-4 py-2 rounded focus:outline-none focus:ring-0 font-medium w-full"
            >
                Create Group
            </button>
        </Link>
    </div>

    <div class="flex flex-col gap-2 p-6" v-if="recentGroups.length">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold w-full">Recent Groups</h2>

            <Link href="/new" class="text-emerald-600 font-medium text-nowrap">
                <i class="fas fa-plus-circle"></i>
                Create Group
            </Link>
        </div>

        <ul class="space-y-2">
            <li v-for="group in recentGroups" :key="group.slug">
                <Link
                    class="flex justify-between items-center border border-slate-200 rounded-lg px-4 py-3 hover:bg-slate-100"
                    :href="`/groups/${group.slug}`"
                >
                    <div>
                        <h3 class="font-medium line-clamp-1">
                            {{ group.name }}
                        </h3>
                        <p class="text-sm text-slate-600">
                            Last visited on
                            {{ formatDate(group.last_visited) }}
                        </p>
                    </div>

                    <i class="fas fa-chevron-right"></i>
                </Link>
            </li>
        </ul>
    </div>

    <div class="flex flex-col gap-2 p-6 bg-slate-100">
        <div class="text-center mb-2">
            <h2 class="text-2xl font-semibold">How It Works?</h2>
            <p class="text-sm">
                Splitting bills with friends, family, or colleagues is super
                easy. Just follow these simple steps.
            </p>
        </div>

        <div class="flex gap-4">
            <div
                class="bg-emerald-600 text-white aspect-square w-10 h-10 rounded-lg self-start flex justify-center items-center mt-1"
            >
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Create Group</h3>
                <p class="text-sm text-slate-600">
                    Create a group and add members to it. You can add as many
                    members as you want.
                </p>
            </div>
        </div>

        <div class="flex gap-4">
            <div
                class="bg-emerald-600 text-white aspect-square w-10 h-10 rounded-lg self-start flex justify-center items-center mt-1"
            >
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Add Bills</h3>
                <p class="text-sm text-slate-600">
                    Add bills to the group. You can add as many bills as you
                    want.
                </p>
            </div>
        </div>

        <div class="flex gap-4">
            <div
                class="bg-emerald-600 text-white aspect-square w-10 h-10 rounded-lg self-start flex justify-center items-center mt-1"
            >
                <i class="fas fa-calculator"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Split Bills</h3>
                <p class="text-sm text-slate-600">
                    Our app will calculate the share of each member and show you
                    the final amount each member owes or is owed and to whom
                    they owe.
                </p>
            </div>
        </div>

        <div class="flex gap-4">
            <div
                class="bg-emerald-600 text-white aspect-square w-10 h-10 rounded-lg self-start flex justify-center items-center mt-1"
            >
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Settle Your Debts</h3>
                <p class="text-sm text-slate-600">
                    Once you know who owes whom and how much, you can settle
                    your debts with each other.
                </p>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-2 p-6">
        <div class="text-center mb-2">
            <h2 class="text-2xl font-semibold">Key Features</h2>
            <p class="text-sm">
                Our app offers a range of features to make splitting bills a
                breeze.
            </p>
        </div>

        <div class="flex gap-4">
            <div
                class="bg-emerald-600 text-white aspect-square w-10 h-10 rounded-lg self-start flex justify-center items-center mt-1"
            >
                <i class="fas fa-user-plus"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">No Registration Needed</h3>
                <p class="text-sm text-slate-600">
                    You don't need to register to use our app. Just create a
                    group and start splitting bills.
                </p>
            </div>
        </div>

        <div class="flex gap-4">
            <div
                class="bg-emerald-600 text-white aspect-square w-10 h-10 rounded-lg self-start flex justify-center items-center mt-1"
            >
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Multiple Currencies</h3>
                <p class="text-sm text-slate-600">
                    Our app supports multiple currencies. You can add bills in
                    any currency you want.
                </p>
            </div>
        </div>

        <div class="flex gap-4">
            <div
                class="bg-emerald-600 text-white aspect-square w-10 h-10 rounded-lg self-start flex justify-center items-center mt-1"
            >
                <i class="fas fa-calculator"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Automatic Calculations</h3>
                <p class="text-sm text-slate-600">
                    Our app will automatically calculate the share of each
                    member and show you the final amount each member owes or is
                    owed and to whom they owe.
                </p>
            </div>
        </div>

        <div class="flex gap-4">
            <div
                class="bg-emerald-600 text-white aspect-square w-10 h-10 rounded-lg self-start flex justify-center items-center mt-1"
            >
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Debt Settlement</h3>
                <p class="text-sm text-slate-600">
                    Once you know who owes whom and how much, you can settle
                    your debts with each other.
                </p>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-2 p-6 pb-10 bg-slate-100">
        <div class="text-center mb-2">
            <h2 class="text-2xl font-semibold">How The Calculation Works?</h2>
            <p class="text-sm">
                Our app uses a simple formula to split the bill based on the
                total amount and the number of people.
            </p>
        </div>

        <div class="flex gap-4">
            <div
                class="bg-emerald-600 text-white aspect-square w-10 h-10 rounded-lg self-start flex justify-center items-center mt-1"
            >
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Total Bill Amount</h3>
                <p class="text-sm text-slate-600">
                    The total amount of the bill, including any tips or taxes.
                </p>
            </div>
        </div>

        <div class="flex gap-4">
            <div
                class="bg-emerald-600 text-white aspect-square w-10 h-10 rounded-lg self-start flex justify-center items-center mt-1"
            >
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Number of People</h3>
                <p class="text-sm text-slate-600">
                    The number of people who are splitting the bill.
                </p>
            </div>
        </div>

        <div class="flex gap-4">
            <div
                class="bg-emerald-600 text-white aspect-square w-10 h-10 rounded-lg self-start flex justify-center items-center mt-1"
            >
                <i class="fas fa-percentage"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Share of Each Member</h3>
                <p class="text-sm text-slate-600">
                    The share of each member is calculated by dividing the total
                    amount by the number of people.
                </p>
            </div>
        </div>

        <div class="flex gap-4">
            <div
                class="bg-emerald-600 text-white aspect-square w-10 h-10 rounded-lg self-start flex justify-center items-center mt-1"
            >
                <i class="fas fa-calculator"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Who Owes Whom?</h3>
                <p class="text-sm text-slate-600">
                    Our app will show you the final amount each member owes or
                    is owed and to whom they owe.
                </p>
            </div>
        </div>
    </div>
</template>
