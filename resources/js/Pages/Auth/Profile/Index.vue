<template>
    <Head title="Profile" />
    <PageHeader title="Profile Information" pageTitle="User" />
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-light-subtle shadow-none border">

                <div class="card-body bg-white rounded-bottom rounded-top p-4" style="height: calc(100vh - 223px); overflow: auto;">
                   <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto mb-3">
                            <img :src="$page.props.user.data.avatar" class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input" @change="previewImage"/>
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                    <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <h5 class="fs-16 mb-0">{{ $page.props.user.data.name }}</h5>
                        <p class="text-muted mb-0">{{ $page.props.user.data.position }}</p>
                        <b-button variant="soft-primary" size="sm" class="mt-2" @click="downloadPds()">
                            <i class="ri-file-download-fill align-bottom me-1"></i> Download PDS (PDF)
                        </b-button>
                    </div>
                    <hr class="text-muted">
                    <b-list-group class="list-group-fill-success mt-4" style="height: calc(100vh - 530px); overflow: auto;">
                        <BListGroupItem :active="activeTab === 2" href="#" class="list-group-item-action" @click="show(2)">
                            <i class="ri-profile-fill align-middle me-2"></i>Personal Information
                        </BListGroupItem>
                        <BListGroupItem :active="activeTab === 3" href="#" class="list-group-item-action" @click="show(3)">
                            <i class="ri-team-fill align-middle me-2"></i>Family Information
                        </BListGroupItem>
                        <BListGroupItem :active="activeTab === 4" href="#" class="list-group-item-action" @click="show(4)">
                            <i class="ri-graduation-cap-fill align-middle me-2"></i>Educational Background
                        </BListGroupItem>
                        <BListGroupItem :active="activeTab === 5" href="#" class="list-group-item-action" @click="show(5)">
                            <i class="ri-award-fill align-middle me-2"></i>Civil Service Eligibility
                        </BListGroupItem>
                        <BListGroupItem :active="activeTab === 6" href="#" class="list-group-item-action" @click="show(6)">
                            <i class="ri-briefcase-fill align-middle me-2"></i>Work Experience
                        </BListGroupItem>
                        <BListGroupItem :active="activeTab === 7" href="#" class="list-group-item-action" @click="show(7)">
                            <i class="ri-hand-heart-fill align-middle me-2"></i>Voluntary Work
                        </BListGroupItem>
                        <BListGroupItem :active="activeTab === 8" href="#" class="list-group-item-action" @click="show(8)">
                            <i class="ri-book-open-fill align-middle me-2"></i>Learning & Development
                        </BListGroupItem>
                        <BListGroupItem :active="activeTab === 9" href="#" class="list-group-item-action" @click="show(9)">
                            <i class="ri-star-fill align-middle me-2"></i>Other Information
                        </BListGroupItem>
                        <BListGroupItem :active="activeTab === 10" href="#" class="list-group-item-action" @click="show(10)">
                            <i class="ri-contacts-book-2-fill align-middle me-2"></i>References
                        </BListGroupItem>
                        <BListGroupItem :active="activeTab === 11" href="#" class="list-group-item-action" @click="show(11)">
                            <i class="ri-file-shield-2-fill align-middle me-2"></i>Declaration & Legal Info
                        </BListGroupItem>
                        <!-- <BListGroupItem :active="activeTab === 12" href="#" class="list-group-item-action" @click="show(12)">
                            <i class="ri-profile-fill align-middle me-2"></i>Digital Certificate
                        </BListGroupItem> -->
                         <BListGroupItem :active="activeTab === 13" href="#" class="list-group-item-action" @click="show(13)">
                            <i class="ri-bank-card-fill  align-middle me-2"></i>Contributions & Account Numbers
                        </BListGroupItem>
                    </b-list-group>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <Edit :addresses="addresses" v-if="activeTab === 2"/>
            <Family :information="userInformation" v-if="activeTab === 3"/>
            <Academic :lists="academics" :levels="dropdowns.levels" v-if="activeTab === 4"/>
            <Eligibility :lists="eligibilities" v-if="activeTab === 5"/>
            <WorkExperience :lists="workExperiences" :contracts="contracts" v-if="activeTab === 6"/>
            <VoluntaryWork :lists="voluntaryWorks" v-if="activeTab === 7"/>
            <Training :lists="trainings" v-if="activeTab === 8"/>
            <OtherInformation :lists="otherInformation" v-if="activeTab === 9"/>
            <Reference :lists="references" v-if="activeTab === 10"/>
            <Declaration :declaration="declaration" v-if="activeTab === 11"/>
            <!-- <Certificate v-if="activeTab === 12"/> -->
            <Account :information="userInformation" v-if="activeTab === 13"/>
        </div>
    </div>
</template>
<script>
import { useForm, Link } from "@inertiajs/vue3"
import Overview from "./Pages/Overview.vue";
import Edit from "./Pages/Edit.vue";
import Certificate from './Pages/Certificate.vue';
import Account from './Pages/Account.vue';
import Family from './Pages/Family.vue';
import Academic from './Pages/Academic.vue';
import Eligibility from './Pages/Eligibility.vue';
import WorkExperience from './Pages/WorkExperience.vue';
import VoluntaryWork from './Pages/VoluntaryWork.vue';
import Training from './Pages/Training.vue';
import OtherInformation from './Pages/OtherInformation.vue';
import Reference from './Pages/Reference.vue';
import Declaration from './Pages/Declaration.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    components: { Link, PageHeader, Overview, Edit, Family, Account, Certificate, Academic, Eligibility, WorkExperience, VoluntaryWork, Training, OtherInformation, Reference, Declaration },
    props: ['addresses','academics','eligibilities','contracts','workExperiences','voluntaryWorks','trainings','otherInformation','references','declaration','dropdowns','userInformation'],
    data() {
        return {
            currentUrl: window.location.origin,
            activeTab: 2,
            form: useForm({
                image: null,
            }),
        };
    },
    methods: {
        show(tab){
            this.activeTab = tab;
        },
        downloadPds(){
            window.open('/profile?option=download');
        },
        previewImage(e) {
            var fileInput = document.querySelector(".profile-img-file-input");
            var preview = document.querySelector(".user-profile-image");
            var file = fileInput.files[0];

            if (!file) return;

             // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert("Only JPEG or PNG images are allowed.");
                fileInput.value = '';
                return;
            }

            // Validate file size (2MB max)
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            if (file.size > maxSize) {
                alert("The image must be less than 2MB.");
                fileInput.value = '';
                return;
            }


            this.form.image = file;
            var reader = new FileReader();

            reader.addEventListener("load", () => {
                preview.src = reader.result;
                this.form.post('/photo', {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.uploaded = true;
                        this.hasAvatar = true;
                    },
                });
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        },
    }
}
</script>
