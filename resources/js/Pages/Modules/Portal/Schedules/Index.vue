<template>
    <Head title="Schedules" />
    <PageHeader title="Schedules" pageTitle="List" />
    <b-row>
        <div class="col-md-12">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-calendar-todo-fill  text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">Schedules</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">A centralized calendar displaying official travel, meetings, audits, holidays, GAD activities, and other important events</p>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white rounded-bottom">
                    <FullCalendar ref="fullCalendar" :options="calendarOptions" />
                </div>
            </div>
        </div>
    </b-row>
</template>

<script>
import _ from "lodash";
import "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import FullCalendar from "@fullcalendar/vue3";
import bootstrapPlugin from "@fullcalendar/bootstrap";
import interactionPlugin, { Draggable } from "@fullcalendar/interaction";
import Multiselect from "@vueform/multiselect";
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components: {
        PageHeader,
        Multiselect,
        FullCalendar,
    },
    data() {
        return {
            calendarOptions: {
                timeZone: "Asia/Manila",
                droppable: true,
                navLinks: true,
                plugins: [
                    dayGridPlugin,
                    timeGridPlugin,
                    interactionPlugin,
                    bootstrapPlugin,
                    listPlugin,
                ],
                themeSystem: "bootstrap",
                headerToolbar: {
                    left: "prev,next today",
                    center: "title",
                    right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
                },
                // buttonText: {
                //     prev: 'Prev',
                //     next: 'Next',
                //     today: 'Today'
                // },
                windowResize: () => {
                    this.getInitialView();
                },
                initialView: this.getInitialView(),
                initialEvents: [],
                editable: true,
                showNonCurrentDates: false,
                fixedWeekCount: false,
                height: 'calc(100vh - 320px)',
                events: [],
                eventClick: this.editEvent,
                eventContent: function(arg) {
                    const title = arg.event.title;
                    const type = arg.event.extendedProps.type; 

                    return {
                        html: `
                            <div class="fc-event-inner-center">
                                <div style="font-weight:600; font-size:11px;">${title}</div>
                                <div style="font-size:10px; opacity:0.7;">(${type})</div>
                            </div>
                        `
                    };
                },

               
        
            },
        };
    },
    created() {
        this.fetch();
    },
    methods: {
        fetch(){
            axios.get('/calendar',{
                params : {
                    option: 'events' 
                }
            })
            .then(response => {
                this.calendarOptions.events = response.data.data;        
            })
            .catch(err => console.log(err));
        },
        getInitialView() {
            if (window.innerWidth >= 768 && window.innerWidth < 1200) {
                return "timeGridWeek";
            } else if (window.innerWidth <= 768) {
                return "listMonth";
            } else {
                return "dayGridMonth";
            }
        },
    }
};
</script>
<style>
.fade-enter-active, .fade-leave-active {
    transition: all 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
    transform: translateY(-5px);
}
.fc .fc-daygrid-event,
.fc .fc-timegrid-event {
    display: flex;
    align-items: center;     /* vertical center */
    justify-content: center; /* horizontal center */
}

/* Remove default padding that offsets centering */
.fc .fc-event-main {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Your custom content */
.fc-event-inner-center {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;

    width: 100%;
    height: 100%;

    padding: 2px;
    overflow: hidden;
}
/* .fc-event-inner-center div {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
} */
.fc-event-inner-center div {
    white-space: normal;    
    word-break: break-word; 
    line-height: 1.2;
}
</style>