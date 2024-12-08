<script setup>
import { Head, usePage, Link, router } from "@inertiajs/vue3";
import { formatDate, formatThousands } from "../../utils";

const appName = import.meta.env.VITE_APP_NAME;

const { bill, group, bill_participants, currencies } = usePage().props;
</script>

<template>
    <Head>
        <title>Bill {{ bill.name }} | {{ appName }}</title>
        <meta property="og:title" :content="`Bill ${bill.name} | ${appName}`" />
        <meta
            property="og:description"
            :content="`Bill ${bill.name} in group ${group.name}`"
        />
    </Head>

    <div class="flex flex-col gap-4">
        <div class="flex justify-between items-start gap-4 border-b pb-4">
            <div class="flex flex-col break-all w-full">
                <h1 class="text-2xl font-semibold">Bill {{ bill.name }}</h1>
                <span class="text-sm text-slate-600">
                    Group:
                    <Link
                        :href="`/groups/${group.slug}`"
                        class="hover:underline text-emerald-500"
                    >
                        {{ group.name }}
                    </Link>
                </span>
                <span class="text-xs text-slate-600">
                    Created at {{ formatDate(bill.created_at) }}
                </span>
            </div>

            <button
                @click="
                    router.get(`/groups/${group.slug}/bills/${bill.id}/edit`)
                "
                class="rounded aspect-square bg-slate-200 text-slate-900 flex items-center justify-center"
            >
                <i class="fas fa-pencil w-10"></i>
            </button>
        </div>

        <div class="flex flex-col">
            <h2 class="text-lg font-semibold">Total Amount</h2>
            <span class="text-2xl font-semibold">
                {{ group.currency.symbol
                }}{{ formatThousands(bill.amount_in_base_currency) }}
                <span
                    class="text-base font-normal text-slate-600"
                    v-if="group.currency_id !== bill.currency_id"
                >
                    ({{ bill.currency.symbol
                    }}{{ formatThousands(bill.amount) }})
                </span>
            </span>
        </div>

        <!-- Participants -->
        <div class="flex flex-col">
            <h2 class="text-lg font-semibold">Participants</h2>

            <ul class="flex flex-col">
                <li
                    v-for="participant in bill_participants"
                    :key="participant.id"
                    class="flex items-center justify-between gap-4 border-b p-2 w-full"
                >
                    <div class="flex gap-2 items-center w-full">
                        <h3 class="font-medium break-all">
                            {{ participant.participant.name }}
                        </h3>

                        <span
                            v-if="participant.paid_amount > 0"
                            class="text-xs font-normal text-white bg-emerald-600 rounded px-1"
                        >
                            Payer
                        </span>
                    </div>

                    <span
                        v-if="participant.paid_amount > 0"
                        class="text-sm text-nowrap text-emerald-600"
                    >
                        {{ group.currency.symbol
                        }}{{
                            formatThousands(
                                participant.paid_amount_in_base_currency,
                                group.currency.decimal_digits
                            )
                        }}
                        <span
                            class="text-xs font-normal"
                            v-if="group.currency_id !== bill.currency_id"
                        >
                            ({{ bill.currency.symbol
                            }}{{
                                formatThousands(
                                    participant.paid_amount,
                                    bill.currency.decimal_digits
                                )
                            }})
                        </span>
                    </span>
                </li>
            </ul>
        </div>
    </div>
</template>
