<script setup>
import { usePage, Head, router, Link } from "@inertiajs/vue3";
import { onMounted } from "vue";
import { formatDate, formatThousands } from "../../utils";

const appName = import.meta.env.VITE_APP_NAME;

const { group, settlements } = usePage().props;
const participants = group.participants.map((participant) => participant.name);
const bills = group.bills;
const totalAmount = group.total_amount_in_base_currency;

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

    <div class="flex flex-col gap-4">
        <div
            class="flex justify-between items-start gap-4 w-full border-b pb-4"
        >
            <div class="flex flex-col">
                <h1 class="text-2xl font-semibold break-all">
                    {{ group.name }}
                </h1>
                <p class="text-sm text-slate-600 break-words">
                    {{ group.description }}
                </p>
                <p class="text-sm text-slate-600 break-words">
                    {{ participants.join(", ") }}
                </p>
                <span class="text-xs text-slate-600 break-words">
                    Created at {{ formatDate(group.created_at) }}
                </span>
            </div>

            <button
                @click="router.get(`/groups/${group.slug}/edit`)"
                class="rounded aspect-square bg-slate-200 text-slate-900 flex items-center justify-center"
            >
                <i class="fas fa-pencil w-10"></i>
            </button>
        </div>

        <div class="flex flex-col">
            <h2 class="text-lg font-semibold">Total Bill Amount</h2>
            <span class="text-2xl font-semibold">
                {{ group.currency.symbol
                }}{{
                    formatThousands(totalAmount, group.currency.decimal_digits)
                }}
            </span>
        </div>

        <div class="flex flex-col" v-if="settlements.length">
            <h2 class="text-lg font-semibold">Settlements</h2>

            <ul class="flex flex-col" v-if="settlements.length">
                <li
                    v-for="(settlement, idx) in settlements"
                    :key="idx"
                    class="flex items-center justify-between gap-4 border-b rounded p-2 w-full"
                >
                    <h3 class="font-medium break-words">
                        {{ settlement.from.name }}
                        <i class="fas fa-arrow-right text-xs mx-1"></i>
                        {{ settlement.to.name }}
                    </h3>

                    <div class="flex flex-col items-end">
                        <span class="font-medium">
                            {{ group.currency.symbol
                            }}{{
                                formatThousands(
                                    settlement.amount,
                                    group.currency.decimal_digits
                                )
                            }}
                        </span>
                    </div>
                </li>
            </ul>
        </div>

        <div class="flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Bills and Payments</h2>

                <Link
                    :href="`/groups/${group.slug}/add-bill`"
                    class="text-emerald-600 font-medium"
                >
                    <i class="fas fa-plus-circle"></i>
                    Add Bill
                </Link>
            </div>

            <ul class="flex flex-col gap-2" v-if="bills.length">
                <li
                    v-for="bill in bills"
                    :key="bill.id"
                    class="flex items-center justify-between gap-4 border rounded p-2 w-full hover:bg-gray-100"
                >
                    <div class="flex flex-col w-full">
                        <h3 class="font-semibold break-all">
                            {{ bill.name }}
                        </h3>
                        <span class="text-xs text-slate-600 break-words">
                            Created at {{ formatDate(bill.created_at) }}
                        </span>
                    </div>

                    <div class="flex flex-col items-end">
                        <span class="text-sm font-medium">
                            {{ group.currency.symbol
                            }}{{
                                formatThousands(
                                    bill.amount_in_base_currency,
                                    group.currency.decimal_digits
                                )
                            }}
                        </span>
                        <span
                            v-if="bill.currency_id !== group.currency_id"
                            class="text-xs"
                        >
                            {{ bill.currency.symbol
                            }}{{
                                formatThousands(
                                    bill.amount,
                                    bill.currency.decimal_digits
                                )
                            }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            @click="
                                router.get(
                                    `/groups/${group.slug}/bills/${bill.id}`
                                )
                            "
                            class="rounded w-8 h-8 bg-slate-200 text-slate-900"
                        >
                            <i class="fas fa-eye w-8"></i></button
                        ><button
                            @click="
                                router.get(
                                    `/groups/${group.slug}/bills/${bill.id}/edit`
                                )
                            "
                            class="rounded w-8 h-8 bg-slate-200 text-slate-900"
                        >
                            <i class="fas fa-pencil w-8"></i>
                        </button>
                    </div>
                </li>
            </ul>

            <div v-else class="flex flex-col items-center gap-2">
                <p class="text-slate-600">No bills and payments found.</p>
                <Link
                    :href="`/groups/${group.slug}/add-bill`"
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
