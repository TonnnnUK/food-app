<template>
    <AuthenticatedLayout>
        <Head :title="workout.name + '  Information'" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <div class="p-6 bg-white rounded-lg md:mb-4 lg:mb-5">
                    <h2 class="text-lg">Add Exercise</h2>

                    <TextInput class="sm:w-[75%] md:w-[50%]" 
                        type="text" 
                        placeholder="Search exercies..." 
                        v-model="search">
                    </TextInput>
                </div>
                
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold">{{workout.name}}</h2>
                        </div>

                        <div>
                            <p>{{workout.description}}</p>
                        </div>


                        <div v-for="set of workout.exercise_sets" :key="set.exercise.id">
                            {{ set.exercise.name }} - {{ set.reps }} reps
                        </div>
                        
                        <div class="mt-4">
                            <h2 class="text-lg font-bold">Equipment needed for this workout</h2>
                            <div v-for="item of equipment" :key="item">
                                {{ item.name }}
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
    import { watch, ref } from "vue";
    import debounce from "lodash/debounce";
    import TextInput from '@/Components/TextInput.vue';

    defineProps({
        'workout': Object,
        'equipment': Object,
    });

    const queryString = window.location.search;
    const searchParams = new URLSearchParams(queryString);
    const searchValue = searchParams.get('search');

    let search = searchValue ? ref(searchValue) : ref('');
    let addExercise = {};

    let watchSearch = () => {
        watch( search, debounce( function(value){
    
            if ( value.length > 1 ) {
                // data.isSearching = true;
                addExercise.name = value.name;
                addExercise.id = value.id;
            }
            
            router.get(`/planner`, { search: value }, {
                preserveState: true,
                preserveScroll: true
            });
    
        }, 300) );
    }

    watchSearch();

</script>