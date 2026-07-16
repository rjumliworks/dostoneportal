<template>
    <div class="auth-page-wrapper d-flex flex-column">
        <div class="auth-page-content d-flex justify-content-center"
             style="background-color:#EFF0F3; min-height:100vh; overflow:hidden;">
            <div class="row p-5">
                <!-- Header Info -->
                <div class="col-lg-12 text-center mb-3">
                    <img src="@assets/images/logos/logo-sm.png" alt="" class="avatar-xs mb-1">
                    <img src="@assets/images/logos/bagongpilipinas.png" alt="" class="avatar-xs mb-1">
                    <h1 class="mb-0 ff-secondary fw-semibold text-capitalize lh-base fs-22">
                        <span class="text-primary">{{ selected.title }}</span>
                    </h1>
                    <h1 class="mb-0 ff-secondary fw-semibold text-capitalize lh-base fs-14">
                        <span class="text-warning">{{ selected.event.name }}</span>
                    </h1>
                    <h1 class="mb-0 ff-secondary fw-semibold text-capitalize lh-base fs-12">
                        <span class="text-success">{{ selected.venue.address }}</span>
                    </h1>
                    <!-- <p class="text-muted mb-2 fs-12">{{ selected.detail.description }}</p> -->
                </div>

                <!-- QR / Camera Box -->
                <div class="col-lg-5" style="margin-top: 65px;">
                    <div class="text-center">
                        <div class="position-relative d-inline-block" style="width:700px; height:400px;">
                            <img src="/images/hands.png"
                                 alt="Phone Frame"
                                 class="img-fluid position-absolute"
                                 style="top:-40%; left:0; width:100%;" />

                            <div class="position-absolute qr-box" style="top:60%; left:53.2%; transform:translate(-50%, -50%);">
                                <div class="video-wrapper position-relative">
                                    <video
                                        ref="video"
                                        autoplay
                                        playsinline
                                        class="qr-child img-thumbnail">
                                    </video>
                                    <div v-if="isScanning" class="scanner-overlay"></div>
                                </div>
                                <button
                                    class="btn btn-lg fw-semibold btn-primary flex-fill m-3"
                                   
                                    @click="captureFrame"
                                >
                                    <h5 class="mb-0 fs-15 fw-semibold text-uppercase text-white" style="font-size: 10.7px">
                                        SUBMIT 
                                    </h5>
                                    <p class="text-white fw-normal fs-10 mb-0">(Please click to submit the attendance)</p>
                                </button>
                            </div>

                           
                        </div>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="col-lg-6 mt-4">
                    <div class="card bg-light-subtle shadow-none border">
                        <div class="card-header bg-light-subtle" style="height:120px;">
                            <div class="d-flex w-100 justify-content-center align-items-center" v-if="error">
                                <div class="p-4 w-100 border rounded bg-danger-subtle text-center">
                                    <p class="mb-0 text-danger fw-semibold">Hi, {{ error.name }}</p>
                                    <p class="mb-0 text-danger fs-11" v-if="error.type == 'not'">You are <b>not registered</b> as a participant. Please go to the <b>Sessions tab</b> to complete your registration</p>
                                    <p class="mb-0 text-danger fs-11" v-else>Your attendance has already been recorded</p>
                                </div>
                            </div>
                           <div v-else-if="participant.avatar" class="pt-1 ps-1 profile-wrapper">
                                <div class="row align-items-center">
                                    <!-- Captured Image -->
                                    

                                    <!-- Registered Profile -->
                                    <div class="col-auto text-center mt-n2">
                                        <img
                                            :src="participant.avatar"
                                            alt="Profile"
                                            class="rounded border"
                                            style="width:100px;height:100px;object-fit:cover;"
                                        >
                                    </div>

                                    <!-- User Info -->
                                    <div class="col">
                                        <p class="text-primary text-opacity-75 mb-1">
                                            Welcome, and thank you.
                                        </p>
                                        <h3 class="text-primary mb-1">{{ participant.name }}</h3>
                                        <p class="text-primary text-muted fs-14">
                                            Attendance confirmed on
                                            <b class="text-primary">{{ participant.birthdate }}</b>
                                        </p>
                                    </div>

                                    <div class="col-auto text-center mt-n2">
            <img
                :src="imagee"
                alt="Captured"
                class="rounded border"
                style="width:100px;height:100px;object-fit:cover;"
            >
        </div>
                                </div>
                            </div>
                            <div v-else class="d-flex w-100 justify-content-center align-items-center">
                                <div class="p-4 w-100 border rounded bg-dark-subtle text-center">
                                    <p class="mb-0 text-dark fs-12">Please use the <b>QR Scanner</b> in the application to scan the provided QR code.</p>
                                    <p class="mb-0 text-muted fs-11">If you are using the mobile browser, please allow camera access to enable QR code scanning.</p>
                                </div>
                            </div>
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
                                            <th width="5%" class="text-center">#</th>
                                            <th>Name</th>
                                            <!-- <th class="text-center">Time</th> -->
                                        </tr>
                                    </thead>
                                    <tbody v-if="attendees.length">
                                        <tr v-for="(list,index) in attendees"
                                            :key="index"
                                            :class="['fs-12',{ 'fw-semibold bg-success-subtle': index === 0 }]">
                                            <td class="text-center">{{ index + 1 }}</td>
                                            <td>{{ list.name }}</td>
                                            <!-- <td class="text-center">{{ list.attended_at }}</td> -->
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr><td colspan="3" class="text-center text-muted">No participants found.</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<div :class="flashClass" class="flash-overlay"></div>
  
