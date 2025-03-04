<script setup>
    import { ref,reactive, onMounted, watch } from "vue"
    import { Modal } from "bootstrap";

    const modalposition = ref(null)
    let vmodalposition = null;
    let errors = ref([])

    const btnCap = ref("Save")
    const offices = ref([])
    const listData = ref([])
    const leaves = ref([])
    let links = ref([])
    let searchData = ref('')

    onMounted(()=>{
         getEmployees();
         getOffice()
         employeeLeave()
    })

     const filterData = ()=>{
        getEmployees();
        
    }

    const filter = ref("")
    const getEmployees = ()=>{
        axios.get('api/employees-list?search='+searchData.value+"&filter="+filter.value).then((res)=>{
            listData.value = res.data.data
            links.value = res.data.links
        })
    }

    const employeeLeave = ()=>{
        axios.get("api/employee-leave").then((res)=>{
            leaves.value = res.data;
        })
    }

    const getOffice = () =>{
        axios.get('api/office-list').then((res)=>{
            offices.value = res.data
        })
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
		getEmployees()
	})

    const noData = (data)=>{
        return data == undefined ? true : (data.length > 0) ? true : false;
    }
    
    const extractAvailable = (data, user)=>{
        let ret = 0;
        let pend = 0
        leaves.value.forEach(val => {
            // console.log(val)
            if(data.leave_id != undefined){
                if(val.user_id  == user.id && data.leave_id == val.leave_id){
                     ret += val.number_of_day     
                }
            }
           
        });
        return data.credits - ret
    }

    const extractPending = (data, user)=>{
        let ret = 0;
        let pend = 0
        leaves.value.forEach(val => {
            if(data.leave_id != undefined){
                if(val.user_id  == user.id && data.leave_id == val.leave_id){
                    
                     if(val.status != 3 || val.status != 2)  
                     {
                           ret+= val.number_of_day  
                     }      
                }
            }
           
        });
        return ret
    }

</script>

<template>
    <div class="container">
        <h4 class="mt-4">EMPLOYEE LEAVE CREDITS</h4>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="row card">
            <div class="col-md-12 card-body">
                <div class="d-flex justify-content-between mb-5">
                    <!-- <button type="button" class="btn btn-success ms-2" @click="addposition">
                        <i class="bi bi-plus-circle"></i>
                        Add
                    </button> -->
                    <div class="input-group w-50">
                        <input type="text" class="form-control" placeholder="Search..." aria-label="Example text with button addon" v-model="searchData" aria-describedby="button-addon1">
                        <button class="btn btn-success" type="button" id="button-addon1">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <div class="input-group w-50 ms-5">
                        <select class="form-control" v-model="filter" @change="filterData(filter)">
                            <option value="0">All</option>
                            <option v-for="(list, index) in offices" :key="index" :value="list.id">{{ list.description }}</option>
                        </select>
                        <button class="btn btn-success" type="button" id="button-addon1">
                            <i class="bi bi-funnel"></i>
                        </button>
                    </div>
                </div>
              
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead class="text-uppercase text-muted">
                            <tr>
                                <th>SCHOOL ID</th>
                                <th>NAME</th>
                                <th>CREDITS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list, index) in listData" :key="index">
                                <td class="text-muted">{{ list.school_id }}</td>
                                <td class="text-muted">{{ list.name }}</td>
                                <td class="text-muted">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                              <th>DESCRIPTION</th>
                                              <th>ALLOWED</th>
                                              <th>AVAILABLE</th>
                                              <th>PENDING</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(lst, idx) in list.empleave_credit" :key="idx">
                                                <td>{{ lst.description }}</td>
                                                <td>{{ lst.is_no_limit == 1 ? "NO LIMIT" :  lst.credits }}</td>
                                                <td>{{ lst.is_no_limit == 1 ? "NO LIMIT" :  extractAvailable(lst, list) }}</td>
                                                <td>{{ lst.is_no_limit == 1 ? "NO LIMIT" :  extractPending(lst, list) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </td>
                            </tr>
                             <tr>
                                <td colspan="3" v-show="!noData(listData)">
                                    No Result Found!
                                </td>
                            </tr>
                        </tbody>
                    </table>
                     <nav class="mt-2 d-flex justify-content-start ms-1" aria-label="..." v-if="noData(listData)">
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

                <!-- modal -->
                
                <div class="modal fade" ref="modalposition" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">POSITION</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start mb-3 row">
                          
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" @click="savePosition()">{{ btnCap }}</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
