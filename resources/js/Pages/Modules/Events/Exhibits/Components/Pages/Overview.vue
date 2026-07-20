<template>
    <div class="row g-3" style="height: calc(100vh - 408px);">
        
 
    </div>
</template>
<script>
    export default {
        props: ['selected'],
        data() {
            return {
                chartOptions: {
                    chart: {
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            distributed: true
                        }
                    },
                    colors: [
                        '#0d6efd', // Blue
                        '#ffc107', // Yellow
                        '#198754'  // Green
                    ],
                    xaxis: {
                        categories: ['Capacity', 'Registered', 'Attended']
                    },
                    legend: {
                        show: false
                    }
                }
            }
        },
        methods: {
           
        }
    }

</script>
<style scoped>
.donut-chart {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    position: relative;
}

.donut-inner {
    position: absolute;
    inset: 10px;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 10px;
}</style>