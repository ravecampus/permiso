<script setup>
    import { ref, reactive, onMounted }  from "vue"
    import { useRoute } from "vue-router"
    import { Modal } from "bootstrap";
    import Print from "../print/ApplicationLeaveCollege.vue";
    // import Print from "../print/ApplicationLeaveLevelNonTeach.vue";
    // import Print from "../print/ApplicationLeaveLevel4.vue";
    // import Print from "../print/ApplicationLeaveLevel3.vue";
    // import Print from "../print/ApplicationLeaveLevel3Non.vue";
    // import Print from "../print/ApplicationLeaveLevel2.vue";
    
    


    const route = useRoute()
    const leave = ref({})
    const errors = ref([])
    const user = ref({})
    const sig_image = ref(null)

    const modaldisapproved = ref(null)
    let vmodaldisapproved = null;

    const formdata = ()=>({
        remarks:"",
        id:null,
    })

    const form = reactive(formdata())
    const resetform = ()=> Object.assign(form, formdata())

    onMounted(()=>{
        vmodaldisapproved = new Modal(modaldisapproved.value);

        let id = route.params.id
        getLeaveId(id)
        getAuthUser();
    })

    
     const getAuthUser = ()=>{
        axios.get('/api/user').then((res)=>{
            let data = res.data
            user.value = res.data
            if(data.digital_signature != null){
                const blob = b64toBlob(data.digital_signature, data.ds_type);
                sig_image.value = URL.createObjectURL(blob)
            }
         
        })
    }


    const format = (d_) => {
        let d = new Date(d_)
        const day =("0" + d.getDate()).slice(-2);
        const month = ("0"+(d.getMonth()+1)).slice(-2);
        const year =  d.getFullYear();

        return  month+ "/"+day+"/" + year;
    }
    const getLeaveId = (id)=>{
        axios.get("api/leave-application/"+id).then((res)=>{
            let data = res.data
            leave.value = res.data
        })
    }

    const setExten = (data)=>{
        return data == 1 ? "AM" : "PM"
    }

    const extractSY = (data)=>{
        if(data != null){
            return data.start+" - "+data.end

        }
    }

    const extractName = (data)=>{

        if(data != null){
            return data.name
        }
    }

    const setStatus = (val)=>{
        let data = val.status
        return data == 0 ? "PENDING" : data == 1 ? checkApprovalStatus(val) : data == 2 ? "APPROVED" :"DISAPPROVED"
    }

    const checkApprovalStatus = (data)=>{
        return data.emp_class_id == 1 ? "PENDING" : "INITIALLY APPROVED"
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

    const extractDigitSig = (data)=>{
        const blob = b64toBlob(data.digital_signature, data.ds_type);
        return URL.createObjectURL(blob)
    }

    const downloadFile = (data)=>{
        const blob = b64toBlob(data.sick_attach, data.sa_type);
        const link = document.createElement('a')
        link.href = URL.createObjectURL(blob)
        link.download = data.sa_orig_name
        link.click()
        URL.revokeObjectURL(link.href)
    }

    const extDescription = (data)=>{
        return data.description;
    }

    const initialApproved = ()=>{
        if(user.value.digital_signature == "" || user.value.digital_signature == null){
             toast.fire({
                icon:'warning',
                title:'Upload digital signature first before you proceed!',
                background:'#c50000'
            })
            return;
        }
        Swal.fire({
            title: "Do you want to Initial Approved this leave ?",
            text: " ",
            icon: "question",
            showCancelButton: true,
            background: '#17a673',
            color: '#fff',
            confirmButtonColor: "#424242",
            cancelButtonColor: "#ffc107",
            confirmButtonText: "YES!"
            }).then((result) => {
            if (result.isConfirmed) {
                axios.post("/api/initial-approved/", leave.value).then((res)=>{
                    toast.fire({
                        title: "APPROVED",
                        text: "Leave application Initially Approved.",
                        icon: "success",
						confirmButtonColor: "#26884b",
                    });
                    let id = route.params.id
                    getLeaveId(id)
                })
                
            }
        });
    }

    const finalApproved = ()=>{
        if(user.value.digital_signature == "" || user.value.digital_signature == null){
             toast.fire({
                icon:'warning',
                title:'Upload digital signature first before you proceed!',
                background:'#c50000'
            })
            return;
        }
        Swal.fire({
            title: "Do you want to Approved this leave ?",
            text: " ",
            icon: "question",
            showCancelButton: true,
            background: '#17a673',
            color: '#fff',
            confirmButtonColor: "#424242",
            cancelButtonColor: "#ffc107",
            confirmButtonText: "YES!"
            }).then((result) => {
            if (result.isConfirmed) {
                axios.post("/api/final-approved/", leave.value).then((res)=>{
                    toast.fire({
                        title: "APPROVED",
                        text: "Leave application Final Approved.",
                        icon: "success",
						confirmButtonColor: "#26884b",
                    });
                    let id = route.params.id
                    getLeaveId(id)
                })
                
            }
        });
    }

    const disapproved = ()=>{
        Swal.fire({
            title: "Do you want to Disapproved this leave ?",
            text: " ",
            icon: "question",
            showCancelButton: true,
            background: '#17a673',
            color: '#fff',
            confirmButtonColor: "#424242",
            cancelButtonColor: "#ffc107",
            confirmButtonText: "YES!"
            }).then((result) => {
            if (result.isConfirmed) {
                axios.post("/api/disapproved/", leave.value).then((res)=>{
                    toast.fire({
                        title: "DISAPPROVED",
                        text: "Leave application Disapproved.",
                        icon: "success",
						confirmButtonColor: "#26884b",
                    });
                    let id = route.params.id
                    getLeaveId(id)
                })
                
            }
        });
    }

    const showDisapproved = ()=>{
        if(user.value.id == leave.value.initial_appr_id){
             if(leave.value.emp_class_id == 4 
                || leave.value.emp_class_id == 5 
                || leave.value.emp_class_id == 6 ){
                   vmodaldisapproved.show()
            }else{
                disapproved()
            }
        }

        if(user.value.id == leave.value.final_appr_id){
             if(leave.value.emp_class_id == 1 
                || leave.value.emp_class_id == 2 
                || leave.value.emp_class_id == 3 ){
                   vmodaldisapproved.show()
            }else{
                disapproved()
            }
        }
       
        
    }

    const formData = new FormData();  
    const uplaodSignature = ()=>{
        
        formData.append("id", user.value.id)
        formData.append("sigfile", sigfile.value)
        axios.post("api/upload-signature", formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
        }).then((res)=>{
            getAuthUser()
            toast.fire({
                icon:'success',
                title:'Digital Signature uploaded successfully!',
                // background:'#c50000'
            })
        })
    }

    const changeFile = ()=>{    
        let data = document.querySelector('.fileupload').files
        signatureUpload(data)
    }
    
    const sigfile = ref({})
    const signatureUpload = (data)=>{
        sigfile.value = data[0]
        sig_image.value = URL.createObjectURL(data[0])

    }

    const disapprovedwithRemarks = ()=>{
        if(form.remarks == ""){

             toast.fire({
                title: "REQUIRED",
                text: "Remarks is required!",
                icon: "warning",
                confirmButtonColor: "#26884b",
            });
            return
        }
        form.id = leave.value.id
        axios.post("/api/disapproved/", form).then((res)=>{
            toast.fire({
                title: "DISAPPROVED",
                text: "Leave application Disapproved.",
                icon: "success",
                confirmButtonColor: "#26884b",
            });
            resetform()
            vmodaldisapproved.hide()
            let id = route.params.id
            getLeaveId(id)
        }).catch((err)=>{
            errors.value = err.response.data.errors
        })
    }


