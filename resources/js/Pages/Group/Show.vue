<script setup>
import { usePage, Head, router, Link } from "@inertiajs/vue3";
import { onMounted, ref, computed, watch } from "vue";
import { formatDate, formatThousands, toast } from "../../utils";
import { Input, PrimaryButton } from "../../Components";

const appName = import.meta.env.VITE_APP_NAME;

const page = usePage();
const group = computed(() => page.props.group);
const settlements = computed(() => page.props.settlements);

const participants = group.value.participants.map(
    (participant) => participant.name
);
const bills = group.value.bills;
const totalAmount = group.value.total_amount_in_base_currency;

const selectedSettlement = ref(null);
const isSettling = ref(false);

const saveGroupToLocalStorage = () => {
    const recentGroupsKey = "recentGroups";
    let recentGroups = JSON.parse(localStorage.getItem(recentGroupsKey)) || [];

    // Remove the group if it already exists
    recentGroups = recentGroups.filter((g) => g.slug !== group.value.slug);

    // Add the group to the end of the list
    recentGroups.push({
        name: group.value.name,
        slug: group.value.slug,
        last_visited: new Date().toISOString(),
    });

    // Save back to localStorage
    localStorage.setItem(recentGroupsKey, JSON.stringify(recentGroups));
};

onMounted(() => {
    saveGroupToLocalStorage();
});

const handleSettle = async () => {
    if (!selectedSettlement.value) return;

    const { from, to, amount } = selectedSettlement.value;
    try {
        isSettling.value = true;
        const response = await axios.post(`/groups/${group.value.id}/settle`, {
            settlements: [{ from: from.id, to: to.id, amount }],
        });

        if (response.status === 200) {
            toast.success("Settlement successful");
            router.reload({ only: ["settlements"] });
        } else {
            throw new Error();
        }
    } catch (error) {
        const errorMessage =
            error.response?.data?.message ||
            error.message ||
            "Failed to settle";
        toast.error(errorMessage);
    } finally {
        isSettling.value = false;
        selectedSettlement.value = null;
    }
};
</script>

<template>
    <Head>
        <title>Group {{ group.name }} | {{ appName }}</title>
        <meta
            property="og:title"
            :content="`Group ${group.name} | ${appName}`"
        />
        <meta
            property="og:description"
            :content="`Split bills in ${group.name} with ease!`"
        />
    </Head>

    <div>
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
                        formatThousands(
                            totalAmount,
                            group.currency.decimal_digits
                        )
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

                        <div class="flex items-center gap-2">
                            <span class="font-medium">
                                {{ group.currency.symbol
                                }}{{
                                    formatThousands(
                                        settlement.amount,
                                        group.currency.decimal_digits
                                    )
                                }}
                            </span>

                            <button
                                @click="
                                    selectedSettlement = {
                                        from: settlement.from,
                                        to: settlement.to,
                                    }
                                "
                                class="rounded bg-emerald-600 text-white px-2 py-0.5 hover:bg-emerald-700 focus:outline-none focus:ring-0"
                            >
                                Settle
                            </button>
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

        <div
            v-if="!!selectedSettlement"
            class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-50 flex items-center justify-center"
        >
            <form
                class="bg-white p-4 rounded shadow-lg w-96 flex flex-col relative"
                @submit.prevent="handleSettle"
            >
                <div class="flex items-start justify-between mb-3">
                    <h3 class="font-medium">
                        Settle the amount between
                        {{ selectedSettlement.from.name }} and
                        {{ selectedSettlement.to.name }}.
                    </h3>

                    <button
                        type="button"
                        @click="selectedSettlement = null"
                        class="text-slate-900"
                    >
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <Input
                    type="number"
                    v-model="selectedSettlement.amount"
                    :label="`Amount (in ${group.currency.code})`"
                    placeholder="10.00"
                    @input="
                        (event) =>
                            (selectedSettlement.amount = parseFloat(
                                event.target.value || 0
                            ))
                    "
                    required
                />
                <PrimaryButton
                    text="Settle"
                    type="submit"
                    class="mt-4"
                    :disabled="!selectedSettlement.amount"
                    :isLoading="isSettling"
                />
            </form>
        </div>
    </div>
</template>
