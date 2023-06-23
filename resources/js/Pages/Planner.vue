<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Meal &amp; Workout Planner
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        This is your planner
                    </div>
                </div>
            </div>
            <div class="mx-auto my-2 max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg">Your meals</h3>
                        
                        <div class="flex flex-col w-full xl:w-9/12">
                            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                <div class="overflow-hidden">
                                    <table class="min-w-full text-sm font-light text-left">
                                    <thead class="font-medium border-b dark:border-neutral-500">
                                        <tr>
                                            <th scope="col" class="px-6 py-4">Meal</th>
                                            <th scope="col" class="px-6 py-4">Ingredients</th>
                                            <th scope="col" class="px-6 py-4">Matching</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="border-b dark:border-neutral-500" v-for="meal of meals" :key="meal.id">
                                            <td class="px-6 py-4 font-medium whitespace-nowrap">
                                                <span class="capitalize">{{ meal.name }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ meal.ingredient_count }} items
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ meal.inventory_match }} {{ meal.inventory_match > 1 ? 'items' : 'item' }} in your inventory ({{ meal.match_percent ? meal.match_percent : 0 }}%)
                                                
                                                <div class="inline">
                                                    <span v-if="meal.match_percent > 90" class="ml-2 text-green-300"><i class="fas fa-check"></i></span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </div>
                                </div>
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
    import { reactive, watch, ref } from "vue";

    defineProps({
        'meals': Object
    });

</script>