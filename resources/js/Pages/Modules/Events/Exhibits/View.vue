<template lang="">

    <Head title="Exhibit" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="w-100 p-4 pb-0" ref="box">
            <BRow>
                <Top :selected="exhibitor.data"/>
                <BCol lg="4">
                    <Sidebar :selected="exhibitor.data"/>
                </BCol>
                <BCol lg="8">
                    <Main :selected="exhibitor.data"/>
                </BCol>
            </BRow>
        </div>
    </div>
</template>
<script>
    import Top from './Components/Top.vue';
    import Main from './Components/Main.vue';
    import simplebar from "simplebar-vue";
    import Sidebar from './Components/Sidebar.vue';
    import PageHeader from '@/Shared/Components/PageHeader.vue';
    export default {
        components: { PageHeader, simplebar, Top, Main, Sidebar },
        props: ['exhibitor'],
    }
</script>
<style scoped>
    .auth-page-wrapper .auth-page-content {
        padding-bottom: 0px;
        width: 100%;
        overflow: hidden;
        background-color: #f3f3f9;
    }
    .file-manager-sidebar {
        min-width: 24%;
        max-width: 24%;
        height: calc(100vh - 92px);
    }
</style>
