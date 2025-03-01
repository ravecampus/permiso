<script setup>
    import { ref, onMounted } from "vue"
    import { useRouter } from "vue-router"

    const user = ref({})
    const notifications = ref([])
    const router = useRouter()

    onMounted(()=>{
        getAuthUser();

        if(!window.winsdev.isLoggedin){
            router.push({name:"login"});
        }

        getNotification()
    })


     const getAuthUser = ()=>{
        axios.get('/api/user').then((res)=>{
            user.value = res.data
        })
    }

    const logout = ()=>{
        Swal.fire({
            title:"Log out",
            text: "Do you want Log out?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#26884b",
            cancelButtonColor: "#ffc107",
            cancelButtonText: "No",
            confirmButtonText: "Yes"
            }).then((result) => {
            if (result.isConfirmed) {
                axios.get('/api/logout').then((res)=>{
                    if (res.data.success) {
                        window.location.href = "/login"
                    } 
                })  
            }
        });
    }

    const getNotification = ()=>{
        axios.get("api/notification").then((res)=>{
            notifications.value = res.data
        })
    }

    const extractRole = (data)=>{
        return data == 1 ? "ADMIN" : data == 2 ? "SCHOOL PRESIDENT": data == 3 ? "OFFICE HEAD" : data == 4 ? "FACULTY / STAFF" :" NONE"
    }

    const showNotification = (data)=>{
        axios.delete("api/notification/"+data.id).then((res)=>{
            getNotification()
            router.push({name:'viewleave', params:{id:data.leave_application_id}})
        })
    }

