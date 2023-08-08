<template>
    <Head title="Recipe" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Recipe
                </h2>
                <Link :href="route('recipes')" class="text-xs text-blue-600 hover:underline">&laquo; Back to recipes</Link>
            </div>
        </template>

        <div class="py-4 md:py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="px-2 overflow-hidden bg-white shadow-sm sm:rounded-lg md:px-4">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <h2 class="text-xl font-bold">{{recipe.name}}</h2>
                                <Link class="text-xs text-blue-600 hover:underline" :href="route('recipe-info', recipe.slug)">View</Link>
                            </div>

                            <div  class="flex items-center gap-2 text-sm">
                                <label for="">Servings</label>
                                <input class="text-sm rounded-lg" type="number" v-model="recipe.servings">
                            </div>
                        </div>
                    </div>

                    <div class="my-4">
                        <div class="flex flex-col gap-4">
                             <div class="flex flex-col">
                                <label class="px-2 text-sm">Link</label>
                                <TextInput class="w-full px-3 py-1 text-sm border sm:w-1/2 lg:w-1/3" v-model="recipe.link" />
                            </div>   
                            <div class="flex flex-col lg:w-2/3">
                                <label class="px-2 text-sm">Description</label>
                                <textarea cols="30" rows="2" class="text-sm text-gray-700 border border-gray-300 rounded-lg" v-model="recipe.description">Description</textarea>
                            </div>
                            <div class="flex flex-col lg:w-2/3">
                                <label class="px-2 text-sm">Method</label>
                                <textarea cols="30" rows="6" class="text-sm text-gray-700 border border-gray-300 rounded-lg" v-model="recipe.method">Method</textarea>
                            </div>

                            <div>
                                <PrimaryButton v-on:click="saveRecipe()">Save</PrimaryButton>
                            </div>
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


                    <div class="flex flex-col w-full px-2 md:px-4 xl:w-9/12">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                <div class="overflow-hidden">
                                    <table class="min-w-full text-sm font-light text-left">
                                    <thead class="font-medium border-b dark:border-neutral-500">
                                        <tr>
                                            <th scope="col" class="px-6 py-4">Ingredient</th>
                                            <th scope="col" class="px-6 py-4">Qty</th>
                                            <th>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="border-b dark:border-neutral-500" v-for="ingredient of recipe.ingredients" :key="ingredient.id">
                                            <td class="px-6 py-4 font-medium whitespace-nowrap"><span class="capitalize">{{ ingredient.name }}</span></td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ ingredient.pivot.qty }} {{ unitsArray[ingredient.pivot.unit_id] }}</td>
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
        'recipe': Object,
        'ingredients': Object,
        'units': Object,
        'foodItems': Object,
        'foodTypes': Object,
        'tags': Object,
        'fetchTags': Object,
        'unitsArray': Object,
    });
        
    let data = reactive({
        recipeID: router.page.props.recipe.id,
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
            
            router.get(`/recipe/${data.recipeID}`, { search: value}, {
                preserveState: true,
                preserveScroll: true
            });
    
        }, 300) );
    }

    watchSearch();
    
    let selectFoodType = (type) => {
        data.selectedFoodType = type
        router.get(`/recipe/${props.recipe.id}/edit`, { type: type.id }, {
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
        qty: '',
    });
    
    let addToMeal = () => {

        newIngredient.unit = router.page.props.units[newIngredient.unitID-1].name; // Set the unit name
        
        validateForm();

        if( Object.keys(data.errors).length == 0 ){
            newIngredient.post(`/recipe/${data.recipeID}`, { ingredient: data.newIngredient}, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (page) => {
                    newIngredient.id = 0;            
                    newIngredient.name = '';
                    newIngredient.unitID = '1';
                    newIngredient.unit = '';
                    newIngredient.qty = '';

                    selectFoodType(0);
                }
            });

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

        router.post(`/recipe/${data.recipeID}/remove/${ingredient.id}`, {
            preserveState: true,
            preserveScroll: true
        });
    }

    let saveRecipe = () => {
        router.post(`/recipe/${data.recipeID}/save`, {
            recipe: props.recipe
        },{
            preserveState: true,
            preserveScroll: true
            
        })
    }

</script>