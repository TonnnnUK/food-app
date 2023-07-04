<template>
    <Head title="Your Inventory" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Inventory
            </h2>
        </template>

        <section class="p-2 my-4 sm:p-8 lg:p-12">
            <div class="flex justify-between">
                <button class="px-4 py-1 text-sm bg-blue-300 rounded-lg hover:bg-blue-400"
                        @click="data.itemForm.open = !data.itemForm.open; data.locationForm.open = false"
                >
                    <span v-html="data.itemForm.open ? `<i class='fas fa-close'></i>` : `<i class='fas fa-plus'></i> Add Item`"></span>
                </button>

                <button class="px-4 py-1 text-sm bg-gray-300 rounded-lg"
                    @click="data.locationForm.open = !data.locationForm.open; data.itemForm.open = false"
                >
                    <span v-html="data.locationForm.open ? `<i class='fas fa-close'></i>` : `<i class='fas fa-cog'></i>`"></span>
                </button>
            </div>
        
            <form @submit.prevent="addLocation" class="flex flex-col gap-1 my-5" v-if="data.locationForm.open">
                <label class="" for="">New Location</label>
                <div class="flex">
                    <input class="w-full text-sm border-gray-300 rounded-l-lg sm:w-1/2 lg:w-1/3" placeholder="Location name (e.g. Cupboard)" type="text" v-model="newLocation.name">
                    <button class="px-4 py-1 text-sm text-white bg-green-500 rounded-r-lg hover:bg-green-600">Add</button>
                </div>
            </form>

            <div class="my-5" v-if="data.itemForm.open">
                <label class="block w-full mt-4" for="">Adding food to</label>

                <div class="flex items-center w-full gap-2 mb-4">
                
                    <div class="w-full">
                        <select class="w-full text-sm border border-gray-300 rounded-lg" v-model="data.addTo">
                            <option value="0" selected>-- Select location --</option>
                            <option v-for="location of locations" :key="location.id" :value="location.id">
                                {{location.name}}
                            </option>
                        </select>
                    </div>

                    <!-- <div class="w-auto">
                        <input class="w-full text-sm bg-gray-200 border-gray-300 rounded-lg w-" 
                                placeholder="Food item" 
                                type="text" 
                                v-model="search" disabled>
                    </div> -->


                </div>
                <div class="flex flex-wrap w-full gap-2 px-4 mb-4 text-xs grow-1">
                    <span class="px-2 py-1 transition duration-200 bg-blue-300 border rounded-lg hover:cursor-pointer hover:bg-blue-400"
                        v-for="food_type of food_types" :key="food_type.id"
                        v-on:click="selectFoodType(food_type.id)"
                    >
                        {{food_type.name}}
                    </span>
                    <span class="px-2 py-1 transition duration-200 bg-blue-300 border rounded-lg hover:cursor-pointer hover:bg-blue-400" v-on:click="selectFoodType('shopping_list')">
                        shopping list
                    </span>
                </div>
                <div class="relative flex flex-wrap">
                    <span v-if="foodItems && Object.keys(foodItems).length > 0" class="absolute right-4" v-on:click="resetItems()">x</span>
                    <div v-for="item of foodItems" :key="item.id" class="flex items-center w-full gap-2 md:w-1/2 lg:w-1/3">
                        <label class="p-1 mb-1 border border-gray-100 cursor-pointer hover:border-gray-400">
                            <input type="checkbox" v-model="selectedItems" :id="item.id" :value="item.id"> {{ item.name }}
                        </label>
                    </div>
                </div>

                <div v-if="data.addTo > 0 && foodItems && foodItems.length > 0">
                    <button class="px-5 py-2 my-2 text-white bg-blue-700 rounded-lg" v-on:click="addFoodItems()">Add items to {{locations[data.addTo-1].name}}</button>
                </div>
            </div>
        </section>

        <section v-if="locations && locations.length > 0" class="p-2 sm:px-8 lg:px-12">
            <div class="mb-5" v-for="location of locations" :key="location.id">
                <div class="flex items-center justify-between p-4 font-bold border cursor-pointer bg-green-50" 
                    @click="data.showLocation.display == location.id ? data.showLocation.display = 0 : data.showLocation.display = location.id"
                >
                    <span>{{ location.name }}</span>
                    
                    <span class="text-2xl" v-show="data.showLocation.display != location.id">+</span>
                    <span class="text-2xl" v-show="data.showLocation.display == location.id">-</span>
                </div>
                <div class="p-4 bg-white border" v-show="data.showLocation.display == location.id">
                    <small class="block my-2">Let's have a look in the {{ location.name }}</small>
                    

                    <div class="flex flex-col w-full xl:w-9/12">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                            
                                <div class="min-w-full text-sm font-light text-left">
                                    
                                    <div class="flex flex-wrap items-center py-4 border-b w-100 dark:border-neutral-500" 
                                        v-for="item of location.recipes" :key="item.id">
                                        <div class="w-3/4 px-2 font-medium md:w-1/4">
                                            <span class="capitalize">{{ item.name }}</span>
                                        </div>
                                        <div class="order-3 w-full px-2 md:w-1/4">
                                            {{ item.pivot.qty ? item.pivot.qty : '' }} {{ units[item.pivot.unit_id-1] ? units[item.pivot.unit_id-1].name : '' }}
                                        </div>
                                        <div class="order-2 w-1/2 px-2 md:w-1/4">Move</div>
                                        <div class="flex justify-end w-1/2 gap-4 px-2 md:w-1/4">
                                            <Link :href="route('meals-by-item', item.id)" class="text-blue-700 transition duration-200 hover:text-blue-900"><i class="fas fa-utensils"></i></Link>
                                            <span class="text-xs font-bold text-red-600 hover:cursor-pointer hover:underline" v-on:click="removeItem('recipe', item.pivot.id)">X</span>
                                        </div>
                                        <div class="w-full px-2 mt-2">
                                            {{ formatDate(item.pivot.date_in) }}
                                            {{ item.pivot.days_old }}
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap items-center py-4 border-b w-100 dark:border-neutral-500" 
                                        v-for="item of location.meals" :key="item.id">
                                        <div class="w-full px-2 font-medium md:w-1/4">
                                            <span class="capitalize">{{ item.name }}</span>
                                        </div>
                                        <div class="w-full px-2 md:w-1/4 ">
                                            {{ item.pivot.qty ? item.pivot.qty : '' }} {{ units[item.pivot.unit_id-1] ? units[item.pivot.unit_id-1].name : '' }}
                                        </div>
                                        <div class="w-1/2 px-2 md:w-1/4">Move</div>
                                        <div class="flex justify-end w-1/2 gap-4 px-2 md:w-1/4">
                                            <Link :href="route('meals-by-item', item.id)" class="text-blue-700 transition duration-200 hover:text-blue-900"><i class="fas fa-utensils"></i></Link>
                                            <span class="text-xs font-bold text-red-600 hover:cursor-pointer hover:underline" v-on:click="removeItem('meal', item.pivot.id)">X</span>
                                        </div>
                                        <div class="w-full px-2 mt-2">
                                            {{ formatDate(item.pivot.date_in) }}
                                            {{ item.pivot.days_old }}
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap items-center justify-between py-4 border-b w-100 dark:border-neutral-500" 
                                        v-for="item of location.items" :key="item.id">
                                        <div class="w-full px-2 font-medium md:w-1/4 grow-2 ">
                                            <span class="capitalize">{{ item.name }}</span>
                                        </div>
                                        <div class="w-full px-2 md:w-1/4 grow-1">{{ item.pivot.qty ? item.pivot.qty : '' }} {{ units[item.pivot.unit_id-1] ? units[item.pivot.unit_id-1].name : '' }}</div>
                                        <div class="w-1/2 px-2 md:w-1/4 grow-1">Move</div>
                                        <div class="flex justify-end w-1/2 gap-4 px-2 md:w-1/4 grow-1">
                                            <Link :href="route('meals-by-item', item.id)" class="text-blue-700 transition duration-200 hover:text-blue-900"><i class="fas fa-utensils"></i></Link>
                                            <span class="text-xs font-bold text-red-600 hover:cursor-pointer hover:underline" v-on:click="removeItem('item', item.pivot.id)">X</span>
                                        </div>
                                        <div class="w-full px-2 mt-2 text-xs grow-1">
                                            Added {{ formatDate(item.pivot.date_in) }} / {{ item.pivot.days_old }}
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div v-else class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        No Inventory locations - create a location to add food items to
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
    import debounce from "lodash/debounce";
    import { CalendarService } from '@/Services/CalendarService';

    let props = defineProps({
        'locations': Array,
        'units': Array,
        'food_types': Object,
        'foodItems': Object,
        'shopping_list': Object
    });

    let data = reactive({
        locationForm: {
            open: false,
        },
        itemForm: {
            open: false,
        },
        showLocation: {
            display: 0
        },
        addTo: 0,
    });


    // INVENTORY LOCATIONS //
    let newLocation = useForm({
        name: ''
    });

    const Calendar = new CalendarService;
    let formatDate = (dateString) => {
        let dt = new Date(dateString);
        return dt.getDate() + ' ' + Calendar.months[dt.getMonth()] + ' ' + dt.getFullYear();
    };


    let addLocation = () => {
        newLocation.post('/inventory')
        newLocation.name = '';
    }
    //////////////////////////
    // INVENTORY LOCATIONS END
    //////////////////////////



    // FOOD SEARCH //
    let search = ref('');
    let selectedItems = ref([]);

    watch( search, debounce( function(value){
        
        router.get('/inventory', { search: value }, {
            preserveState: true
        });

    }, 300) );


    let selectFoodType = (type_id) => {
        router.get('/inventory', { type: type_id }, {
            preserveState: true
        });
    };


    let resetItems = () => {
        router.get('/inventory', '', {
            preserveState: true
        });
    }
    //////////////////////////
    // FOOD SEARCH END //
    //////////////////////////



    // ADD FOOD ITEMS //

    let addFoodItems = () => {
        router.post('/inventory/add-items', { 
                items: selectedItems['_rawValue'],
                location: data.addTo,
            }, {
            preserveState: true
        });

        selectedItems = ref([]);

    }
    //////////////////////////
    // ADD FOOD ITEMS END //
    //////////////////////////


    // Remove FOOD ITEMS //

    let removeItem = (type, id) => {
        router.post(`/inventory/remove`, {
            'type': type,
            'id': id
        },{ 
            preserveState: true
        });

    }
    //////////////////////////
    // ADD FOOD ITEMS END //
    //////////////////////////

</script>
