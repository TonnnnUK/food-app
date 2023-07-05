<script setup>
import Header from '../Shared/Header.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
        <Header></Header>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="p-4 mx-auto max-w-7xl md:py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
