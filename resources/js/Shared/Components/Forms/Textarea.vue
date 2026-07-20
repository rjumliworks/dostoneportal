<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
    light: Boolean,
    modelSize: {
        type: Number,
        default: 3
    }

});

defineEmits(['update:modelValue']);
const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <textarea ref="input" class="form-control" :rows="modelSize"  :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" style="min-height: 38.4px !important;" :style="(light) ? 'background-color: #f5f6f7;' : ''"/>
</template>
