<script setup>
    import GuestLayout from '@/Layouts/GuestLayout.vue';
</script>

<template>
    <Head title="Workout Info" />

    <GuestLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Workouts
                </h2>
                <Link :href="route('workouts')" class="text-xs text-blue-600 hover:underline">&laquo; Back to workouts</Link>
            </div>
        </template>

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

                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script>
export default{

    props: {
        'workout': Object,
    },

}


</script>