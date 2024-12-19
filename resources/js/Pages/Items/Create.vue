<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { computed, ref, onMounted } from 'vue';
import { Ckeditor } from '@ckeditor/ckeditor5-vue';
import {
    ClassicEditor,
    Autosave,
    Bold,
    Code,
    Essentials,
    FontBackgroundColor,
    FontColor,
    FontFamily,
    FontSize,
    Highlight,
    Italic,
    Paragraph,
    RemoveFormat,
    SpecialCharacters,
    Strikethrough,
    Subscript,
    Superscript,
    Underline
} from 'ckeditor5';
import 'ckeditor5/ckeditor5.css';
import MapPicker from '@/Components/Map.vue';

const LICENSE_KEY = ''

const isLayoutReady = ref(false);

const editor = ClassicEditor;

const config = computed(() => {
    if (!isLayoutReady.value) {
        return null;
    }

    return {
        toolbar: {
            items: [
                'fontSize',
                'fontFamily',
                'fontColor',
                'fontBackgroundColor',
                '|',
                'bold',
                'italic',
                'underline',
                'strikethrough',
                'subscript',
                'superscript',
                'code',
                'removeFormat',
                '|',
                'specialCharacters',
                'highlight'
            ],
            shouldNotGroupWhenFull: false
        },
        plugins: [
            Autosave,
            Bold,
            Code,
            Essentials,
            FontBackgroundColor,
            FontColor,
            FontFamily,
            FontSize,
            Highlight,
            Italic,
            Paragraph,
            RemoveFormat,
            SpecialCharacters,
            Strikethrough,
            Subscript,
            Superscript,
            Underline
        ],
        fontFamily: {
            supportAllValues: true
        },
        fontSize: {
            options: [10, 12, 14, 'default', 18, 20, 22],
            supportAllValues: true
        },
        licenseKey: LICENSE_KEY,
        placeholder: 'Type or paste your content here!'
    };
});

onMounted(() => {
    isLayoutReady.value = true;
});

const props = defineProps({
    category: Object,
});

const form = useForm({
    name: '',
    category_id: '',
    price: '',
    description: '',
    item_condition: '',
    item_type: '',
    is_published: false,
    image: null,
    owner_name: '',
    contact_number: '',
    address: '',
    location: {
        latitude: 16.78802399927551,
        longitude: 96.15924064343807
    },
    imagePreview: null,
});

const errors = computed(() => form.errors);

const fileInput = ref(null);

function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            form.imagePreview = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function handleDrop(event) {
    event.preventDefault();
    const file = event.dataTransfer.files[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            form.imagePreview = e.target.result; // Set preview URL
        };
        reader.readAsDataURL(file);
    }
}

function handleDragOver(event) {
    event.preventDefault();
}

function triggerFileInput() {
    fileInput.value.click();
}

async function submitForm() {
    form.post(route('items.store'), {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
        onFinish: () => form.reset(),
    });
}
console.log(props.category);

</script>

