<script setup>
    import { ref,reactive, onMounted, watch } from "vue"
    import { Modal } from "bootstrap";

    const modal = ref(null)
    let vmodal = null;
    let errors = ref([])

    const btnCap = ref("Save")
    const listData = ref([])
    let links = ref([])
    let searchData = ref('')

    onMounted(()=>{
        vmodal = new Modal(modal.value);
        getData()

         modal.value.addEventListener("hidden.bs.modal", clearModal)
    })

    const clearModal = ()=>{
        resetform()
        errors.value = []
        btnCap.value = "Save"
    }

    const formdata = ()=>({
        start:new Date().getFullYear(),
        end:null,
        status:{},
        is_active:null
    })

    const form = reactive(formdata())
    const resetform = ()=> Object.assign(form, formdata())
    const addschoolyear = ()=>{
        vmodal.show();
    }

    const saveData = ()=>{
        if(form.id == undefined){
            axios.post('api/setup-school-year', form).then((res)=>{
                toast.fire({
                    icon:'success',
                    title:'School Year Added Successfully!'
                })
                vmodal.hide();
                resetform()
                getData()
            }).catch((err)=>{
              
                if(err.response.status == 409){
                    toast.fire({
                        icon:'warning',
                        title:'Duplicate Entry!',
                        background:'#c50000'
                    })
                }else{
                    errors.value = err.response.data.errors
                }
            })
        }else{
            axios.put('api/setup-school-year/'+form.id, form).then((res)=>{
                toast.fire({
                    icon:'success',
                    title:'School Year Modified Successfully!'
                })
                vmodal.hide();
                resetform()
                getData();
            }).catch((err)=>{
                 if(err.response.status == 409){
                    toast.fire({
                        icon:'warning',
                        title:'Duplicate Entry!',
                        background:'#c50000'
                    })
                }else{
                    errors.value = err.response.data.errors
                }
            })
        }
        
    }

    const getData = ()=>{

        axios.get('/api/school-year-list?search='+searchData.value).then((res)=>{
            listData.value = res.data
            listData.value.forEach(val => {
                if(val.is_active == 1){
                    form.status[val.id] = true
                }else{
                    form.status[val.id] = false
                }
            });
        })
    }

    const editData = (data)=>{
        form.start = data.start
        form.id = data.id
        vmodal.show();
        btnCap.value = "Save Changes"
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
		getData()
    })
    
    const endSetup = (data)=>{
        form.end = ""
        if(data != null){
            form.end = Number(data) + 1;
        }
        
    }

    const noData = (data)=>{
        return data == undefined ? true : (data.length > 0) ? true : false;
    }
    const activeStatus = (data)=>{
        return data == 1 ? "ACTIVE" : "INACTIVE ";
    }

    const echangeStatus = (data)=>{
        Swal.fire({
            title: "Do you want to change status?",
            // text: "You won't be able to revert this!",
            icon: "question",
            showCancelButton: true,
            background: '#17a673',
            color: '#fff',
            confirmButtonColor: "#424242",
            cancelButtonColor: "#ffc107",
            confirmButtonText: "YES!"
            }).then((result) => {
            if (result.isConfirmed) {
                axios.get(`/api/school-year-status/${data.id}`).then((res)=>{
                    toast.fire({
                        title: "STATUS!",
                        text: "Status has been changed.",
                        icon: "success",
						confirmButtonColor: "#26884b",
                    });
                     getData();
                })
                
            }else{
                 getData();
            }
            
        });
       
        
    }
  
</script>

<template>
    <div class="container">
        <h4 class="mt-4">SCHOOL YEAR SETUP</h4>
         <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="row card mt-3">
            <div class="col-md-12 card-body">
                <div class="d-flex justify-content-start mb-4">
                 
                    <button type="button" class="btn btn-success ms-2" @click="addschoolyear">
                        <i class="bi bi-plus-circle"></i>
                        Add
                    </button>
                </div>
                <!-- <div class="input-group input-group-sm p-2">
                    <input type="text" class="form-control" placeholder="Search..." aria-label="Example text with button addon" v-model="searchData" aria-describedby="button-addon1">
                    <button class="btn btn-success" type="button" id="button-addon1">
                        <i class="bi bi-search"></i>
                    </button>
                </div> -->
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead class="text-uppercase text-muted">
                            <tr>
                                <th>SCHOOL YEAR</th>
                                <th>STATUS</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list, index) in listData" :key="index">
                                <td class="text-muted fw-bold">{{ list.start +" - "+list.end }}</td>
                                <td class="text-muted">
                                    
                                    <!-- {{ list.is_active }} -->
                                    <div class="form-check form-switch">
                                        <input class="form-check-input form-success" v-model="form.status[list.id]"  @change="echangeStatus(list)" type="checkbox" role="switch">
                                        <label class="form-check-label">
                                          {{ activeStatus(list.is_active) }}
                                        </label>
                                    </div>
                                                                            
                                </td>
                                <td class="text-muted">
                                    <div class="btn-group">
                                        <button type="button" @click="editData(list)" class="btn btn-success btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </div>
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
                
                <div class="modal fade" ref="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">SET UP SCHOOL YEAR</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start mb-3 row">
                         
                           <div class="form-group col-md-6">
                               <label>START</label>
                                <VueDatePicker v-model="form.start" :change="endSetup(form.start)" year-picker></VueDatePicker>
                               <span class="text-danger" v-if="errors.start">{{errors.start[0]}}</span>
                           </div>
                            <div class="form-group col-md-6">
                               <label>END</label>
                                <input type="text" disabled class="form-control" v-model="form.end" year-picker>
                               <span class="text-danger" v-if="errors.end">{{errors.end[0]}}</span>
                           </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" @click="saveData()">{{ btnCap }}</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
