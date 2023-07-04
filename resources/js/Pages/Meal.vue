
<template>
    <Head :title="`Meal: ${meal.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Meal: {{ meal.name }}
                </h2>
                <Link :href="route('meals')" class="text-xs text-blue-600 hover:underline">&laquo; Back to meals</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2" v-if="!data.editingName">
                                <h2 class="text-xl font-bold">{{meal.name}}</h2>
                                <button class="px-2 py-1 text-xs transition duration-200 bg-gray-200 rounded-lg hover:bg-gray-300"
                                    @click="data.editingName = true;"
                                >
                                    Edit
                                </button>

                                <div class="flex gap-2 ml-4">
                                    <span v-on:click="data.makeDuplicate = !data.makeDuplicate" class="cursor-pointer"><i class="fas fa-copy"></i></span>
                                    <span v-if="data.makeDuplicate" class="text-sm">Make a duplicate? <span v-on:click="duplicateRecipe()" class="text-red-600 cursor-pointer hover:underline">Yes</span></span>
                                </div>
                            </div>

                            <div v-if="data.editingName" class="flex gap-2">
                                <TextInput class="px-3 text-xl border" v-model="meal.name" />
                                <button class="px-2 py-1 text-xs text-white transition duration-200 bg-blue-500 rounded-lg hover:bg-blue-600" 
                                    v-on:click="data.editingName = false; saveTitle()"
                                >
                                    Save
                                </button>
                            </div>

                            <div class="flex items-center gap-2 text-sm">
                                <label for="">Servings</label>
                                <TextInput class="w-12" type="number" v-model="meal.servings" />
                            </div>
                        </div>

                        <div class="my-2 lg:my-4">
                            <div class="flex items-center justify-between">

                                <div class="flex items-center gap-2">
                                    <div>
                                        <label class="block pl-2 text-sm">Add ingredient</label>
                                        <TextInput type="text" placeholder="Search ingredients..." v-model="search"  />
                                    </div>
                                    <div>
                                        <label class="block pl-2 text-sm">Unit</label>
                                        <SelectInput v-model="newIngredient.unitID">
                                            <option v-for="unit of units" :key="unit.id" :value="unit.id">{{unit.name}}</option>
                                        </SelectInput>
                                    </div>
                                    <div>
                                        <label class="block pl-2 text-sm">Qty</label>
                                        <TextInput type="text" placeholder="Enter qty..." class="w-28" v-model="newIngredient.qty"  />
                                    </div>
                                    <div class="flex flex-col">
                                        <label for="block">&nbsp;</label>
                                        <PrimaryButton class="bg-blue-500 rounded-lg hover:bg-blue-600" v-on:click="addToMeal()">Add to meal</PrimaryButton>
                                    </div>

                                </div>
                                <div class="text-xs">
                                    <button class="px-3 py-2 transition duration-200 bg-yellow-200 rounded-lg hover:bg-yellow-300"
                                        v-on:click="addToList()"
                                    >
                                        Add to shopping list
                                    </button>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 my-2 md:my-4" v-if="data.isSearching">
                                <FoodItemPill v-for="item of foodItems" :key="item.id" :item="item.name" v-on:click="selectFoodItem(item)" />   
                            </div>

                            <div class="my-4" v-if="Object.keys(data.errors).length > 0">
                                <span class="block text-xs text-red-600" v-for="error in Object.keys(data.errors)" :key="error">
                                    {{ data.errors[error] }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <div class="flex flex-col w-full xl:w-9/12">
                                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                    <div class="overflow-hidden">
                                        <table class="min-w-full text-sm font-light text-left">
                                        <thead class="font-medium border-b dark:border-neutral-500">
                                            <tr>
                                                <th scope="col" class="px-6 py-4">Ingredient</th>
                                                <th scope="col" class="px-6 py-4">Qty</th>
                                                <th scope="col"></th>
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

                        </div>

                        <button :class="{saveEnable : 'bg-green-50'}" 
                                class="px-4 py-1 mt-8 bg-gray-200 border border-gray-400 rounded-lg">
                            Save
                        </button>
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

    defineProps({
        'meal': Object,
        'ingredients': Object,
        'units': Object,
        'foodItems': Object
    });

    
    let data = reactive({
        mealID: router.page.props.meal.id,
        isSearching: false,
        searchItems: [],
        errors: [],
        editingName: false,
        makeDuplicate: false
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
                preserveState: true
            });
    
        }, 300) );
    }

    watchSearch();

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
                preserveState: true
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
            preserveState: true
        });
    }

    let saveTitle = () => {
        router.post(`/meal/${data.mealID}/save-title`, { newTitle: router.page.props.meal.name } , {
            preserveState: true
        });
    }

    let duplicateRecipe = () => {
        router.post(`/meal/${data.mealID}/duplicate`, {
            preserveState: true
        });

        data.makeDuplicate = false;
    }
        
    let addToList = (item ) => {
        router.post(`/meal/${data.mealID}/add-to-shopping-list`, {
            preserveState: true
        });
    }

</script>