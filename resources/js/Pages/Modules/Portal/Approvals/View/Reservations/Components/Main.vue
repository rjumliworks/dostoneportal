<template>
    <div class="card bg-light-subtle shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-chat-history-fill text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">Travel Order Activity</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">Includes official comments, document references, and chronological status updates</p>
                </div>
            </div>
        </div>
        <div class="card-body bg-white border-bottom border-bottom">
            <div class="row g-3 p-0">
                <div class="col-md-4">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div v-if="information.status.name == 'Pending'" class="avatar-title bg-light rounded-circle fs-16 text-warning">
                                <i class="ri-record-circle-fill"></i>
                            </div>
                            <div v-else-if="information.status.name == 'Recommended'" class="avatar-title bg-light rounded-circle fs-16 text-secondary">
                                <i class="ri-record-circle-fill"></i>
                            </div>
                            <div v-else-if="information.status.name == 'Approved'" class="avatar-title bg-light rounded-circle fs-16 text-success">
                                <i class="ri-checkbox-circle-fill"></i>
                            </div>
                            <div v-else-if="information.status.name == 'Disapproved'" class="avatar-title bg-light rounded-circle fs-16 text-danger">
                                <i class="ri-close-circle-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Status :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{information.status.name}} </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div v-if="information.is_disapproved" class="avatar-title bg-light rounded-circle fs-16 text-danger">
                                <i class=" ri-close-circle-fill"></i>
                            </div>
                            <div v-else-if="!information.recommended" class="avatar-title bg-light rounded-circle fs-16 text-warning">
                                <i class="ri-close-circle-fill"></i>
                            </div>
                            <div v-else-if="information.recommended" class="avatar-title bg-light rounded-circle fs-16 text-success">
                                <i class="ri-checkbox-circle-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Recommended by :</p>
                            <h6 v-if="!information.recommended" class="text-truncate fw-semibold fs-12 mb-0">
                                <span v-if="information.status.name == 'Disapproved'">-</span>
                                <span v-else>Pending</span>
                            </h6>
                            <h6 v-else class="fw-semibold fs-12 mb-0">{{information.recommended.name}}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div v-if="information.is_disapproved" class="avatar-title bg-light rounded-circle fs-16 text-danger">
                                <i class=" ri-close-circle-fill"></i>
                            </div>
                            <div v-else-if="!information.approved" class="avatar-title bg-light rounded-circle fs-16 text-warning">
                                <i class=" ri-close-circle-fill"></i>
                            </div>
                            <div v-else-if="information.approved" class="avatar-title bg-light rounded-circle fs-16 text-success">
                                <i class=" ri-checkbox-circle-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Approved by :</p>
                            <h6 v-if="!information.approved" class="text-truncate fw-semibold fs-12 mb-0">
                                <span v-if="information.status.name == 'Disapproved'">-</span>
                                <span v-else>Pending</span>
                            </h6>
                            <h6 v-else class="text-truncate fw-semibold fs-12 mb-0">{{information.approved.name}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card bg-white rounded-bottom shadow-none mb-0">
            <div class="step-arrow-nav mt-0">
                <ul class="nav nav-pills nav-justified custom-nav" role="tablist">
                    <li @click="openMenu(menu)" class="nav-item" role="presentation" v-for="(menu, index) in menus" v-bind:key="index">
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
                                    <template v-if="menu == 'Home'">
                                        <div class="card-body bg-white rounded-bottom" style="height: calc(100vh - 592px); overflow: auto;">
                                            <div class="d-flex mb-4" :class="{ 'border-bottom': index !== information.comments.length - 1 }" v-for="(list,index) in information.comments" v-bind:key="index">
                                                <div class="flex-shrink-0">
                                                    <img :src="list.avatar" alt="" class="avatar-xs rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <BLink  @click="setReply(list)" href="javascript: void(0);" class="badge text-muted bg-light float-end"><i class="mdi mdi-reply"></i>Reply</BLink>
                                                    <h5 class="fs-13 mb-1">
                                                        <span class="fw-semibold text-primary">{{list.name}}</span>
                                                        <small class="text-muted ms-2">{{timeAgo(list.created_at)}}</small>
                                                    </h5>
                                                    <p>{{list.content}}</p>
                                                    
                                                    <div class="d-flex" :class="index2 === 0 ? 'mt-4' : 'mt-1'" v-for="(sub,index2) in list.replies" v-bind:key="index2">
                                                        <div class="flex-shrink-0">
                                                            <img :src="list.avatar" alt="" class="avatar-xs rounded-circle" />
                                                        </div>
                                                        
                                                        <div class="flex-grow-1 ms-3">
                                                            <h5 class="fs-13 mb-1">
                                                            <span class="fw-semibold text-primary">{{sub.name}}</span>
                                                            <small class="text-muted ms-2">{{timeAgo(sub.created_at)}}</small></h5>
                                                            <p>{{sub.content}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <Signatories :information="information" :statuses="information.statuses" v-if="menu == 'Signatories'" />
                                    <Attachment :information="information" v-if="menu == 'Attachment'" />
                                </div>
                            </transition>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card-footer" v-if="menu == 'Home'">
            <form>
                <BRow class="g-0 align-items-center">
                    <BCol cols="auto">
                        <div class="chat-input-links me-2">
                            <div class="links-list-item">
                                <BButton type="button" variant="link" class="text-decoration-none emoji-btn" id="emoji-btn">
                                    <i class="bx bx-smile align-middle"></i>
                                </BButton>
                            </div>
                        </div>
                    </BCol>
                    <BCol>
                        <!-- Basic example -->
                        <div class="input-group" v-if="replyuser">
                            <span class="input-group-text" id="basic-addon1">{{replyuser}}</span>
                            <input type="text" v-model="form.content" class="form-control" placeholder="Add reply.." aria-label="Username" aria-describedby="basic-addon1">
                        </div> 
                        <input type="text" v-else v-model="form.content" class="form-control chat-input bg-light border-light" placeholder="Add Comment.." >
                    </BCol>
                    <BCol cols="auto">
                        <div class="chat-input-links ms-2">
                            <div class="links-list-item">
                                <BButton @click="submit('ok')" variant="success" type="button" class="chat-send">
                                    <i class="ri-send-plane-2-fill align-bottom"></i>
                                </BButton>
                            </div>
                        </div>
                    </BCol>
                </BRow>
            </form>
       </div>
        
    </div>
</template>
<script>
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);
import { useForm } from '@inertiajs/vue3';
import Attachment from '../../../Pages/Attachment.vue';
import Signatories from '../../../Pages/Signatories.vue';
import simplebar from "simplebar-vue";
import Multiselect from "@vueform/multiselect";
export default {
    components: { simplebar, Multiselect, Signatories, Attachment },
    props: ['information'],
    data(){
        return {
            form: useForm({
                request_id: this.information.id,
                content: null,
                comment_id: null,
                option: 'comment'
            }),
            menus: [
                'Home','Signatories','Attachment'
            ],
            menu: 'Home',
            replyuser: null,
            now: dayjs()
        }
    },
    computed: {
        r() {
            return this.information.filter(s => s.is_approval_only === 0);
        },
        rc() {
            return this.r.length;
        },
        crc() {
            return this.r.filter(s => s.recommended_id !== null).length;
        },
        approvalStatus() {
            const allApproved = this.information.every(s => s.approved_id !== null);
            return allApproved ? 1 : 0;
        }
    },
    mounted() {
        // Update every 60 seconds
        this.interval = setInterval(() => {
            this.now = dayjs(); // re-triggers computed values
        }, 60000);
    },
    beforeUnmount() {
        clearInterval(this.interval);
    },
    methods: { 
        openMenu(menu){
            this.menu = menu;
        },
        setReply(comment) {
            this.form.comment_id = comment.id; // store the user you're replying to
            this.replyuser = `@${comment.name} `;
            this.form.option = 'reply';
        },
        submit(){
            this.form.post('/comment',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.form.reset();
                    this.form.option = 'comment';
                    this.replyuser = null;
                },
            });
        },
        timeAgo(date) {
            return dayjs(date).from(this.now);
        }
    }
}
</script>