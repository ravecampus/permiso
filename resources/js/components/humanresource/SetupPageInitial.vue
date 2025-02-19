<script setup>
    import { ref, reactive, onMounted, watch } from "vue"
    import { useRouter, useRoute } from "vue-router"
    import { Modal } from "bootstrap";

    const route = useRoute()

    const modalinitial = ref(null)
    let vmodalinitial = null;
    const employee = ref([])
    const initials = ref([])
    const searchEmp = ref("")
    const searchInitial = ref("")

    const formdata = ()=>({
            user_id:null,
            emp_class_id:null,
            name:""
        })

    const form = reactive(formdata())
    const resetform = ()=> Object.assign(form, formdata())

    onMounted(()=>{
        const id = route.params.id
        vmodalinitial = new Modal(modalinitial.value);
        getEmployee()
        getInitials(id)
    })

    const getEmployee = () => {
         axios.get('/api/employee?search='+searchEmp.value).then((res)=>{
            employee.value = res.data.data
        })
    }

    const getInitials = (id) => {
         axios.get('api/setup-initial-list/'+id+'?search='+searchInitial.value).then((res)=>{
            initials.value = res.data
        })
    }

    const addModal = ()=>{
        vmodalinitial.show()
    }

    watch(searchEmp, ()=>{
		getEmployee()
    })
    
    const noData = (data)=>{
        return data == undefined ? true : (data.length > 0) ? true : false;
    }
    
    const removeInitialApproval = (data)=>{
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
                axios.delete(`/api/setup-initial-delete/${data.id}`).then((res)=>{
                    toast.fire({
                        title: "REMOVED!",
                        text: "Data has been removed.",
                        icon: "success",
						confirmButtonColor: "#26884b",
                    });
                    getInitials(route.params.id);
                })
                
            }
        });
    }

    const addInitialApproval = (data)=>{
        form.emp_class_id = route.params.id
        form.user_id = data.id
        form.name = data.name
        axios.post('api/setup-initial', form).then((res)=>{
            toast.fire({
                icon:'success',
                title:'Added Successfully!'
            })
            getInitials(route.params.id)
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
                 <h4>INITIAL APPROVAL</h4>
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
                        <tr v-for="(list, index) in initials" :key="index">
                            <td>{{ list.name }}</td>
                            <td>
                                <button type="button" @click="removeInitialApproval(list)" class="btn btn-sm btn-secondary">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" v-show="!noData(initials)">
                                No Result Found!
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- modal -->
    <div class="modal fade" ref="modalinitial" tabindex="-1" >
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ADD INITIAL APPROVAL</h5>
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
                                        <button type="button" @click="addInitialApproval(list)" class="btn btn-sm btn-success">
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
