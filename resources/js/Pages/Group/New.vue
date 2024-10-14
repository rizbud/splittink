<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { GroupForm } from "../../Components";

const appName = import.meta.env.VITE_APP_NAME;

const { currencies } = usePage().props;
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
