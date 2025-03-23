<script setup>
    import { ref,reactive, onMounted } from "vue"
    import { Modal } from "bootstrap";

    const user = ref({})
    const errors = ref([])

    const modal = ref(null)
    let vmodal = null;
    const modalname = ref(null)
    const modalpic = ref(null)
    let vmodalname = null;
    let vmodalpic = null;
    const btnCap = ref("Save")

    const formData = new FormData();  
    
    const format = (d) => {
        const day =("0" + d.getDate()).slice(-2);
        const month = ("0"+(d.getMonth()+1)).slice(-2);
        const year =  d.getFullYear();

        return  month+ "/"+day+"/" + year;
    }

    onMounted(()=>{
        vmodal = new Modal(modal.value);
        vmodalname = new Modal(modalpic.value);
        vmodalpic = new Modal(modalpic.value);
        getAuthUser()
        modal.value.addEventListener("hidden.bs.modal", clearModal)
        modalname.value.addEventListener("hidden.bs.modal", clearModal)
        modalpic.value.addEventListener("hidden.bs.modal", clearImage)
    })

    const clearModal = ()=>{
        resetform()
        errors.value = []
    }

    const clearImage = ()=>{
        profilepic.value = ""
        document.querySelector('.fileupload').value = ""
    }

    const fdata=()=>({
        password:"",
        password_confirmation:"",
        name:"",
        image:"",
    })

    const form = reactive(fdata())
    const resetform = () => Object.assign(form, fdata())

    const getAuthUser = ()=>{
        axios.get("api/user").then((res)=>{
            user.value = res.data
        })
    }

    const savePassword = ()=>{
        errors.value = []
        axios.put("api/employees-change-password/"+form.id, form).then((res)=>{
            resetform()
            toast.fire({
                icon:'success',
                title:'Password has been changed!'
            })
            errors.value = []
            getAuthUser()
            vmodal.hide()
            window.location.href="/login"
        }).catch((err)=>{
            errors.value = err.response.data.errors
        })
    }

    const saveName = ()=>{
        errors.value = []
        axios.put("api/change-name/"+form.id, form).then((res)=>{
            resetform()
            toast.fire({
                icon:'success',
                title:'Name has been changed!'
            })
            errors.value = []
            getAuthUser()
            vmodalname.hide()
        }).catch((err)=>{
            errors.value = err.response.data.errors
        })
    }


    const extractRole = (data)=>{
        return data == 1 ? "ADMIN" : data == 2 ? "SCHOOL PRESIDENT": data == 3 ? "OFFICE HEAD" : data == 4 ? "FACULTY / STAFF" :" NONE"
    }

    const changePassword = ()=>{
        form.id = user.value.id
        vmodal.show()
    }

    const changeName = ()=>{
        form.name = user.value.name
        form.id = user.value.id
        vmodalname.show()
    }
    const profilepic = ref("")
    const changeFile = ()=>{   
        form.id = user.value.id
        let data = document.querySelector('.fileupload').files
        profilepic.value = URL.createObjectURL(data[0])
        vmodalpic.show();
        form.image =  data[0]
    }

    const saveProfilePic = ()=>{
        Object.entries(form).forEach(([key, value]) => {
            formData.append(key, value);
        });
        btnCap.value = "Saving..."
        axios.post("api/profile-picture", formData,{
            headers: {
                'Content-Type': 'multipart/form-data'
            },
        }).then((res)=>{
            btnCap.value = "Save"
            vmodalpic.hide()
            toast.fire({
                icon:'success',
                title:'Profile Picture has been changed!',
            })
            getAuthUser()
            errors.value = []
        }).catch((err)=>{
            errors.value = err.response.data.errors
            btnCap.value = "Save"
        })
    }

    const extractImage = (data)=>{
        const blob = b64toBlob(data.image, data.img_type);
        return URL.createObjectURL(blob)
    }

    const b64toBlob = (b64Data, contentType='', sliceSize=512) => {
        const byteCharacters = atob(b64Data);
        const byteArrays = [];
        for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            const slice = byteCharacters.slice(offset, offset + sliceSize);

            const byteNumbers = new Array(slice.length);
            for (let i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
            }

            const byteArray = new Uint8Array(byteNumbers);
            byteArrays.push(byteArray);
        }
            
        const blob = new Blob(byteArrays, {type: contentType});
        return blob;
    }