<template>
    <AppLayout title="Edit Item">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Item List >> Add Item
            </h2>
        </template>

        <form @submit.prevent="submitForm" class="space-y-4 bg-white shadow w-[90%] mx-auto my-10 rounded-2xl p-6">
            <div class="flex gap-5">
                <div class="flex flex-col w-1/2 gap-4">
                    <p class="text-[1.5rem] font-bold">Item Information</p>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium">Name<span
                                class="font-bold text-red-500">*</span></label>
                        <input type="text" id="name" v-model="form.name" class="mt-1 block w-full border rounded-md p-2"
                            placeholder="Enter item name" />
                        <span v-if="errors.name" class="text-red-500">{{ errors.name }}</span>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium">Category<span
                                class="font-bold text-red-500">*</span></label>
                        <select id="category_id" v-model="form.category_id"
                            class="mt-1 block w-full border rounded-md p-2">
                            <option value="" disabled>Select a category</option>
                            <option v-for="category in props.category" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <span v-if="errors.category_id" class="text-red-500">{{ errors.category_id }}</span>
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium">Price<span
                                class="font-bold text-red-500">*</span></label>
                        <input type="text" id="price" v-model="form.price"
                            class="mt-1 block w-full border rounded-md p-2" placeholder="Enter item price" />
                        <span v-if="errors.price" class="text-red-500">{{ errors.price }}</span>
                    </div>

                    <!-- Description -->
                    <label>Description<span class="font-bold text-red-500">*</span></label>
                    <div class="main-container">
                        <div class="editor-container editor-container_classic-editor" ref="editorContainerElement">
                            <div class="editor-container__editor">
                                <div ref="editorElement">
                                    <ckeditor v-if="editor && config" v-model="form.description" :editor="editor"
                                        :config="config" />
                                </div>
                            </div>
                        </div>
                        <span v-if="errors.description" class="text-red-500">{{ errors.description }}</span>
                    </div>

                    <!-- Item Condition -->
                    <div>
                        <label for="item_condition" class="block text-sm font-medium">Item Condition</label>
                        <select id="item_condition" v-model="form.item_condition"
                            class="mt-1 block w-full border rounded-md p-2">
                            <option value="" disabled>Select condition</option>
                            <option value="New">New</option>
                            <option value="Used">Used</option>
                            <option value="Good Second Hand">Good Second Hand</option>
                        </select>
                        <span v-if="errors.item_condition" class="text-red-500">{{ errors.item_condition }}</span>
                    </div>

                    <!-- Item Type -->
                    <div>
                        <label for="item_type" class="block text-sm font-medium">Item Type</label>
                        <select id="item_type" v-model="form.item_type" class="mt-1 block w-full border rounded-md p-2">
                            <option value="" disabled>Select type</option>
                            <option value="Sell">Sell</option>
                            <option value="Buy">Buy</option>
                            <option value="For Exchange">For Exchange</option>
                        </select>
                        <span v-if="errors.item_type" class="text-red-500">{{ errors.item_type }}</span>
                    </div>

                    <!-- Publish Status -->
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" v-model="form.is_published"
                                class="rounded border-gray-300 text-indigo-600" />
                            <span class="ml-2 text-sm">Publish this item</span>
                        </label>
                        <span v-if="errors.is_published" class="text-red-500">{{ errors.is_published }}</span>
                    </div>

                    <!-- Drag-and-drop Image Upload -->
                    <div class="border-2 border-dashed border-indigo-300 p-6 text-center rounded-md cursor-pointer flex justify-center items-center h-[50vh]"
                        @drop="handleDrop" @dragover="handleDragOver" @click="triggerFileInput">
                        {{ console.log(form.imagePreview) }}
                        <template v-if="!form.imagePreview">
                            <p class="text-indigo-300">Drag and drop your image here, or click to select<span
                                    class="font-bold text-red-500">*</span></p>
                        </template>
                        <template v-else>
                            <img :src="form.imagePreview" alt="Preview" class="max-h-full max-w-full object-contain" />
                        </template>
                        <input ref="fileInput" type="file" @change="handleFileUpload" class="hidden" />
                    </div>
                    <span v-if="errors.image" class="text-red-500">{{ errors.image }}</span>
                </div>

                <div class="flex flex-col w-1/2 gap-4">
                    <p class="text-[1.5rem] font-bold">Owner Information</p>

                    <!-- Owner Name -->
                    <div>
                        <label for="owner_name" class="block text-sm font-medium">Owner Name<span
                                class="font-bold text-red-500">*</span></label>
                        <input type="text" id="owner_name" v-model="form.owner_name"
                            class="mt-1 block w-full border rounded-md p-2" placeholder="Enter owner name" />
                        <span v-if="errors.owner_name" class="text-red-500">{{ errors.owner_name }}</span>
                    </div>

                    <!-- Contact Number -->
                    <div>
                        <label for="contact_number" class="block text-sm font-medium">Contact Number</label>
                        <input type="text" id="contact_number" v-model="form.contact_number"
                            class="mt-1 block w-full border rounded-md p-2" placeholder="Enter contact number" />
                        <span v-if="errors.contact_number" class="text-red-500">{{ errors.contact_number }}</span>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium">Address</label>
                        <input type="text" id="address" v-model="form.address"
                            class="mt-1 block w-full border rounded-md p-2" placeholder="Enter address" />
                        <span v-if="errors.address" class="text-red-500">{{ errors.address }}</span>
                    </div>

                    <!-- Display the latitude and longitude from the map picker -->
                    <div class="mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <MapPicker v-model="form.location" />
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-4 items-center justify-end">
                <Link class="border px-4 py-2 rounded-md hover:bg-gray-100" :href="route('items.index')">
                Cancel
                </Link>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Submit
                </button>
            </div>
        </form>
    </AppLayout>
</template>
