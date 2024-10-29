<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { GroupForm } from "../../Components";

const appName = import.meta.env.VITE_APP_NAME;

const { currencies } = usePage().props;
</script>

<template>
    <Head>
        <title>Create a New Group | {{ appName }}</title>
        <meta
            property="og:title"
            :content="'Create a New Group | ' + appName"
        />
        <meta
            property="og:description"
            content="Create a new group to start splitting bills among your friends, family, or colleagues!"
        />
    </Head>

    <div>
        <h1 class="text-center text-2xl font-semibold mb-6">
            Create a New Group
        </h1>

        <GroupForm :currencies="currencies" />
    </div>
</template>
