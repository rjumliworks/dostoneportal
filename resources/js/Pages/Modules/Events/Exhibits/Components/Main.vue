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
                            <h5 class="mb-0 fs-14"><span class="text-body">More About This Exhibitor</span></h5>
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
                                    <span v-if="index == 1" class="badge bg-info ms-1 position-relative" style="top: -2px;">{{ selected.visitors.length }}</span>
                                    <span v-if="index == 2" class="badge bg-info ms-1 position-relative" style="top: -2px;">{{ selected.voters.length }}</span>
                                    <span v-if="index == 3" class="badge bg-info ms-1 position-relative" style="top: -2px;">{{ selected.feedbacks.length }}</span>
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
                                            <Visitors :id="selected.id" :visitors="selected.visitors" v-else-if="menu == 'Visitors'"/>
                                            <Voters :id="selected.id" :voters="selected.voters" v-else-if="menu == 'Voters'"/>
                                            <Csf :feedbacks="selected.feedbacks" v-else-if="menu == 'Feedbacks'"/>
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
import Voters from './Pages/Voters.vue';
import Overview from './Pages/Overview.vue';
import Visitors from './Pages/Visitors.vue';
export default {
    components: { Visitors, Overview, Voters, Csf },
    props:['selected'],
    data(){
        return {
            currentUrl: window.location.origin,
            menus: [
                'Overview','Visitors','Voters','Feedbacks'
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
                if(this.selected.id == event.data.session_id){
                    switch(event.type){
                        case 'minus-ex':
                        
                        break;
                        case 'plus-ex':
                        
                        break;
                    }
                }
            });
        },
    }
}
</script>