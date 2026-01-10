<template>
    <Head title="Human Resource - Date Time Record" />
    <body>
    <div class="account-pages my-4 pt-sm-1">
        <div class="container" style="max-width: 1400px;">
                
            
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="text-center mb-4">
                        <img src="/images/logo-sm.png" alt="" class="avatar-sm mb-2">
                        <p class="fs-14 fw-semibold text-p text-uppercase">Department of Science & Technology - IX</p>
                        <p class="fs-13 text-muted" style="margin-top: -20px;">Human Resource - Date Time Record</p>
                    </div>
                    <div class="card border bg-white">
                        <div class="card-header bg-primary">
                            <span class="text-white float-end fs-20 fw-semibold text-center dfw-medium" v-text="currentTime"></span>
                            <h4 class="text-white text-uppercase fw-semibold  fs-20 mt-1 mb-n2">{{ currentDate}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-5"> 
                                    <div class="video-wrapper">
                                        <video
                                            ref="video"
                                            autoplay
                                            playsinline
                                            class="qr-child img-thumbnail">
                                        </video>
                                        <div v-if="isScanning" class="scanner-overlay"></div>
                                        <!-- <div class="face-recognition-overlay">
                                            <div class="face-outline"></div>
                                            <div class="face-outline-inner"></div>
                                            <div class="scan-line-vertical"></div>
                                            <div class="corner top-left"></div>
                                            <div class="corner top-right"></div>
                                            <div class="corner bottom-left"></div>
                                            <div class="corner bottom-right"></div>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div v-if="status == 'New'" class="d-flex w-100 justify-content-center align-items-center mb-2">
                                        <div class="p-4 w-100 border rounded bg-success-subtle">
                                            <div class="d-flex mb-n3">
                                                <div class="flex-shrink-0 me-3">
                                                    <div style="height:2.5rem;width:2.5rem;">
                                                        <img :src="employee.avatar" alt="user-img" class="avatar-sm rounded-circle mt-n2">
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="mb-0 fs-14"><span class="text-body">{{employee.name}}</span></h5>
                                                    <p class="text-muted text-truncate-two-lines fs-12">{{employee.division}}</p>
                                                </div>
                                                <div class="flex-0">
                                                     <h5 class="mb-0 fs-14"><span class="text-body">{{employee.time}}</span></h5>
                                                     <p class="text-muted text-truncate-two-lines float-end fs-12">{{employee.type}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else-if="status == 'Duplicate'" class="d-flex w-100 justify-content-center align-items-center mb-2">
                                        <div class="p-4 w-100 border rounded bg-warning-subtle text-center">
                                            <p class="mb-0 text-dark fs-12">Duplicate attendance detected for <b class="text-danger">{{ employee.name }}</b>.</p>
                                            <p class="mb-0 text-muted fs-11">The system has already logged this employee's time-in/time-out. No additional entry is needed.</p>
                                        </div>
                                    </div>
                                    <div v-else-if="status == 'Error'" class="d-flex w-100 justify-content-center align-items-center mb-2">
                                        <div class="p-4 w-100 border rounded bg-danger-subtle text-center">
                                            <p class="mb-0 text-dark fs-12">Employee not found in the system.</p>
                                            <p class="mb-0 text-muted fs-11">No matching employee was found based on the QR code or face data. Please verify your credentials or seek assistance.</p>
                                        </div>
                                    </div>
                                     <div v-else-if="status == 'Disabled'" class="d-flex w-100 justify-content-center align-items-center mb-2">
                                         <div class="p-4 w-100 border rounded bg-danger-subtle text-center">
                                             <p class="mb-0 text-dark fs-12">Time-in (AM) is only available before 12:00 PM.</p>
                                             <p class="mb-0 text-muted fs-11">Please use Time-in (PM) instead.</p>
                                         </div>
                                     </div>
                                    <div v-else class="d-flex w-100 justify-content-center align-items-center mb-2">
                                        <div class="p-4 w-100 border rounded bg-dark-subtle text-center">
                                            <p class="mb-0 text-dark fs-12"> Please face the camera to begin.</p>
                                            <p class="mb-0 text-muted fs-11"> Make sure your face is clearly visible for accurate recognition.</p>
                                        </div>
                                    </div>
                                    <div class="card bg-light-subtle shadow-none border">
                                        <div class="card-header bg-light-subtle">
                                            <div class="d-flex mb-n3">
                                                <div class="flex-shrink-0 me-3">
                                                    <div style="height:2.5rem; width:2.5rem;">
                                                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                                            <i class="ri-file-list-3-line text-primary fs-24"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="mb-0 fs-14"><span class="text-body">List of Attendees</span></h5>
                                                    <p class="text-muted fs-12">
                                                        Shows participants who have successfully scanned the QR code.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body bg-white rounded-bottom">
                                            <div class="table-responsive table-card"
                                                style="height:calc(100vh - 550px); overflow-x:hidden;">
                                                <table class="table table-nowrap align-middle mb-0">
                                                    <thead class="bg-light thead-fixed">
                                                        <tr class="fs-11">
                                                            <th style="width: 7%;" class="text-center">#</th>
                                                            <th>Name</th>
                                                            <th style="width: 18%;" class="text-center">Type</th>
                                                            <th style="width: 15%;" class="text-center">Time</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-if="lists.length">
                                                        <tr v-for="(list,index) in lists"
                                                            :key="index"
                                                            :class="['fs-12',{ 'bg-success-subtle': list.subtype === 'in',
                                                                 'bg-danger-subtle': list.subtype === 'out'
                                                                }]">
                                                            <td class="text-center">
                                                                <img :src="list.avatar" alt="user-img" class="avatar-xs rounded-circle">
                                                            </td>
                                                            <td>{{ list.name }}</td>
                                                            <td class="text-center">{{ list.type }}</td>
                                                            <td class="text-center">{{ list.time }}</td>
                                                        </tr>
                                                    </tbody>
                                                    <tbody v-else>
                                                        <tr><td colspan="4" class="text-center text-muted">No employees found.</td></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-lg fw-semibold btn-light flex-fill" @click="captureFrame('Time In (am)')">AM IN</button>
                                        <button class="btn btn-lg fw-semibold btn-light flex-fill" @click="captureFrame('Time Out (am)')">AM OUT</button>
                                        <button class="btn btn-lg fw-semibold btn-light flex-fill" @click="captureFrame('Time In (pm)')">PM IN</button>
                                        <button class="btn btn-lg fw-semibold btn-light flex-fill" @click="captureFrame('Time Out (pm)')">PM OUT</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
        </div>
    </div>
