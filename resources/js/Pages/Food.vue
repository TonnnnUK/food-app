<template>
    <Head title="Food" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Food
            </h2>
        </template>


        <div class="p-4 sm:p-6 lg:p-8 xl:p-12">


            <form @submit.prevent="addItem" class="flex items-center gap-2 my-5 ">
                <label class="block w-auto text-xs" for="">New Item</label>
                <div class="flex w-full gap-2 lg:w-1/2">
                    <input class="w-full text-sm border-gray-300 rounded-lg sm:w-full lg:w-10/12" placeholder="Enter an ingredient" type="text" v-model="newItem.name">
                    <select class="w-full text-sm border-gray-300 rounded-lg sm:w-full lg:w-10/12" v-model="newItem.food_type_id">
                        <option value="0">-- select food type --</option>
                        <option v-for="type of foodtypes" :key="type.id" :value="type.id">{{type.name}}</option>
                    </select>
                    <button class="px-4 py-1 text-sm text-white bg-green-500 rounded-lg hover:bg-green-600">Add</button>
                </div>
            </form>

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center gap-2 mb-4">
                        <h2 class="text-xl font-bold ">All Food Items</h2>
                        <span class="text-xs">{{fooditems.length}} items</span>
                    </div>

                    <div class="flex flex-wrap">
                        <span v-for="item of fooditems" :key="item.id" class="w-full mb-2 md:w-1/2 lg:w-1/4">{{item.name}}</span>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, router } from '@inertiajs/vue3';

    defineProps({
        'fooditems': Object,
        'foodtypes': Object
    });

    let newItem = useForm({
        name: '',
        food_type_id: '0'
    });

    let addItem = () => {
        newItem.post('/food')
        newItem.name = '';
        newItem.food_type_id = '0';
    }
</script>