</script>

<template>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10 d-flex justify-content-between">
                        <div class="profile-pic me-4 border p-3 m-0">
                            <img class="img-pic" :src="user.image == null ? '/img/permiso.png' : extractImage(user)"/>
                              <input type="file" @change="changeFile" class="d-none fileupload" id="propic" accept=".jpeg, .jpg, .png"/>
                            <label for="propic" class="btn btn-outline-success btn-sm mt-2">
                                <i class="bi bi-pen"></i>
                                Edit Profile Pic
                            </label>
                        </div>
                        <div class="row">
                             <div class="col-md-6 mt-3">
                                <label><small>NAME:</small>&nbsp;
                                <span class="fw-bold fs-5 text-success">{{ user.name }}</span></label>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label><small>DATE OF EMPLOYMENT:</small> &nbsp;
                                <span class="fw-bold fs-5 text-success">{{ format(new Date(user.date_of_employment)) }}</span></label>
                            </div>
                            <hr>
                            <div class="col-md-6 mt-2">
                                <label><small>OFFICE:</small> &nbsp;
                                <span class="fw-bold fs-5 text-success">{{ user.office != null ? user.office.description :""  }}</span></label>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label ><small>POSITION:</small> &nbsp;
                                <span class="fw-bold fs-5 text-success">{{ user.position != null ?  user.position.description :"" }}</span></label>
                            </div>
                            <hr>
                             <div class="col-md-12 mt-3">
                                <label><small>EMPLOYMENT CLASSIFICATION:</small> &nbsp;
                                <span class="fw-bold fs-5 text-success">{{ user.emp_class !=null ? user.emp_class.title :""  }}</span></label>
                            </div>
                            <hr>
                            <div class="col-md-6 mt-2">
                                <label ><small>SCHOOL ID:</small> &nbsp;
                                <span class="fw-bold fs-5 text-success">{{ user.school_id }}</span></label>
                            </div>
                             <div class="col-md-6 mt-2">
                                <label ><small>ROLE:</small> &nbsp;
                                <span class="fw-bold fs-5 text-success">{{ extractRole(user.role) }}</span></label>
                            </div>
                            <hr>
                        </div>
                       
                    </div>
                    <div class="col-md-2 border text-center">
                        <h5 class="mt-3">Change on?</h5>
                        <hr>
                        <div class="btn-group mt-2 mb-3">
                            <!-- <button type="button" @click="changeName" class="btn btn-success btn-sm">Name</button> -->
                            <button type="button"  @click="changePassword" class="btn btn-secondary btn-sm">Password</button>
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
                    <p >Note: Once you change your password, it will automatically logout! </p>
                    <div class="form-group mb-3">
                        <label>PASSWORD</label>
                        <input type="password" class="form-control" v-model="form.password" placeholder="Enter Password">
                        <span class="text-danger" v-if="errors.password">{{errors.password[0]}}</span>
                    </div>
                    <div class="form-group">
                        <label>PASSWORD CONFIRMATION</label>
                        <input type="password" class="form-control" v-model="form.password_confirmation" placeholder="Enter Password Confirmation">
                        <span class="text-danger" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" @click="savePassword()">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" ref="modalname" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">CHANGE MY NAME</h5>
                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start mb-3">
                    <div class="form-group mb-3">
                        <label>NAME</label>
                        <input type="text" class="form-control" v-model="form.name" placeholder="Enter Password">
                        <span class="text-danger" v-if="errors.name">{{errors.name[0]}}</span>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" @click="saveName()">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>

         <div class="modal fade" ref="modalpic" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">CHANGE PROFILE PICTURE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <img :src="profilepic" class="img-pic"/>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" @click="saveProfilePic()">{{ btnCap }}</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    .img-pic{
        width: 8rem;
        height: 8rem;
        border: 1px solid #225a26;
        border-radius: 5px;
        padding: 4px;
    }
</style>

