<template>
    <div v-if="isVisible" class="modal-overlay" @click="closeModal">
        <div class="modalbg" @click.stop>
            <div class="modal-header print-hide">
                <!-- Header content (if any) -->
            </div>
            <div class="modal-body print-modal">
                <img src="https://icpmymis.com/images/ICPLogo.JPG" alt="Entrance Monitoring" class="center-image" @click="closeModal">
                <div class="text-container">
                    <h1>Entrance Monitoring System</h1>
                    <p>
                        Welcome to our Entrance Monitoring System. This system is designed to help manage and monitor the entry and exit of individuals within a facility, ensuring security and efficiency.
                    </p>
                </div>
            </div>
            <div class="modal-footer mt-2 print-hide">
                <button class="btn btn-success" @click="printID">Print</button>
                <button class="btn btn-danger" @click="closeModal">Close</button>
            </div>
        </div>
    </div>
</template>

<script setup>

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
    background: rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modalbg {
    background: white;
    border-radius: 10px;
    width: 768px; /* Tablet size */
    padding: 20px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
}

.modal-body {
    text-align: center;
}

.center-image {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    cursor: pointer;
}

.text-container {
    margin-top: 20px;
}

h1 {
    font-size: 1.5em;
    margin-bottom: 10px;
    color: #343a40;
}

p {
    font-size: 1em;
    color: #6c757d;
    max-width: 600px;
    margin: 0 auto;
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
        width: auto !important;
    }

    .modal-body {
        display: block;
    }

    .print-hide {
        display: none;
    }
}
</style>
