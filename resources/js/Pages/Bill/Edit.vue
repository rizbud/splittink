<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { BillForm } from "../../Components";

const appName = import.meta.env.VITE_APP_NAME;

const { bill, group, bill_participants, currencies } = usePage().props;
</script>

<template>
    <Head>
        <title>Edit Bill {{ bill.name }} | {{ appName }}</title>
    </Head>

    <div>
        <h1 class="text-center text-2xl font-semibold mb-6">
            Edit Bill {{ bill.name }}
        </h1>
        <BillForm
            :group="group"
            :bill="bill"
            :bill-participants="bill_participants"
            :currencies="currencies"
        />
    </div>
</template>
