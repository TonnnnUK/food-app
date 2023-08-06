<template>
    <Head title="Recipe" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Recipe
                    </h2>
                    <Link :href="route('edit-recipe', recipe.id)" class="text-xs text-blue-600 hover:underline"
                         v-if="router.page.props.auth.user.email == 'a.hutchinson86@gmail.com'"
                    >
                        Edit
                    </Link>
                </div>
                <Link :href="route('recipes')" class="text-xs text-blue-600 hover:underline">&laquo; Back to recipes</Link>
            </div>
        </template>

        <div class="py-4 md:py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold">{{recipe.name}}</h2>

                            <div  class="flex items-center gap-2 text-sm">
                                <span>Servings: {{ recipe.servings }} </span>
                            </div>
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
                                            <th scope="col" class="flex justify-end">
                                                <button class="px-3 py-2 transition duration-200 bg-yellow-200 rounded-lg hover:bg-yellow-300"
                                                        v-on:click="addAllToList()"
                                                        v-if="$page.props.auth.user.email != null"
                                                >
                                                    Add all to shopping list
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="border-b dark:border-neutral-500" v-for="ingredient of recipe.ingredients" :key="ingredient.id">
                                            <td class="px-6 py-4 font-medium whitespace-nowrap"><span class="capitalize">{{ ingredient.name }}</span></td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ ingredient.pivot.qty }} {{ units[ingredient.pivot.unit_id] }}</td>
                                            <td class="px-6 py-4 text-right whitespace-nowrap">
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
import { reactive, ref, watch } from "vue";
import SmallButton from '@/Components/SmallButton.vue';
import FormButton from '@/Components/FormButton.vue';
import TagButton from '@/Components/TagButton.vue';
import { UrlParamService } from '@/Services/URLParamService';

defineProps({
    'recipe': Object,
    'units': Object
});

console.log(router);

</script>