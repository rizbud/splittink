<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { formatDate } from "../utils";

const appName = import.meta.env.VITE_APP_NAME;
</script>

<template>
    <Head>
        <title>Terms of Service | {{ appName }}</title>
        <meta property="og:title" :content="'Terms of Service | ' + appName" />
        <meta
            property="og:description"
            content="Splitt.ink provides a free, registration-free platform to help users split bills and track shared expenses."
        />
    </Head>

    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-semibold">Terms of Service</h1>

        <p class="indent-4 text-justify">
            Welcome to
            <Link href="/" class="text-emerald-600 hover:underline"
                >Splitt.ink</Link
            >! These Terms of Service ("Terms") govern your use of our platform.
            By accessing or using Splitt.ink, you agree to be bound by these
            Terms. Please read them carefully.
        </p>

        <div>
            <h2 class="text-xl font-semibold mb-1">1. Acceptance of Terms</h2>
            <p class="indent-4 text-justify">
                By using
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >, you agree to comply with and be legally bound by these Terms.
                If you do not agree to any part of these Terms, please do not
                use our service.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-1">2. Service Overview</h2>
            <p class="indent-4 text-justify">
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                provides a free, registration-free platform to help users split
                bills and track shared expenses. The service is offered as-is,
                and we do not guarantee uninterrupted or error-free access to
                the platform.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-1">
                3. No Registration Required
            </h2>
            <p class="indent-4 text-justify">
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                does not require user registration or the submission of personal
                information. You can use the service without creating an
                account. However, by using the service, you accept these Terms.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-1">4. User Responsibility</h2>
            <p class="indent-4 text-justify">
                <span class="font-semibold">Accuracy of Entries:</span> You are
                responsible for ensuring the accuracy of the expenses and
                amounts entered into
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >. The platform helps calculate and divide shared costs, but the
                responsibility for entering correct information remains with the
                user.
            </p>
            <p class="indent-4 text-justify">
                <span class="font-semibold">Lawful Use:</span> You agree to use
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                only for lawful purposes and in compliance with all applicable
                laws and regulations. You may not use the platform to engage in
                any illegal activity or harm others.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-1">5. Intellectual Property</h2>
            <p class="indent-4 text-justify">
                All content, design, logos, and intellectual property related to
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                are owned by
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                and are protected by applicable laws. You may not copy, modify,
                distribute, or otherwise use any content from
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                without prior written permission.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-1">
                6. Limitation of Liability
            </h2>
            <p class="indent-4 text-justify">
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                is provided on an "as is" and "as available" basis. We make no
                warranties, either express or implied, regarding the service’s
                availability, functionality, or fitness for a particular
                purpose.
            </p>
            <p class="indent-4 text-justify">
                <span class="font-semibold">No Financial Advice:</span>
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                provides a tool for splitting expenses but is not responsible
                for any financial transactions or agreements between users.
            </p>
            <p class="indent-4 text-justify">
                <span class="font-semibold">No Liability:</span> To the fullest
                extent permitted by law,
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                is not liable for any direct, indirect, incidental, or
                consequential damages arising from your use of the service,
                including but not limited to data loss, errors, or inaccurate
                calculations.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-1">7. Third-Party Services</h2>
            <p class="indent-4 text-justify">
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                may integrate or link to third-party services for analytics,
                advertising, or other purposes. These third-party services have
                their own terms and privacy policies, which you should review.
                Splitt.ink is not responsible for the content or services
                provided by third parties.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-1">
                8. Changes to the Service
            </h2>
            <p class="indent-4 text-justify">
                We reserve the right to modify or discontinue the service,
                either temporarily or permanently, with or without notice. We
                may also update these Terms from time to time. Continued use of
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                following any changes to the Terms constitutes acceptance of the
                new Terms.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-1">9. Termination</h2>
            <p class="indent-4 text-justify">
                We reserve the right to terminate or suspend access to
                <Link href="/" class="text-emerald-600 hover:underline">
                    Splitt.ink</Link
                >
                at our discretion, without prior notice, for any reason,
                including violation of these Terms.
            </p>
        </div>

        <div v-if="false">
            <h2 class="text-xl font-semibold mb-1">10. Governing Law</h2>
            <p class="indent-4 text-justify">
                These Terms are governed by and construed in accordance with the
                laws of Indonesia, without regard to its conflict of law
                principles. Any legal disputes arising out of or in connection
                with these Terms shall be resolved in the courts of Indonesia.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-1">10. Contact Information</h2>
            <p class="indent-4 text-justify">
                If you have any questions or concerns about these Terms, please
                contact us at
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
