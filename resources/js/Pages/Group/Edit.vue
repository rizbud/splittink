<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { GroupForm } from "../../Components";

const appName = import.meta.env.VITE_APP_NAME;

const { group, currencies, settlements } = usePage().props;
</script>

<template>
    <Head>
        <title>Edit {{ group.name }} | {{ appName }}</title>
    </Head>

    <div>
        <h1 class="text-center text-2xl font-semibold mb-6">
            Edit {{ group.name }}
        </h1>

        <GroupForm
            :group="group"
            :currencies="currencies"
            :settlements="settlements"
        />
    </div>
</template>
