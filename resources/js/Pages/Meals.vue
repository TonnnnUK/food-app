<template>
    <Head title="Meals" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold leading-tight text-gray-800 md:text-xl">
                Meals
            </h2>
        </template>


        <div class="p-4 sm:p-6 lg:p-8 xl:p-12">


            <form @submit.prevent="addMeal" class="flex flex-col w-full gap-2 my-2 md:items-center md:my-5 md:flex-row md:flex-wrap">
                <label class="block w-auto" for="">New Meal</label>
                <div class="flex flex-col md:items-center gap-y-2 md:gap-x-2 md:flex-row lg:w-auto">
                    <div class="w-[95%] lg:w-1/3">
                        <input class="w-[95%] md:w-full text-sm border-gray-300 rounded-lg" placeholder="Enter a meal name" type="text" v-model="newMeal.name">
                    </div>
                    <div class="w-1/3">
                        <input class="w-[95%] text-sm border-gray-300 rounded-lg" placeholder="Servings" type="text" v-model="newMeal.servings">
                    </div>
                    <div class="w-full md:w-auto">
                        <FormButton class="text-white bg-green-500 hover:bg-green-600">Add</FormButton>
                    </div>
                </div>
            </form>

            <div class="mx-auto mt-4 max-w-7xl">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-4 bg-white border-b border-gray-200 md:p-6">
                        <div class="flex items-center justify-between mb-2 md:mb-4">
                           <h2 class="text-xl font-bold">Your meals</h2>
                            <SmallButton class="bg-gray-300 hover:bg-gray-400 focus:ring-gray-400"
                                v-on:click="filters.show = !filters.show"    
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
                                        v-on:click="selectTag(index)"
                                    >
                                        {{ tag }}
                                    </TagButton>
                                 </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap my-4 gap-y-2 md:my-2">
                            <div v-for="meal of meals" :key="meal.id" class="flex items-center w-full gap-2">
                                <Link  
                                    class="text-blue-800 lg:text-sm hover:underline hover:text-blue-900" :href="route('meal-info', meal.id)">
                                    {{meal.name}}
                                </Link>
                                <span class="text-xs">- {{ meal.ingredients.length }} items</span>
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
import { reactive, ref, watch } from "vue";
import SmallButton from '@/Components/SmallButton.vue';
import FormButton from '@/Components/FormButton.vue';
import TagButton from '@/Components/TagButton.vue';


    defineProps({
        'meals': Object,
        'tags': Object,
        'food_item_tags': Object,
    });

    let filters = reactive({
        show: false
    })

    let newMeal = useForm({
        name: '',
        servings: ''
    });

    let addMeal = () => {
        newMeal.post('/meals')
        newMeal.name = '';
    }

    let selectTag = ( slug ) => {
        if( Number.isInteger(slug) ){
            console.log('selected tag is number', slug)
            router.get('/meals', { tag: slug }, {
                preserveState: true,
                preserveScroll: true
            });
        } else {
            router.get('/meals', { food_item: slug }, {
                preserveState: true,
                preserveScroll: true
            });
        }
    }
</script>
