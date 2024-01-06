<template>
    <Head title="Planner" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold leading-tight text-gray-800 sm:text-xl">
                Meal &amp; Workout Planner
            </h2>
        </template>

        <div class="p-2 sm:p-4">

            <!-- TABS  -->
            <div class="mx-auto mt-2 mb-2 sm:mt-4 max-w-7xl md:pl-12 md:mb-0">
                <div class="flex flex-wrap gap-2 text-center">
                    <div v-for="tab of data.tabs" :key="tab.label"
                        class="w-[48%] sm:w-auto"
                    >
                        <PlannerTab 
                            class="select-none"
                            :class="data.selectedContent == tab.label ? `${tab.bg} font-bold` : 'bg-white hover:font-bold border border-gray-300 ' + `${tab.bg} hover:${tab.hover}`"
                            v-on:click="changeTab(tab)"
                            :item="tab.label"
                        />
                    </div>
                </div>
            </div>

            <!-- CALENDAR -->
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 " v-if="data.selectedContent == 'calendar'">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-4 md:gap-0">
                            <div class="w-full md:w-auto">
                                <h3 class="text-lg">Your Planner</h3>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mx-2 lg:text-xs">
                                <span class="text-xs md:text-default">Generate Shopping list for</span>
                                <div class="flex items-center gap-2">
                                    <TextInput class="w-12 text-xs" type="text" v-model="data.generateListDays" />
                                    <span>days</span>    
                                </div>    
                                <span class="px-3 py-1 text-sm transition duration-200 bg-green-200 rounded-lg cursor-pointer hover:bg-green-300"
                                    v-if="data.confirmGenerate"
                                    v-on:click="generateList()"
                                >
                                    Confirm
                                </span>
                                <span class="px-3 py-1 text-sm transition duration-200 rounded-lg cursor-pointer"
                                    :class="data.confirmGenerate ? 'text-red-600 hover:underline hover:text-red-800' : 'bg-blue-100 hover:bg-blue-200'"
                                    v-on:click="data.confirmGenerate = !data.confirmGenerate">
                                    <span v-text="data.confirmGenerate ? 'x' : 'Generate'" />
                                </span>
                            </div>
                            <div class="flex items-center gap 2">
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
                                        <span class="lg:hidden">{{ Calendar.days[day.getDay()-1] }}</span>
                                        {{ day.getDate() }}
                                        <span class="lg:hidden">{{ Calendar.months[day.getMonth()] }}</span>
                                        <span class="px-2 py-1 ml-2 bg-gray-200 rounded-lg" v-if="Calendar.datesAreEqual(day, dt)">Today</span>
                                    </span>
                                </span>

                                <div class="my-2">
                                    <span v-for="entry of entries" :key="entry.id">
                                        <span class="" v-if="matchingDate(day, entry) && entry.id != data.deleteEntryId" v-on:click="data.deleteEntryId = entry.id"> 
                                            <small class="inline-block px-3 py-1 mb-1 text-xs transition duration-200 bg-red-100 border border-red-200 rounded-lg hover:cursor-pointer hover:bg-red-200" 
                                                v-if="entry.meal"
                                            >
                                                {{ entry.meal.name }} 
                                            </small>
                                            <small class="inline-block px-3 py-1 mb-1 text-xs transition duration-200 bg-yellow-100 border border-yellow-200 rounded-lg hover:cursor-pointer hover:bg-yellow-200" 
                                                v-if="entry.recipe"
                                            >
                                                {{ entry.recipe.name }} 
                                            </small>
                                            <small class="inline-block px-3 py-1 mb-1 text-xs transition duration-200 bg-blue-100 border border-blue-200 rounded-lg hover:cursor-pointer hover:bg-blue-200" 
                                                v-if="entry.workout"
                                            >
                                                {{ entry.workout.name }} 
                                            </small>
                                            <small class="inline-block px-3 py-1 mb-1 text-xs transition duration-200 bg-green-100 border border-green-200 rounded-lg hover:cursor-pointer hover:bg-green-200" 
                                                v-if="entry.custom_workout"
                                            >
                                                {{ entry.custom_workout.name }} 
                                            </small>
                                            <small class="inline-block px-3 py-1 mb-1 text-xs transition duration-200 bg-gray-100 border border-gray-200 rounded-lg hover:cursor-pointer hover:bg-gray-200" 
                                                v-if="entry.entry_type 
                                            == 'Note'">
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

            <!-- MEALS -->
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 " v-if="data.selectedContent == 'meals'">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between my-2">
                            <h3 class="text-lg">Your meals</h3>
                            <SmallButton class="bg-gray-300 hover:bg-gray-400 focus:ring-gray-400"
                                v-on:click="toggleFilters()"    
                            >
                                {{ filters.show ? 'x' : 'Filters' }}
                            </SmallButton>
                        </div>

                        <div class="flex flex-col my-2 md:my-4" v-if="filters.show">
                            <span class="my-2 font-bold md:text-xs lg:my-4">Filter by</span>

                            <div class="flex items-center gap-2 mb-2">
                                <label class="text-xs">Tags:</label>
                                <div class="flex flex-wrap gap-2">
                                    <TagButton v-for="tag of tags" :key="tag.id"
                                        v-on:click="selectTag(tag.id)"
                                    >
                                        {{ tag.tag_name }}
                                    </TagButton>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="text-xs">Ingredient:</label>
                                 <div class="flex flex-wrap gap-2">
                                    <TagButton v-for="(tag, index) of food_item_tags" :key="tag"
                                        :class="filters.selectedFoodItem == index ? 'bg-orange-100 text-orange-600 border-orange-300 hover:bg-orange-100 hover:border-orange-300 ring-orange-300' : ''"  
                                        v-on:click="selectTag(index)"
                                    >
                                        {{ tag }}
                                    </TagButton>
                                 </div>
                            </div>
                        </div>
                        
                        <div class="hidden w-full border-b lg:flex dark:border-neutral-500">
                            <div class="w-1/3 px-6 py-4">Meal</div>
                            <div class="w-2/3 px-6 py-4">Ingredients</div>
                        </div>
                        <div class="flex flex-col w-full">
                            <div class="flex flex-wrap items-center py-2 my-2 border-b dark:border-neutral-500 gap-y-2" v-for="meal of meals" :key="meal.id">
                                <div class="w-full font-medium sm:px-2 sm:py-2 sm:w-1/2 lg:w-4/12">
                                    <Link :href="route('meal-info', meal.id)" class="text-blue-600 capitalize hover:text-blue-800 hover:underline">{{ meal.name }}</Link>
                                    <div class="inline">
                                        <span v-if="meal.match_percent > 90" class="ml-2 text-green-300"><i class="fas fa-check"></i></span>
                                    </div>
                                </div>
                                <div class="w-full sm:px-2 sm:py-2 sm:w-1/2 lg:w-8/12">
                                    {{ meal.inventory_match }} / {{ meal.ingredient_count }} items in your inventory ({{ meal.match_percent ? meal.match_percent : 0 }}%) 
                                </div>
                                <div class="flex flex-col w-full sm:px-2" v-if="Object.keys(meal.missingItems).length > 0">
                                    <div>
                                        <small>Missing Items</small> 
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="flex flex-col px-2 py-2 my-1 ml-2 bg-gray-100 sm:flex-wrap sm:flex-row gap-y-2 sm:justify-between md:text-xs lg:w-3/4" 
                                            v-for="item of meal.missingItems" :key="item.id"
                                        >

                                            <div class="inline-flex items-center justify-between sm:mr-8">
                                                <span v-on:click="selectMissingItem(meal.id, item.id)" 
                                                    class="capitalize cursor-pointer select-none md:w-auto hover:font-bold">
                                                    {{ item.name}}
                                                </span>
                                                <span class="block p-2 cursor-pointer text-default hover:font-bold sm:hidden" 
                                                        v-on:click="data.expandMissing = {meal: 0, item: 0}"
                                                         v-if="data.expandMissing.meal == meal.id && data.expandMissing.item == item.id"
                                                >
                                                    x
                                                </span>
                                            </div>    

                                            <div class="inline-flex flex-wrap items-center sm:flex-row sm:items-center gap-x-2 gap-y-2 sm:w-auto" v-if="data.expandMissing.meal == meal.id && data.expandMissing.item == item.id">
                                                <small class="w-full sm:w-auto">Add to</small> 
                                                <div v-if="!data.openAddToInventory">
                                                    <span class="inline-block px-4 py-1 text-sm transition duration-200 bg-gray-200 rounded-full select-none"
                                                        v-if="shopping_list_ids.includes(item.id)"
                                                    >
                                                        In shopping list
                                                    </span>
                                                </div>
                                                <div v-if="!data.openAddToInventory">
                                                    <span class="inline-block px-4 py-1 text-sm transition duration-200 bg-blue-100 rounded-full cursor-pointer select-none hover:bg-blue-200"
                                                        v-on:click="addToList(item)"
                                                        v-if="!shopping_list_ids.includes(item.id)"
                                                    >
                                                        Shopping list
                                                    </span> 
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-block px-4 py-1 text-sm transition duration-200 bg-orange-100 rounded-full cursor-pointer select-none hover:bg-orange-200"
                                                        v-on:click="data.openAddToInventory = item.id; data.addToLocation = 0"
                                                    >
                                                        Inventory
                                                    </span> 

                                                    <div class="flex gap-2" v-if="data.openAddToInventory == item.id">
                                                        <SelectInput class="md:text-sm" v-model="data.addToLocation">
                                                            <option value="0">--Select Location--</option>
                                                            <option v-for="location of locations" :value="location.id" :key="location.id">
                                                                {{ location.name }}
                                                            </option>
                                                        </SelectInput>
                                                        <FormButton class="bg-blue-200 hover:bg-blue-300" 
                                                            v-on:click="addToInventory(item)">
                                                           Add
                                                        </FormButton>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="hidden p-2 cursor-pointer sm:block text-default hover:font-bold" 
                                                    v-on:click="data.expandMissing = {meal: 0, item: 0}; data.openAddToInventory = false"
                                                        v-if="data.expandMissing.meal == meal.id && data.expandMissing.item == item.id"
                                            >
                                                x
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WORKOUTS -->
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 " v-if="data.selectedContent == 'workouts'">
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

            <!-- SHOPPING LIST -->
             <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 " v-if="data.selectedContent == 'shopping list'">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="mb-2 text-lg md:mb-4">Shopping List</h3>

                        <div class="flex flex-col gap-2 mb-2 sm:items-center sm:flex-row md:justify-start">
                            <label class="block pl-2 text-sm sm:w-auto sm:text-right">Add item</label>
                            <TextInput class="sm:w-[75%] md:w-[50%]" type="text" placeholder="Search ingredients..." v-model="search"  />
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <FoodItemPill v-for="item of foodItems" :key="item.id" :item="item.name" v-on:click="selectFoodItem(item)" />   
                        </div>

                        <div class="flex flex-wrap justify-between my-4 text-sm">
                            <div class="flex flex-wrap items-center w-full p-2 my-1 group sm:w-[48%] bg-gray-50 border border-gray-200" v-for="item of shopping_list" :key="item.id">
                                <div class="flex items-center justify-between w-full capitalize select-none">
                                    <!-- <input type="checkbox" v-model="data.listBulkAdd"> -->
                                    <span>{{ item.name }}</span>

                                    <div class="flex items-center gap-2">

                                        <span class="w-auto p-1 cursor-pointer group-hover:opacity-100 text-default" :class="data.deleteShoppingItem == item.id ? 'opacity-100' : 'opacity-0'"
                                            v-on:click="data.deleteShoppingItem == item.id ? data.deleteShoppingItem = 0 : data.deleteShoppingItem = item.id"
                                        >
                                            x

                                            <span class="lowercase hover:underline hover:text-red-600"
                                                    v-if="item.id == data.deleteShoppingItem" 
                                                    v-on:click="removeFromList(item)">
                                                delete
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <!-- ENTRY MODAL -->
            <Modal :show="data.addingEntry" @close="closeModal">
                <PlannerEntryForm 
                    :entry="data.entryData" 
                    :filters="props.filters" 
                    :tags="props.tags" 
                    @addedEntry="resetEntryForm()" 
                    @filteredMeals="updateMealsForModal(meals)"
                />
                
            </Modal>


        </div>
    </AuthenticatedLayout>
