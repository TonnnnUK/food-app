<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="Exercise Info" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Exercises
                </h2>
                <Link :href="route('exercises')" class="text-xs text-blue-600 hover:underline">&laquo; Back to exercises</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold">{{exercise.name}}</h2>

                        </div>

                        <div>
                            <p>{{exercise.description}}</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default{

    props: {
        'exercise': Object,
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