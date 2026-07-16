<template>
    <BRow>
        <BCol lg="12">
            <BCard no-body class="mt-n4 mx-n4">
                <div class="bg-warning-subtle">
                    <BCardBody class="pb-0 px-4">
                        <BRow class="mb-3">
                            <BCol md>
                                <BRow class="align-items-center g-3">
                                    <BCol md="auto">
                                        <div class="avatar-md">
                                            <div class="avatar-title bg-white rounded-circle">
                                                <img src="/assets/images/brands/slack.png" alt="" class="avatar-xs">
                                            </div>
                                        </div>
                                    </BCol>
                                    <BCol md>
                                        <div>
                                            <h4 class="fw-semibold text-success">{{ selected.title }}</h4>
                                            <div class="hstack gap-3 flex-wrap">
                                                <!-- <div><i class="ri-hashtag align-bottom me-1"></i>{{selected.code}}</div>
                                                <div class="vr" style="width: 1px;"></div> -->
                                                <div>Event : <span class="fw-medium">{{selected.event.name }}</span></div>
                                                <div class="vr" style="width: 1px;"></div>
                                                <!-- <div>Current Capacity : <span class="fw-medium">{{selected.detail.attendees}}/{{ selected.detail.capacity }}</span></div>
                                                <div class="vr" style="width: 1px;"></div> -->
                                                <div>Date : 
                                                    <span class="fw-medium" v-if="selected.event.start == selected.event.end">{{selected.event.start}}</span>
                                                    <span class="fw-medium" v-else>{{selected.event.start}} - {{selected.event.end}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </BCol>
                                </BRow>
                            </BCol>
                            <BCol md="auto">
                                <div class="hstack gap-1 flex-wrap">
                                    <!-- <BButton variant="link" class="py-0 fs-16 favourite-btn active">
                                        <i class="ri-star-fill" @click="toggleFavourite"></i>
                                    </BButton>
                                    <BButton variant="link" class="py-0 fs-16 text-body">
                                        <i class="ri-share-line"></i>
                                    </BButton>
                                    <BButton variant="link" class="py-0 fs-16 text-body">
                                        <i class="ri-flag-line"></i>
                                    </BButton> -->
                                </div>
                            </BCol>
                        </BRow>
                    </BCardBody>
                </div>
            </BCard>
        </BCol>
    </BRow>
</template>
<script>
export default {
    props:['selected']
}
</script>