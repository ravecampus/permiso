<script setup>
    import { ref, onMounted } from "vue"

    const user = ref({})
    onMounted(()=>{

        console.log(window.winsdev.user)
        getAuthUser();
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

</script>
<template>
    <div>
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-app">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.html">Start Bootstrap</a>
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
            <a href="" class="text-light text-body ms-auto me-0 me-md-3 my-2 my-md-0">
                <i class="bi bi-bell fs-4 text-light"></i>
                <span class="badge rounded-pill badge-notification bg-danger">999+</span>
            </a>
            <div class="user text-light ">
                <div class="mb-0 pb-0"> {{ user.name }}</div>
            </div>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
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
                           
                            <router-link  class="nav-link mt-4" :to="{name:'permiso'}">
                                <div class="sb-nav-link-icon"><i class="bi bi-speedometer"></i></div>
                                Dashboard
                            </router-link>
                            <div class="sb-sidenav-menu-heading pt-1">Management</div>
                            <!-- <div class="sb-sidenav-menu-heading">Settings</div> -->
                            <router-link class="nav-link" :to="{name:'office_setup'}">
                                <div class="sb-nav-link-icon"><i class="bi bi-building-fill-add"></i></div>
                                Manage Offices
                             </router-link>
                            <router-link :to="{name:'position_setup'}" class="nav-link">
                                <div class="sb-nav-link-icon"><i class="bi bi-person-lines-fill"></i></div>
                                Manage Positions
                            </router-link>
                            <router-link :to="{name:'employee_setup'}" class="nav-link">
                                <div class="sb-nav-link-icon"><i class="bi bi-people"></i></div>
                                Employee Record
                            </router-link>
                             <router-link :to="{name:'setupschoolyear'}" class="nav-link">
                                <div class="sb-nav-link-icon"><i class="bi bi-calendar2-plus"></i></div>
                                Manage S.Y.
                            </router-link>
                             <router-link :to="{name:'emplevel'}" class="nav-link">
                                <div class="sb-nav-link-icon"><i class="bi bi-gear-wide-connected"></i></div>
                                Emp. Classif. Setup
                            </router-link>
                            <div class="sb-sidenav-menu-heading pt-1">Application</div>
                            <router-link :to="{name:'leaveselection'}" class="nav-link">
                                <div class="sb-nav-link-icon"><i class="bi bi-filetype-docx"></i></div>
                                Leave Application
                            </router-link>
                            <router-link :to="{name:'myleave'}" class="nav-link">
                                <div class="sb-nav-link-icon"><i class="bi bi-file-arrow-up-fill"></i></div>
                                My Leave 
                            </router-link>
                            <div class="sb-sidenav-menu-heading pt-1">Request</div>
                             <router-link :to="{name:'initialrequest'}" class="nav-link">
                                <div class="sb-nav-link-icon"><i class="bi bi-file-arrow-up-fill"></i></div>
                                Leave Request
                            </router-link>

                            <!-- <router-link :to="{name:'myleave'}" class="nav-link">
                                <div class="sb-nav-link-icon"><i class="bi bi-file-arrow-up-fill"></i></div>
                                Leave Request
                            </router-link>
                             -->
                            <!-- <div class="sb-sidenav-menu-heading">Interface</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Layouts
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="layout-static.html">Static Navigation</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Pages
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a> -->
                            <!-- <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Authentication
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="login.html">Login</a>
                                            <a class="nav-link" href="register.html">Register</a>
                                            <a class="nav-link" href="password.html">Forgot Password</a>
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                        Error
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="401.html">401 Page</a>
                                            <a class="nav-link" href="404.html">404 Page</a>
                                            <a class="nav-link" href="500.html">500 Page</a>
                                        </nav>
                                    </div>
                                </nav>
                            </div> -->
                            <!-- <div class="sb-sidenav-menu-heading">Addons</div>
                            <a class="nav-link" href="charts.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Charts
                            </a>
                            <a class="nav-link" href="tables.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                            </a> -->
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Start Bootstrap
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
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
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
    .nav-link{
        
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

    

</style>

