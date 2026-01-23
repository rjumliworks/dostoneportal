<template>
    <div class="card-body bg-white rounded-bottom" style="height: calc(100vh - 592px); overflow: auto;">
        <div class="d-flex mb-4" v-for="(list,index) in information.comments" v-bind:key="index">
            <div class="flex-shrink-0">
                <img src="/assets/images/users/avatar-8.jpg" alt="" class="avatar-xs rounded-circle" />
            </div>
            <div class="flex-grow-1 ms-3">
                <h5 class="fs-13">{{list.user.profile.firstname}} {{list.user.profile.lastname}}
                    <small class="text-muted ms-2">{{list.created_at}}</small>
                </h5>
                <p class="text-muted">{{list.content}}</p>
                <BLink  @click="setReply(list)" href="javascript: void(0);" class="badge text-muted bg-light"><i class="mdi mdi-reply"></i>Reply</BLink>
                <div class="d-flex mt-4" v-for="(subcomment,index2) in list.replies" v-bind:key="index2">
                    <div class="flex-shrink-0">
                        <img src="/assets/images/users/avatar-10.jpg" alt="" class="avatar-xs rounded-circle" />
                    </div>
                    
                    <div class="flex-grow-1 ms-3">
                        <h5 class="fs-13">Alexis Clarke <small class="text-muted ms-2">22 Dec 2021 -
                                02:32PM</small></h5>
                        <p class="text-muted">Please be sure to check your Spam
                            mailbox to see if your email filters have identified the
                            email from Dell as spam.</p>
                        <BLink href="javascript: void(0);" class="badge text-muted bg-light"><i class="mdi mdi-reply"></i> Reply
                        </BLink>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import simplebar from "simplebar-vue";
export default {
    components: { simplebar },
    props: ['information'],
   data(){
       return {
           filter: {
               keyword: null
           }
       }
   },
   methods: {
       openCreate(){
           this.$refs.create.show(this.id);
       },
       openCertification(data,id,course){
           if(data.length > 0){
               
           }else{
               this.$refs.certification.show(id,course);
           }
       }   
   }
}
</script>