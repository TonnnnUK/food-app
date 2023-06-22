<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Inventory
            </h2>
        </template>

        <section class="p-2 sm:p-8 lg:p-12">
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

            <div class="flex flex-col gap-1 my-5" v-if="data.itemForm.open">
                <label for="">Adding food to</label>
                <div class="flex items-center w-full gap-2 sm:w-1/2 lg:w-1/3">
                
                    <select class="text-sm border border-gray-300 rounded-lg" v-model="data.addTo">
                        <option value="0" selected>-- Select location --</option>
                        <option v-for="location of locations" :key="location.id" :value="location.id">
                            {{location.name}}
                        </option>
                    </select>

                    <input class="w-full text-sm border-gray-300 rounded-lg" 
                            placeholder="Food Search" 
                            type="text" 
                            v-model="search">

                </div>
                <div class="flex flex-wrap">

                    <div v-for="item of foodItems" :key="item.id" class="flex items-center w-full gap-2 md:w-1/2 lg:w-1/3">
                        <input type="checkbox" v-model="selectedItems" :id="item.id" :value="item.id"> {{ item.name }}
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
                            <div class="overflow-hidden">
                                <table class="min-w-full text-sm font-light text-left">
                                <thead class="font-medium border-b dark:border-neutral-500">
                                    <tr>
                                        <th scope="col" class="px-6 py-4">Item</th>
                                        <th scope="col" class="px-6 py-4">Qty</th>
                                        <th scope="col" class="px-6 py-4">Date In</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b dark:border-neutral-500" v-for="item of location.items" :key="item.id">
                                        <td class="px-6 py-4 font-medium whitespace-nowrap">
                                            <span class="capitalize">{{ item.name }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ item.pivot.qty ? item.pivot.qty : '' }} {{ units[item.pivot.unit_id-1] ? units[item.pivot.unit_id-1].name : '' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ item.pivot.date_in }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <Link :href="route('meals-by-item', item.id)" class="text-blue-700 transition duration-200 hover:text-blue-900"><i class="fas fa-utensils"></i></Link>
                                        </td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <span class="text-xs font-bold text-red-600 hover:cursor-pointer hover:underline" v-on:click="removeItem(location, item)">X</span>
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

    defineProps({
        'locations': Array,
        'units': Array,
        'foodItems': Object,
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

        router.get('/inventory', { search: value}, {
            preserveState: true
        });

    }, 300) );
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

    let removeItem = (location, item) => {
        router.post(`/inventory/${location.id}/${item.id}/remove`, {
            preserveState: true
        });


    }
    //////////////////////////
    // ADD FOOD ITEMS END //
    //////////////////////////

</script>