<script>
const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'  };
const options1 = { hour12: false  };
import { useForm } from '@inertiajs/vue3';
import FingerprintJS from '@fingerprintjs/fingerprintjs'
export default {
    layout: null,
    props: ['session'],
    data() {
        return {
            selected: this.session.data,
            participant: {},
            imagee: null,
             currentDate: null,
            currentTime: null,
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
            flashClass:'',
            type: null,
            lists: [],
            isScanning: false,
            statusTimeout: null,
            tableHeightLocked: false,
            cameraStream: null,
            deviceId: null,
            attendees: []
        }
    },

     mounted() {
        this.initDeviceId();
        this.clockInterval = setInterval(() => {
            this.currentTime = new Date().toLocaleTimeString("en-US");
            this.currentDate = new Date().toLocaleDateString("en-US", options);
        }, 1000);
        this.keepAliveInterval = setInterval(() => {
            axios.get('/keep-alive'); 
        }, 1000 * 60 * 30); 
        this.initCamera();
        this.syncTableHeight(true)

        this.onResize = () => this.syncTableHeight(true)
        window.addEventListener('resize', this.onResize)
    },
    beforeUnmount() {
        window.removeEventListener('resize', this.syncTableHeight)
        clearInterval(this.clockInterval);
        clearInterval(this.keepAliveInterval);
        if (this.cameraStream) {
            this.cameraStream.getTracks().forEach(track => track.stop());
        }
    },
    methods: {
      async initDeviceId() {
            try {
                const fp = await FingerprintJS.load()
                const result = await fp.get()
                this.deviceId = result.visitorId
                console.log(this.deviceId);
            } catch (e) {
                console.error("Failed to load FingerprintJS:", e)
            }},
        flashSuccess(){
            this.$refs.successBeep.currentTime = 0
            this.$refs.successBeep.play()
            this.flashClass = "flash-overlay flash-success"
            setTimeout(()=>{
                this.flashClass = "flash-overlay"
            },400)
        },
        flashError(){
            this.$refs.errorBuzz.currentTime = 0
            this.$refs.errorBuzz.play()
            this.flashClass = "flash-overlay flash-error"
            setTimeout(()=>{
                this.flashClass = "flash-overlay"
            },400)
        },
        resetStatusTimer() {
            // Clear previous timer if exists
            if (this.statusTimeout) {
                clearTimeout(this.statusTimeout);
            }
            this.statusTimeout = setTimeout(() => {
                this.status = null;
                this.employee = null;
                this.statusTimeout = null;
            }, 15000);
        },
        syncTableHeight(force = false) {
            this.$nextTick(() => {
                const left = this.$refs.leftCol
                const table = this.$refs.scrollabletable
                if (!left || !table) return

                if (this.tableHeightLocked && !force) return

                const offset = 180
                table.style.height = `${left.offsetHeight - offset}px`

                this.tableHeightLocked = true
            })
        },
        selectType(value) {
            this.type = value
        },
        fetch(page_url){
            page_url = page_url || '/';
            return axios.get(page_url,{
                params : {
                    option: 'list',
                    code: this.code,
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
            this.form.post('/',{
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
            this.cameraStream = await navigator.mediaDevices.getUserMedia({ video: true });
            this.$refs.video.srcObject = this.cameraStream;
        },
        async captureFrame() {
            //  if (!this.type) {
            //     return
            // }
            const type = this.type;
            const video = this.$refs.video;
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg'));
            const formData = new FormData();
            formData.append('image', blob, 'capture.jpg');
            formData.append('type', type); 
            formData.append('device', this.deviceId); 
            formData.append('code', this.code); 
            formData.append('option', 'dtr'); 

            try {
                this.isScanning = true;
                const res = await axios.post('/recognize', formData); 
                const data = res.data;
                this.attendees.push(data.data);
                this.participant = data.data;
                this.imagee = data.captured_image;

                // Force Vue to detect change even if repeated status
                this.status = null;
                await this.$nextTick();
                this.status = data.info;

                // Update employee only if not an error
                if (data.info === 'New' || data.info === 'Success' || data.info === 'Duplicate' || data.info === 'Disabled Overlap' || data.info === 'Disabled AM' || data.info === 'Wrong Station') {
                    this.employee = data.data ? { ...data.data } : null;
                    this.user = this.employee;

                    if(data.info == 'Duplicate'){
                        this.$refs.errorBuzz.play()
                        setTimeout(() => {
                            this.speak("Duplicate attendance detected ")
                        }, 600)
                    };
                    if(data.info == 'Disabled Overlap'){
                        this.$refs.errorBuzz.play()
                        setTimeout(() => {
                            this.speak("You cannot time in because you have already timed out for this period.") 
                        }, 600)
                    }
                    if(data.info == 'Disabled AM'){
                        this.$refs.errorBuzz.play()
                        setTimeout(() => {
                            this.speak("You cannot time in because its already PM period.") 
                        }, 600)
                    }
                    if (data.info === 'New' || data.info === 'Success') {
                        // Add to the list only for new/success entries
                        this.$refs.successBeep.play();
                        setTimeout(() => {
                            this.speak("Your attendance has been recorded successfully.")
                        }, 600)
                        this.lists = [this.employee, ...this.lists];
                    }
                     if (data.info === 'Wrong Station') {
                        // Add to the list only for new/success entries
                        this.$refs.errorBuzz.play();
                        setTimeout(() => {
                            this.speak("Station mismatch detected. Kindly use the correct assigned station.")
                        }, 600)
                    }
                }
                this.resetStatusTimer();
            }catch (e) {
                this.$refs.errorBuzz.play()
                setTimeout(() => {
                    this.speak("Employee not found.")
                }, 600)
                this.status = 'Error';
                this.type = null;
                this.resetStatusTimer();
                setTimeout(() => {
                    this.isScanning = false;
                }, 2000);
            }finally {
                this.type = null;
                setTimeout(() => {
                    this.isScanning = false;
                }, 2000);
            }

        },
        speak(text) {
            const message = new SpeechSynthesisUtterance(text)
            message.lang = "en-US"
            speechSynthesis.speak(message)
        }
        
    },
}
</script>
<style>
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
.table-responsive {
    min-height: 200px;
}
.flash-overlay{
    position: fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    pointer-events:none;
    opacity:0;
    transition: opacity 0.2s ease;
    z-index:9999;
}

.flash-success{
    background:rgba(0,255,0,0.25);
    opacity:1;
}

.flash-error{
    background:rgba(255,0,0,0.25);
    opacity:1;
}
</style>