</template>


<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { useForm, router } from '@inertiajs/vue3';
    import { reactive, watch, ref } from "vue";
    import { CalendarService } from '../Services/CalendarService';
    import { UrlParamService } from '@/Services/URLParamService';
    import PlannerTab from '@/Components/PlannerTab.vue'
    import FoodItemPill from '@/Components/FoodItemPill.vue';
    import Modal from '@/Components/Modal.vue';
    import PlannerEntryForm from '@/Components/PlannerEntryForm.vue';
    import debounce from "lodash/debounce";
    import TextInput from '@/Components/TextInput.vue';
    import FormButton from '@/Components/FormButton.vue';
    import SmallButton from '@/Components/SmallButton.vue';
    import TagButton from '@/Components/TagButton.vue';
    import SelectInput from '@/Components/SelectInput.vue';

    const props = defineProps({
        'recipes': Object,
        'meals': Object,
        'workouts': Object,
        'custom_workouts': Object,
        'entries': Object,
        'shopping_list': Object,
        'shopping_list_ids': Array,
        'filters': Object,
        'foodItems': Object,
        'locations': Object,
        'tags': Object,
        'food_item_tags': Object,
    });

    let data = reactive({
        selectedContent: 'calendar',
        selectedMonth: '',
        tabs: {
            0: {
                label: 'calendar',
                bg: 'bg-purple-100',
                hover: 'bg-purple-200'
            },
            1: {
                label: 'meals',
                bg: 'bg-blue-100',
                hover: 'bg-blue-200'
            },
            2: {
                label: 'workouts', 
                bg: 'bg-green-100',
                hover: 'bg-green-200'
            },
            3: {
                label: 'shopping list',
                bg: 'bg-orange-100',
                hover: 'bg-orange-200'
            }
        },
        itemForm: {
            open: false,
        },
        showLocation: {
            display: 0
        },
        addTo: 0,
        fullCalendar: [],
        addingEntry: false,
        generateListDays: "7",
        confirmGenerate: false,
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
                filter: props.filters
            },
            selected: {
                day: 0,
                month: 0,
                year: 0
            }
        },
        deleteEntryId: 0,
        deleteShoppingItem: 0,
        expandMissing: {
            meal: 0,
            item: 0,
        },
        listBulkAdd: []
    });

    window.url = new UrlParamService;

    let changeTab = (tab) => {
        data.selectedContent = tab.label;

    }

    // CALENDAR
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
            preserveState: true,
            preserveScroll: true
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
        data.entryData.options.meals = props.meals;
        data.entryData.entry_data = {                
            name: '',
            notes: '',
        };
        
        data.addingEntry = false;
        router.get('/planner', {
            preserveState: true,
            preserveScroll: true
        });
    };

    
    let deleteEntry = ( entry ) => {
        
        router.delete(`/entry/${entry.id}`, {
            preserveState: true,
            preserveScroll: true
        });

        
    }

    let updateMealsForModal = ( meals ) => {
        console.log('updateMealsForModals please', meals);
        data.entryData.options.meals = meals;
    }
    // END CALENDAR


    // MEALS
    let filters = reactive({
        show: false,
        selectedFoodItem: false,
        selectedTag: false,
    });

    if( window.url.getParam('food_item') ){
        filters.selectedFoodItem = window.url.getParam('food_item');
        filters.show = true;
    }

    let toggleFilters = () => {
        filters.show = !filters.show
        if( filters.show == false ){
            router.get('/planner', {}, {
                preserveState: true,
                preserveScroll: true
            });
        }
    };

    let selectTag = ( slug ) => {
        if( Number.isInteger(slug) ){
            console.log('selected tag is number', slug)
            router.get('/planner', { tag: slug }, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: ( page ) => {
                    filters.selectedTag = slug 
                }
            });
        } else {
            router.get('/planner', { food_item: slug }, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: ( page ) => {
                    filters.selectedFoodItem = slug
                }
            });
        }
    }
    // END MEALS

    // SHOPPING LIST
    let addToList = ( item ) => {
        router.post(`/shopping-list/add/${item.id}`, {
            preserveState: true,
            preserveScroll: true
        });
    }

    let addToInventory = ( item ) => {
        router.post(`/inventory/add`, {
            item: item,
            location: data.addToLocation
        },
        {
            preserveState: true,
            preserveScroll: true
        });
    }


    let selectMissingItem = ( meal, item ) => {
        data.expandMissing = {
            meal: meal,
            item: item
        }
        console.log('selcting missing item');
    };

    let removeFromList = ( item ) => {
        router.post(`/shopping-list/remove/${item.shopping_list_id}`, {
            preserveState: true,
            preserveScroll: true
        });
    }


    let generateList = () => {
        router.post('/shopping-list/generate', {
            days: data.generateListDays
        }, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                data.confirmGenerate = false;
            }
        });

    };


    // SHOPPING LIST TAB
    // FOOD SEARCH //
    const queryString = window.location.search;
    const searchParams = new URLSearchParams(queryString);
    const searchValue = searchParams.get('search');

    let search = searchValue ? ref(searchValue) : ref('');
    let addItem = {};

    let watchSearch = () => {
        watch( search, debounce( function(value){
    
            if ( value.length > 1 ) {
                data.isSearching = true;
                addItem.name = value.name;
                addItem.id = value.id;
            }
            
            router.get(`/planner`, { search: value }, {
                preserveState: true,
                preserveScroll: true
            });
    
        }, 300) );
    }

    watchSearch();

    let selectFoodItem = (item) => {
        router.post(`shopping-list/add/${item.id}`, {
            preserveState: true,
            preserveScroll: true,
        });

    };

</script>