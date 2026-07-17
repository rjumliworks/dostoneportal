<template>
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
                    <h5 class="mb-0 fs-14"><span class="text-body">More About This Event</span></h5>
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
                                    <Exhibitor :types="types" :id="selected.id" :exhibitors="selected.exhibitors" v-if="menu == 'Exhibitors'"/>
                                    <Session :id="selected.id" :venues="selected.venues" :sessions="selected.sessions" v-if="menu == 'Sessions'"/>
                                    <Venue :id="selected.id" :venues="selected.venues" :detail="selected.detail" v-if="menu == 'Venues'"/>
                                    <Organizer :sessions="selected.sessions" v-if="menu == 'Organizers'"/>
                                </div>
                            </transition>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Venue from './Pages/Venue.vue';
import Session from './Pages/Session.vue';
import Exhibitor from './Pages/Exhibitor.vue';
import Organizer from './Pages/Organizer.vue';
export default {
    components: { Session, Venue, Organizer, Exhibitor },
    props:['selected','types'],
    data(){
        return {
            currentUrl: window.location.origin,
            menus: [
                'Sessions','Exhibitors','Organizers','Venues','Highlights'
            ],
            menu: 'Sessions',
            index: null,
        }
    },
}
</script>