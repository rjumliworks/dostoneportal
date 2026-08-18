<template>
    <PageHeader title="Dashboard" pageTitle="DOST-IX" />
    <BRow class="g-3">

        <div class="col-md-12">
            <b-card no-body class="bg-white-subtle border shadow-none">
                <b-card-body>
                    <div class="row">
                        <div class="col-12">
                             <div class="d-flex mb-n2">
                                <div class="flex-shrink-0 me-3">
                                    <div style="height:2rem;width:2rem;">
                                    <img src="@assets/images/logo-sm.png" alt="" class="avatar-xs">
                                    </div>
                                </div>
                                    <div class="flex-grow-1 mt-n2 mb-n2">
                                    <h5 class="mb-0 mt-2 fs-14 fw-semibold text-uppercase text-primary"> DEPARTMENT OF SCIENCE AND TECHNOLOGY</h5>
                                    <p class="text-primary fs-11"><span class="fw-semibold">One<span class="text-info">DOST</span>4U</span> : <span class="text-muted">Solutions and Opportunities for All</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-card-body>
            </b-card>
        </div>

        <div class="col-md-3 mt-n2">
            <div class="card overflow-hidden">
                <div class="card-body bg-marketplace2 justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs">
                                <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-15">
                                    <img class="rounded-circle header-profile-user" :src="$page.props.user.data.avatar" @error="setDefaultImage($event)" :alt="$page.props.user.data.username">
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fs-13 fw-semibold text-primary mb-0 text-uppercase">{{ $page.props.user.data.name }}</h6>
                            <p class="fs-11 text-muted mb-0">{{ $page.props.user.data.position }}</p>
                        </div>
                    </div>
                    <hr class="text-muted mt-2 mb-0"/>
                    <div class="card-header p-0 border-0 bg-light-subtle mt-2 mb-n1">
                        <div class="row g-0 text-center">
                            <div class="col-6 col-sm-3">
                                <div class="p-3 border border-dashed border-start-0">
                                    <h5 class="mb-0 fs-12 mt-n2">{{ dtr?.data.am_in_at?.time ?? '-' }}</h5>
                                    <p class="text-muted fs-11 mb-n2">AM IN</p>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div class="p-3 border border-dashed border-start-0">
                                    <h5 class="mb-0 fs-12 mt-n2">{{ dtr?.data.am_out_at?.time ?? '-' }}</h5>
                                    <p class="text-muted fs-11 mb-n2">AM OUT</p>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div class="p-3 border border-dashed border-start-0">
                                    <h5 class="mb-0 fs-12 mt-n2">{{ dtr?.data.pm_in_at?.time ?? '-' }}</h5>
                                    <p class="text-muted fs-11 mb-n2">PM IN</p>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div class="p-3 border border-dashed border-start-0 border-end-0">
                                    <h5 class="mb-0 fs-12 mt-n2">{{ dtr?.data.pm_out_at?.time ?? '-' }}</h5>
                                    <p class="text-muted fs-11 mb-n2">PM OUT</p>
                                </div>
                            </div>
                        </div>
                    </div>
 
                </div>
            </div>
        </div>

        <div class="col-md-6 mt-n2">
            <div class="row g-2">
                
                <div class="col-lg-2">
                    <div class="card border overflow-hidden  shadow-none" style="border-radius: 10px; cursor: pointer;">
                        <div class="position-relative">

                            <!-- Background Image -->
                            <img src="/images/background/dost.png"
                                alt=""
                                class="img-fluid w-100 bg-dark-subtle"
                                style="height: 90px; object-fit: cover;">

                            <!-- Bottom White Section -->
                            <div class="bg-white text-center pt-3 pb-2 position-relative">
                                
                                <!-- Overlapping Add Button -->
                                <div class="position-absolute top-0 start-50 translate-middle">
                                    <div class="rounded-circle bg-secondary border border-3 border-white d-flex align-items-center justify-content-center"
                                        style="width: 30px; height: 30px;">
                                        <i class="ri-add-line text-white fs-5"></i>
                                    </div>
                                </div>

                                <h6 class="mb-0 fs-10 fw-semibold mt-0">
                                    Create Story
                                </h6>
                            </div>

                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2">
                    <div class="card border overflow-hidden" style="border-radius: 10px;">
                        <div class="position-relative">
                            <img src="/images/background/img-1.gif"
                                alt=""
                                class="img-fluid w-100"
                                style="height: 125px; object-fit: cover;">

                            <!-- Dark overlay -->
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25"></div>

                            <!-- Avatar -->
                            <div class="position-absolute top-0 start-0 p-3">
                                <img :src="$page.props.user.data.avatar"
                                    alt=""
                                    class="rounded-circle border border-3 border-secondary"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            </div>

                            <!-- Full-width text background -->
                            <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 px-3 py-2">
                                <h6 class="mb-0 text-white fs-12 fw-semibold">
                                    {{ $page.props.user.data.name }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="card border overflow-hidden" style="border-radius: 10px;">
                        <div class="position-relative">
                            <img src="/images/background/img-4.gif"
                                alt=""
                                class="img-fluid w-100"
                                style="height: 125px; object-fit: cover;">

                            <!-- Dark overlay -->
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25"></div>

                            <!-- Avatar -->
                            <div class="position-absolute top-0 start-0 p-3">
                                <img :src="$page.props.user.data.avatar"
                                    alt=""
                                    class="rounded-circle border border-3 border-secondary"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            </div>

                            <!-- Full-width text background -->
                            <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 px-3 py-2">
                                <h6 class="mb-0 text-white fs-12 fw-semibold">
                                    {{ $page.props.user.data.name }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="col-lg-2">
                    <div class="card border overflow-hidden" style="border-radius: 10px;">
                        <div class="position-relative">
                            <img src="/images/background/img-3.gif"
                                alt=""
                                class="img-fluid w-100"
                                style="height: 125px; object-fit: cover;">

                            <!-- Dark overlay -->
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25"></div>

                            <!-- Avatar -->
                            <div class="position-absolute top-0 start-0 p-3">
                                <img :src="$page.props.user.data.avatar"
                                    alt=""
                                    class="rounded-circle border border-3 border-secondary"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            </div>

                            <!-- Full-width text background -->
                            <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 px-3 py-2">
                                <h6 class="mb-0 text-white fs-12 fw-semibold">
                                    {{ $page.props.user.data.name }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="card border overflow-hidden" style="border-radius: 10px;">
                        <div class="position-relative">
                            <img src="/images/background/img-5.gif"
                                alt=""
                                class="img-fluid w-100"
                                style="height: 125px; object-fit: cover;">

                            <!-- Dark overlay -->
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25"></div>

                            <!-- Avatar -->
                            <div class="position-absolute top-0 start-0 p-3">
                                <img :src="$page.props.user.data.avatar"
                                    alt=""
                                    class="rounded-circle border border-3 border-secondary"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            </div>

                            <!-- Full-width text background -->
                            <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 px-3 py-2">
                                <h6 class="mb-0 text-white fs-12 fw-semibold">
                                    {{ $page.props.user.data.name }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="card border overflow-hidden  shadow-none" style="border-radius: 10px; cursor: pointer;">
                        <div class="position-relative">

                            <!-- Background Image -->
                            <img src="/images/background/dost.png"
                                alt=""
                                class="img-fluid w-100 bg-dark-subtle"
                                style="height: 125px; object-fit: cover;">

                            <!-- Bottom White Section -->
                          

                        </div>
                    </div>
                </div>

            </div>
            
        </div>

        <div class="col-md-3 mt-n2">
            <div class="card overflow-hidden">
                <div class="card-body bg-marketplace d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="fs-14 lh-base mb-0 fw-semibold">
                            Morale <span class="text-success">Survey</span>
                        </h4>
                        <p class="mb-0 mt-0 pt-1 fs-11 text-muted">
                            Help us improve the workplace by sharing your feedback and experience.
                        </p>
                        <div class="mt-2" v-if="this.$page.props.surverRequired">
                            <a href="#!" class="btn btn-sm btn-danger"> Submit Now </a>
                        </div>
                        <div class="mt-2" v-else>
                            <button class="btn btn-sm btn-success" disabled> Thank you for submitting! </button>
                        </div>
                    </div>
                    <img src="/images/background/background1.png" alt="" class="img-fluid d-none d-lg-block ms-3" style="width: 70px;">
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-n2">
            <simplebar style="height: calc(100vh - 445px); overflow: auto;">

                <Link href="/dtr">
                    <div class="card overflow-hidden shadow-none mt-0 mb-3" style="cursor: pointer;">
                        <div class="card-body bg-secondary-subtle">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-purple bg-opacity-10 text-purple rounded-circle fs-17">
                                            <i class="ri-alarm-fill fs-24"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-12 text-purple mb-1">Daily Time Record</h6>
                                    <p class="fs-11 text-muted mb-0">Tracks daily attendance, working hours, and employee time entries.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </Link>

                <div class="card shadow-none border mb-3">
                    <div class="card-header bg-light-subtle">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fs-12">Attendance Overview </h5>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="d-flex gap-1 align-items-center my-n2">
                                    <i class="ri-calendar-2-line"></i> 
                                    <span class="text-muted fw-normal fs-12"> {{ currentMonth }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-header p-0 border-0 bg-light-subtle">
                        <div class="row g-0 text-center">
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border border-dashed border-start-0">
                                    <h5 class="mb-1">{{ attendance[0] }}</h5>
                                    <p class="text-muted mb-0 fs-12">Incomplete</p>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border border-dashed border-start-0">
                                    <h5 class="mb-1">{{ attendance[1] }}</h5>
                                    <p class="text-muted mb-0 fs-12">Lates</p>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border border-dashed border-start-0 border-end-0">
                                    <h5 class="mb-1">{{ attendance[2] }}</h5>
                                    <p class="text-muted mb-0 fs-12">Absences</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-none border mt-0">
                    <div class="card-header bg-light-subtle">
                        <div class="d-flex mb-n3">
                            <div class="flex-shrink-0 me-3 mt-1">
                                <div style="height:2rem;width:2rem;">
                                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                        <i class="ri-file-list-fill text-primary fs-20"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 mt-0 fs-13"><span class="text-body">My Pending Request</span></h5>
                                <p class="text-muted text-truncate-two-lines fs-11">Review and track your pending requests</p>
                            </div>
                        </div>
                    </div>
                    <div class="cards border-bottom shadow-none" no-body style="height: 230px;">
                         <div class="card-body">
                            <div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show material-shadow fs-12 mt-2" role="alert">
                                <i class="ri-alert-line label-icon"></i>No request found.
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="card shadow-none border mt-n2">
                    <div class="card-header bg-light-subtle">
                        <div class="d-flex mb-n3">
                            <div class="flex-shrink-0 me-3 mt-1">
                                <div style="height:2rem;width:2rem;">
                                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                        <i class="ri-calendar-fill text-primary fs-20"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 mt-0 fs-13"><span class="text-body">My Schedules</span></h5>
                                <p class="text-muted text-truncate-two-lines fs-11">Your upcoming schedules at a glance.</p>
                            </div>
                        </div>
                    </div>
                    <div class="cards border-bottom shadow-none" no-body style="height: 250px;">
                        <div class="card-body">
                            <div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show material-shadow fs-12 mt-2" role="alert">
                                <i class="ri-alert-line label-icon"></i>No schedules found
                            </div>
                        </div>
                    </div>
                </div>
            </simplebar>
        </div>

        <div class="col-md-6 mt-n2">
            <simplebar style="height: calc(100vh - 445px); overflow: auto;">
                <div class="row mt-0">

                    <div class="col-xxl-12 col-sm-6 project-card mb-n2">
                        <div class="card shadow-none border" style="border-radius: 10px;">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-15">
                                                <img class="rounded-circle header-profile-user" :src="$page.props.user.data.avatar" @error="setDefaultImage($event)" :alt="$page.props.user.data.username">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <button type="button" class="form-control rounded-pill bg-light-subtle border text-start text-muted fs-13 py-2" @click="openCreatePost()">
                                            What's on your mind, {{ firstName }}?
                                        </button>
                                    </div>
                                    <div class="flex-shrink-0 d-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-icon btn-sm rounded-circle" title="Live video" @click="openCreatePost('video')">
                                            <i class="ri-vidicon-fill text-danger fs-16"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-sm rounded-circle" title="Photo/video" @click="openCreatePost('photo')">
                                            <i class="ri-image-2-fill text-success fs-16"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-sm rounded-circle" title="Feeling/activity" @click="openCreatePost('feeling')">
                                            <i class="ri-emotion-happy-fill text-warning fs-16"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-12 col-sm-6 project-card" v-for="post in myPosts" :key="post.id">
                        <div class="card card-height-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-15">
                                                <img class="rounded-circle header-profile-user" :src="$page.props.user.data.avatar" @error="setDefaultImage($event)" :alt="$page.props.user.data.username">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fs-13 fw-semibold mb-0">{{ $page.props.user.data.name }}s</h6>
                                        <p class="fs-11 text-muted mb-0">Just now</p>
                                    </div>
                                </div>
                                <p v-if="post.content" class="fs-13 mt-3 mb-0" style="white-space: pre-wrap;">{{ post.content }}</p>
                                <div v-if="post.images.length" class="row g-1 mt-2">
                                    <div class="col-6" v-for="(img, idx) in post.images" :key="idx">
                                        <img :src="img" alt="" class="img-fluid rounded w-100" style="height: 160px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer border-top border-top-dashed mb-n2 fs-12">
                                <p class="fw-medium mb-0 mt-n1 float-end"><i class="ri-message-3-fill text-primary align-middle"></i> 0</p>
                                <p class="fw-medium mb-0 mt-n1"><i class="mdi mdi-heart-outline text-danger align-middle"></i> 0</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-12 col-sm-6 project-card" v-for="post in posts.data" :key="'post-' + post.id">
                        <div class="card card-height-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-15">
                                                <img class="rounded-circle header-profile-user" :src="post.user.avatar" @error="setDefaultImage($event)" :alt="post.user.name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fs-13 fw-semibold mb-0">{{ post.user.name }}</h6>
                                        <p class="fs-11 text-muted mb-0">{{ post.posted_at }}</p>
                                    </div>
                                </div>
                                <div class="mt-3 post-content-preview" style="cursor: pointer;" @click="openPost(post)">
                                    <div v-if="isSignatoryPost(post)" class="d-flex align-items-start gap-2">
                                        <div class="flex-shrink-0">
                                            <img :src="signatoryThumbnail" alt="Signatory document" class="rounded img-thumbnail" style="width: 90px; height: 110px; object-fit: cover;">
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <h5 v-if="post.title" class="mb-1 fs-14 fw-semibold text-primary">{{ post.title }}</h5>
                                            <p class="fs-12 text-muted signatory-content-clamp mb-0" v-html="post.content"></p>
                                        </div>
                                    </div>
                                    <template v-else>
                                        <h5 v-if="post.title" class="mb-1 fs-14 fw-semibold text-primary">{{ post.title }}</h5>
                                        <p class="fs-12 text-muted text-truncate-two-lines mb-0" v-html="post.content"></p>
                                    </template>
                                </div>
                            </div>
                            <div class="card-footer border-top border-top-dashed mb-n2 fs-12 d-flex align-items-center justify-content-between">
                                <ReactionPicker :post-id="post.id" :reaction-types="reactions" :my-reaction="post.my_reaction" @updated="(payload) => onPostUpdated(post.id, payload)" />
                                <button type="button" class="btn btn-sm btn-light" @click="openPost(post)">
                                    <i class="ri-message-3-line align-middle me-1"></i>{{ post.comments_count }} comment{{ post.comments_count === 1 ? '' : 's' }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-if="!posts.data.length" class="col-xxl-12">
                        <div class="text-center text-muted py-5">
                            <i class="ri-file-list-3-line fs-24 d-block mb-2"></i>
                            <p class="fs-12 mb-0">No post found.</p>
                        </div>
                    </div>
                </div>
            </simplebar>
        </div>

        <div class="col-md-3 mt-n2">
            <simplebar style="height: calc(100vh - 445px); overflow: auto;">
                <div class="card shadow-none border mt-n1">
                    <div class="card-header bg-light-subtle">
                        <div class="d-flex mb-n3">
                            <div class="flex-shrink-0 me-3 mt-1">
                                <div style="height:2rem;width:2rem;">
                                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                        <i class="ri-team-fill text-primary fs-20"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 mt-0 fs-13"><span class="text-body">Whereabouts</span></h5>
                                <p class="text-muted text-truncate text-truncate-two-lines fs-11"> Authorized signatories and approvers.</p>
                            </div>
                        </div>
                    </div>
                    <div class="cards border-bottom shadow-none" no-body style="height: 170px;">
                        <div v-if="loadingWhereabouts"
                            class="d-flex flex-column justify-content-center align-items-center h-100">

                            <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                            <p class="mt-2 fs-11 text-muted mb-0">Loading attendance...</p>
                        </div>

                        <simplebar v-else style="max-height: 170px; overflow-x: hidden;">
                        <ul class="list-group list-group-flush border-dashed mb-n4 mt-n2 p-3">

                            <li
                                class="list-group-item px-0"
                                v-for="group in whereaboutsGroups"
                                :key="group.key"
                            >
                                <div class="d-flex align-items-center justify-content-between">

                                    <!-- LEFT SIDE: STATUS -->
                                    <div class="flex-grow-1 ms-2">
                                        <h6 class="fs-12 mb-0 d-flex align-items-center">
                                            <i :class="['align-middle me-1', group.meta.icon, 'text-' + group.meta.color]"></i>
                                            {{ group.status }}
                                        </h6>
                                        <p v-if="group.station" class="text-muted fs-10 mb-0">{{ group.station }}</p>
                                    </div>

                                    <!-- RIGHT SIDE: AVATARS -->
                                    <div class="flex-shrink-0 text-end">
                                        <div
                                            v-if="group.users.length"
                                            class="avatar-group d-flex align-items-center"
                                            style="cursor: pointer;"
                                            @click="openWhereaboutsModal(group)"
                                        >

                                            <!-- FIRST 6 USERS -->
                                            <a
                                                v-for="user in group.users.slice(0, 6)"
                                                :key="user.user_id"
                                                href="javascript:void(0);"
                                                class="avatar-group-item material-shadow"

                                                :title="user.name"
                                                v-b-tooltip.hover
                                            >
                                                <div class="avatar-xxs">
                                                    <img
                                                        v-if="user.avatar"
                                                        :src="user.avatar"
                                                        class="avatar-img rounded-circle"
                                                    />
                                                    <div v-else class="avatar-img rounded-circle bg-danger">
                                                        {{ user.name.charAt(0) }}
                                                    </div>
                                                </div>
                                            </a>

                                            <!-- TOTAL COUNT / SHOW ALL -->
                                            <a
                                                class="avatar-group-item material-shadow"
                                                v-b-tooltip.hover
                                                title="Show all"
                                            >
                                                <div class="avatar-xxs">
                                                    <span :class="['avatar-title rounded-circle text-white fs-10', 'bg-' + group.meta.color]">
                                                        {{ group.users.length }}
                                                    </span>
                                                </div>
                                            </a>

                                        </div>
                                        <span v-else class="text-muted fs-11">None</span>
                                    </div>

                                </div>
                            </li>

                        </ul>
                        </simplebar>

                    </div>
                </div>
                <div class="card shadow-none border mt-n2">
                    <div class="card-header bg-light-subtle">
                        <div class="d-flex mb-n3">
                            <div class="flex-shrink-0 me-3 mt-1">
                                <div style="height:2rem;width:2rem;">
                                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                        <i class=" ri-mark-pen-fill text-primary fs-20"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 mt-0 fs-13"><span class="text-body">Organizational Chart</span></h5>
                                <p class="text-muted text-truncate text-truncate-two-lines fs-11"> Authorized signatories and approvers.</p>
                            </div>
                        </div>
                    </div>
                    <div class="cards border-bottom shadow-none" no-body style="height: 230px;">
                        <ul class="list-group list-group-flush border-dashed mb-n4 mt-n2 p-3">
                            
                            <li class="list-group-item px-0 border-0" :class="(index == 0) ? '' : 'border-top' " v-for="(list,index) in designations.data" v-bind:key="index">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <img :src="list.user?.avatar ?? list.oic?.avatar" alt="" class="rounded-circle avatar-sm">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 :class="(list.is_oic) ? 'text-warning' : 'text-primary'" class="fw-semibold mb-0 fs-12">{{ list.user?.name ?? list.oic?.name }}</h6>
                                        <p class="mb-0 fs-11">{{(list.is_oic) ? 'OIC -' : ''}} {{ list.designation }}</p>
                                        <p class="text-muted mb-0 fs-11">{{ list.assigned.name }}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <p class="text-muted mb-0 fs-11">-</p>
                                    </div>
                                </div>
                            </li>
                           
                        </ul>
                    </div>
                </div>

                <div class="card shadow-none border mt-n1">
                    <div class="card-header bg-light-subtle">
                        <div class="d-flex mb-n3">
                            <div class="flex-shrink-0 me-3 mt-1">
                                <div style="height:2rem;width:2rem;">
                                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                        <i class=" ri-calendar-fill text-primary fs-20"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 mt-0 fs-13"><span class="text-body">Upcoming Schedules</span></h5>
                                <p class="text-muted text-truncate-two-lines fs-11">Schedules only within the week.</p>
                            </div>
                        </div>
                    </div>
                    <div class="cards border-bottom shadow-none" no-body style="height: 230px;">
                        <div class="card-body">
                            <ul class="list-group list-group-flush border-dashed mt-n2">
                                <li class="list-group-item ps-0">
                                    <div class="row align-items-center g-3">
                                        <div class="col-auto">
                                            <div class="avatar-sm p-1 py-2 h-auto rounded-3 material-shadow bg-info">
                                                <div class="text-center">
                                                    <h5 class="mb-0 fs-12 text-white">2</h5>
                                                    <div class="fs-10 text-white">Tue</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h5 class="fw-normal mt-0 mb-0 fs-11">Zamboanga Peninsula</h5>
                                            <p class="text-primary text-truncate fw-semibold fs-12 mb-0">AI Roadmapping</p>
                                            <h5 class="text-muted fw-normal mt-0 mb-0 fs-11">Workshop</h5>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="card shadow-none border mt-n2">
                    <div class="card-header bg-light-subtle">
                        <div class="d-flex mb-n3">
                            <div class="flex-shrink-0 me-3 mt-1">
                                <div style="height:2rem;width:2rem;">
                                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                        <i class="ri-cake-2-fill text-danger fs-20"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 mt-0 fs-13"><span class="text-body">Birthdays</span></h5>
                                <p class="text-muted text-truncate-two-lines fs-11">Celebrating employees with birthdays this month</p>
                            </div>
                            <div v-if="birthdays.length" class="flex-shrink-0">
                                <span class="badge bg-danger-subtle text-danger fs-10">{{ birthdays.length }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="cards border-bottom shadow-none" no-body style="height: 250px;">
                        <div v-if="!nearestBirthdays.length" class="d-flex align-items-center justify-content-center h-100">
                            <p class="text-muted fs-11 mb-0">No more birthdays this month.</p>
                        </div>
                        <ul v-else class="list-group list-group-flush border-dashed mb-n4 mt-0 p-3">
                            <li v-for="person in nearestBirthdays" :key="'nearest-' + person.id" class="list-group-item bg-danger-subtle mb-2 rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <img :src="person.avatar" alt="" class="rounded-circle avatar-xs">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fs-12">{{ person.fullname }}</h6>
                                        <p class="text-muted mb-0 fs-11">{{ isNearestToday ? 'Today' : new Date(person.birthdate).toLocaleDateString('en-US', { month: 'long', day: 'numeric' }) }}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span v-if="isNearestToday" class="badge bg-danger fs-10">Today</span>
                                        <i v-else class="ri-hearts-fill fs-24 text-danger"></i>
                                    </div>
                                </div>
                            </li>
                            <simplebar v-if="remainingBirthdays.length" style="height: 150px; overflow: auto;">
                                <li class="list-group-item px-0 border-0 border-bottom" v-for="list in remainingBirthdays" :key="list.id">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <img :src="list.avatar" alt="" class="rounded-circle avatar-xxs">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 fs-12">{{ list.fullname }}</h6>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <p class="text-muted mb-0 fs-11">{{ new Date(list.birthdate).toLocaleDateString('en-US', { month: 'long', day: 'numeric' }) }}</p>
                                        </div>
                                    </div>
                                </li>
                            </simplebar>
                        </ul>
                    </div>
                </div>
            </simplebar>
        </div>

    </BRow>

    <Whereabouts ref="whereaboutsModal" />
    <CreatePost ref="createPostModal" @posted="handleNewPost" />
    <ViewPost ref="viewPostModal" :reaction-types="reactions" @updated="(payload) => onPostUpdated(payload.id, payload)" />
</template>
<script>
import simplebar from "simplebar-vue";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Whereabouts from './Modals/Whereabouts.vue';
import CreatePost from './Modals/CreatePost.vue';
import ViewPost from './Modals/ViewPost.vue';
import ReactionPicker from '@/Shared/Components/ReactionPicker.vue';

const SIGNATORY_POST_TYPE_ID = 203; // Special Order posts created from the Signatory module

const WHEREABOUTS_ORDER = ['Present', 'Absent', 'OB/On Travel', 'Suspension', 'Holiday'];
const WHEREABOUTS_META = {
    'Present': { icon: 'ri-checkbox-circle-fill', color: 'success' },
    'Absent': { icon: 'ri-close-circle-fill', color: 'danger' },
    'OB/On Travel': { icon: 'ri-flight-takeoff-line', color: 'info' },
    'Holiday': { icon: 'ri-calendar-event-fill', color: 'warning' },
    'Suspension': { icon: 'ri-alert-fill', color: 'primary' },
};

export default {
    components: { PageHeader, simplebar, Whereabouts, CreatePost, ViewPost, ReactionPicker },
    props: ['birthdays','dtr','designations', 'attendance','whereabouts','posts','reactions'],
    data(){
        return {
            whereabouts: [],
             loadingWhereabouts: false,
             myPosts: []
        }
    },
    computed: {
        firstName() {
            return this.$page.props.user.data.name?.split(' ')[0] ?? '';
        },
        // birthdays are already scoped to the current month by the backend; only the day-of-month matters here.
        // birthdays earlier than today are already "done" and dropped entirely rather than pushed to the bottom.
        upcomingBirthdays() {
            const todayDate = new Date().getDate();
            return [...this.birthdays]
                .filter(person => new Date(person.birthdate).getDate() >= todayDate)
                .sort((a, b) => new Date(a.birthdate).getDate() - new Date(b.birthdate).getDate());
        },
        // everyone sharing the nearest upcoming day (today if anyone's birthday is today, otherwise the next date this month)
        nearestBirthdays() {
            if (!this.upcomingBirthdays.length) return [];
            const nearestDay = new Date(this.upcomingBirthdays[0].birthdate).getDate();
            return this.upcomingBirthdays.filter(person => new Date(person.birthdate).getDate() === nearestDay);
        },
        isNearestToday() {
            return this.nearestBirthdays.length > 0
                && new Date(this.nearestBirthdays[0].birthdate).getDate() === new Date().getDate();
        },
        // everyone after the nearest day, shown below so they're not duplicated in the highlighted section above
        remainingBirthdays() {
            if (!this.nearestBirthdays.length) return [];
            const nearestDay = new Date(this.nearestBirthdays[0].birthdate).getDate();
            return this.upcomingBirthdays.filter(person => new Date(person.birthdate).getDate() !== nearestDay);
        },
        whereaboutsGroups() {
            const groups = {};

            // Present / Absent are always shown, even with 0 members (e.g. on a full holiday/suspension)
            ['Present', 'Absent'].forEach(status => {
                groups[status] = { key: status, status, station: null, users: [] };
            });

            this.whereabouts.forEach(item => {
                const key = item.station ? `${item.status}|${item.station}` : item.status;

                if (!groups[key]) {
                    groups[key] = { key, status: item.status, station: item.station, users: [] };
                }

                groups[key].users.push(item);
            });

            return Object.values(groups)
                .map(group => ({
                    ...group,
                    meta: WHEREABOUTS_META[group.status] || { icon: 'ri-user-3-fill', color: 'secondary' },
                }))
                .sort((a, b) => {
                    const order = WHEREABOUTS_ORDER.indexOf(a.status) - WHEREABOUTS_ORDER.indexOf(b.status);
                    return order !== 0 ? order : (a.station || '').localeCompare(b.station || '');
                });
        },
        currentMonth() {
            return new Date().toLocaleString('default', { month: 'long', year: 'numeric' });
        },
        signatoryThumbnail() {
            return '/images/thumbnails/preview.jpg';
        }
    },
    created(){
        this.fetchDaily();
    },
    methods: {
        fetchDaily(){
             this.loadingWhereabouts = true;
            axios.get('/dashboard',{
                params : {
                    option: 'whereabouts',
                }
            })
            .then(response => {
                this.whereabouts = response.data;         
            })
            .catch(err => console.log(err))
            .finally(() => {
                this.loadingWhereabouts = false;
            });
        },
        openWhereaboutsModal(group){
            this.$refs.whereaboutsModal.show(group);
        },
        openCreatePost(mode = null){
            this.$refs.createPostModal.show(mode);
        },
        handleNewPost(post){
            this.myPosts.unshift(post);
        },
        openPost(post){
            this.$refs.viewPostModal.show(post.id);
        },
        isSignatoryPost(post){
            return post.type_id === SIGNATORY_POST_TYPE_ID;
        },
        onPostUpdated(postId, payload){
            const post = this.posts.data.find(p => p.id === postId);
            if (!post) return;
            if (payload.reactions_count !== undefined) post.reactions_count = payload.reactions_count;
            if (payload.my_reaction !== undefined) post.my_reaction = payload.my_reaction;
            if (payload.comments_count !== undefined) post.comments_count = payload.comments_count;
        },
    }
}
</script>
<style scoped>
.avatar-xxs {
    width: 24px;
    height: 24px;
    flex: 0 0 24px;
    position: relative;
}

.signatory-content-clamp {
    overflow: hidden;
    text-overflow: ellipsis;
    -webkit-line-clamp: 4;
    line-clamp: 4;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    white-space: normal;
}

.post-content-preview :deep(img) {
    max-width: 100%;
    max-height: 160px;
    height: auto;
    object-fit: cover;
    border-radius: 6px;
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    border-radius: 50%;
}
</style>