</body>
</template>
<script>
const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'  };
const options1 = { hour12: false  };
const twelve = new Date("2022-03-25 11:00:00").toLocaleTimeString("en-US",options1);
const twelvethirty = new Date("2022-03-25 12:30:00").toLocaleTimeString("en-US",options1);
const one = new Date("2022-03-25 15:00:00").toLocaleTimeString("en-US",options1);
import { useForm } from '@inertiajs/vue3';
import { isError } from 'lodash';
export default {
    layout: null,
    data() {
        return {
            currentUrl: window.location.origin,
            currentDate: null,
            currentTime: null,
            currentTime2: null,
            user: '',
            activebutton: 0,
            inactive: false,
            message: '',
            status: '',
            employee: null,
            form: useForm({
                image: null,
                username: null,
                type:'Time In (am)',
                option: 'dtr'
            }),
            lists: [],
            isScanning: false,
        };
    },
    mounted() {
        setInterval(() => {
            this.currentSecond = new Date().toLocaleTimeString([],{seconds: '2-digit'});
            this.currentTime = new Date().toLocaleTimeString("en-US");
            this.currentDate = new Date().toLocaleDateString("en-US",options);
        }, 1000);
        this.keepAliveInterval = setInterval(() => {
            axios.get('/keep-alive'); 
        }, 1000 * 60 * 30); 
        this.initCamera();
    },
    beforeUnmount() {
        clearInterval(this.keepAliveInterval);
    },
    created(){
        this.fetch();
    },
    methods: {
        fetch(page_url){
            page_url = page_url || '/attendance';
            return axios.get(page_url,{
                params : {
                    option: 'lists',
                    count: 20,
                }
            })
            .then(response => {
                this.lists = response.data;       
            });
        },
        find(){
            this.user = ''; 
            this.inactive = false;
            this.capturePhoto();
            this.form.post('/attendance',{
                preserveScroll: true,
                onSuccess: (response) => {
                    if(response.props.flash.info == 'Error'){
                        this.status = response.props.flash.info;
                        setInterval(() => {
                            this.status = null;
                        }, 9000);
                    }else{
                        this.status = response.props.flash.info;
                        this.user = response.props.flash.data;
                        this.form.username = null;
                        setInterval(() => {
                            this.user = null;
                            this.status = null;
                        }, 9000);
                    } 
                },
            });
        },
        async initCamera() {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            this.$refs.video.srcObject = stream;
        },   
        async captureFrame(type) {
        const video = this.$refs.video;
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg'));
        const formData = new FormData();
        formData.append('image', blob); 
        formData.append('type', type); 
        formData.append('option', 'dtr'); 

        try {
            this.isScanning = true;
            const res = await axios.post('/recognize', formData); 
            const data = res.data;

            // Force Vue to detect change even if repeated status
            this.status = null;
            await this.$nextTick();
            this.status = data.info;

            // Update employee only if not an error
            if (data.info === 'New' || data.info === 'Success' || data.info === 'Duplicate') {
                this.employee = data.data ? { ...data.data } : null;
                this.user = this.employee;

                if (data.info === 'New' || data.info === 'Success') {
                    // Add to the list only for new/success entries
                    this.lists = [this.employee, ...this.lists];
                }
            }

        } catch (e) {
            console.error(e);
            this.status = 'Error';
            setTimeout(() => {
                this.isScanning = false;
            }, 2000);
        }
        finally {
            setTimeout(() => {
                this.isScanning = false;
            }, 2000);
        }

    }

    }
}
</script>
<style>
/* --- Existing Styles --- */
    .nav-pills .nav-link {
        font-weight: bold;
        font-size: 16px;
    }
    .qr-child {
        padding-top: 8px;
        padding-left: 8px;
        padding-bottom: 8px;
        width: 100%;
        height: 100%;
        object-fit: cover;   
    }


