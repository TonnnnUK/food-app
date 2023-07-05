<template>
    <Head title="Meals" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold leading-tight text-gray-800 md:text-xl">
                Meals
            </h2>
        </template>


        <div class="p-4 sm:p-6 lg:p-8 xl:p-12">


            <form @submit.prevent="addMeal" class="flex flex-col gap-2 my-2 md:my-5 md:flex-row md:flex-wrap">
                <label class="block w-auto" for="">New Meal</label>
                <div class="flex flex-col w-full gap-y-2 md:gap-2 md:flex-row lg:w-auto">
                    <div class="w-[95%]">
                        <input class="w-[95%] text-sm border-gray-300 rounded-lg" placeholder="Enter a meal name" type="text" v-model="newMeal.name">
                    </div>
                    <div class="w-1/3">
                        <input class="w-[95%] text-sm border-gray-300 rounded-lg" placeholder="Servings" type="text" v-model="newMeal.servings">
                    </div>
                    <div class="w-full ">
                        <button class="px-4 py-1 text-lg text-white bg-green-500 rounded-lg md:text-sm hover:bg-green-600">Add</button>
                    </div>
                </div>
            </form>

            <div class="mx-auto mt-4 max-w-7xl">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-4 bg-white border-b border-gray-200 md:p-6">
                        <h2 class="text-xl font-bold">Your meals</h2>

                        <div class="flex flex-wrap my-4 gap-x-2 gap-y-2 md:my-2">
                            <Link v-for="meal of meals" :key="meal.id" 
                                class="px-4 py-2 text-blue-800 transition duration-200 rounded-lg md:text-xs bg-blue-50 hover:bg-blue-100" :href="route('meal-info', meal.id)">
                                {{meal.name}}
                            </Link>
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
