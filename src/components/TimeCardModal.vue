<template>
    <div v-if="isVisible" class="modal-overlay" @click="closeModal">
        <div class="modalbg " @click.stop>
            <div class="modal-header">
                <h1>Title</h1>
            </div>
            <div class="modal-body  ">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi quis culpa ab dicta, fugiat
                    consequatur iste neque autem modi dolorum, ipsum saepe libero expedita, illum cupiditate? Architecto
                    aliquid, nulla iusto sunt in quasi saepe praesentium necessitatibus consequuntur ad, corrupti
                    laborum. Eaque, nesciunt nisi recusandae inventore distinctio dolorum blanditiis cupiditate odio.
                </p>
            </div>
            <div class="modal-footer mt-2">
                <button class="btn btn-success" @click="printID">Print</button>
                <button class="btn btn-danger" @click="closeModal">Close</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    isVisible: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
        default: 'Modal Title',
    },
});

const emit = defineEmits(['close']);

const closeModal = () => {
    emit('close');
};

function printID() {
    window.print();
}
</script>

<style scoped>
.modal-overlay {

    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modalbg {
    background: white;
    border-radius: 0;
    width: 1200px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
}

.modal-body {
    display: flex;
    margin: 0;
}

.modal-footer {
    display: flex;
    justify-content: center;
    gap: 10px;
}

@media print {
    body * {
        visibility: hidden;
    }

    .modal-overlay,
    .modalbg {
        visibility: visible;
        position: static !important;
        /* Ensure it's not fixed for printing */
        width: auto !important;
        /* Allow natural width */
    }

    .modal-body {
        display: block;
        /* Ensure body content flows */
    }

    .print-hide {
        display: none;
    }
}
</style>
