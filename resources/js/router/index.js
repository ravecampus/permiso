import {createRouter, createWebHistory, useRouter } from "vue-router";

import Mainpage from '../components/Mainpage.vue'
import Login from '../components/Login.vue'
import SetupEmployee from '../components/humanresource/EmployeeSetup.vue'
import SetupPosition from '../components/humanresource/PositionSetup.vue'
import SetupOffice from '../components/humanresource/OfficeSetup.vue'
import SetupLeaveType from '../components/humanresource/LeaveTypeSetup.vue'
import SetupEmpClassification from '../components/humanresource/EmpClassificationSetup.vue'
import SetupPage from '../components/humanresource/SetupPage.vue'
import InitialApp from '../components/humanresource/SetupPageInitial.vue'
import FinalApp from '../components/humanresource/SetupPageFinal.vue'
import SetupLeaveCredit from '../components/humanresource/SetupPageLeaveCredit.vue'
import SetupSchoolYear from '../components/humanresource/SetupPageSY.vue'
import EmpLeaveCredit from '../components/humanresource/EmpLeaveCredit.vue'

import SelectionLeave from '../components/page/SelectionLeave.vue'
import LeaveApplication from '../components/page/LeaveApplication.vue'
import MyLeave from '../components/page/MyLeave.vue'
import  ViewLeave from '../components/page/ViewLeave.vue'
import  LeaveRequest from '../components/page/LeaveRequest.vue'
import  InitialRequest from '../components/page/InitialRequest.vue'
import  FinalRequest from '../components/page/FinalRequest.vue'
import  Dashboard from '../components/page/Dashboard.vue'
import  Profile from '../components/page/Profile.vue'

import NotFound from '../components/NotFound.vue'



const routes = [
    {
        path:'/',
        name:'permiso',
        component: Mainpage,
        children:[
            {
                path:'',
                name:'dashboard',
                component: Dashboard,
            },
            {
                path:'/setup/employee',
                name:'employee_setup',
                component: SetupEmployee,
            },
            {
                path:'/setup/position',
                name:'position_setup',
                component: SetupPosition,
            },
            {
                path:'/setup/office',
                name:'office_setup',
                component: SetupOffice,
            },
            {
                path:'/setup/leave-type',
                name:'leave_type_setup',
                component: SetupLeaveType,
            },
            {
                path:'/setup/level',
                name:'emplevel',
                component: SetupEmpClassification,
            },
            {
                path:'/setup/school-year',
                name:'setupschoolyear',
                component: SetupSchoolYear,
            },
            {
                path:'/employee/leave-credit',
                name:'empleavecredit',
                component: EmpLeaveCredit,
            },
            {
                path:'/setup/level/:id',
                name:'emplevelsetup',
                component: SetupPage,
                children:[
                    {
                        path:'initial-approval',
                        // path:'',
                        name:'initialaprv',
                        component: InitialApp 
                    },
                    {
                        path:'final-approval',
                        name:'finalaprv',
                        component: FinalApp 
                    },
                    {
                        path:'leave-credits',
                        name:'setupleavecredit',
                        component: SetupLeaveCredit
                    },
                ]
                   
            },
            {
                path:'/apply',
                name:'leaveselection',
                component: SelectionLeave,
            },
            {
                path:'/apply/leave-application/:id',
                name:'leaveapplication',
                component: LeaveApplication,
            },
            {
                path:'/apply/my-leave',
                name:'myleave',
                component: MyLeave,
            },
            {
                path:'/profile',
                name:'profile',
                component: Profile,
            },
            {
                path:'/apply/leave-application/:id/edit/:leave_id',
                name:'leaveapplication_edit',
                component: LeaveApplication,
            },
            {
                path:'/apply/view-leave/:id',
                name:'viewleave',
                component: ViewLeave,
            },
            {
                path:'/leave-request/',
                name:'leaverequest',
                component: LeaveRequest,
                children:[
                    {
                        path:'',
                        name:'initialrequest',
                        component: InitialRequest,
                    }, 
                    {
                        path:'final',
                        name:'finalrequest',
                        component: FinalRequest,
                    }, 
                ]
            },
        ]
    },
    {
        path:'/login',
        name:'login',
        component: Login,
    },
    
    {
        path:'/:pathMatch(.*)*',
        name:'notfound',
        component: NotFound 
    }

]

const router  = createRouter({
    history:createWebHistory(),
    routes
})

const openRoutes = [
    'login',
    'notfound',
    'permiso',
    'dashboard'
    
];

// const adminRoutes = [
//     'about',
//     'search',
//     'main.document',
//     'rechiva',
//     'publicpage',
//     'admin',
//     'dashboard',
//     'settings',
//     'setting.authors',
//     'setting.departments',
//     'setting.typeofpapers',
//     'admin.request',
//     'admin.requestdoc',
//     'admin.published',
//     'admin.archived',
//     'admin.account',
//     'admin.canceled',
//     'notfound',
//     'admin.users',
//     'admin.user',
//     'admin.upload_p',
//     'admin.upload_a',
//     'admin.editupload_p',
//     'admin.editupload_a',
//     'admin.report',
    
// ];

// const facultyRoutes = [
//     'about',
//     'rechiva',
//     'publicpage',
//     'signin',
//     'search',
//     'signup',
//     'main.document',
//     'notfound',
//     'faculty',
//     'faculty.upload_a',
//     'faculty.upload_p',
//     'faculty.documents',
//     'faculty.document',
//     'faculty.approved',
//     'faculty.published',
//     'faculty.canceled',
//     'faculty.archived',
//     'faculty.myaccount',
//     'faculty.dashboard',
//     'faculty.editupload_p',
//     'faculty.editupload_a'
    
// ];

// const facultyInctive = [
//     'about',
//     'rechiva',
//     'publicpage',
//     'signin',
//     'main.document',
//     'search',
//     'signup',
//     'notfound',
//     'faculty',
//     'faculty.dashboard',
//     'faculty.myaccount',
    
// ];

router.beforeEach((to, from, next)=>{
    if(!window.winsdev.isLoggedin){
        if(openRoutes.includes(to.name)){
            return next();
        }else{
            return next({ name: 'notfound' })
        }
    }else{
        return next()
        let user = window.winsdev.user;
        // if(adminRoutes.includes(to.name) && user.role === 2){
        //     return next();
        // }else if(user.role === 1){
        //     if(facultyRoutes.includes(to.name) && user.activate == 1){
        //         return next(); 
        //     }else if(facultyInctive.includes(to.name) && user.activate == 0){
        //         return next(); 
        //     }else{
        //         return next({ name: 'notfound' })
        //     }
            
        // }else{
        //     return next({ name: 'notfound' })
        // }
    }
});



export default router