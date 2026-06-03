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
                <!-- <div class="row g-3" id="folderlist-data">
                    <div class="col-xxl-3 col-6 folder-card">
                        <div class="card bg-white shadow-none" id="folder-1">
                            <div class="card-body">
                        
                                <div class="text-center">
                                    <div class="mb-2">
                                        <i class="ri-folder-2-fill align-bottom text-warning display-5"></i>
                                    </div>
                                    <h6 class="fs-16 folder-name">File Server</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-6 folder-card">
                        <div class="card bg-white shadow-none" id="folder-1">
                            <div class="card-body">
                        
                                <div class="text-center">
                                    <div class="mb-2">
                                        <i class="ri-folder-2-fill align-bottom text-warning display-5"></i>
                                    </div>
                                    <h6 class="fs-16 folder-name">Media Files</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-6 folder-card">
                        <div class="card bg-white shadow-none" id="folder-1">
                            <div class="card-body">
                        
                                <div class="text-center">
                                    <div class="mb-2">
                                        <i class="ri-folder-2-fill align-bottom text-warning display-5"></i>
                                    </div>
                                    <h6 class="fs-16 folder-name">Employees</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-6 folder-card">
                        <div class="card bg-white shadow-none" id="folder-1">
                            <div class="card-body">
                        
                                <div class="text-center">
                                    <div class="mb-2">
                                        <i class="ri-folder-2-fill align-bottom text-warning display-5"></i>
                                    </div>
                                    <h6 class="fs-16 folder-name">Files</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div> -->
            
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
                                <h5 class="mb-0 fs-13">Attendance Overview</h5>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="d-flex gap-1 align-items-center my-n2">
                                    <button type="button" class="btn avatar-xs p-0 favourite-btn material-shadow-none active">
                                        <span class="avatar-title bg-transparent fs-15">
                                            <i class="ri-star-fill"></i>
                                        </span>
                                    </button>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-header p-0 border-0 bg-light-subtle">
                        <div class="row g-0 text-center">
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border border-dashed border-start-0">
                                    <h5 class="mb-1">{{ attendance[0] }}</h5>
                                    <p class="text-muted mb-0">Incomplete</p>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border border-dashed border-start-0">
                                    <h5 class="mb-1">{{ attendance[1] }}</h5>
                                    <p class="text-muted mb-0">Lates</p>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border border-dashed border-start-0 border-end-0">
                                    <h5 class="mb-1">{{ attendance[2] }}</h5>
                                    <p class="text-muted mb-0">Absences</p>
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

                    <div class="col-xxl-12 col-sm-6 project-card">
                        <div class="card card-height-100">
                            <div class="card-body">
                                <div class="bookmark-icon position-absolute top-0 end-0 p-3"> 
                                    <BButton variant="link" class="btn-icon" @click="toggleLike(list)" data-bs-toggle="button" aria-pressed="true">
                                        <i class="mdi mdi-cards-heart fs-16"></i>
                                    </BButton>
                                </div>
                                <div class="d-flex flex-column h-100">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-15">
                                                    <img class="rounded-circle header-profile-user" :src="$page.props.user.data.avatar" @error="setDefaultImage($event)" :alt="$page.props.user.data.username">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fs-13 fw-semibold mb-0">{{ $page.props.user.data.name }}</h6>
                                            <p class="fs-11 text-muted mb-0">May 26 at 5:40 PM</p>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-2 mt-4">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-xl">
                                                <span class="avatar-title bg-warning-subtle rounded p-2">
                                                    <img src="assets/images/brands/slack.png" alt="" class="img-fluid p-1">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1 fs-14 fw-semibold text-primary">Announcement Notice</h5>
                                            <p class="fs-12 text-muted text-truncate-two-lines mb-3">This announcement is currently a placeholder and not yet finalized. Content is under development.</p>
                                        </div>
                                    </div>
                                  
                                </div>

                            </div>
                              <div class="card-footer border-top border-top-dashed mb-n2 fs-12" style="cursor: pointer;" @click="openView(list)">
                            <p class="fw-medium mb-0 mt-n1 float-end"><i class="ri-message-3-fill text-primary align-middle"></i> 2</p>
                            <p class="fw-medium mb-0 mt-n1"><i class="mdi mdi-heart text-danger align-middle"></i> 5</p>
                        </div>
                        </div>
                    </div>

                    <div class="col-xxl-12 col-sm-6 project-card">
                        <div class="card card-height-100">
                            <div class="card-body">
                                <div class="bookmark-icon position-absolute top-0 end-0 p-3"> 
                                    <BButton variant="link" class="btn-icon" @click="toggleLike(list)" data-bs-toggle="button" aria-pressed="true">
                                        <i class="mdi mdi-cards-heart fs-16"></i>
                                    </BButton>
                                </div>
                                <div class="d-flex flex-column h-100">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-15">
                                                    <img class="rounded-circle header-profile-user" :src="$page.props.user.data.avatar" @error="setDefaultImage($event)" :alt="$page.props.user.data.username">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fs-13 fw-semibold mb-0">{{ $page.props.user.data.name }}</h6>
                                            <p class="fs-11 text-muted mb-0">May 26 at 5:40 PM</p>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-2 mt-4">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-xl">
                                                <span class="avatar-title bg-warning-subtle rounded p-2">
                                                    <img src="assets/images/brands/slack.png" alt="" class="img-fluid p-1">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1 fs-14 fw-semibold text-primary">Please see DOST Special Order No. 575, Series of 2026</h5>
                                            <p class="fs-12 text-muted text-truncate-two-lines mb-3">Re: Designation of Mr. RICARDO J. APOLINARIO III, Chief Science Research Specialist as Officer-in-Charge, Office of the Regional Director, DOST - Zamboanga Peninsula For your information. Thank you.</p>
                                        </div>
                                    </div>
                                  
                                </div>

                            </div>
                              <div class="card-footer border-top border-top-dashed mb-n2 fs-12" style="cursor: pointer;" @click="openView(list)">
                            <p class="fw-medium mb-0 mt-n1 float-end"><i class="ri-message-3-fill text-primary align-middle"></i> 2</p>
                            <p class="fw-medium mb-0 mt-n1"><i class="mdi mdi-heart text-danger align-middle"></i> 5</p>
                        </div>
                        </div>
                    </div>

                    <div class="col-xxl-12 col-sm-6 project-card">
                        <div class="card card-height-100">
                            <div class="card-body">
                                <div class="bookmark-icon position-absolute top-0 end-0 p-3"> 
                                    <BButton variant="link" class="btn-icon" @click="toggleLike(list)" data-bs-toggle="button" aria-pressed="true">
                                        <i class="mdi mdi-cards-heart fs-16"></i>
                                    </BButton>
                                </div>
                                <div class="d-flex flex-column h-100">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-15">
                                                    <img class="rounded-circle header-profile-user" :src="$page.props.user.data.avatar" @error="setDefaultImage($event)" :alt="$page.props.user.data.username">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fs-13 fw-semibold mb-0">{{ $page.props.user.data.name }}</h6>
                                            <p class="fs-11 text-muted mb-0">May 26 at 5:40 PM</p>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-2 mt-4">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-xl">
                                                <span class="avatar-title bg-warning-subtle rounded p-2">
                                                    <img src="assets/images/brands/slack.png" alt="" class="img-fluid p-1">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1 fs-14 fw-semibold text-primary">Please see DOST SPECIAL ORDER No. 081, Series of 2026</h5>
                                            <p class="fs-12 text-muted text-truncate-two-lines mb-3">Re: Constitution of the Artificial Intelligence (AI) Focal Persons of the Department of Science and Technology Regional Office IX (DOST IX)
