<template>
    <div class="card-body bg-white border-bottom shadow-none">
        <b-row class="mt-n3 mb-2 ms-n4 me-n4">
            <b-col lg>
                <div class="input-group mb-1">
                    <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                    <input type="text" v-model="keyword" placeholder="Search Participant" class="form-control" style="width: 30%;">
                </div>
            </b-col>
        </b-row>
        <div class="d-flex flex-wrap gap-2 mt-1" v-if="rankings.length > 0">
            <b-button
                v-for="(day,index) in rankings"
                v-bind:key="index"
                size="sm"
                :variant="isSelected(day) ? 'primary' : 'soft-primary'"
                @click="selectedDate = day.date"
            >
                <i class="ri-calendar-event-line align-bottom me-1"></i>{{ day.label }}
                <span class="badge ms-1" :class="isSelected(day) ? 'bg-white text-primary' : 'bg-primary text-white'">{{ day.rankings.length }}</span>
            </b-button>
        </div>
    </div>
    <div class="table-responsive table-card" style="height: calc(100vh - 460px);">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="bg-primary text-white thead-fixed">
                <tr class="fs-10">
                    <th style="width: 6%;" class="text-center">Rank</th>
                    <th>Participant</th>
                    <th style="width: 20%;" class="text-center">Affiliation</th>
                    <th style="width: 12%;" class="text-center">Points</th>
                    <th style="width: 15%;" class="text-center">Last Earned</th>
                </tr>
            </thead>
            <tbody v-if="filtered.length > 0">
                <tr v-for="(list,index) in filtered" v-bind:key="index" class="fs-12">
                    <td class="text-center">
                        <span v-if="index < 3" class="badge fs-12" :class="medalClass(index)">
                            <i class="ri-award-fill align-bottom"></i> {{ index + 1 }}
                        </span>
                        <span v-else>{{ index + 1 }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 avatar-xs me-2">
                                <img :src="list.avatar" class="rounded-circle avatar-xs" style="object-fit:cover;" alt="Avatar">
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-12 mb-0 fw-semibold text-primary text-uppercase">{{list.name}}</h5>
                                <p class="fs-12 text-muted mb-0">{{list.code}}</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">{{ list.affiliation?.name || '-' }}</td>
                    <td class="text-center">
                        <span class="badge bg-success-subtle text-success fs-12">{{ list.points }} pts</span>
                    </td>
                    <td class="text-center">{{ list.last_earned_at }}</td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        {{ rankings.length > 0 ? 'No points earned on this day yet.' : 'No points earned yet.' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    props: ['rankings'],
    data(){
        return {
            keyword: '',
            selectedDate: this.rankings.length ? this.rankings[this.rankings.length - 1].date : null,
        }
    },
    computed: {
        selectedDay(){
            return this.rankings.find(day => day.date === this.selectedDate) || { rankings: [] };
        },
        filtered(){
            const list = this.selectedDay.rankings || [];
            if(!this.keyword) return list;
            const keyword = this.keyword.toLowerCase();
            return list.filter(entry => (entry.name || '').toLowerCase().includes(keyword));
        }
    },
    methods: {
        isSelected(day){
            return this.selectedDate === day.date;
        },
        medalClass(index){
            return ['bg-warning text-dark','bg-secondary text-white','bg-danger-subtle text-danger'][index];
        }
    }
}
</script>
