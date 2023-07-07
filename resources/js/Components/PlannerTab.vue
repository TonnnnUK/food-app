<script setup>
    defineProps({
        item: {
            type: String,
        },
        classes: {
            type: String
        }
    });
</script>
<template>
    <div class="w-auto p-3 text-xs capitalize transition duration-200 border border-b-0 border-gray-200 rounded-lg cursor-pointer md:rounded-t-lg md:rounded-b-none">
        {{ item }}
    </div>
</template>
