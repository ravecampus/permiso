<script setup>
    import { ref,reactive, onMounted, watch } from "vue"
    import { Modal } from "bootstrap";

    const modalposition = ref(null)
    let vmodalposition = null;
    let errors = ref([])

    const btnCap = ref("Save")
    const listData = ref([])
    let links = ref([])
    let searchData = ref('')

    onMounted(()=>{
        vmodalposition = new Modal(modalposition.value);
        getData()

         modalposition.value.addEventListener("hidden.bs.modal", clearModal)
    })

    const clearModal = ()=>{
        resetform()
        errors.value = []
        btnCap.value = "Save"
    }

    const formdata = ()=>({
        position_name:"",
        id:null,
    })

    const form = reactive(formdata())
    const resetform = ()=> Object.assign(form, formdata())
    const addposition = ()=>{
        vmodalposition.show();
    }

    const savePosition = ()=>{

        if(form.id == undefined){
            axios.post('api/position', form).then((res)=>{
                toast.fire({
                    icon:'success',
                    title:'Description Added Successfully!'
                })
                vmodalposition.hide();
                resetform()
                getData()
            }).catch((err)=>{
                errors.value = err.response.data.errors
            })
        }else{
            axios.put('api/position/'+form.id, form).then((res)=>{
                toast.fire({
                    icon:'success',
                    title:'Description Modified Successfully!'
                })
                vmodalposition.hide();
                resetform()
                getData();
            }).catch((err)=>{
                errors.value = err.response.data.errors
            })
        }
        
    }

    const getData = ()=>{
        axios.get('/api/position?search='+searchData.value).then((res)=>{
            listData.value = res.data.data
            links.value = res.data.links
        })
    }

    const editData = (data)=>{
        form.position_name = data.description
        form.id = data.id
        vmodalposition.show();
        btnCap.value = "Save Changes"
    }

    const deleteData = (data)=>{
        Swal.fire({
            title: "Do you want to delete "+data.description+" ?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            background: '#17a673',
            color: '#fff',
            confirmButtonColor: "#424242",
            cancelButtonColor: "#ffc107",
            confirmButtonText: "YES!"
            }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/api/position/${data.id}`).then((res)=>{
                    toast.fire({
                        title: "Deleted!",
                        text: "Description has been deleted.",
                        icon: "success",
						confirmButtonColor: "#26884b",
                    });
                    getData();
                })
                
            }
        });
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

    const noData = (data)=>{
        return data == undefined ? true : (data.length > 0) ? true : false;
    }


</script>

<template>
    <div class="container">
        <h4 class="mt-4">POSITION SETUP</h4>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="row card">
            <div class="col-md-12 card-body">
                <div class="d-flex justify-content-between mb-5">
                    <button type="button" class="btn btn-success ms-2" @click="addposition">
                        <i class="bi bi-plus-circle"></i>
                        Add
                    </button>
                    <div class="input-group w-50">
                        <input type="text" class="form-control" placeholder="Search..." aria-label="Example text with button addon" v-model="searchData" aria-describedby="button-addon1">
                        <button class="btn btn-success" type="button" id="button-addon1">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
              
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead class="text-uppercase text-muted">
                            <tr>
                                <th>POSITION NAME</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list, index) in listData" :key="index">
                                <td class="text-muted">{{ list.description }}</td>
                                <td class="text-muted">
                                    <div class="btn-group">
                                        <button type="button" @click="editData(list)" class="btn btn-success btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm" @click="deleteData(list)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                             <tr>
                                <td colspan="2" v-show="!noData(listData)">
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
                           <div class="form-group">
                               <label>POSITION NAME</label>
                               <input type="text" class="form-control" v-model="form.position_name">
                               <span class="text-danger" v-if="errors.position_name">{{errors.position_name[0]}}</span>
                           </div>
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
