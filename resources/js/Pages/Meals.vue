<template>
    <Head title="Meals" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Meals
            </h2>
        </template>


        <div class="p-4 sm:p-6 lg:p-8 xl:p-12">


            <form @submit.prevent="addMeal" class="flex items-center gap-2 my-5 ">
                <label class="block w-auto" for="">New Meal</label>
                <div class="flex w-full gap-2 lg:w-1/2">
                    <input class="w-full text-sm border-gray-300 rounded-lg sm:w-full lg:w-10/12" placeholder="Enter a meal name" type="text" v-model="newMeal.name">
                    <input class="w-full text-sm border-gray-300 rounded-lg sm:w-full lg:w-10/12" placeholder="Servings" type="text" v-model="newMeal.servings">
                    <button class="px-4 py-1 text-sm text-white bg-green-500 rounded-lg hover:bg-green-600">Add</button>
                </div>
            </form>

            <div class="mx-auto max-w-7xl">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-xl font-bold">Your meals</h2>

                        <div class="flex flex-wrap gap-2 my-2">
                            <div v-for="meal of meals" :key="meal.id">
                                <Link class="px-4 py-1 text-xs text-blue-700 transition duration-200 rounded-lg bg-blue-50 hover:bg-blue-100" :href="route('meal-info', meal.id)">
                                    {{meal.name}}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, router } from '@inertiajs/vue3';
import { reactive, ref, watch } from "vue";


    defineProps({
        'meals': Object
    });

    let newMeal = useForm({
        name: '',
        servings: ''
    });

    let addMeal = () => {
        newMeal.post('/meals')
        newMeal.name = '';
    }
</script>
