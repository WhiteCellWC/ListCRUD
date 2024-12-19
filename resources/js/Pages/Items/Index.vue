<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const items = ref([]);
const pagination = ref({
    currentPage: 1,
    lastPage: 1,
    links: []
});
const loading = ref(false);
const error = ref(null);
const params = ref({
    page: pagination.value.currentPage,
    sortBy: 'id',
    sortOrder: 'asc',
    show: 10
});

async function fetchItems() {
    loading.value = true;
    error.value = null;
    try {
        const response = await axios.get('/item-list', {
            params: params.value,
        });
        items.value = response.data.data;
        pagination.value.links = response.data.links;
        pagination.value.currentPage = response.data.current_page;
        pagination.value.lastPage = response.data.last_page;
        pagination.value.total = response.data.total;
    } catch (err) {
        error.value = err.response?.data?.message || 'An error occurred.';
        console.error(err);
    } finally {
        loading.value = false;
    }
}

const paginationLinks = computed(() => {
    const links = [];
    const { currentPage, lastPage } = pagination.value;

    if (currentPage > 1) {
        links.push({ label: '«', page: currentPage - 1 });
    }

    if (currentPage > 3) {
        links.push({ label: '1', page: 1 });
        if (currentPage > 4) {
            links.push({ label: '...', page: null });
        }
    }

    for (let i = Math.max(1, currentPage - 2); i <= Math.min(lastPage, currentPage + 2); i++) {
        links.push({ label: i.toString(), page: i });
    }

    if (currentPage < lastPage - 2) {
        if (currentPage < lastPage - 3) {
            links.push({ label: '...', page: null });
        }
        links.push({ label: lastPage.toString(), page: lastPage });
    }

    if (currentPage < lastPage) {
        links.push({ label: '»', page: currentPage + 1 });
    }

    return links;
});

async function goToPage(page) {
    if (page && page !== pagination.value.currentPage) {
        params.value.page = page;
        await fetchItems();
    }
}

async function sortBy(column) {
    const currentSortBy = params.value.sortBy;
    const currentSortOrder = params.value.sortOrder;

    const sortOrder =
        currentSortBy === column
            ? currentSortOrder === "asc"
                ? "desc"
                : "asc"
            : "asc";

    params.value = {
        ...params.value,
        sortBy: column,
        sortOrder: sortOrder,
    };

    await fetchItems();
}

async function deleteItem($id) {
    if (confirm('Are you sure you want to delete this Item? This action cannot be undone')) {
        try {
            const response = await axios.delete(route('items.destroy', $id));
        } catch (err) {
        } finally {
            await fetchItems();
        }
    }
}

async function changeShowRow(e) {
    params.value.show = e.target.value;
    await fetchItems();
}

async function togglePublish(item) {
    try {
        const response = await axios.post(`/items/${item.id}/toggle-publish`);
        item.is_published = response.data.is_published;
    } catch (error) {
        console.error('Failed to toggle publish state:', error);
        alert('Failed to update the publish state.');
    }
}

const showingEntries = computed(() => {
    const start = (pagination.value.currentPage - 1) * params.value.show + 1;
    const end = Math.min(pagination.value.currentPage * params.value.show, pagination.value.total);
    return `Showing ${start} to ${end} of ${pagination.value.total} entries`;
});

fetchItems();

</script>

<template>
    <AppLayout title="Item">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Item List
            </h2>
        </template>

        <div class="flex items-center justify-between mb-6 w-[90%] mx-auto mt-10">
            <select @change="changeShowRow" class="bg-white border-0 rounded-md shadow">
                <option value="10">Show 10</option>
                <option value="20">Show 20</option>
                <option value="30">Show 30</option>
                <option value="40">Show 40</option>
                <option value="50">Show 50</option>
            </select>
            <Link :href="route('items.create')" class="bg-indigo-600 p-2 rounded-md shadow text-white">Create New item +
            </Link>
        </div>
        <div class="overflow-x-auto shadow-sm sm:rounded-lg w-[90%] mx-auto mt-5">
            <table class="min-w-full text-sm text-gray-500">
                <thead class="bg-indigo-600">
                    <tr class="text-white">
                        <th class="p-3 text-left">
                            Action
                        </th>
                        <th class="p-3 text-left cursor-pointer" @click="sortBy('id')">
                            No
                        </th>
                        <th class="p-3 text-left cursor-pointer" @click="sortBy('name')">
                            Item
                        </th>
                        <th class="p-3 text-left">
                            Category
                        </th>
                        <th class="p-3 text-left">
                            Description
                        </th>
                        <th class="p-3 text-left cursor-pointer" @click="sortBy('price')">
                            Price
                        </th>
                        <th class="p-3 text-left cursor-pointer" @click="sortBy('owner')">
                            Owner
                        </th>
                        <th class="p-3 text-left cursor-pointer" @click="sortBy('is_published')">
                            Publish
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items" :key="item.id" class="bg-white border-b">
                        <td class="p-3">
                            <div class="flex items-center gap-2">
                                <Link :href="route('items.edit', item.id)">
                                <font-awesome-icon class="text-blue-500 text-[1.1rem] cursor-pointer"
                                    :icon="['fas', 'pen-to-square']" />
                                </Link>
                                <div @click="deleteItem(item.id)">
                                    <font-awesome-icon class="text-red-500 text-[1.1rem] cursor-pointer"
                                        :icon="['fas', 'trash']" />
                                </div>
                            </div>
                        </td>
                        <td class="p-3">{{ item.id }}</td>
                        <td class="p-3">{{ item.name }}</td>
                        <td class="p-3">{{ item.category.name }}</td>
                        <td class="p-3" v-html="item.description"></td>
                        <td class="p-3">$ {{ item.price }}</td>
                        <td class="p-3">{{ item.owner_name }}</td>
                        <td class="p-3">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer -z-20" :checked="item.is_published === 1"
                                    @change="togglePublish(item)" />
                                <div
                                    class="w-10 px-1 h-6 bg-gray-200 rounded-full peer-checked:bg-indigo-600 peer-checked:before:translate-x-4 before:content-[''] before:translate-y-1 before:block before:w-4 before:h-4 before:rounded-full before:bg-white before:transition-transform">
                                </div>
                            </label>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex w-[90%] justify-between mx-auto mt-2 pb-10">
            <div class="text-gray-600 text-sm mt-2">
                {{ showingEntries }}
            </div>
            <div class="flex">
                <button v-for="link in paginationLinks" :key="link.label" @click="goToPage(link.page)" :class="[
                    'px-3 py-1 mx-1 rounded',
                    link.page === pagination.currentPage ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-600',
                    !link.page && 'cursor-default opacity-50'
                ]" :disabled="!link.page">
                    {{ link.label }}
                </button>
            </div>
        </div>
    </AppLayout>
</template>
