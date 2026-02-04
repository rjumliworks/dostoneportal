<template>
    <div class="card bg-light-subtle shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-line-chart-fill text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">{{year}} Monthly Transaction Overview</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">These terms clearly indicate that the chart provides a monthly summary of the collected, uncollected, and total transaction values</p>
                </div>
            </div>
        </div>
        <div class="card-body bg-white border-bottom">
            <div class="d-flex justify-content-between mb-2">
                <button class="btn btn-outline-primary btn-sm" @click="prevWeek">Prev Week</button>
                <span class="fw-bold">{{ formatWeekRange(currentWeek) }}</span>
                <button class="btn btn-outline-primary btn-sm" @click="nextWeek">Next Week</button>
            </div>
            <apexchart ref="realtimeChart" class="apex-charts" type="bar" dir="ltr" :series="series"
                :options="chartOptions">
            </apexchart>
        </div>
        <div class="card-body"></div>
    </div>
</template>
<script>
import _ from 'lodash';
import moment from 'moment';
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            total: [],
            year: new Date().getFullYear(),
            series: [],
            currentWeek: null,
            chartOptions: {
                chart: {height: 303.5,toolbar: {show: false,},},
                markers: {
                    size: [0, 0, 0],
                    strokeWidth: 2,
                    hover: { size: 4},
                },
                xaxis: {
                    categories: [],
                    axisTicks: {show: false},
                    axisBorder: {show: false},
                },
                grid: {
                    show: true,
                    xaxis: {lines: {show: true}},
                    yaxis: {lines: { show: false}},
                    padding: { top: 0,right: -2,bottom: 15,left: 10,},
                },
                legend: {
                    show: true,
                    horizontalAlign: "center",
                    offsetX: 0,
                    offsetY: -5,
                    markers: {width: 9,height: 9,radius: 6},
                    itemMargin: { horizontal: 10, vertical: 0},
                },
                 dataLabels: {
                    enabled: false, 
                },
                plotOptions: {
                bar: {
                    columnWidth: "60%",
                    barHeight: "70%",
                },
                },
                colors: ["#34c38f", "#ffd478", "#ea6868"],
            },
        }
    },
    created() {
        this.currentWeek = moment().isoWeekday(1).format('YYYY-MM-DD');
        this.fetch();
    },
    methods: {
        formatWeekRange(start) {
            const startDate = moment(start);            // Monday
            const endDate = startDate.clone().isoWeekday(5); // Friday
            return `${startDate.format('MMM DD')} - ${endDate.format('MMM DD')}`;
        },
        fetch() {
            axios.get(this.currentUrl + '/humanresource',{
                params : {
                    option : 'bar',
                    week: this.currentWeek,
                }
            })
            .then(response => {
                this.provinces = response.data.provinces;
                this.chartOptions = {
                    ...this.chartOptions,
                    ...{
                        xaxis: {
                            categories: response.data.categories
                        }
                    }
                };
                this.series = response.data.lists;
                this.total = response.data.total;

                this.$nextTick(() => {
                    setTimeout(() => {
                        window.dispatchEvent(new Event('resize'));
                    }, 100);
                });
            })
            .catch(err => console.log(err));
        },
        openView(){
            this.$refs.industry.show();
        },
        updateYear(year){
            this.year = year;
            this.fetch();
        },
        prevWeek() {
            this.currentWeek = moment(this.currentWeek).subtract(1, 'week').isoWeekday(1).format('YYYY-MM-DD');
            this.fetch();
        },
        nextWeek() {
            const next = moment(this.currentWeek).add(1, 'week').isoWeekday(1);
            if(next.isAfter(moment(), 'week')) return; // prevent future weeks
            this.currentWeek = next.format('YYYY-MM-DD');
            this.fetch();
        }
    }
}
</script>