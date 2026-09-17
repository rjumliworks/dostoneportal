<template>
    <Head title="Files" />
    <PageHeader title="Files" pageTitle="List" />
    <b-row class="g-3">
        <div class="col-md-3">
            <Sidebar ref="sidebar"/>
        </div>
        <div class="col-md-9">
            <Main ref="main"/>
        </div>
    </b-row>
</template>
<script>
import Main from './Components/Main.vue';
import Sidebar from './Components/Sidebar.vue';
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components: { Main, Sidebar, PageHeader },
}
</script>