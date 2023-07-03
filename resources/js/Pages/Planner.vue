<template>
    <Head title="Planner" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Meal &amp; Workout Planner
            </h2>
        </template>

        <div class="px-4 py-4">

            <div class="mx-auto my-4 max-w-7xl">
                <div class="flex gap-3">
                    <PlannerTab 
                        v-for="tab of data.tabs" :key="tab"
                        :class="data.selectedContent == tab ? 'bg-purple-300 font-bold' : 'bg-white hover:bg-purple-300'"
                        v-on:click="changeTab(tab)"
                        :item="tab"
                    />
                </div>
            </div>

            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8" v-if="data.selectedContent == 'calendar'">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg">Your Planner</h3>
                            </div>
                            <div class="flex text-xs border rounded-lg">
                                <div class="px-1 py-2 transition duration-200 bg-gray-200 rounded-l-lg hover:cursor-pointer hover:bg-gray-300"
                                    @click="selectMonth(data.selectedMonth-1)"
                                >
                                    &lt;
                                </div>
                                <div class="px-2 py-2">
                                    {{ Calendar.months[data.selectedMonth] }}
                                </div>
                                <div class="px-1 py-2 transition duration-200 bg-gray-200 rounded-r-lg hover:cursor-pointer hover:bg-gray-300"
                                    @click="selectMonth(data.selectedMonth+1)"
                                >
                                    &gt;
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap">
                            <div class="hidden lg:block w-[14%] inline-block capitalize border text-xs p-2 bg-gray-100 text-center font-bold" v-for="dayOfWeek of Calendar.days" :key="dayOfWeek">
                                {{dayOfWeek}}
                            </div>
                        </div>
                        <div class="flex flex-col flex-wrap sm:flex-row">
                            <div v-for="day of data.fullCalendar" :key="day" class="w-full sm:w-1/2 md:w-1/4 lg:w-[14%] inline-block capitalize border text-xs p-2 flex flex-col group">
                                <span class="mt-1 mb-1" :class="Calendar.datesAreEqual(day, dt) ? 'font-bold' : '' ">
                                    <span :class="Calendar.dateIsBefore(day, dt) ? 'text-gray-300' : '' ">
                                        <span class="lg:hidden">{{ Calendar.days[day.getDay()] }}</span>
                                        {{ day.getDate() }}
                                        <span class="lg:hidden">{{ Calendar.months[day.getMonth()] }}</span>
                                        <span class="px-2 py-1 ml-2 bg-gray-200 rounded-lg" v-if="Calendar.datesAreEqual(day, dt)">Today</span>
                                    </span>
                                </span>

                                <div class="my-2">
                                    <span v-for="entry of entries" :key="entry.id">
                                        <span class="" v-if="matchingDate(day, entry) && entry.id != data.deleteEntryId" v-on:click="data.deleteEntryId = entry.id"> 
                                            <small class="block px-3 py-1 mb-1 text-xs transition duration-200 bg-red-100 border border-red-200 rounded-lg hover:cursor-pointer hover:bg-red-200" v-if="entry.meal">
                                                {{ entry.meal.name }} 
                                            </small>
                                            <small class="block px-3 py-1 mb-1 text-xs transition duration-200 bg-yellow-100 border border-yellow-200 rounded-lg hover:cursor-pointer hover:bg-yellow-200" v-if="entry.recipe">
                                                {{ entry.recipe.name }} 
                                            </small>
                                            <small class="block px-3 py-1 mb-1 text-xs transition duration-200 bg-blue-100 border border-blue-200 rounded-lg hover:cursor-pointer hover:bg-blue-200" v-if="entry.workout">
                                                {{ entry.workout.name }} 
                                            </small>
                                            <small class="block px-3 py-1 mb-1 text-xs transition duration-200 bg-purple-100 border border-purple-200 rounded-lg hover:cursor-pointer hover:bg-purple-200" v-if="entry.custom_workout">
                                                {{ entry.custom_workout.name }} 
                                            </small>
                                            <small class="block px-3 py-1 mb-1 text-xs transition duration-200 bg-gray-100 border border-gray-200 rounded-lg hover:cursor-pointer hover:bg-gray-200" v-if="entry.entry_type == 'Note'">
                                                {{ entry.entry_data.notes }} 
                                            </small>
                                        </span>
                                        <span class="flex justify-between px-3 py-1 mb-1 text-xs transition duration-200 bg-gray-200 rounded-lg hover:bg-gray-300" 
                                            v-if="matchingDate(day, entry) && data.deleteEntryId == entry.id">
                                            <span class="py-1 lowercase cursor-pointer hover:underline" v-on:click="deleteEntry(entry)">Click to remove entry</span>
                                            <span class="p-1 lowercase cursor-pointer" v-on:click="data.deleteEntryId = 0">x</span>
                                        </span>
                                    </span>
                                </div>

                                <div class="flex items-center justify-center transition duration-200 opacity-0 grow group-hover:opacity-100" 
                                    
                                >
                                    <span class="self-center px-3 py-2 text-white transition duration-200 bg-gray-600 rounded-lg hover:cursor-pointer hover:bg-gray-800"
                                        @click="addAnEntry(day)" 
                                        v-if="Calendar.dateIsBeforeOrEqual(day, dt)">
                                        +
                                    </span>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="mx-auto my-2 max-w-7xl sm:px-6 lg:px-8" v-if="data.selectedContent == 'meals'">
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
                                            <th scope="col" class="px-6 py-4">Missing</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="border-b dark:border-neutral-500" v-for="meal of meals" :key="meal.id">
                                            <td class="px-6 py-4 font-medium whitespace-nowrap">
                                                <span class="capitalize">{{ meal.name }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ meal.inventory_match }} out of {{ meal.ingredient_count }} items in your inventory ({{ meal.match_percent ? meal.match_percent : 0 }}%) 
                                                <div class="inline">
                                                    <span v-if="meal.match_percent > 90" class="ml-2 text-green-300"><i class="fas fa-check"></i></span>
                                                </div>
                                            </td>
                                            <td class="flex gap-2" colspan="3" v-if="Object.keys(meal.missingItems).length > 0">
                                                <div>
                                                    <span class="inline-block px-2 py-1 my-1 ml-2 text-xs transition duration-100 delay-100 bg-gray-200 rounded-full cursor-pointer group hover:bg-green-100" v-for="item of meal.missingItems" :key="item.key">
                                                        {{ item.name}}

                                                        <span class="absolute inline-block ml-2 transition duration-200 delay-200 opacity-0 group-hover:relative group-hover:opacity-100 hover:underline hover:text-red-600">
                                                            Add to list
                                                        </span> 
                                                    </span>
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

            <div class="mx-auto my-2 max-w-7xl sm:px-6 lg:px-8" v-if="data.selectedContent == 'workouts'">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="mb-4 text-lg">Your Workouts</h3>

                        <div class="flex flex-wrap mb-8">
                            <div v-for="workout of custom_workouts" :key="workout.id" class="w-1/2 text-sm">
                                {{ workout.name }}
                            </div>
                        </div>

                        <h3 class="mb-4 text-lg">Other Workouts</h3>
                        <div class="flex flex-wrap justify-center">
                            <div v-for="workout of workouts" :key="workout.id" class="w-1/2 text-sm">
                                {{ workout.name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <Modal :show="data.addingEntry" @close="closeModal">
                <PlannerEntryForm :entry="data.entryData" @addedEntry="resetEntryForm()"></PlannerEntryForm>
            </Modal>


        </div>
    </AuthenticatedLayout>
</template>


<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { useForm, router } from '@inertiajs/vue3';
    import { reactive, watch, ref } from "vue";
    import { CalendarService } from '../Services/CalendarService';
    import PlannerTab from '@/Components/PlannerTab.vue'
    import Modal from '@/Components/Modal.vue';
    import PlannerEntryForm from '@/Components/PlannerEntryForm.vue';

    const props = defineProps({
        'recipes': Object,
        'meals': Object,
        'workouts': Object,
        'custom_workouts': Object,
        'entries': Object
    });

    let data = reactive({
        selectedContent: 'calendar',
        selectedMonth: '',
        tabs: ['calendar', 'meals', 'workouts'],
        itemForm: {
            open: false,
        },
        showLocation: {
            display: 0
        },
        addTo: 0,
        fullCalendar: [],
        addingEntry: false,
        entryData: {
            recipe_id: 0,
            meal_id: 0,
            workout_id: 0,
            custom_workout_id: 0,
            date_time: 0,
            entry_type: 0,
            entry_data: {
                name: '',
                notes: '',
                completed: false
            },
            options: {
                recipes: props.recipes,
                meals: props.meals,
                workouts: props.workouts,
                custom_workouts: props.custom_workouts,
            },
            selected: {
                day: 0,
                month: 0,
                year: 0
            }
        },
        deleteEntryId: 0
    });

    let changeTab = (tab) => {
        data.selectedContent = tab;
    }

    let dt = new Date();
    let today = dt.getDate();
    let thisMonth = dt.getMonth();

    data.selectedMonth = thisMonth;

    const Calendar = new CalendarService();
    data.fullCalendar = Calendar.generateCalendar(thisMonth);

    const selectMonth = (month) => {
        console.log('selecting month', month);
        data.selectedMonth = month;
        data.fullCalendar = Calendar.generateCalendar(month);

        router.get('/planner', { 
            month: month+1, 
        }, {
            preserveState: true
        });

    };

    const addAnEntry = (date) => {
        data.addingEntry = true;
        data.entryData.date_time = date;
        data.entryData.selected.day = date.getDate();
        data.entryData.selected.month = Calendar.twoDigitMonth(date);
        data.entryData.selected.year = date.getFullYear();

    }

    const closeModal = () => {
        data.addingEntry = false;
    };

    const matchingDate = (day, entry) => {
 
        let format = `${day.getFullYear()}-${Calendar.twoDigitMonth(day)}-${Calendar.twoDigitDay(day)}`;
        
        if( format == entry.date_time ){
            return true;
        } else {
            return false;
        }

    };

    const resetEntryForm = () => {
        data.entryData.recipe_id = 0;
        data.entryData.meal_id = 0;
        data.entryData.workout_id = 0;
        data.entryData.custom_workout_id = 0;
        data.entryData.date_time = 0;
        data.entryData.entry_type = 0;
        data.entryData.entry_data = {                
            name: '',
            notes: '',
        };

        data.addingEntry = false;
    };

    
    let deleteEntry = ( entry ) => {

        console.log('deleting entry', entry);
        
        router.delete(`/entry/${entry.id}`, {
            preserveState: true
        });

        
    }

</script>