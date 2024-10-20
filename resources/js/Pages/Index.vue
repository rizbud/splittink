<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import { formatDate } from "../utils";

const recentGroups = ref([]);

const loadRecentGroups = () => {
    const recentGroupsKey = "recentGroups";
    const list = JSON.parse(localStorage.getItem(recentGroupsKey)) || [];
    recentGroups.value = list.sort((a, b) => {
        return new Date(b.last_visited) - new Date(a.last_visited);
    });
};

onMounted(() => {
    loadRecentGroups();
});
</script>

<template>
    <div>
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-xl font-semibold w-full">Recent Groups</h2>

            <Link href="/new" class="text-emerald-600 font-medium text-nowrap">
                <i class="fas fa-plus-circle"></i>
                Create Group
            </Link>
        </div>
        <ul class="space-y-3" v-if="recentGroups.length">
            <li v-for="group in recentGroups" :key="group.slug">
                <Link
                    class="flex justify-between items-center border border-slate-200 rounded-lg px-4 py-3 hover:bg-slate-100"
                    :href="`/groups/${group.slug}`"
                >
                    <div>
                        <h3 class="font-medium line-clamp-1">
                            {{ group.name }}
                        </h3>
                        <p class="text-sm text-slate-600">
                            Last visited on
                            {{ formatDate(group.last_visited) }}
                        </p>
                    </div>

                    <i class="fas fa-chevron-right"></i>
                </Link>
            </li>
        </ul>

        <div v-else class="flex flex-col items-center">
            <p class="text-slate-600">No recent groups found.</p>
            <Link href="/new" class="text-emerald-600">
                <button
                    class="bg-emerald-600 text-white px-4 py-2 rounded-lg mt-4 hover:bg-emerald-700 focus:outline-none focus:ring-0"
                >
                    Create a new group
                </button>
            </Link>
        </div>
    </div>
</template>
