<template lang="">
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="w-100 p-4 pb-0" ref="box">
            <BRow>
                <Top :selected="event.data"/>
                <BCol lg="12">
                    <Main :types="types" :selected="event.data"/>
                </BCol>
            </BRow>
        </div>
    </div>
</template>
<script>
    import Top from './Components/Top.vue';
    import Main from './Components/Main.vue';
    import simplebar from "simplebar-vue";
    import PageHeader from '@/Shared/Components/PageHeader.vue';
    export default {
        // layout: null,
        components: { PageHeader, simplebar, Top, Main },
        props: ['event','types'],
    }

</script>