For your information.</p>
                                        </div>
                                    </div>
                                  
                                </div>

                            </div>
                              <div class="card-footer border-top border-top-dashed mb-n2 fs-12" style="cursor: pointer;" @click="openView(list)">
                            <p class="fw-medium mb-0 mt-n1 float-end"><i class="ri-message-3-fill text-primary align-middle"></i> 2</p>
                            <p class="fw-medium mb-0 mt-n1"><i class="mdi mdi-heart text-danger align-middle"></i> 5</p>
                        </div>
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
                       
                        <ul v-else class="list-group list-group-flush border-dashed mb-n4 mt-n2 p-3">
   
                            <li
                                class="list-group-item px-0"
                                v-for="(users, status) in groupedAttendance"
                                :key="status"
                            >
                                <div class="d-flex align-items-center justify-content-between">

                                    <!-- LEFT SIDE: STATUS -->
                                    <div class="flex-grow-1 ms-2">
                                        <h6 class="fs-12 mb-0">
                                            {{ status }}
                                        </h6>
                                    </div>

                                    <!-- RIGHT SIDE: AVATARS -->
                                    <div class="flex-shrink-0 text-end">
                                        <div class="avatar-group d-flex align-items-center">

                                            <!-- FIRST 10 USERS -->
                                            <a
                                                v-for="user in users.slice(0, 10)"
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
                                                        class="rounded-circle img-fluid"
                                                    />
                                                    <div v-else class="avatar-title rounded-circle bg-danger">
                                                        {{ user.name.charAt(0) }}
                                                    </div>
                                                </div>
                                            </a>

                                            <!-- + REMAINING -->
                                            <a
                                                v-if="users.length > 10"
                                                class="avatar-group-item material-shadow"
                                            >
                                                <div class="avatar-xxs">
                                                    <span class="avatar-title rounded-circle bg-info text-white fs-10">
                                                        {{ users.length - 10 }}
                                                    </span>
                                                </div>
                                            </a>

                                        </div>
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
                        </div>
                    </div>
                    <div class="cards border-bottom shadow-none" no-body style="height: 250px;">
                        <ul class="list-group list-group-flush border-dashed mb-n4 mt-0 p-3">
                            <li v-if="nearestBirthday" class="list-group-item bg-danger-subtle mb-2 rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <img :src="nearestBirthday.avatar" alt="" class="rounded-circle avatar-xs">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fs-12">{{ nearestBirthday.fullname }}</h6>
                                        <p class="text-muted mb-0 fs-11">{{ new Date(nearestBirthday.birthdate).toLocaleDateString('en-US', { month: 'long', day: 'numeric' }) }}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="ri-hearts-fill fs-24 text-danger"></i>
                                    </div>
                                </div>
                            </li>
                            <simplebar style="height: 150px; overflow: auto;">
                                <li class="list-group-item px-0 border-0 border-bottom" v-for="(list,index) in sortedBirthdays" v-bind:key="index">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <img :src="list.avatar" alt="" class="rounded-circle avatar-xxs">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 fs-12">{{ list.fullname }}</h6>
                                            <!-- <p class="text-muted mb-0 fs-11">{{ new Date(list.birthdate).toLocaleDateString('en-US', { month: 'long', day: 'numeric' }) }}</p> -->
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
</template>
<script>
import simplebar from "simplebar-vue";
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    components: { PageHeader, simplebar },
    props: ['birthdays','dtr','designations', 'attendance','whereabouts'],
    data(){
        return {
            whereabouts: [],
             loadingWhereabouts: false
        }
    },
    computed: {
        sortedBirthdays() {
            return [...this.birthdays].sort((a, b) => {
                const today = new Date();

                const getNextBirthday = (birthdate) => {
                    const date = new Date(birthdate);

                    const next = new Date(
                        today.getFullYear(),
                        date.getMonth(),
                        date.getDate()
                    );

                    if (next < today) {
                        next.setFullYear(today.getFullYear() + 1);
                    }

                    return next;
                };

                return getNextBirthday(a.birthdate) - getNextBirthday(b.birthdate);
            });
        },
        nearestBirthday() {
            return this.sortedBirthdays.length
                ? this.sortedBirthdays[0]
                : null;
        },
        groupedAttendance() {
            return this.whereabouts.reduce((groups, item) => {

                if (!groups[item.status]) {
                    groups[item.status] = [];
                }

                groups[item.status].push(item);

                return groups;

            }, {});
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
    }
}
</script>