</script>
<template>
    <div>
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-app">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-1" href="index.html">
                <img class="dashboard-logo" :src="'/img/dashboard.png'" />
            </a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="bi bi-list lead"></i></button>
            <!-- Navbar Search-->
            <!-- <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form> -->

             
            <!-- Navbar-->
             <ul class="navbar-nav ms-auto ms-auto me-0 my-2 my-md-0">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link text-light text-body  notication" id="notification" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell fs-4 text-light"></i>
                        <span class="badge rounded-pill badge-notification bg-danger" v-if="notifications.length > 0">{{ notifications.length }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notification" >
                        <div class="text-center fw-bold text-success" v-if="notifications.length == 0"> No Notification available !</div>
                       <div class="noti-div position-relative mt-3" v-if="notifications.length > 0"> 
                        <li v-for="(list, index) in notifications" :key="index" >
                            <a  href="#" class="dropdown-item p-2" @click="showNotification(list)">
                                <div class="notification d-flex justify-content-start">
                                    <div class="person">
                                        <div class="badge rounded-pill bg-success p-1 mt-3">
                                            <i class="bi bi-person fs-3 text-light"></i>
                                        </div>
                                    </div>
                                    <div class="message fs-6 ms-3 text-success fw-bold me-3">
                                        {{ list.message }}
                                        <div class="fw-normal m-0">
                                            {{ list.sender.name }}
                                        </div>
                                        <p class="p-0 m-0 text-muted fw-normal">
                                            <small>
                                            <timeago  :datetime="list.created_at"/>
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                
                       </div>
                        <li><hr class="dropdown-divider" /></li>
                       
                    </ul>
                </li>
            </ul>
            <div class="user text-light ">
                <div class="mb-0 pb-0"> {{ user.name }}</div>
            </div>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person fs-4"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><router-link class="dropdown-item" :to="{name:'profile'}">Profile</router-link></li>
                        <!-- <li><a class="dropdown-item" href="#!">Activity Log</a></li> -->
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" @click="logout" href="#">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                           
                            <router-link  class="nav-link side mt-4" :to="{name:'dashboard'}">
                                <div class="sb-nav-link-icon"><i class="bi bi-speedometer"></i></div>
                                Dashboard
                            </router-link>
                            <div class="sb-sidenav-menu-heading pt-1">Management</div>
                            <router-link class="nav-link side" :to="{name:'office_setup'}">
                                <div class="sb-nav-link-icon"><i class="bi bi-building-fill-add"></i></div>
                                Manage Offices
                             </router-link>
                            <router-link :to="{name:'position_setup'}" class="nav-link side">
                                <div class="sb-nav-link-icon"><i class="bi bi-person-lines-fill"></i></div>
                                Manage Positions
                            </router-link>
                            <router-link :to="{name:'leave_type_setup'}" class="nav-link side">
                                <div class="sb-nav-link-icon"><i class="bi bi-journal-plus"></i></div>
                                Manage Leave Type
                            </router-link>
                            <router-link :to="{name:'employee_setup'}" class="nav-link side">
                                <div class="sb-nav-link-icon"><i class="bi bi-people"></i></div>
                                Employee Record
                            </router-link>
                             <router-link :to="{name:'setupschoolyear'}" class="nav-link side">
                                <div class="sb-nav-link-icon"><i class="bi bi-calendar2-plus"></i></div>
                                Manage S.Y.
                            </router-link>
                             <router-link :to="{name:'emplevel'}" class="nav-link side">
                                <div class="sb-nav-link-icon"><i class="bi bi-gear-wide-connected"></i></div>
                                Emp. Classif. Setup
                            </router-link>
                             <router-link :to="{name:'empleavecredit'}" class="nav-link">
                                <div class="sb-nav-link-icon"><i class="bi bi-person-lines-fill"></i></div>
                                Emp. Leave Credits
                            </router-link>
                            <div class="sb-sidenav-menu-heading pt-1">Application</div>
                            <router-link :to="{name:'leaveselection'}" class="nav-link side">
                                <div class="sb-nav-link-icon"><i class="bi bi-filetype-docx"></i></div>
                                Leave Application
                            </router-link>
                            <router-link :to="{name:'myleave'}" class="nav-link side">
                                <div class="sb-nav-link-icon"><i class="bi bi-file-arrow-up-fill"></i></div>
                                My Leave 
                            </router-link>
                            <div class="sb-sidenav-menu-heading pt-1">Request</div>
                             <router-link :to="{name:'initialrequest'}" class="nav-link side">
                                <div class="sb-nav-link-icon"><i class="bi bi-file-arrow-up-fill"></i></div>
                                Leave Request
                            </router-link>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer mt-5">
                        <div class="small">Logged in as:</div>
                        {{ extractRole(user.role) }}
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <router-view></router-view>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2025</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .bg-app{
        background-color: #4a6248;
    }

    .sb-sidenav-dark {
        background-color: #455d43;
        color: #fff;
        // padding:1rem;
    }
    .sb-sidenav-dark .sb-sidenav-menu .nav-link {
        color: #fff;
         
    }
    .sb-sidenav-dark .sb-sidenav-menu .nav-link .sb-nav-link-icon {
         color: #fff;
    }
    .side{
        
        &:hover, :focus{
         
            color: #ffffff;
            background: #55a04e;
            cursor: pointer;
            // border-radius: 2.5rem;
            //  border: 1px solid #fff;
        }
    }
     .router-link-exact-active {
        display: inline-block;
        color: #ffffff;
        background: #55a04e;
        text-align: center;
        text-decoration: none;
        // border: 1px solid #fff;
        cursor: pointer;
        // border-radius: 2.5rem;
        // transition: color .15sease-in-out, background-color .15sease-in-out, border-color .15sease-in-out, box-shadow .15sease-in-out;
    }

    .dashboard-logo{
        width: 10em;
        margin-top: .2em;

    }

    .notication{
        margin-right: 3rem !important;
        .badge{
            margin-left: -.8rem !important;
            position: absolute;
        }
    }

    .dropdown-item{
        &:hover{
            background-color: #d3f3d0 !important;
            // color:#fff !important;
        }
    }

    .noti-div{
        max-height: 15rem;
        // width: 22rem !important;
        overflow-y: hidden; /* Hide vertical scrollbar */
        overflow-x: hidden;
        margin: 0 auto;
        &:hover {
            // width: 22rem !important;
            overflow-y: scroll;
            margin: 0 auto;
        }
        // width: 22rem !important;
    }
    ::-webkit-scrollbar {
        width: .2rem;
    }
    ::-webkit-scrollbar-track {
        background: #0aa35c;
     }

</style>

