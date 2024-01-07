<template>
    <AuthenticatedLayout>
        <Head :title="workout.name + '  Information'" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
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
                            <h2 class="text-xl font-bold">Equipment Needed</h2>
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
    
    defineProps({
        'workout': Object,
        'equipment': Object,
    });

</script>