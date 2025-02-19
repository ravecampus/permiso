<script setup>
    import { ref, onMounted } from "vue"

    const listData = ref([])
    onMounted(()=>{
        getData()
    })

    const getData = () =>{
        axios.get('api/leveling').then((res)=>{
            listData.value = res.data
        })
    }

</script>

<template>
    <div class="container">
        <h4 class="mt-4">EMPLOYMENT CLASSIFICATION</h4>
        <h5>SELECT TO SETUP</h5>
        <p>Approval and Leave Credits Setup</p>
        <div class="row">
            <div class="col-xl-4 col-md-4 mb-3" v-for="(list, index) in listData" :key="index">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5>{{ list.title }}</h5>
                        <div class="sub-title">
                            {{ list.sub_title }}
                        </div>

                        <router-link :to="{name:'initialaprv', params:{id:list.id}}" class="btn btn-success mt-2">
                            SETUP
                        </router-link>
                    </div>
                </div>
            </div>
          
        </div>
    </div>
</template>
