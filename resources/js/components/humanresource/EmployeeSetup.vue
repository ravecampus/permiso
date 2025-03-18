
<script setup>
    import { reactive, ref, onMounted, watch } from "vue"
    import { Modal } from "bootstrap";

    const errors = ref([])
    const errors_ = ref([])
    const errors3 = ref([])
    const errors4 = ref([])
    const empclass = ref([])
    const positions = ref([])
    const offices = ref([])
    const employees = ref([])
    const btncap = ref("Save")
    const searchData = ref("")
    let links = ref([])

    const modalposition = ref(null)
    const modaloffice = ref(null)
    const modal = ref(null)
    let vmodal = null;
    let vmodalp = null;
    let vmodalo = null;
    
    const format = (d) => {
        const day =("0" + d.getDate()).slice(-2);
        const month = ("0"+(d.getMonth()+1)).slice(-2);
        const year =  d.getFullYear();

        return  month+ "/"+day+"/" + year;
    }
    
    onMounted(() => {
        vmodal = new Modal(modal.value);
        vmodalp = new Modal(modalposition.value);
        vmodalo = new Modal(modaloffice.value);
        getData()
        getOffice()
        getPosition()
        getEmployees()
        modal.value.addEventListener("hidden.bs.modal", clearModal)
    })
    const clearModal = ()=>{
        resetform2()
        errors_.value = []
    }

    const getData = () =>{
        axios.get('api/leveling').then((res)=>{
            empclass.value = res.data
        })
    }

    const getPosition = () =>{
        axios.get('api/position-list').then((res)=>{
            positions.value = res.data
        })
    }

    const getOffice = () =>{
        axios.get('api/office-list').then((res)=>{
            offices.value = res.data
        })
    }

    const formdata = ()=>({
        name:"",
        school_id:"",
        password:"",
        office: "",
        employee_classification:"",
        date_of_employment:"",
        role:"",
        position:"",
        password_confirmation:"",
        default_password: false,
        id:undefined
    })
    const fdata=()=>({
        password:"",
        password_confirmation:"",
    })
    const fdatap=()=>({
        id:null,
        position_name:"",
    })
    const fdatao=()=>({
        id:null,
        description:"",
    })
    const form = reactive(formdata())
    const form2 = reactive(fdata())
    const form3 = reactive(fdatap())
    const form4 = reactive(fdatao())
    const resetform4 = () => Object.assign(form4, fdatao())
    const resetform3 = () => Object.assign(form3, fdatap())
    const resetform2 = () => Object.assign(form2, fdata())
    const resetform = () => Object.assign(form, formdata())

    const saveUser = ()=>{
        if(form.id === undefined){
            btncap.value = "Saving..."
            axios.post('api/employees', form).then((res)=>{
                btncap.value = "Save"
                resetform()
                toast.fire({
                    icon:'success',
                    title:'Employee Added Successfully!'
                })
                errors.value = []
                getEmployees();
            }).catch((err)=>{
                btncap.value = "Save"
                errors.value = err.response.data.errors
            })
        }else{
            btncap.value = "Saving..."
            axios.put('api/employees-edit/'+form.id, form).then((res)=>{
                btncap.value = "Save"
                resetform()
                toast.fire({
                    icon:'success',
                    title:'Employee Added Successfully!'
                })
                errors.value = []
                distxt.value = false
                dispass.value = true
                defview.value = true
                getEmployees();
            }).catch((err)=>{
                btncap.value = "Save Changes"
                errors.value = err.response.data.errors
            })
        }
       
    }

    const filterData = ()=>{
        getEmployees();
    }

    const filter = ref("")
    const getEmployees = ()=>{
        axios.get('api/employees-list?search='+searchData.value+"&filter="+filter.value).then((res)=>{
            employees.value = res.data.data
            links.value = res.data.links
        })
    }

    
    const noData = (data)=>{
        return data == undefined ? true : (data.length > 0) ? true : false;
    }

    const dispass = ref(true);
    const disablePassword = ()=>{
        if(form.default_password === true){
            dispass.value = false
        }else{
            dispass.value = true
        }
    }

    const extractPosition = (id)=>{
        let ret = ""
        positions.value.forEach(val => {
            if(id === val.id){
                ret = val.description
            }
        });
        return ret;
    }

    watch(searchData, ()=>{
		getEmployees()
	})
    const defview = ref(true)
    const distxt = ref(false)
    const editEmployee = (data)=>{
        distxt.value = true
        errors.value = []
        btncap.value ="Save Changes"
        dispass.value = false
        defview.value = false
        form.school_id = data.school_id
        form.name = data.name
        form.id = data.id
        form.position = data.position_id
        form.office = data.office_id
        form.employee_classification = data.emp_class_id
        form.date_of_employment = data.date_of_employment
        form.role = data.role
    }

    const cancelData = ()=>{
        resetform()
        distxt.value = false
        dispass.value = true
        defview.value = true
        btncap.value ="Save"
        errors.value = []
    }

    const changePage = (link)=>{
        if(!link.url || link.active){
            return
        }
        axios.get(link.url).then((res)=>{
            employees.value = res.data.data
            links.value = res.data.links
        })
    }

    const changePassword = (data)=>{
        form2.id = data.id
        vmodal.show()
    }

    const savePassword = ()=>{
        errors_.value = []
        axios.put("api/employees-change-password/"+form2.id, form2).then((res)=>{
            resetform2()
            toast.fire({
                icon:'success',
                title:'Password has been changed!'
            })
            errors_.value = []
            getEmployees()
            vmodal.hide()
        }).catch((err)=>{
            errors_.value = err.response.data.errors
        })
    }

    const addposition = ()=>{
        vmodalp.show()
    }

    const addoffice = ()=>{
        vmodalo.show()
    }

    const savePosition = ()=>{

    if(form3.id == undefined){
            axios.post('api/position', form3).then((res)=>{
                toast.fire({
                    icon:'success',
                    title:'Description Added Successfully!'
                })
                // vmodalp.hide();
                resetform3()
                getPosition()
            }).catch((err)=>{
                errors3.value = err.response.data.errors
            })
        }else{
            axios.put('api/position/'+form3.id, form3).then((res)=>{
                toast.fire({
                    icon:'success',
                    title:'Description Modified Successfully!'
                })
                // vmodalp.hide();
                resetform3()
                getPosition();
            }).catch((err)=>{
                errors3.value = err.response.data.errors
            })
        }
        
    }

    const editPosition = (data)=>{
        form3.id = data.id
        form3.position_name = data.description
    }

    const selectPosition = (data)=>{
        form.position = data.id
        vmodalp.hide()
    }

    const saveOffice = ()=>{

        if(form4.id == undefined){
            axios.post('api/office', form4).then((res)=>{
                toast.fire({
                    icon:'success',
                    title:'Description Added Successfully!'
                })
                // vmodalo.hide();
                resetform4()
                getOffice()
            }).catch((err)=>{
                errors4.value = err.response.data.errors
            })
        }else{
            axios.put('api/office/'+form4.id, form4).then((res)=>{
                toast.fire({
                    icon:'success',
                    title:'Description Modified Successfully!'
                })
                // vmodalo.hide();
                resetform4()
                getOffice();
            }).catch((err)=>{
                errors.value = err.response.data.errors
            })
        }
        
    }

    const editOffice = (data)=>{
        form4.id = data.id
        form4.description = data.description
    }

    const selectOffice = (data)=>{
        form.office = data.id
        vmodalo.hide()
    }

    const extractRole = (data)=>{
        return data == 1 ? "ADMIN" : data == 2 ? "SCHOOL PRESIDENT": data == 3 ? "OFFICE HEAD" : data == 4 ? "FACULTY / STAFF" :" NONE"
    }


