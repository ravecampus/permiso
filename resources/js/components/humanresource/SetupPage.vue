<script setup>
    import { ref, onMounted } from "vue"
    import { useRouter, useRoute } from "vue-router"


    const leveling = ref({})
    const route = useRoute()
    const router = useRouter()

    onMounted(()=>{
        var id = route.params.id;
        getDatawithID(id)

   
        // router.push({name:'initialaprv'})


    })
    const getDatawithID = (id) =>{
        axios.get('api/leveling/'+ id).then((res)=>{
            leveling.value = res.data
        })
    }
</script>

<template>
    <div class="container">
        <h4 class="mt-4">SETUP PAGE -> {{ leveling.title }}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="row">
            <div class="col-md-12">
               
                <ul class="nav nav-pills mt-4">
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{name:'initialaprv'}" aria-current="page">INITIAL APPROVAL</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{name:'finalaprv'}" aria-current="page">FINAL APPROVAL</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{name:'setupleavecredit'}" aria-current="page">LEAVE CREDITS</router-link>                        
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
        background-color: #17a673 !important;

    }
</style>
