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
    <div class="w-auto p-3 text-xs capitalize transition duration-200 border-b border-gray-200 rounded-lg cursor-pointer">
        {{ item }}
    </div>
</template>
