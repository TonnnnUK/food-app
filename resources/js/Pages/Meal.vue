
<template>
    <Head :title="`Meal: ${meal.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Edit Meal
                </h2>
                <Link :href="route('meals')" class="text-xs text-blue-600 hover:underline">&laquo; Back to meals</Link>
            </div>
        </template>

        <div class="py-4 md:py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex flex-col w-full gap-2 sm:items-center sm:flex-row">
                            <h2 v-if="!data.editingName" class="text-xl font-bold">{{meal.name}}</h2>
                            <div v-if="data.editingName" class="flex gap-2">
                                <TextInput class="px-3 text-xl border" v-model="meal.name" />
                                <button class="px-2 py-1 text-xs text-white transition duration-200 bg-blue-500 rounded-lg hover:bg-blue-600" 
                                    v-on:click="data.editingName = false; saveTitle()"
                                >
                                    Save
                                </button>
                            </div>
                            <div class="flex gap-2 my-4">
                                <div class="flex">
                                    <span v-on:click="data.makeDuplicate = !data.makeDuplicate" class="cursor-pointer"><i class="fas fa-copy"></i></span>
                                    <span v-if="data.makeDuplicate" class="text-sm">Make a duplicate? <span v-on:click="duplicateRecipe()" class="text-red-600 cursor-pointer hover:underline">Yes</span></span>
                                </div>
                                <button class="px-2 py-1 text-xs transition duration-200 bg-gray-200 rounded-lg hover:bg-gray-300"
                                    @click="data.editingName = true;"
                                    v-if="!data.editingName"
                                >
                                    Edit
                                </button>
                            </div>
                        </div>
                        <div class="flex my-4">
                            <div class="flex items-center order-2 w-3/4 gap-2 text-sm sm:w-auto">
                                <label for="">Servings</label>
                                <TextInput class="w-12" type="number" v-model="meal.servings" />
                            </div>
                        </div>

                        <div class="my-4">
                            <div class="flex flex-col gap-2 md:items-center md:flex-row md:flex-wrap">

                                <label class="block pl-2 text-sm sm:w-[20%] md:w-full">Add ingredient</label>
                                <div class="inline-flex flex-col gap-2">
                                    
                                    <!-- FOOD ITEM SEARCH -->
                                    <div class="flex flex-col flex-wrap w-full gap-2 sm:items-center sm:flex-row md:flex-wrap overflow-auto max-h-[150px] md:max-h-full md:overflow-y-hidden"
                                            v-if="data.selectedFoodType == false"
                                    >
                                        <div class=""
                                            v-for="food_type of foodTypes" :key="food_type.id"
                                        >
                                            <span class="w-[95%] md:w-auto block px-2 py-2 text-sm capitalize transition duration-200 border rounded-lg select-none md:w-auto md:px-4 hover:cursor-pointer hover:bg-orange-100 hover:border-orange-300 bg-blue-100 border-blue-300"
                                                v-on:click="selectFoodType(food_type)"
                                            >
                                                {{food_type.name}}
                                            </span>

                                        </div>

                                    </div>
                                    
                                    <div class="flex" v-if="data.selectedFoodType != false">
                                        <span class="w-[95%] sm:w-auto block px-2 py-2 text-sm capitalize transition duration-200 border rounded-lg select-none md:w-auto md:px-4 bg-orange-100 border-orange-300">
                                            {{ data.selectedFoodType.name }}
                                        </span>

                                        <span v-on:click="data.selectedFoodType = false" class="p-2 cursor-pointer">x</span>
                                    </div>

                                    <div class="flex flex-wrap gap-2 my-2 md:my-4" v-if="data.isSearching">
                                        <FoodItemPill v-for="item of foodItems" :key="item.id" :item="item.name" v-on:click="selectFoodItem(item)" />   
                                    </div>
                                    
                                    <div class="flex flex-col gap-2 my-4" v-if="data.selectedFoodType != false">

                                        <div class="flex flex-col gap-2 sm:items-center sm:flex-row">
                                            <label class="block pl-2 text-sm sm:w-[20%] sm:text-right">Item</label>
                                            <FoodItemPill :item="search" v-on:click="search = ''; console.log('clear item')" />   
                                        </div>

                                        <div class="flex flex-col gap-2 sm:items-center sm:flex-row">
                                            <label class="block pl-2 text-sm sm:w-[20%] sm:text-right">Unit</label>
                                            <SelectInput class="sm:w-[75%]" v-model="newIngredient.unitID">
                                                <option v-for="unit of units" :key="unit.id" :value="unit.id">{{unit.name}}</option>
                                            </SelectInput>
                                        </div>

                                        <div class="flex flex-col gap-2 sm:items-center sm:flex-row">
                                            <label class="block pl-2 text-sm sm:w-[20%] sm:text-right">Qty</label>
                                            <TextInput class="sm:w-[75%]" type="text" placeholder="Enter qty..." v-model="newIngredient.qty"  />
                                        </div>

                                        <div class="flex items-end w-full md:w-auto">
                                            <div class="flex flex-col">
                                                <PrimaryButton class="bg-blue-500 rounded-lg hover:bg-blue-600" v-on:click="addToMeal()">Add to meal</PrimaryButton>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            
                            </div>

                            
                            <div class="my-4" v-if="Object.keys(data.errors).length > 0">
                                <span class="block text-xs text-red-600" v-for="error in Object.keys(data.errors)" :key="error">
                                    {{ data.errors[error] }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col w-full xl:w-9/12">
                            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                    <div class="overflow-hidden">
                                        <table class="min-w-full text-sm font-light text-left">
                                        <thead class="font-medium border-b dark:border-neutral-500">
                                            <tr>
                                                <th scope="col" class="px-6 py-4">Ingredient</th>
                                                <th scope="col" class="px-6 py-4">Qty</th>
                                                <th scope="col" class="flex justify-end">
                                                    <button class="px-3 py-2 transition duration-200 bg-yellow-200 rounded-lg hover:bg-yellow-300"
                                                            v-on:click="addAllToList()"
                                                    >
                                                        Add all to shopping list
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="border-b dark:border-neutral-500" v-for="ingredient of meal.ingredients" :key="ingredient.id">
                                                <td class="px-6 py-4 font-medium whitespace-nowrap"><span class="capitalize">{{ ingredient.name }}</span></td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ ingredient.pivot.qty }} {{ ingredient.pivot.unit == 'number' ? '' : ingredient.pivot.unit }}</td>
                                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                                    <span class="text-xs font-bold text-red-600 hover:cursor-pointer hover:underline" v-on:click="removeIngredient(ingredient)">X</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col w-full px-2 my-2 md:my-4 md:px-4">
                            <h3>Tags</h3>
                            <div class="flex justify-between">
                                <div class="md:w-1/2">
                                    <div class="flex flex-wrap gap-2">
                                        <FoodItemPill v-for="tag of tags" :key="tag.id" :item="tag.tag_name" />
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2 md:w-1/2">
                                    <div class="flex gap-2">
                                        <input class="w-auto text-xs border-0 border-b border-gray-300 rounded" placeholder="new tag..." type="text" v-model="newTag" />
                                        <SmallButton class="bg-blue-300 hover:bg-blue-400" v-on:click="saveTag">Save</SmallButton>
                                    </div>

                                    <small v-if="fetchTags.length > 0 ">Your existing tags...</small>
                                    <div class="flex flex-wrap gap-2">
                                        <FoodItemPill v-for="foundtag of fetchTags" :key="foundtag" :item="foundtag.tag_name" v-on:click="selectTag(foundtag.id)" />
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
    import debounce from "lodash/debounce";
    import TextInput from '@/Components/TextInput.vue';
    import SelectInput from '@/Components/SelectInput.vue';
    import FoodItemPill from '@/Components/FoodItemPill.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import SmallButton from '@/Components/SmallButton.vue';

    const props = defineProps({
        'meal': Object,
        'ingredients': Object,
        'units': Object,
        'foodItems': Object,
        'foodTypes': Object,
        'tags': Object,
        'fetchTags': Object,
    });

    
    let data = reactive({
        mealID: router.page.props.meal.id,
        isSearching: false,
        searchItems: [],
        errors: [],
        editingName: false,
        makeDuplicate: false,
        selectedFoodType: false,
    });


    // FOOD SEARCH //
    const queryString = window.location.search;
    const searchParams = new URLSearchParams(queryString);
    const searchValue = searchParams.get('search');

    let search = searchValue ? ref(searchValue) : ref('');

    let watchSearch = () => {
        watch( search, debounce( function(value){
    
            if ( value.length > 1 ) {
                data.isSearching = true;
                newIngredient.name = value.name;
                newIngredient.id = value.id;
            }
            
            router.get(`/meal/${data.mealID}`, { search: value}, {
                preserveState: true,
                preserveScroll: true
            });
    
        }, 300) );
    }

    watchSearch();
    
    let selectFoodType = (type) => {
        data.selectedFoodType = type
        router.get(`/meal/${props.meal.id}`, { type: type.id }, {
            preserveState: true,
            preserveScroll: true,

            onSuccess: (page) => {
                data.isSearching = true;
            }
        });
    };

    //////////////////////////
    // FOOD SEARCH END //
    //////////////////////////

    // SELECT FOOD ITEM //
    let selectFoodItem = (item) => {
        newIngredient.id = item.id;
        newIngredient.name = item.name;
        data.isSearching = false
        search = ref(item.name);
        watchSearch();
    }


    let newIngredient = useForm({
        id: 0,
        name: '',
        unitID: '1',
        unit: '',
        qty: '',
    });
    
    let addToMeal = () => {

        newIngredient.unit = router.page.props.units[newIngredient.unitID-1].name; // Set the unit name
        
        validateForm();

        if( Object.keys(data.errors).length == 0 ){
            newIngredient.post(`/meal/${data.mealID}`, { ingredient: data.newIngredient}, {
                preserveState: true,
                preserveScroll: true
            });

            newIngredient.id = 0;            
            newIngredient.name = '';
            newIngredient.unitID = '1';
            newIngredient.unit = '';
            newIngredient.qty = '';
        }
        
    }

    let validateForm = () => {
        data.errors = [];

        if( newIngredient.name == '' ){
            data.errors['ingredient'] = 'Select an ingredient'
        }

        if( newIngredient.qty == '' ){
            data.errors['qty'] = 'Add a quantity'
        }
    }

    let removeIngredient = (ingredient) => {

        router.post(`/meal/${data.mealID}/remove/${ingredient.id}`, {
            preserveState: true,
            preserveScroll: true
        });
    }

    let saveTitle = () => {
        router.post(`/meal/${data.mealID}/save-title`, { newTitle: router.page.props.meal.name } , {
            preserveState: true,
            preserveScroll: true
        });
    }

    let duplicateRecipe = () => {
        router.post(`/meal/${data.mealID}/duplicate`, {
            preserveState: true,
            preserveScroll: true,

            onSuccess: (page) => {
                data.makeDuplicate = false;
            }
        });

    }
        
    let addAllToList = (item ) => {
        router.post(`/meal/${data.mealID}/add-to-shopping-list`, {
            preserveState: true,
            preserveScroll: true
        });
    }

    
    let newTag = ref('');
    let watchNewTag = () => {
        watch( newTag, debounce( function(value){
    
            router.get(`/meal/${data.mealID}`, { tag: value}, {
                preserveState: true,
                preserveScroll: true
            });
        
    
        }, 300) );
    }

    watchNewTag();

    let selectTag = (tag) => {
        router.post(`/meal/${data.mealID}/add-tag/${tag}`, null, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (page) => {
                    newTag.value = '';
                }
            });

    }

    let saveTag = () => {
        router.post(`/meal/${data.mealID}/new-tag`, { tag: newTag._value}, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (page) => {
                    newTag.value = '';
                }
            });
    }

</script>