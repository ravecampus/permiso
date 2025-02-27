<script setup>

    import { ref , onMounted } from "vue"

    const leave_sy = ref([])
    const leaves = ref([])

    onMounted(()=>{
        getMyLeaveSY()
        getLeave()
    })

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

    const available = (data)=>{
        let ret = 0
        leave_sy.value.forEach(val => {
            if(val.leave_id == data.leave_id){
                ret += val.number_of_day
            }
        });

        return data.credits - ret;
    }

</script>


<template>
    <div class="container mt-5">
        <h1 class="text-success text-center">WELCOME TO PERMISO</h1>
        <div class="row mt-4">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center"> LEAVE CREDITS</h3>
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="">
                                        <th class="text-success">LEAVE TYPE</th>
                                        <th class="text-success">ALLOWED</th>
                                        <th class="text-success">AVAILABLE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(list, index) in leaves" :key="index">
                                        <td class="fs-6 fw-bold text-muted">{{ list.description }}</td>
                                        <td>{{ list.is_no_limit == 1 ? "NO LIMIT" :  list.credits }}</td>
                                        <td>{{ list.is_no_limit == 1 ? "NO LIMIT" :  available(list) }}</td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7"></div>
        </div>
    </div>
</template>
