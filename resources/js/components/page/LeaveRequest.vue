<script setup>
    import { ref, onMounted, watch } from "vue"
    import { useRouter } from "vue-router"

    const initial = ref([])
    const final = ref([])
    const user = ref({})
    const user_ = ref({})

    const router = useRouter()

    onMounted(()=>{
        user_.value = window.winsdev.user
        initialCount()
        finalCount()
        getAuthUser()

        if(user_.value.role === 2){
            router.push({name:"finalrequest"})
        }else{
            router.push({name:"initialrequest"})
        }
    })

    const getAuthUser = ()=>{
        axios.get('/api/user').then((res)=>{
            user.value = res.data
        })
    }

    const initialCount = ()=>{
        axios.get("api/initial-count").then((res)=>{
            initial.value = res.data
        });
    }

     const finalCount = ()=>{
        axios.get("api/final-count").then((res)=>{
            final.value = res.data
        });
    }


</script>

<template>
    <div class="container">
        <h4 class="mt-4">LEAVE REQUEST</h4>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
          <div class="row">
            <div class="col-md-12">
               
                <ul class="nav nav-pills mt-4">
                    <li class="nav-item" v-if="user_.role !== 2">
                        <router-link class="nav-link" :to="{name:'initialrequest'}" aria-current="page">INITIAL APPROVAL
                             &nbsp;<span class="badge bg-danger" v-if="initial.length > 0">{{ initial.length }}</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{name:'finalrequest'}" aria-current="page">FINAL APPROVAL
                            &nbsp;<span class="badge bg-danger" v-if="final.length > 0">{{ final.length }}</span>
                        </router-link>
                    </li>
                    
                </ul>
                <div class="content mt-3">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    .nav-link{
        color:#26884b !important;
    }

    .nav-link.router-link-exact-active {
        font-weight: bold;
        color: #fff !important;
        background-color: #55a04e !important;

    }
</style>

