<template>
    <b-col lg="12">
        <BCard no-body class="mt-n4 mx-n4">
            <div class="bg-success-subtle">
                <b-card-body class="pb-0 px-4">
                     <b-row class="mb-3">
                        <div class="col-auto">
                            <a :href="selected.qr" target="_blank" rel="noopener noreferrer">
                                <div style="width: 50px; height: 50px;">
                                    <img :src="selected.qr" alt="user-img" class="img-thumbnail">
                                </div>
                            </a>
                        </div>
                        <b-col md>
                            <b-row class="align-items-center g-3">
                                <b-col md>
                                    <div>
                                        <h4 class="fw-semibold text-success mb-1">{{ selected.title }}</h4>
                                        <div class="hstack gap-3 flex-wrap">
                                            <div><span class="fw-medium">{{selected.event.name }}</span></div>
                                            <div class="vr" style="width: 1px;"></div>
                                            <div>Date : 
                                                <span class="fw-medium" v-if="selected.event.start == selected.event.end">{{selected.event.start}}</span>
                                                <span class="fw-medium" v-else>{{selected.event.start}} - {{selected.event.end}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </b-col>
                            </b-row>
                        </b-col>
                        <b-col md="auto">
                            <div class="hstack gap-4 flex-wrap mt-2">
                                <Link :href="'/sessions'">
                                    <div class="text-muted" @click="hide()">  
                                        <i class="ri-close-circle-fill fs-16"></i> Close
                                    </div>
                                </Link>
                                <div class="vr" style="width: 1px;"></div>
                                <div v-if="selected.status.name === 'Pending'">  
                                    <b-button @click="openSave(selected.id)" variant="primary" block :disabled="(analyses == 0) ? true : false"><i class="ri-save-fill me-1"></i> Save</b-button>
                                </div>
                                 <div v-if="selected.status.name !== 'Pending'" @click="openStatus(selected.qr)">  
                                    <b-button variant="primary" block><i class="ri-information-fill me-1"></i>Update Status</b-button>
                                </div>
                                
                            </div>
                        </b-col>
                    </b-row>
                    
                </b-card-body>
            </div>
        </BCard>
    </b-col>
    <Status ref="status"/>
</template>
<script>
import Status from './Modals/Status.vue';
export default {
    props:['selected'],
    components: { Status },
    computed: {
        dateRangeText() {
            const schedules = this.selected?.schedules || [];

            if (schedules.length === 0) return 'No date';

            let start = schedules[0].date;
            let end = schedules[0].date;

            schedules.forEach(s => {
                if (s.date < start) start = s.date;
                if (s.date > end) end = s.date;
            });

            const formatDate = (dateStr) => {
                const date = new Date(dateStr);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            };

            return start === end
                ? formatDate(start)
                : `${formatDate(start)} - ${formatDate(end)}`;
        }
    },
    methods: { 
        openStatus(){
            this.$refs.status.show(this.selected);
        }
    }
}
</script>