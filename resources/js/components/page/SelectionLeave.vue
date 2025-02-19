<script setup>
    import { ref, onMounted } from "vue"
    import { useRouter } from "vue-router"

    const listData = ref([])
    const user = ref({})

    const router = useRouter()
    onMounted(()=>{
        user.value = window.winsdev.user
        getData()
    })

    const getData = () =>{
        axios.get('api/leveling').then((res)=>{
            listData.value = res.data
        })
    }

    const gotoLeaveApp = (data) =>{
        router.push({name:'leaveapplication', params:{id:data.id}})
    }

</script>

<template>
    <div class="container">
        <h4 class="mt-4">NDMC LEAVE APPLIACTION FORM</h4>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="row">
            <div class="col-xl-4 col-md-4 mb-3" v-for="(list, index) in listData" :key="index">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h4>{{ list.title }}</h4>
                        <div class="sub-title">
                            {{ list.sub_title }}
                        </div>

                        <button type="button" @click="gotoLeaveApp(list)" class="btn btn-success mt-2" :class="user.emp_class_id == list.id ? '':'disabled'" >
                            APPLY
                        </button>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</template>
