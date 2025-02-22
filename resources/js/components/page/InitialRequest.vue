<script setup>
   import { ref, onMounted, watch } from "vue"
   import { useRouter } from "vue-router"

    const initialrequests = ref([]);
    const searchData = ref("")
    let links = ref([])

    const router = useRouter()

    onMounted(()=>{
        getInitialRequest();
    })

    const getInitialRequest = ()=>{
        axios.get("api/initial-request?search="+searchData.value).then((res)=>{
            initialrequests.value = res.data.data
            links.value = res.data.links
        })
    }
    const format = (d_) => {
        let d = new Date(d_)
        const day =("0" + d.getDate()).slice(-2);
        const month = ("0"+(d.getMonth()+1)).slice(-2);
        const year =  d.getFullYear();

        return  month+ "/"+day+"/" + year;
    }

    const changePage = (link)=>{
        if(!link.url || link.active){
            return
        }
        axios.get(link.url).then((res)=>{
            listData.value = res.data.data
            links.value = res.data.links
        })
    }
    watch(searchData, ()=>{
		getInitialRequest()
    })
    
    const setExten = (data)=>{
        return data == 1 ? "AM" : "PM"
    }

    const noData = (data)=>{
        return data == undefined ? true : (data.length > 0) ? true : false;
    }

    const viewData = (data)=>{
        router.push({name:'viewleave', params:{id:data.id}})
    }

    const setStatus = (data)=>{
        return data == 0 ? "NEED TO REVIEW" : data == 1 ? "APPROVED INITIALLY" : data == 2 ? "APPROVED" :"DISAPPROVED"
    }



</script>

<template>
    <div class="card">
        <div class="card-body">
            <h4 class="text-muted mb-3">INITIAL APPROVAL REQUEST</h4>
            <div class="table-responsive">
                <div class="input-group w-50 mb-3">
                    <input type="text" class="form-control" placeholder="Search..." aria-label="Example text with button addon" v-model="searchData" aria-describedby="button-addon1">
                    <button class="btn btn-success" type="button" id="button-addon1">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <table class="table table-sm table-striped">
                    <thead class="text-uppercase text-muted">
                        <tr>
                            <th>NAME OF APPLICANT</th>
                            <th>APPLIED FOR</th>
                            <th>DATE APPLIED</th>
                            <!-- <th>INCLUSIVE DATE</th> -->
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list, index) in initialrequests" :key="index">
                            <td class="text-muted">{{ list.name }}</td>
                            <td>{{ list.description }}</td>
                            <td>{{ format(list.date_apply) }}</td>
                            <td>
                                <small class="text-success fw-bold">
                                {{ setStatus(list.status) }}
                                </small>
                            </td>
                            <td class="text-muted">
                                <div class="btn-group">
                                    <button type="button" @click="viewData(list)" class="btn btn-success btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <!-- <button type="button" class="btn btn-secondary btn-sm" @click="deleteData(list)">
                                        <i class="bi bi-trash"></i>
                                    </button> -->
                                </div>
                            </td>
                        </tr>
                            <tr>
                            <td colspan="5" v-show="!noData(initialrequests)">
                                No Result Found!
                            </td>
                        </tr>
                    </tbody>
                </table>
                    <nav class="mt-2 d-flex justify-content-start ms-1" aria-label="..." v-if="noData(initialrequests)">
                    <ul class="pagination pagination-sm custom-page bg-success">
                        <li class="page-item" aria-current="page" v-for="(link, index) in links" :key="index">
                            <a class="page-link"
                            href="#"
                        
                            v-html="link.label"
                            :class="{active:link.active, disabled: !link.url }"

                            @click="changePage(link)"
                            ></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>
