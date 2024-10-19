<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { BillForm } from "../../Components";

const appName = import.meta.env.VITE_APP_NAME;

const { group, currencies } = usePage().props;
</script>

<template>
    <Head>
        <title>Add Bill for {{ group.name }} | {{ appName }}</title>
    </Head>

    <div>
        <h1 class="text-center text-2xl font-semibold mb-6">
            Add Bill for {{ group.name }}
        </h1>
        <BillForm :group="group" :currencies="currencies" />
    </div>
</template>