</script>

<template>
    <div class="container">
        <Print v-if="leave.id != undefined" :leaveapp="leave"></Print>
        <h4 class="mt-4 d-print-none">VIEW LEAVE</h4>
        <ol class="breadcrumb mb-4 d-print-none">
            <li class="breadcrumb-item active">Provide information about your leave status</li>
        </ol>
        <div class="row d-print-none" v-if="leave.id != null">
            <div class="col-md-8">
                <div class="card text-left">
                  <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="data-group">
                                <small class="text-muted">Name:</small>
                                <blockquote class="blockquote">
                                    <p>{{ leave.signature.name }}</p>
                                </blockquote>
                            </div>
                            <div class="data-group">
                                <small class="text-muted">Date of Employment:</small>
                                <blockquote class="blockquote">
                                    <p>{{ format(new Date(leave.signature.date_of_employment)) }}</p>
                                </blockquote>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="data-group">
                                <small class="text-muted">Office:</small>
                                <blockquote class="blockquote">
                                    <p>{{ extDescription(leave.signature.office) }}</p>
                                </blockquote>
                            </div>
                            <div class="data-group">
                                <small class="text-muted">Position:</small>
                                <blockquote class="blockquote">
                                    <p>{{ extDescription(leave.signature.position) }}</p>
                                </blockquote>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-6">
                            <div class="data-group">
                                <small class="text-muted">Leave applied for:</small>
                                <blockquote class="blockquote">
                                    <p>{{ leave.description }}</p>
                                </blockquote>
                            </div>

                            <div class="data-group">
                                <small class="text-muted ">Cause / Reason:</small>
                                <blockquote class="blockquote">
                                    <p>{{ leave.cause }}</p>
                                </blockquote>
                            </div>
                            <div class="data-group">
                                <small class="text-muted ">Number of day/s:</small>
                                <blockquote class="blockquote">
                                    <p>{{ leave.number_of_day }}</p>
                                </blockquote>
                            </div>
                        </div>
                          <div class="col-md-6">
                            <div class="data-group">
                                <small class="text-muted">Date apply:</small>
                                <blockquote class="blockquote">
                                    <p>{{ format(leave.date_apply) }}</p>
                                </blockquote>
                            </div>

                            <div class="data-group">
                                <small class="text-muted ">Inclusive date:</small>
                                <blockquote class="blockquote">
                                    <p>
                                        {{ format(leave.from)  }} {{ setExten(leave.from_ext) }}-
                                        {{ format(leave.until)  }} {{ setExten(leave.unti_ext) }}

                                    </p>
                                </blockquote>
                            </div>
                        </div>
                        <div class="col-md-12 row">

                            <div class="data-group col-md-6" v-if="leave.emp_class_id != 1">
                                <small class="text-muted">Initial Approval:</small>
                                <blockquote class="blockquote">
                                    <p>{{ extractName(leave.initial) }}</p>
                                </blockquote>
                                <figcaption class="blockquote-footer" v-if="leave.initial_appr_date != null">
                                    Date approved: <cite title="Approval" class="text-danger fw-bold"> {{ format(leave.initial_appr_date) }}</cite>
                                </figcaption>
                                
                            </div>
                            <div class="data-group col-md-6">
                                <small class="text-muted">Final Approval:</small>
                                <blockquote class="blockquote">
                                    <p>{{ extractName(leave.final) }}</p>
                                </blockquote>
                                 <figcaption class="blockquote-footer" v-if="leave.final_appr_date != null">
                                    Date approved: <cite title="Approval" class="text-danger fw-bold">{{ format(leave.final_appr_date) }}</cite>
                                </figcaption>
                            </div>

                        </div>
                    </div>
                  </div>
                  <div class="card-footer">
                      <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-secondary">Leave Status:</small>
                            <h4 class="text-muted"> {{ setStatus(leave) }} ...</h4>
                            <figcaption class="blockquote-footer mt-1" v-if="leave.disapproved_date != null">
                                Date: <cite title="Approval" class="text-danger fw-bold">{{ format(leave.disapproved_date) }}</cite>
                                <div class="fw-bold">
                                  <small class="text-muted">REMARKS :</small>  {{ leave.remarks }}
                                </div>
                            </figcaption>
                        </div>

                        <div>
                            <small class="text-secondary">School Year:</small>
                            <h5 class="text-success"> {{ extractSY(leave.schoolyear) }}</h5>
                            
                        </div>
                      </div>
                  </div>
                    <div class="card-footer" v-if="(user.id == leave.initial_appr_id && leave.status == 0) || (user.id == leave.final_appr_id && leave.status == 1)">
                        <div class="btn-group">
                            
                            <button type="button" @click="initialApproved()" v-if="(user.id == leave.initial_appr_id && leave.status == 0)" class="btn btn-warning">APPROVED</button>
                            <button type="button" @click="finalApproved()"  v-if="(user.id == leave.final_appr_id && leave.status == 1)" class="btn btn-success">APPROVED</button>
                            <button type="button" @click="showDisapproved()" 
                            v-if="(user.id == leave.initial_appr_id && leave.status == 0) 
                            ||  (user.id == leave.final_appr_id && leave.status == 1)" 
                            class="btn btn-secondary">DISAPPROVED</button>
                        </div>
                    </div>
                </div>
               

            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title text-muted">
                            Attachment
                        </div>
                        <hr class="p-0 m-0">
                        <div class="form-group">
                            <label>DIGITAL SIGNATURE</label>
                            <div class="img mb-3 mt-1">
                                <img class="img-responsive digital-img"  :src="leave.signature == null ? '/img/digital.jpg':extractDigitSig(leave.signature)">
                            </div>
                           
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>MEDICAL CERTIFICATE</label>
                            <div class="img mb-3 mt-2" v-if="leave.sick_attach != null">
                                <p class="text-success">
                                 <strong class="me-2">{{leave.sa_orig_name }}</strong>
                                </p>
                                 <button type="button" @click="downloadFile(leave)"  class="btn btn-outline-secondary btn-sm">
                                     <span class="bi bi-download"></span> download
                                 </button>
                            </div>
                           
                        </div>
                    </div>
                </div>

                <div class="card mt-2" v-if="(user.id == leave.initial_appr_id) || (user.id == leave.final_appr_id)">
                    <div class="card-body">
                        <div class="card-title text-muted">
                            APPROVAL
                        </div>
                        <hr class="p-0 m-0">
                        <div class="form-group">
                            <label>DIGITAL SIGNATURE</label>
                              <input type="file" @change="changeFile" class="d-none fileupload" id="digitalsig" accept="image/*"/>
                            <div class="img mb-3 mt-1">
                                <img class="img-responsive digital-img"  :src="(sig_image == null || sig_image == '' )? '/img/digital.jpg':sig_image">
                            </div>
                           
                        </div>
                        <div class="btn-group">
                            <label for="digitalsig" class="btn btn-secondary btn-sm">
                                <i class="bi bi-upload"></i>
                                upload
                            </label>
                            <button type="button" @click="uplaodSignature" class="btn btn-success btn-sm">
                                <i class="bi bi-floppy"></i>
                                save
                            </button>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        
            <div class="modal fade" ref="modaldisapproved" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">DISAPPROVED</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start mb-3 row">
                        <div class="form-group">
                            <label>REMARKS</label>
                            <textarea class="form-control" v-model="form.remarks"></textarea>
                            <span class="text-danger" v-if="errors.remarks">{{errors.remarks[0]}}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" @click="disapprovedwithRemarks()">Disapproved</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>
    </div>
</template>
<style lang="scss" scoped>
    .digital-img{
        width: 150px;
        height: 150px;

        border: 1px solid #225a26;
        border-radius: 5px;
    }

    @media print {
         .container{
             color: #000 !important;
            font-family: "Times New Roman";
            font-size: 15px;
             margin: 0 !important;
             padding: 0 !important;
         }
    }

</style>

