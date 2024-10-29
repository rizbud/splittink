<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { formatDate } from "../utils";

const appName = import.meta.env.VITE_APP_NAME;
</script>

<template>
    <Head>
        <title>Privacy Policy | {{ appName }}</title>
        <meta property="og:title" :content="'Privacy Policy | ' + appName" />
        <meta
            property="og:description"
            content="Splitt.ink is committed to protecting your privacy. This Privacy Policy explains how we collect, use, and disclose your personal information."
        />
    </Head>

    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-semibold">Privacy Policy</h1>

        <p class="indent-4 text-justify">
            At
            <Link href="/" class="text-emerald-600 hover:underline">
                Splitt.ink</Link
            >, we value your privacy and are committed to protecting your
            personal information. This Privacy Policy outlines how we collect,
            use, and protect your data when you use our services. By using
            Splitt.ink, you agree to the terms of this Privacy Policy.
        </p>

        <div>
            <h2 class="text-xl font-semibold mb-1">
                1. No Personal Data Collection
            </h2>
            <p class="indent-4 text-justify">
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                does not require user registration, and we do not collect any
                personal data such as email addresses or phone numbers. You can
                use our app without providing any personal information.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-1">2. Anonymous Usage Data</h2>
            <p class="indent-4 text-justify">
                To improve our services, we collect anonymous usage data such as
                usage statistics, device information, and general location data
                (based on IP address). This information helps us understand how
                users interact with
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                and allows us to enhance the app's performance.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-1">3. Cookies</h2>
            <p class="indent-4 text-justify">
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                may use cookies to improve user experience and track website
                traffic. Cookies are small text files stored on your device that
                help us analyze how you use our app. You can disable cookies in
                your browser settings, but this may affect the functionality of
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-1">4. Third-Party Services</h2>
            <p class="indent-4 text-justify">
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                may use third-party services to analyze user behavior, provide
                customer support, or deliver targeted advertisements. These
                services may collect data about your interactions with
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >, but they are bound by their own privacy policies.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-1">5. Data Security</h2>
            <p class="indent-4 text-justify">
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                takes data security seriously and implements industry-standard
                measures to protect your information. However, no method of
                transmission over the internet or electronic storage is 100%
                secure. While we strive to safeguard your data, we cannot
                guarantee absolute security.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-1">
                6. Changes to Privacy Policy
            </h2>
            <p class="indent-4 text-justify">
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                reserves the right to update this Privacy Policy at any time. We
                encourage you to review this page periodically for any changes.
                By continuing to use
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >, you agree to the updated terms.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-1">7. Contact Us</h2>
            <p class="indent-4 text-justify">
                If you have any questions or concerns about this Privacy Policy,
                please contact us at
                <a
                    href="mailto:contact@splitt.ink"
                    class="text-emerald-600 hover:underline"
                >
                    contact@splitt.ink</a
                >.
            </p>
        </div>

        <span class="text-xs text-gray-500">
            Last updated: {{ formatDate("2024-10-21T18:00:00+0700") }}
        </span>
    </div>
</template>
