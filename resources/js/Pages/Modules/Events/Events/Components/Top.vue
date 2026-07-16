<template>
    <b-col lg="12">
        <b-card no-body>
            <div class="bg-info-subtle">
                <b-card-body class="pb-0 px-4">
                     <b-row class="mb-3">
                        <b-col md>
                            <b-row class="align-items-center g-3">
                                <b-col md>
                                    <div>
                                        <h4 class="fw-semibold text-primary">{{ selected.name }} {{ selected.year }}</h4>
                                        <div class="hstack gap-3 flex-wrap">
                                            <div><i class="ri-hashtag align-bottom me-1"></i>{{selected.code}}</div>
                                            <div class="vr" style="width: 1px;"></div>
                                            <div>Year : <span class="fw-medium">{{selected.year}}</span></div>
                                            <div class="vr" style="width: 1px;"></div>
                                            <div>Start Date : <span class="fw-medium">{{selected.start}}</span></div>
                                            <div class="vr" style="width: 1px;"></div>
                                            <div>End Date : <span class="fw-medium">{{selected.end}}</span></div>
                                        </div>
                                    </div>
                                </b-col>
                            </b-row>
                        </b-col>
                        <b-col md="auto">
                            <div class="hstack gap-4 flex-wrap mt-2">
                                <Link href="/events">
                                    <div class="text-muted" @click="hide()">  
                                        <i class="ri-close-circle-fill fs-16"></i> Close
                                    </div>
                                </Link>
                                
                            </div>
                        </b-col>
                    </b-row>
                    
                </b-card-body>
            </div>
        </b-card>
    </b-col>
</template>
<script>
export default {
    props:['selected']
}
</script>