.scanner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none; 
    overflow: hidden;
    z-index: 20; /* Keep scanner line above everything */
}

.scanner-overlay::before {
    content: '';
    position: absolute;
    top: -50%;
    left: 0;
    width: 100%;
    height: 50%;
    background: linear-gradient(to bottom, rgba(0,255,0,0) 0%, rgba(0,255,0,0.3) 50%, rgba(0,255,0,0) 100%);
    animation: scan 2s linear infinite;
}

@keyframes scan {
    0% { top: -50%; }
    100% { top: 100%; }
}

/* --- New Animated Face Recognition Overlay Styles --- */
.face-recognition-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none; /* Ignore mouse events */
    z-index: 10; /* Below the main scanner overlay */
}

/* 1. Face Outline (Elliptical Shape) */
.face-outline {
    position: absolute;
    top: 47%;
    left: 50%;
    width: 70%;        /* bigger */
    height: 80%;       /* bigger */
    max-width: 340px;
    max-height: 460px;
    transform: translate(-50%, -50%);
    
    border: 5px solid rgba(52, 236, 246, 0.6);
    border-radius: 50%;
    box-shadow: none;
}
.face-outline-inner {
    position: absolute;
    top: 47%;
    left: 50%;
    
    width: 55%;              /* smaller than outer */
    height: 65%;             /* smaller than outer */
    max-width: 260px;
    max-height: 340px;
    
    transform: translate(-50%, -50%);
    border: 3px dashed rgba(52, 236, 246, 0.5); /* dashed = broken line */
    border-radius: 50%;
    box-shadow: none;
}

/* 2. Animated Vertical Scan Line (Inside the face frame) */
.scan-line-vertical {
    position: absolute;
    top: 15%; /* Start above the ellipse */
    bottom: 15%; /* End below the ellipse */
    left: 50%;
    width: 2px;
    background: linear-gradient(
        to bottom, 
        rgba(0, 255, 0, 0) 0%, 
        rgb(25, 215, 209) 50%, 
        rgba(0, 255, 0, 0) 100%
    );
    transform: translateX(-50%);
    opacity: 0.6;
    animation: vertical-scan 3s ease-in-out infinite alternate; 
    filter: drop-shadow(0 0 5px rgb(59, 205, 235));
}

@keyframes vertical-scan {
    0% { height: 1%; top: 49%; }
    50% { height: 70%; top: 15%; } 
    100% { height: 1%; top: 84%; }
}


/* 3. Corner Brackets (for a tech look) */
.corner {
    position: absolute;
    width: 25px; 
    height: 25px; 
    border-color: #49d4ed;
    border-style: solid;
    z-index: 30; /* Ensure corners are above the ellipse and scanner */
    opacity: 0.8;
    animation: corner-blink 2s linear infinite alternate;
}

/* Define border for each corner */
.top-left {
    top: 20px;
    left: 20px;
    border-width: 5px 0 0 5px;
}

.top-right {
    top: 20px;
    right: 20px;
    border-width: 5px 5px 0 0;
}

.bottom-left {
    bottom: 40px;
    left: 20px;
    border-width: 0 0 5px 5px;
}

.bottom-right {
    bottom: 40px;
    right: 20px;
    border-width: 0 5px 5px 0;
}

/* Corner Blink Animation */
@keyframes corner-blink {
    0%, 100% { opacity: 0.8; }
    50% { opacity: 1; filter: drop-shadow(0 0 5px rgb(71, 209, 243)); }
}

</style>