<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 480px;" header-class="p-3 bg-light" title="Not Submitted" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div>
            <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.25rem;width:2.25rem;">
                        <span class="avatar-title rounded-circle fs-16 bg-danger-subtle text-danger">
                            <i class="ri-close-circle-fill"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14">{{ status }}</h5>
                    <p class="text-muted mb-0 fs-11">
                        {{ users.length }} employee{{ users.length === 1 ? '' : 's' }} haven't submitted the survey
                    </p>
                </div>
            </div>

            <div class="mb-2" v-if="users.length > 5">
                <input type="text" v-model="keyword" class="form-control form-control-sm" placeholder="Search name...">
            </div>

            <simplebar style="max-height: 250px;">
                <ul class="list-group list-group-flush border-dashed mb-0">
                    <li class="list-group-item px-0" v-for="user in filteredUsers" :key="user.id">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-xs">
                                    <img v-if="user.avatar" :src="user.avatar" alt="" class="avatar-img rounded-circle">
                                    <div v-else class="avatar-title rounded-circle bg-danger-subtle text-danger fs-12">{{ user.name.charAt(0) }}</div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fs-13">{{ user.name }}</h6>
                            </div>
                        </div>
                    </li>
                    <li v-if="!filteredUsers.length" class="list-group-item px-0 text-center text-muted fs-12">
                        No matching employees.
                    </li>
                </ul>
            </simplebar>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
        </template>
    </b-modal>
</template>
<script>
import simplebar from "simplebar-vue";

export default {
    components: { simplebar },
    data(){
        return {
            showModal: false,
            status: null,
            users: [],
            keyword: null,
        }
    },
    computed: {
        filteredUsers(){
            if (!this.keyword) return this.users;
            const kw = this.keyword.toLowerCase();
            return this.users.filter(user => user.name.toLowerCase().includes(kw));
        }
    },
    methods: {
        show(status, users){
            this.status = status;
            this.users = users;
            this.keyword = null;
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>
<style scoped>
.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    border-radius: 50%;
}
</style>
