<script setup>
import { Head, Link } from "@inertiajs/vue3";

const appName = import.meta.env.VITE_APP_NAME;
</script>

<template>
    <Head>
        <title>About Us | {{ appName }}</title>
        <meta property="og:title" :content="'About Us | ' + appName" />
        <meta
            property="og:description"
            content="Welcome to Splitt.ink, the easiest way to split bills among your group!"
        />
    </Head>

    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-semibold">About Us</h1>

        <p class="indent-4 text-justify">
            Welcome to
            <Link href="/" class="text-emerald-600 hover:underline"
                >Splitt.ink</Link
            >, the easiest way to split bills among your group! Our app is
            designed to simplify the process of sharing expenses with friends,
            family, or colleagues.
        </p>

        <p class="indent-4 text-justify">
            At
            <Link href="/" class="text-emerald-600 hover:underline"
                >Splitt.ink</Link
            >, we understand that managing group expenses can be a hassle.
            Whether it's dining out, traveling, or planning events, keeping
            track of who owes what shouldn't be complicated. That's why we've
            created a user-friendly platform that allows you to quickly and
            accurately split bills with just a few taps.
        </p>

        <p class="indent-4 text-justify">
            With our intuitive interface, you can effortlessly add expenses,
            assign amounts, and calculate who owes what in real-time. No more
            awkward conversations or confusing spreadsheets.
            <Link href="/" class="text-emerald-600 hover:underline"
                >Splitt.ink</Link
            >
            streamlines the process so you can focus on what really matters:
            enjoying your time with others.
        </p>
    </div>
</template>
