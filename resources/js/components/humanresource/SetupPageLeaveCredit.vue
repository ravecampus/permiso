<script setup>
    import { ref, reactive, onMounted, watch } from "vue"
    import { useRouter, useRoute } from "vue-router"
    import { Modal } from "bootstrap";

    const route = useRoute()

    const modalcredit = ref(null)
    let vmodalcredit = null;
    const leaves = ref([])
    const credits = ref([])
    const errors = ref([])
    const btnCap = ref("Save")
    const disabletxtLeave = ref(false)

    const formdata = ()=>({
            id:null,
            leave:null,
            emp_class_id:null,
            credits:null,
            description:"",
            is_no_limit:0,
        })

    const form = reactive(formdata())
    const resetform = ()=> Object.assign(form, formdata())

    onMounted(()=>{
        const id = route.params.id
        vmodalcredit = new Modal(modalcredit.value);
        getLeave()
        getCredits(id)
        modalcredit.value.addEventListener("hidden.bs.modal", clearModal)
    })

    const clearModal = ()=>{
        btnCap.value = "Save"
        disabletxtLeave.value = false
        resetform()
        visitext.value = true
        
    }

    const getLeave = () => {
         axios.get('/api/leave').then((res)=>{
            leaves.value = res.data
        })
    }

    const getCredits = (id) => {
         axios.get('api/setup-credit-list/'+id).then((res)=>{
            credits.value = res.data
        })
    }

    const addModal = ()=>{
        vmodalcredit.show()
    }
    
    const noData = (data)=>{
        return data == undefined ? true : (data.length > 0) ? true : false;
    }

    const visitext = ref(true)
    const disableCredit = ()=>{
        if(form.is_no_limit === true){
            visitext.value = false;
        }else{
            visitext.value = true;
        }
    }
    
    const editCredit = (data)=>{
        form.id = data.id
        form.leave = data.leave_id
        form.credits = data.credits
        form.is_no_limit = data.is_no_limit == 1 ? true : false 
        disableCredit();
        vmodalcredit.show();
        disabletxtLeave.value = true;

        btnCap.value = "Save changes"
    }

    // const removeCredit = (data)=>{
    //     Swal.fire({
    //         title: "Do you want to remove "+data.name+" ?",
    //         text: "Are you sure!",
    //         icon: "warning",
    //         showCancelButton: true,
    //         background: '#17a673',
    //         color: '#fff',
    //         confirmButtonColor: "#424242",
    //         cancelButtonColor: "#ffc107",
    //         confirmButtonText: "YES!"
    //         }).then((result) => {
    //         if (result.isConfirmed) {
    //             axios.delete(`/api/setup-final-delete/${data.id}`).then((res)=>{
    //                 toast.fire({
    //                     title: "REMOVED!",
    //                     text: "Data has been removed.",
    //                     icon: "success",
	// 					confirmButtonColor: "#26884b",
    //                 });
    //                 getCredits(route.params.id);
    //             })
                
    //         }
    //     });
    // }

    const saveCredit = ()=>{
        form.emp_class_id = route.params.id
        errors.value = []

        if(form.id == undefined){
            axios.post('api/setup-credit', form).then((res)=>{
                toast.fire({
                    icon:'success',
                    title:'Added Successfully!'
                })
                vmodalcredit.hide();
                getCredits(route.params.id)
            }).catch((err)=>{
               

                if(err.response.status == 400){
                    toast.fire({
                        icon:'warning',
                        title:' Already Added!',
                        background:'#c50000'
                    })
                }else{
                    errors.value = err.response.data.errors
                }
            })
        }else{
            axios.put('api/setup-credit-edit/'+form.id, form).then((res)=>{
                toast.fire({
                    icon:'success',
                    title:'Leave Credit has been modified!'
                })
                vmodalcredit.hide();
                getCredits(route.params.id)
            }).catch((err)=>{
                errors.value = err.response.data.errors
            
            })
        }
       
    }

    const nolimitDescription = (data)=>{
        return data == 1 ? "YES" : "NO"
    }

</script>

<template>
 <div>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <h4>LEAVE CREDITS</h4>
                <button type="button" @click="addModal" class="btn btn-success">
                    <i class="bi bi-plus-square"></i> ADD
                </button>
            </div>
            <div class="table-responsive mt-2">
                <table class="table table-sm table-striped">
                     <thead>
                        <tr>
                            <th>DESCRIPTION</th>
                            <th>CREDITS</th>
                            <th>NO LIMIT</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="(list, index) in credits" :key="index">
                            <td>{{ list.description }}</td>
                            <td>{{ list.credits }}</td>
                            <td>{{ nolimitDescription(list.is_no_limit) }}</td>
                            <td>
                                <button type="button" @click="editCredit(list)" class="btn btn-sm btn-secondary">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" v-show="!noData(credits)">
                                No Result Found!
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- modal -->
    <div class="modal fade" ref="modalcredit" tabindex="-1" >
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ADD LEAVE CREDIT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start mb-3 row">
                    <div class="form-group mb-2">
                        <label>LEAVE DESCRIPTION</label>
                        <select class="form-select" v-model="form.leave" :disabled="disabletxtLeave">
                            <option v-for="(lst, idx) in leaves" :key="idx" :value="lst.id">{{ lst.description }}</option>
                        </select>
                        <span class="text-danger" v-if="errors.leave">{{errors.leave[0]}}</span>
                    </div>
                     <div class="form-group mb-2">
                        
                        <div class="form-check">
                            <input class="form-check-input" @change="disableCredit()" type="checkbox" v-model="form.is_no_limit" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                CREDITS NO LIMIT
                            </label>
                        </div>
                    </div>
                     <div class="form-group mb-3" v-if="visitext">
                        <label>LEAVE CREDITS</label>
                        <input type="number" class="form-control" v-model="form.credits" placeholder="Enter credits">
                        <span class="text-danger" v-if="errors.credits">{{errors.credits[0]}}</span>
                    </div>
                  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" @click="saveCredit()">{{ btnCap }}</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
 </div>

</template>
