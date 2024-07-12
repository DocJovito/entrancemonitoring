<template>
    <div class="container mt-4">
        <div class="row">
            <div class="col-6" style="background-color: lightblue;">
                <EmpSelect />
            </div>
            <div class="col-6" style="background-color: lightcoral;">
                <TimeCard />
            </div>
        </div>
    </div>

</template>

<script setup>
import EmpSelect from "@/components/EmpSelect.vue"
import TimeCard from "@/components/TimeCard.vue"
</script>