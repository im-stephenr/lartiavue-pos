<script setup>
const props = defineProps({
    isOpen: Boolean,
});

defineEmits(["handleToggleModal"]);
</script>
<template>
    <Transition v-if="isOpen" name="modal">
        <div class="modal-mask">
            <div class="modal-container relative">
                <div class="modal-header">
                    <slot name="header">default header</slot>
                    <button
                        @click.prevent="$emit('closeModal')"
                        type="button"
                        class="cursor-pointer absolute right-[10px] top-[10px]"
                        title="Close Modal"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="size-4 text-gray-400 hover:text-gray-700"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18 18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <div class="modal-body">
                    <slot name="body">No Content</slot>
                </div>

                <div class="modal-footer">
                    <slot name="footer">
                        default footer
                        <button
                            type="button"
                            class="modal-default-button bg-blue-500 py-1 px-4 text-white"
                        >
                            OK
                        </button>
                    </slot>
                </div>
            </div>
        </div>
    </Transition>
</template>
