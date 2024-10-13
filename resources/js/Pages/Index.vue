<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

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
        <h2 class="text-lg font-semibold mb-3">Recent Groups</h2>
        <ul class="space-y-3" v-if="recentGroups.length">
            <li v-for="group in recentGroups" :key="group.slug">
                <Link
                    class="flex justify-between items-center border border-slate-200 rounded-lg px-4 py-3 hover:bg-slate-100"
                    :href="`/groups/${group.slug}`"
                >
                    <div>
                        <h3 class="font-medium">{{ group.name }}</h3>
                        <p class="text-sm text-gray-500">
                            Last visited on
                            {{
                                new Date(group.last_visited).toLocaleString(
                                    "en-US",
                                    {
                                        month: "short",
                                        day: "numeric",
                                        year: "numeric",
                                        weekday: "short",
                                        hour: "numeric",
                                        minute: "numeric",
                                    }
                                )
                            }}
                        </p>
                    </div>

                    <i class="fas fa-chevron-right"></i>
                </Link>
            </li>
        </ul>

        <div v-else class="flex flex-col items-center">
            <p class="text-gray-500">No recent groups found.</p>
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
