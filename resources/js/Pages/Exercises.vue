<script setup>
    import GuestLayout from '@/Layouts/GuestLayout.vue';
</script>

<template>
    <Head title="Exercises" />

    <GuestLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Exercises
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 flex flex-wrap">

                        <div class="w-full sm:w-1/2 md:w-1/3 xl:1/4" v-for="exercise of exercises" :key="exercise.id">
                            <Link class="text-blue-700 hover:underline" :href="route('exercise-info', exercise.slug)">
                                {{exercise.name}}
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script>
export default 
{
    
    props: {
        'exercises': Object
    },

}
</script>
