<script setup>
defineProps({
    item: {
        type: String,
    },
});
</script>

<template>
    <span class="px-3 py-1 text-xs transition duration-200 bg-blue-300 rounded-full hover:bg-blue-400 hover:cursor-pointer">
        {{ item }}
    </span>
</template>
