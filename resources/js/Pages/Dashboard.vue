<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="flex items-end font-semibold leading-tight text-gray-800 md:text-xl">Welcome, <span class="ml-2 text-sm">{{$page.props.auth.user.username}}</span> </h2>
        </template>

        <div class="py-4 lg:py-8">

            <div class="mx-auto mb-4 w-[95%] max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white rounded-lg shadow-sm ">
                    <h2 class="p-6">What's goin on, then?</h2>

                    <div class="p-6 mb-4 ">
                        <h3 class="text-lg">Meals</h3>

                        <div v-for="entry of entries" :key="entry.id" class="w-full text-xs border-b dark:border-neutral-500">
                            <div class="flex flex-wrap items-center w-full py-4 md:py-0 md:flex-row" v-if="entry.entry_type == 'Meal' || entry.entry_type == 'Recipe'">
                                <div class="w-full md:py-4 md:w-1/6 md:w-2/12">
                                    <span :class="entry.in_past ? 'text-red-600 font-bold' : '' ">{{ entry.date_time_formatted }}</span> 
                                </div>
                                <div class="w-full italic md:py-4 md:w-1/6 md:w-2/12">{{ entry.date_time_human }} </div>
                                <div class="w-full my-4 font-bold md:py-4 md:w-1/3 md:w-5/12 md:my-0">
                                    <span class="capitalize">{{ entry.meal ? entry.meal.name : entry.recipe.name }}</span>
                                </div>
                                <div class="flex items-center gap-1 md:py-4 md:w-1/4 md:w-3/12">
                                    <div>
                                        <span class="px-2 py-1 mx-1 transition duration-200 bg-green-100 border border-green-200 rounded-full cursor-pointer select-none hover:bg-green-200"
                                            v-on:click="data.handleComplete = entry.id; data.handlingLeftovers = 0;"
                                        >
                                            Finished
                                        </span>
                                    </div>
                                    <div>
                                        <span class="px-2 py-1 mx-1 transition duration-200 bg-yellow-200 border border-yellow-300 rounded-full cursor-pointer select-none hover:bg-yellow-300"
                                            v-on:click="data.handlingLeftovers = entry.id; data.handleComplete = 0"
                                        >
                                            Leftovers?
                                        </span>
                                    </div>
                                    <div class="px-2 py-1 text-lg cursor-pointer" v-if="data.handlingLeftovers == entry.id || data.handleComplete == entry.id" 
                                        v-on:click="data.handlingLeftovers = 0; data.handleComplete = 0;">
                                        x
                                    </div>
                                </div>
                            </div>
                            <div v-if="data.handlingLeftovers == entry.id">
                                <div class="p-2 my-2" colspan="4">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                        <span>Enter leftovers portions</span>
                                        <TextInput class="p-2 text-xs border border-gray-300" v-model="data.leftovers.qty" />
                                        <SelectInput class="text-xs" v-model="data.leftovers.location">
                                            <option value="0">-- Select location --</option>
                                            <option v-for="location of locations" :key="location.id" :value="location.id">{{location.name}}</option>
                                        </SelectInput>
                                        <button class="px-3 py-2 text-white bg-green-500 border border-green-600 rounded-lg hover:bg-green-600" 
                                            v-on:click="handleLeftovers(entry)">
                                            Save
                                        </button>   
                                    </div>
                                </div>
                            </div>
                            <div v-if="data.handleComplete == entry.id">
                                <div class="p-2">
                                    Remove from inventory: 

                                    <div class="flex flex-wrap my-2 gap-x-2">
                                        <label v-for="item of entry.meal != null ? entry.meal.ingredients : entry.recipe.ingredients" :key="item.id" class="w-full p-1 py-2 mx-2 cursor-pointer md:w-1/4 hover:bg-gray-200">
                                            <input type="checkbox" v-model="data.itemsToRemove" :id="item.id" :value="item.id" class="mr-1">
                                            {{ item.name }}
                                        </label>

                                    </div>
                                    <button class="px-3 py-2 mt-2 text-white bg-green-500 border border-green-600 rounded-lg hover:bg-green-600"
                                        v-on:click="completeMeal(entry);"
                                    >Remove Items &amp; Complete</button>   
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="p-6">
                        <h3 class="text-lg">Workouts</h3>
                        
                         <div v-for="entry of entries" :key="entry.id" class="w-full text-xs border-b dark:border-neutral-500">
                            <div class="flex flex-wrap w-full py-4 md:py-0 md:flex-row" v-if="entry.entry_type == 'Workout' || entry.entry_type == 'Custom Workout'">
                                <div class="w-full md:w-1/4 md:py-4">
                                    <span :class="entry.in_past ? 'text-red-600 font-bold' : '' ">{{ entry.date_time_formatted }}</span>
                                </div>
                                <div class="w-full italic md:w-1/4 md:py-4">{{ entry.date_time_human }} </div>
                                <div class="w-full my-4 font-bold md:w-1/4 md:py-4 md:my-0">
                                    <span class="capitalize">{{ entry.workout ? entry.workout.name : entry.custom_workout.name }}</span>
                                </div>
                                <div class="flex items-center gap-1 md:w-1/4 md:py-4">
                                    <span class="px-2 py-1 bg-green-100 border border-green-200 rounded-full select-none">Completed?</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="p-6">
                        <h3 class="mb-2 text-lg md:mb-4">Shopping List</h3>

                        <div class="flex flex-wrap capitalize">
                            <div class="w-full sm:w-1/2 lg:w-1/3" v-for="item of shopping_list" :key="item.id">
                                {{ item.name }}
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
import { reactive, watch, ref } from "vue";
import { router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';

const props = defineProps({
    entries: Object,
    locations: Object,
    shopping_list: Object,
});

let data = reactive({
    handlingLeftovers: 0,
    handleComplete: 0,
    itemsToRemove: [],
    leftovers: {
        location: "0",
        entry: null,
        qty: "0"
    }
});

let handleLeftovers = (entry) => {
    data.leftovers.entry = entry; 
    router.post(`/dashboard/leftovers`, data.leftovers, {
        preserveState: true
    });
    
};

let completeMeal = (entry) => {
    

    router.post(`/dashboard/${entry.id}/complete`, { 
            removeItems: data.itemsToRemove
        }, {
        preserveState: true
    });
};
</script>