<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="flex items-end text-xl font-semibold leading-tight text-gray-800">Welcome, <span class="ml-2 text-sm">{{$page.props.auth.user.username}}</span> </h2>
        </template>

        <div class="py-12">

            <div class="mx-auto mb-4 max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white rounded-lg shadow-sm ">
                    <h2 class="p-6">What's goin on, then?</h2>

                    <div class="p-6 mb-4 ">
                        <h3 class="text-lg">Meals</h3>

                        <table v-for="entry of entries" :key="entry.id" class="text-xs border-b dark:border-neutral-500" >
                            <tr v-if="entry.entry_type == 'Meal' || entry.entry_type == 'Recipe'">
                                <td class="w-40 px-2 py-4">
                                    <span :class="entry.in_past ? 'px-2 py-1 text-red-600 font-bold' : 'px-2 py-1' ">{{ entry.date_time_formatted }}</span> 
                                </td>
                                <td class="px-2 py-4 w-28">{{ entry.date_time_human }} </td>
                                <td class="px-2 py-4 font-bold w-60">
                                    <span class="capitalize">{{ entry.meal ? entry.meal.name : entry.recipe.name }}</span>
                                </td>
                                <td class="px-2 py-4">
                                    <span class="px-2 py-1 mx-1 transition duration-200 bg-green-100 border border-green-200 rounded-full cursor-pointer hover:bg-green-200"
                                        v-on:click="data.handleComplete = entry.id; data.handleLeftovers = 0;"
                                    >
                                        Finished
                                    </span>
                                    <span class="px-2 py-1 mx-1 transition duration-200 bg-yellow-200 border border-yellow-300 rounded-full cursor-pointer hover:bg-yellow-300"
                                        v-on:click="data.handlingLeftovers = entry.id; data.handleComplete = 0"
                                    >
                                        Leftovers?
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="data.handlingLeftovers == entry.id">
                                <td class="p-2 my-2" colspan="4">
                                    <div class="flex items-center gap-2">
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
                                </td>
                            </tr>
                            <tr v-if="data.handleComplete == entry.id">
                                <td class="p-2" colspan="4">
                                    Remove from inventory: 

                                    <div class="flex flex-wrap my-2 gap-x-2">
                                        <label v-for="item of entry.meal != null ? entry.meal.ingredients : entry.recipe.ingredients" :key="item.id" class="w-1/4 p-1 py-2 mx-2 cursor-pointer hover:bg-gray-200">
                                            <input type="checkbox" v-model="data.itemsToRemove" :id="item.id" :value="item.id" class="mr-1">
                                            {{ item.name }}
                                        </label>

                                    </div>
                                    <button class="px-3 py-2 mt-2 text-white bg-green-500 border border-green-600 rounded-lg hover:bg-green-600"
                                        v-on:click="completeMeal(entry);"
                                    >Remove Items &amp; Complete</button>   
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div class="p-6">
                        <h3 class="text-lg">Workouts</h3>
                        
                         <table v-for="entry of entries" :key="entry.id" class="text-xs border-b dark:border-neutral-500" >
                            <tr v-if="entry.entry_type == 'Workout' || entry.entry_type == 'Custom Workout'">
                                <td class="w-40 px-2 py-4">
                                    <span :class="entry.in_past ? 'px-2 py-1 text-red-600 font-bold' : 'px-2 py-1' ">{{ entry.date_time_formatted }}</span>
                                </td>
                                <td class="px-2 py-4 w-28">{{ entry.date_time_human }} </td>
                                <td class="px-2 py-4 font-bold w-60">
                                    <span class="capitalize">{{ entry.workout ? entry.workout.name : entry.custom_workout.name }}</span>
                                </td>
                                <td class="px-2 py-4">
                                    <span class="px-2 py-1 bg-green-100 border border-green-200 rounded-full">Completed?</span>
                                </td>
                            </tr>
                        </table>

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
    router.post(`/entry/leftovers`, data.leftovers, {
        preserveState: true
    });
    
};

let completeMeal = (entry) => {
    

    router.post(`/entry/${entry.id}/complete`, { 
            removeItems: data.itemsToRemove
        }, {
        preserveState: true
    });
};
</script>