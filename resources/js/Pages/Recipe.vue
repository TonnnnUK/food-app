<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { reactive, watch, ref } from "vue";
</script>

<template>
    <Head title="Recipe" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Recipe
                </h2>
                <Link :href="route('meals')" class="text-xs text-blue-600 hover:underline">&laquo; Back to meals</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold">{{meal.name}}</h2>

                            <div  class="flex items-center gap-2 text-sm">
                                <label for="">Servings</label>
                                <input class="text-sm rounded-lg" type="number" v-model="meal.servings">
                            </div>
                        </div>

                        <ul>
                            <li v-for="ingredient of meal.ingredients" :key="ingredient.id">
                                {{ ingredient.name }} - {{ ingredient.pivot.qty }} {{ ingredient.pivot.unit == 'number' ? '' : ingredient.pivot.unit }}
                            </li>
                        </ul>

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

<script>
export default{

    props: {
        'meal': Object,
        'ingredients': Object
    },
    
    setup() {
        const state = reactive({
            saveEnable: false
        });    

        return {
            state
        };
    }
}


</script>