</script>
<template>
    <div class="container-fluid">
        <h4 class="mt-4">EMPLOYEE SETUP</h4>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <label>NAME</label>
                            <input type="text" v-model="form.name" class="form-control" placeholder="Enter Name">
                            <span class="text-danger" v-if="errors.name">{{errors.name[0]}}</span>
                        </div>
                        <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label>POSITION</label>
                            <div class="input-group">
                                <select class="form-select" v-model="form.position">
                                    <option v-for="(list, index) in positions" :key="index" :value="list.id">{{ list.description }}</option>
                                </select>
                             
                                <button class="btn btn-outline-secondary" @click="addposition" type="button">
                                    <i class="bi bi-plus"></i>
                                </button>
                           
                            </div>
                            <span class="text-danger" v-if="errors.position">{{errors.position[0]}}</span>
                        </div>
                        
                        <div class="form-group col-md-6 mb-3">
                            <label>OFFICE</label>
                            <div class="input-group">
                                <select class="form-select" v-model="form.office">
                                    <option v-for="(list, index) in offices" :key="index" :value="list.id">{{ list.description }}</option>
                                </select>
                           
                                <button class="btn btn-outline-secondary" @click="addoffice" type="button">
                                    <i class="bi bi-plus"></i>
                                </button>
                               
                            </div>
                            <span class="text-danger" v-if="errors.office">{{errors.office[0]}}</span>
                        </div>
                        <div class="form-group mb-3 col-md-6">
                            <label>EMPLOYMENT CLASSIFICATION</label>
                            <select class="form-select" v-model="form.employee_classification">
                                <option v-for="(lst, idx) in empclass" :key="idx" :value="lst.id">{{ lst.title }}</option>
                            </select>
                            <span class="text-danger" v-if="errors.employee_classification">{{errors.employee_classification[0]}}</span>
                        </div>
                         <div class="form-group mb-3 col-md-6">
                            <label>DATE OF EMPLOYMENT</label>
                            <VueDatePicker v-model="form.date_of_employment" :format="format"></VueDatePicker>
                            <span class="text-danger" v-if="errors.date_of_employment">{{errors.date_of_employment[0]}}</span>                            
                        </div>
                        
                        <div class="form-group mb-2 col-md-6">
                            <label>SCHOOL ID</label>
                            <input type="text" v-model="form.school_id" class="form-control" placeholder="Enter School ID">
                            <span class="text-danger" v-if="errors.school_id">{{errors.school_id[0]}}</span>
                        </div>

                        <div class="form-group mb-3 col-md-6">
                            <label>ROLE</label>
                            <select class="form-select" v-model="form.role">
                            
                                <option value="1">Admin</option>
                                <option value="2">School President</option>
                                <option value="3">Office Head</option>
                                <option value="4">Faculty/Staff</option>
                            </select>
                            <span class="text-danger" v-if="errors.role">{{errors.role[0]}}</span>
                        </div>
                       
                        <div class="form-group col-md-12 mb-4" v-if="defview">
                             <hr>
                            <div class="form-check">
                                <input class="form-check-input" @change="disablePassword()" type="checkbox" v-model="form.default_password">
                                <label class="form-check-label" for="flexCheckDefault">
                                DEFAULT PASSWORD
                                </label>
                            </div>
                        </div>
                         <div class="form-group mb-2 col-md-6" v-if="dispass">
                            <label>PASSWORD</label>
                            <input type="password" v-model="form.password" class="form-control" placeholder="Enter Password">
                            <span class="text-danger" v-if="errors.password">{{errors.password[0]}}</span>
                        </div>
                         <div class="form-group mb-2 col-md-6" v-if="dispass">
                            <label>PASSWORD CONFIRMATION</label>
                            <input type="password" v-model="form.password_confirmation" class="form-control" placeholder="Enter Password Confirmation">
                            <span class="text-danger" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</span>                       
                        </div>
                        </div>
                        <div class="btn-group">
                            <button type="button" @click="saveUser" class="btn btn-success mt-3">{{ btncap }}</button>
                            <button type="button" @click="cancelData" class="btn btn-secondary mt-3">Cancel</button>
                        </div>
                       
                    </div>
                </div>
               
            </div>
            <div class="col-md-6">
               <div class="card">
                   <div class="card-header d-flex justify-content-between">
                        <div class="input-group p-2">
                            <input type="text" class="form-control" placeholder="Search for School ID or Name..." v-model="searchData">
                            <button class="btn btn-success" type="button" id="button-addon1">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        <div class="input-group p-2">
                           <select class="form-control" v-model="filter" @change="filterData(filter)">
                                <option value="0">All</option>
                                <option v-for="(list, index) in offices" :key="index" :value="list.id">{{ list.description }}</option>
                           </select>
                            <button class="btn btn-success" type="button" id="button-addon1">
                                <i class="bi bi-funnel"></i>
                            </button>
                        </div>
                        <div class="p-2">
                            <router-link :to="{name:'printemployee'}" class="btn btn-success" title="print">
                                <i class="bi bi-printer"></i>
                           </router-link>
                        </div>
                        
                   </div>
                   <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>SCHOOL ID</th>
                                        <th>NAME</th>
                                        <th>POSITION</th>
                                        <th>ROLE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(list, index) in employees" :key="index">
                                        <td><span class="fw-bold text-success">{{ list.school_id }}</span></td>
                                        <td>{{ list.name }}</td>
                                        <td>{{ extractPosition(list.position_id) }}</td>
                                        <td>{{ extractRole(list.role) }}</td>
                                        <td>
                                          <div class="btn-group">
                                                <button type="button" @click="editEmployee(list)" class="btn btn-sm btn-success" title="Edit Employee">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button type="button" @click="changePassword(list)" class="btn btn-sm btn-secondary" title="Change password">
                                                    <i class="bi bi-asterisk"></i>
                                                </button>
                                          </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" v-show="!noData(employees)">
                                            No Result Found!
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                            <nav class="mt-2 d-flex justify-content-start ms-1" aria-label="..." v-if="noData(employees)">
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
            </div>
        </div>

         <div class="modal fade" ref="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">CHANGE PASSWORD</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start mb-3">
                    
                    <div class="form-group mb-3">
                        <label>PASSWORD</label>
                        <input type="password" class="form-control" v-model="form2.password" placeholder="Enter Password">
                        <span class="text-danger" v-if="errors_.password">{{errors_.password[0]}}</span>
                    </div>
                    <div class="form-group">
                        <label>PASSWORD CONFIRMATION</label>
                        <input type="password" class="form-control" v-model="form2.password_confirmation" placeholder="Enter Password Confirmation">
                        <span class="text-danger" v-if="errors_.password_confirmation">{{errors_.password_confirmation[0]}}</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" @click="savePassword()">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" ref="modalposition" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ADD POSITION</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start mb-3">
                    
                    <div class="form-group mb-3">
                        <label>POSITION NAME</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="form3.position_name" placeholder="Enter Position Name">
                            <button class="btn btn-secondary" v-if="form3.id != undefined " @click="resetform3" type="button">
                                <i class="bi bi-x-square"></i>
                            </button>
                            <button class="btn btn-success" @click="savePosition" type="button" id="button-addon1">
                                <i class="bi" :class="form3.id == undefined ? 'bi-floppy-fill' :'bi-pencil-square'"></i>
                            </button>
                        </div>
                          <span class="text-danger" v-if="errors3.position_name">{{errors3.position_name[0]}}</span>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead class="text-uppercase text-muted">
                                    <tr>
                                        <th>Position Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(list, index) in positions" :key="index">
                                        <td class="text-muted">{{ list.description }}</td>
                                        <td class="text-muted">
                                            <div class="btn-group">
                                                <button type="button" @click="editPosition(list)" class="btn btn-secondary btn-sm">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-success btn-sm" @click="selectPosition(list)">
                                                    <i class="bi bi-check2-square"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" v-show="!noData(positions)">
                                            No Result Found!
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" ref="modaloffice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ADD OFFICE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start mb-3">
                    
                    <div class="form-group mb-3">
                        <label>DESCRIPTION</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="form4.description" placeholder="Enter Description">
                            <button class="btn btn-secondary" v-if="form4.id != undefined " @click="resetform4" type="button">
                                <i class="bi bi-x-square"></i>
                            </button>
                            <button class="btn btn-success" @click="saveOffice" type="button" id="button-addon1">
                                <i class="bi" :class="form4.id == undefined ? 'bi-floppy-fill' :'bi-pencil-square'"></i>
                            </button>
                        </div>
                          <span class="text-danger" v-if="errors4.description">{{errors4.description[0]}}</span>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead class="text-uppercase text-muted">
                                    <tr>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(list, index) in offices" :key="index">
                                        <td class="text-muted">{{ list.description }}</td>
                                        <td class="text-muted">
                                            <div class="btn-group">
                                                <button type="button" @click="editOffice(list)" class="btn btn-secondary btn-sm">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-success btn-sm" @click="selectOffice(list)">
                                                    <i class="bi bi-check2-square"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" v-show="!noData(offices)">
                                            No Result Found!
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
