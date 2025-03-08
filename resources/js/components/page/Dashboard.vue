<script setup>

    import { ref , onMounted } from "vue"
    import { useRouter } from "vue-router"

    const leave_sy = ref([])
    const leaves = ref([])
    const user = ref([])
    const history = ref([])

    onMounted(()=>{
        getMyLeaveSY()
        getLeave()
        getAuthUser()
        getHistory()
    })
    
    const router = useRouter()
    const format = (data) => {
        const d = new Date(data)
        const day =("0" + d.getDate()).slice(-2);
        const month = ("0"+(d.getMonth()+1)).slice(-2);
        const year =  d.getFullYear();

        return  month+ "/"+day+"/" + year;
    }

    const getAuthUser = ()=>{
        axios.get('/api/user').then((res)=>{
            user.value = res.data
        })
    }

    const getMyLeaveSY = ()=>{
        axios.get("/api/my-leave-sy").then((res)=>{
            leave_sy.value = res.data
        })
    }

    const getLeave = () => {
         axios.get('/api/source-leave').then((res)=>{
            leaves.value = res.data
        })
    }

    const getHistory = ()=>{
        axios.get("/api/dashboard-history").then((res)=>{
            history.value = res.data
        })
    }

    const available = (data)=>{
        let ret = 0
        leave_sy.value.forEach(val => {
            if(val.leave_id == data.leave_id){
                ret += val.number_of_day
            }
        });

        return data.credits - ret;
    }
    const extractPending = (data)=>{
        let ret = 0;
        let pend = 0
        leave_sy.value.forEach(val => {
            if(data.leave_id != undefined){
                if(data.leave_id == val.leave_id){
                    
                     if(val.status != 3 || val.status != 2)  
                     {
                           ret+= val.number_of_day  
                     }      
                }
            }
           
        });
        return ret
    }

    const setStatus = (val)=>{
        let data = val.status
        return data == 0 ? "PENDING" : data == 1 ? checkApprovalStatus(val) : data == 2 ? "APPROVED" :"DISAPPROVED"
    }

    const checkApprovalStatus = (data)=>{
        return data.emp_class_id == 1 ? "PENDING" : "INITIALLY APPROVED"
    }

    const viewLeave = (data)=>{
        router.push({name:"viewleave", params:{id:data.id}})
    }

</script>


<template>
    <div class="container mt-5">
        <h1 class="text-success text-center mb-5">WELCOME TO PERMISO</h1>
        <div class="row mt-4 d-flex justify-content-center">
            <div class="col-md-5" v-if="user.id != undefined && user.role != 2">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center text-success"> LEAVE CREDITS</h3>
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="">
                                        <th class="text-success">LEAVE TYPE</th>
                                        <th class="text-success">ALLOWED</th>
                                        <th class="text-success">AVAILABLE</th>
                                        <th class="text-success">PENDING</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(list, index) in leaves" :key="index">
                                        <td class="fs-6 fw-bold text-muted">{{ list.description }}</td>
                                        <td class="fw-bold">{{ list.is_no_limit == 1 ? "NO LIMIT" :  list.credits }}</td>
                                        <td class="fw-bold">{{ list.is_no_limit == 1 ? "NO LIMIT" :  available(list) }}</td>
                                        <td class="fw-bold">{{ list.is_no_limit == 1 ? "NO LIMIT" :  extractPending(list) }}</td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">LEAVE APPLICATION HISTORY</h6>
                        <hr>
                        <table class="table table-sm mt-3">
                            <thead>
                                <tr class="text-muted">
                                    <th>NAME</th>
                                    <th>LEAVE TYPE</th>
                                    <th>STATUS</th>
                                    <th>DATE</th>
                                    <th></th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr v-for="(list,index) in history" :key="index">
                                    <td><strong class="text-success">{{ list.name }}</strong></td>
                                    <td>{{ list.description }}</td>
                                    <td>{{  setStatus(list) }}</td>
                                    <td>{{ format(list.date_apply) }}</td>
                                    <td>
                                        <button type="button" @click="viewLeave(list)" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-arrow-right-circle fs-5"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
