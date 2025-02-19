<script setup>
    import { ref, reactive, onMounted, watch } from "vue"
    import { useRouter, useRoute } from "vue-router"
    import { Modal } from "bootstrap";

    const route = useRoute()

    const modalfinal = ref(null)
    let vmodalfinal = null;
    const employee = ref([])
    const finals = ref([])
    const searchEmp = ref("")
    const searchFinal = ref("")

    const formdata = ()=>({
            user_id:null,
            emp_class_id:null,
            name:""
        })

    const form = reactive(formdata())
    const resetform = ()=> Object.assign(form, formdata())

    onMounted(()=>{
        const id = route.params.id
        vmodalfinal = new Modal(modalfinal.value);
        getEmployee()
        getFinals(id)
    })

    const getEmployee = () => {
         axios.get('/api/employee?search='+searchEmp.value).then((res)=>{
            employee.value = res.data.data
        })
    }

    const getFinals = (id) => {
         axios.get('api/setup-final-list/'+id+'?search='+searchFinal.value).then((res)=>{
            finals.value = res.data
        })
    }

    const addModal = ()=>{
        vmodalfinal.show()
    }

    watch(searchEmp, ()=>{
		getEmployee()
    })
    
    const noData = (data)=>{
        return data == undefined ? true : (data.length > 0) ? true : false;
    }
    
    const removeFinalApproval = (data)=>{
        Swal.fire({
            title: "Do you want to remove "+data.name+" ?",
            text: "Are you sure!",
            icon: "warning",
            showCancelButton: true,
            background: '#17a673',
            color: '#fff',
            confirmButtonColor: "#424242",
            cancelButtonColor: "#ffc107",
            confirmButtonText: "YES!"
            }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/api/setup-final-delete/${data.id}`).then((res)=>{
                    toast.fire({
                        title: "REMOVED!",
                        text: "Data has been removed.",
                        icon: "success",
						confirmButtonColor: "#26884b",
                    });
                    getFinals(route.params.id);
                })
                
            }
        });
    }

    const addFinalApproval = (data)=>{
        form.emp_class_id = route.params.id
        form.user_id = data.id
        form.name = data.name
        axios.post('api/setup-final', form).then((res)=>{
            toast.fire({
                icon:'success',
                title:'Added Successfully!'
            })
            getFinals(route.params.id)
        }).catch((res)=>{
            toast.fire({
                icon:'warning',
                title:' Already Added!',
                background:'#c50000'
            })
        })
    }

</script>

<template>
 <div>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <h4>FINAL APPROVAL</h4>
                <button type="button" @click="addModal" class="btn btn-success">
                    <i class="bi bi-plus-square"></i> ADD
                </button>
            </div>
           
            <div class="table-responsive mt-2">
                <table class="table table-sm table-striped">
                     <thead>
                        <tr>
                            <th>NAME</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="(list, index) in finals" :key="index">
                            <td>{{ list.name }}</td>
                            <td>
                                <button type="button" @click="removeFinalApproval(list)" class="btn btn-sm btn-secondary">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" v-show="!noData(finals)">
                                No Result Found!
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- modal -->
    <div class="modal fade" ref="modalfinal" tabindex="-1" >
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ADD FINAL APPROVAL</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start mb-3 row">
                    <div class="input-group">
                        <input type="text" v-model="searchEmp" class="form-control" placeholder="Search Employee's Name">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>NAME</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(list, index) in employee" :key="index">
                                    <td>{{ list.name }}</td>
                                    <td>
                                        <button type="button" @click="addFinalApproval(list)" class="btn btn-sm btn-success">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" v-show="!noData(employee)">
                                        No Result Found!
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
 </div>

</template>
