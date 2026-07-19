<template>
    <BRow>
        <div class="col-md-12">
           <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-file-text-line text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">More About This Session</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">Explore key details about this event — from featured sessions and venues to organizers, exhibitors, and highlights.</p>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-white rounded-bottom shadow-none mb-0">
                    <div class="step-arrow-nav mt-0">
                        <ul class="nav nav-pills nav-justified custom-nav" role="tablist">
                            <li class="nav-item" role="presentation" v-for="(menu, index) in menus" v-bind:key="index">
                                <button class="nav-link fs-12 p-3" :class="(index == 0) ? 'active' : ''" 
                                    :id="menu+'-tab'" data-bs-toggle="pill" :data-bs-target="'#'+menu" 
                                    type="button" role="tab" :aria-controls="menu" aria-selected="true">
                                    {{menu}}  
                                    <span v-if="index == 2" class="badge bg-info ms-1 position-relative" style="top: -2px;">{{ selected.participants.length }}</span>
                                    <span v-if="index == 3" class="badge bg-info ms-1 position-relative" style="top: -2px;">{{ selected.questions.length }}</span>
                                    <span v-if="index == 4" class="badge bg-info ms-1 position-relative" style="top: -2px;">{{ selected.feedbacks.length }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body bg-white rounded-bottom">
                    <div class="tab-content">
                        <div class="tab-pane" :class="(index == 0) ? 'show active' : ''" :id="menu" role="tabpanel" :aria-labelledby="menu+'-tab'" v-for="(menu, index) in menus" v-bind:key="index">
                            
                            <div class="carousel-container">
                                <div class="carousel-content">
                                    <transition mode="out-in">
                                        <div :key="index" class="tab-content">
                                            <Overview :selected="selected" v-if="menu == 'Overview'" />
                                            <Activity :id="selected.id" :activities="selected.activities" :schedules="selected.schedules" v-else-if="menu == 'Activities'"/>
                                            <Participant :id="selected.key" :participants="selected.participants" v-else-if="menu == 'Participants'"/>
                                            <Certificate  v-else-if="menu == 'Certificates'"/>
                                            <Question :questions="selected.questions" v-else-if="menu == 'Questions'"/>
                                            <Csf :feedbacks="selected.feedbacks" v-else-if="menu == 'CSF'"/>
                                         
                                        </div>
                                    </transition>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BRow>
</template>
<script>
import Csf from './Pages/Csf.vue';
import Overview from './Pages/Overview.vue';
import Activity from './Pages/Activity.vue';
import Question from './Pages/Question.vue';
import Participant from './Pages/Participant.vue';
import Certificate from './Pages/Certificate.vue';
export default {
    components: { Activity, Participant, Certificate, Overview, Question, Csf },
    props:['selected'],
    data(){
        return {
            currentUrl: window.location.origin,
            menus: [
                'Overview','Activities','Participants','Questions','CSF'
            ],
            menu: 'Overview',
            index: null,
        }
    },
    mounted() {
        this.setupEchoListener();
    },
    methods: {
        setupEchoListener() {
            window.Echo.channel('session')
            .listen('SessionEvent', (event) => {
                console.log(event.data);
                console.log(this.selected.id == event.data.session_id);
                if(this.selected.id == event.data.session_id){
                    switch(event.type){
                        case 'register':
                            this.selected.participants.unshift(event.data);
                        break;
                        case 'cancel':
                            const index = this.selected.participants.findIndex(p => p.participant_id === event.data.participant_id);
                            if (index !== -1) {
                                this.selected.participants.splice(index, 1);
                            }
                        break;
                        case 'question':
                            this.selected.questions.unshift(event.data);
                        break;
                        case 'feedback':
                            this.selected.feedbacks.unshift(event.data);
                        break;
                        case 'attendance':
                            const index2 = this.selected.participants.findIndex(p => p.code === event.data.code);
                            this.selected.participants[index2] = event.data;
                        break;
                    }
                }
            });
        },
    }
}
</script>