<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { GroupForm } from "../../Components";

const appName = import.meta.env.VITE_APP_NAME;

const { group, currencies, settlements } = usePage().props;
</script>

<template>
    <Head>
        <title>Edit Group {{ group.name }} | {{ appName }}</title>
        <meta
            property="og:title"
            :content="`Edit Group ${group.name} | ${appName}`"
        />
        <meta
            property="og:description"
            :content="`Edit group ${group.name} to start splitting bills among your friends, family, or colleagues!`"
        />
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
