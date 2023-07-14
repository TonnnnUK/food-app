<template>
    <div class="p-4">
        <div class="flex items-center justify-between mb-4 ">
            <h3 class="text-lg">PLANNER ENTRY FORM</h3>
        </div>

        
        <div class="flex items-center gap-2 mb-2">
            <label class="text-xs text-right w-28" for="">Date</label>
            <span>{{formatDate}}</span>
        </div>

        <div class="flex items-center gap-2 mb-2">
            <label class="text-xs text-right w-28" for="">Entry Type</label>
            <select class="text-xs border border-gray-400 rounded-lg" v-model="entry.entry_type">
                <option value="0">-- Select entry type --</option>
                <option v-for="entry_type in data.entry_types" :value="entry_type" :key="entry_type">
                    {{ entry_type }}
                </option>
            </select>
        </div>

        <div class="flex items-center gap-2 mb-2" v-if="props.entry.entry_type == 'Meal'">
            <label class="text-xs text-right w-28" for="">Meal</label>
            <select class="text-xs border border-gray-400 rounded-lg" v-model="entry.meal_id">
                <option value="0">-- Select meal --</option>
                <option v-for="meal in props.entry.options.meals" :value="meal.id" :key="meal.id">
                    {{ meal.name }}
                </option>
            </select>
        </div>
        
        <div class="flex items-center gap-2 mb-2" v-if="props.entry.entry_type == 'Recipe'">
            <label class="text-xs text-right w-28" for="">Recipe</label>
            <select class="text-xs border border-gray-400 rounded-lg" v-model="entry.recipe_id">
                <option value="0">-- Select recipe --</option>
                <option v-for="recipe in props.entry.options.recipes" :value="recipe.id" :key="recipe.id">
                    {{ recipe.name }}
                </option>
            </select>
        </div>
        
        <div class="flex items-center gap-2 mb-2" v-if="props.entry.entry_type == 'Workout'">
            <label class="text-xs text-right w-28" for="">Workout</label>
            <select class="text-xs border border-gray-400 rounded-lg" v-model="entry.workout_id">
                <option value="0">-- Select workout --</option>
                <option v-for="workout in props.entry.options.workouts" :value="workout.id" :key="workout.id">
                    {{ workout.name }}
                </option>
            </select>
        </div>
        
        <div class="flex items-center gap-2 mb-2" v-if="props.entry.entry_type == 'Custom Workout'">
            <label class="text-xs text-right w-28" for="">Custom Workout</label>
            <select class="text-xs border border-gray-400 rounded-lg" v-model="entry.custom_workout_id">
                <option value="0">-- Select workout --</option>
                <option v-for="workout in props.entry.options.custom_workouts" :value="workout.id" :key="workout.id">
                    {{ workout.name }}
                </option>
            </select>
        </div>

        <div class="flex gap-2 mb-2" v-if="entry.entry_type != 0">
            <label class="text-xs text-right w-28" for="">Notes</label>
            <textarea class="w-1/2 text-xs border-gray-300 rounded-lg" v-model="entry.entry_data.notes"></textarea>
        </div>

        <div class="my-4" v-if="Object.keys(data.errors).length > 0">
            <span class="block text-xs text-red-600" v-for="error in Object.keys(data.errors)" :key="error">
                {{ data.errors[error] }}
            </span>
        </div>

        <div class="flex items-center justify-between">
            <button class="px-4 py-2 text-xs text-white transition duration-200 bg-purple-400 rounded-lg hover:bg-purple-500"
                v-on:click="addToPlanner()"
            >
                Save
            </button>

            <span class="text-xs text-red-600 hover:underline hover:cursor-pointer" 
                    v-if="props.editingEntry"
                    v-on:click="deleteEntry()"
            >
                Delete
            </span>
        </div>

    </div>
</template>
<script setup>
    import { reactive, computed } from "vue";
    import { router } from '@inertiajs/vue3';
    import { CalendarService } from '../Services/CalendarService';
    import TextInput from '@/Components/TextInput.vue';

    const props = defineProps({
        show: {
            type: Boolean,
            default: false,
        },
        editingEntry: {
            type: Boolean,
            default: false,
        },
        entry: {
            type: Object,
        },
    });

    const emit = defineEmits(['addedEntry']);


    let data =  reactive({
        entry_types: {
            'recipes': 'Recipe', 
            'meals': 'Meal', 
            'workouts': 'Workout', 
            'custom_workouts': 'Custom Workout',
            'note': 'Note',
        },
        errors: []
    });

    let addToPlanner = () => {

        validateData(); 

        router.post(`/entry/add`, { entry: props.entry}, {
            preserveState: true,
            preserveScroll: true
        });

        emit('addedEntry');
        
    }


    let validateData = () => {
        data.errors = [];
        if( 
            !Number.isInteger(props.entry.recipe_id) && 
            !Number.isInteger(props.entry.meal_id) && 
            !Number.isInteger(props.entry.workout_id) && 
            !Number.isInteger(props.entry.custom_workout_id) 
        
        ){
            data.errors['invalid_ids'] = 'Something went wrong'
        }

    }

    let Calendar = new CalendarService;
    let formatDate = props.entry.date_time.getDate()+' '+Calendar.months[props.entry.date_time.getMonth()]+' '+props.entry.date_time.getFullYear();

</script>