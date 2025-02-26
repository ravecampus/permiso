<script setup>
    import { ref, reactive, onMounted } from "vue"
    import { useRoute, useRouter } from "vue-router"

    const leaves = ref([])
    const initials = ref([])
    const finals = ref([])
    const errors = ref([])
    const sickfilter = ref(0)
    const attachment_ = ref("")
    const sig_image = ref(null)
    const formData = new FormData();  
    const formData2 = new FormData();

    const leave_sy = ref([])
    const balance = ref(0)
    const allowed = ref(0)

    const weekend = ref("")

    const btnCap = ref("Submit")


    const format = (d) => {
        const day =("0" + d.getDate()).slice(-2);
        const month = ("0"+(d.getMonth()+1)).slice(-2);
        const year =  d.getFullYear();

        return  month+ "/"+day+"/" + year;
    }

    const formdata=()=>({
        id:null,
        leave:"",
        leavedata:"",
        emp_class_id:"",
        leave_credit_id:"",
        application_date: format(new Date()),
        cause:"",
        number_of_day:"",
        from:"",
        from_extension:"",
        until:"",
        until_extension:"",
        initial_approval:"",
        final_approval:"",
        attachment:"",
    })
    const form = reactive(formdata())
    const resetform = () => Object.assign(form, formdata())

    const route = useRoute();
    const router = useRouter()
    const user = ref({})

    onMounted(()=>{
        let id = route.params.leave_id

        if(id != null){
             getLeaveId(id)
             btnCap.value ="Save Changes"
        }

        getAuthUser();
        getLeave()
        getInitial()
        getFinal()
        getMyLeaveSY()
    })

    const getMyLeaveSY = ()=>{
        axios.get("/api/my-leave-sy").then((res)=>{
            leave_sy.value = res.data
        })
    }

    const getAuthUser = ()=>{
        axios.get('/api/user').then((res)=>{
            let data = res.data
            user.value = res.data

            if(data.digital_signature != null && data.digital_signature != ''){
                 const blob = b64toBlob(data.digital_signature, data.ds_type);
                 sig_image.value = URL.createObjectURL(blob)
            }else{
                sig_image.value = null
            }
        })
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

    const getLeave = () => {
         axios.get('/api/source-leave').then((res)=>{
            leaves.value = res.data
        })
    }
    const getInitial = () => {
         axios.get('/api/source-initial').then((res)=>{
            initials.value = res.data
        })
    }
    const getFinal = () => {
         axios.get('/api/source-final').then((res)=>{
            finals.value = res.data
        })
    }

    const getLeaveId = (id)=>{
        openRtxt.value = true
        axios.get("api/leave-application/"+id).then((res)=>{
            let data = res.data
            form.id = data.id
            form.leave = data.leave_id
            form.emp_class_id = route.params.id
            form.application_date = data.date_apply
            form.cause = data.cause
            form.from = data.from
            form.until = data.until
            form.until_extension = data.until_ext
            form.from_extension = data.from_ext
            form.number_of_day = data.number_of_day
            form.initial_approval = data.initial_appr_id
            form.final_approval = data.final_appr_id
            if( data.sick_attach != null){
               sickfilter.value = 1;
            }else{
                sickfilter.value = 0;
            }
           
            // const blob = b64toBlob(data.sick_attach, data.sa_type);
            // form.attachment = URL.createObjectURL(blob)
            attach_.value = data.sa_orig_name

        })
    }

    const checkFromDate = ()=>{
        let d = new Date();
            d.setDate(d.getDate() + 3);
            let date = d.toISOString().slice(0, 10);

            let day3 =  new Date(date);
            if(form.from != "" && (form.leave === 5 || form.leave === 6)){
                if(form.from <= day3){
                    toast.fire({
                        icon:'warning',
                        title:'Date From must be 3 days before!',
                        background:'#c50000'
                    })
                    form.from = "";
                    form.until = "";
                }
            }
          
    }

    const checkUntilDate = ()=>{
            if(form.from > form.until && form.until !=""){
                toast.fire({
                    icon:'warning',
                    title:'Date Until must greater than Date From!',
                    background:'#c50000'
                })
                form.until = "";
                return;
            }

            var from = new Date(form.from);
            var to = new Date(form.until);
                if(from.getTime() == to.getTime()){
                    if(form.from_extension == 1 && form.until_extension == 2 ){
                        form.number_of_day = 1;
                    }else{
                        form.number_of_day = 0.5;
                    }
                }
              if(from < to){
                    var weeks, date_diff, iAdjust = 0;
                    if (to < from) return -1; // error code if dates transposed
                    var week_from = from.getDay(); // day of week
                    var week_to = to.getDay();
                    week_from = (week_from == 0) ? 7 : week_from; // change Sunday from 0 to 7
                    week_to = (week_to == 0) ? 7 : week_to;
                   if(form.leave == 4 || form.leave == 5 || form.leave == 6 ){
                        if ((week_from > 5) && (week_to > 5)) iAdjust = 1; // adjustment if both days on weekend
                        week_from = (week_from > 5) ? 5 : week_from; // only count weekdays
                        week_to = (week_to > 5) ? 5 : week_to;
                   }


                    // calculate differnece in weeks (1000mS * 60sec * 60min * 24hrs * 7 days = 604800000)
                    weeks = Math.floor((to.getTime() - from.getTime()) / 604800000)

                    if(form.leave == 4 || form.leave == 5 || form.leave == 6){

                        if (week_from <= week_to) {
                            date_diff = (weeks * 5) + (week_to - week_from)
                        } else {
                            date_diff = ((weeks + 1) * 5) - (week_from - week_to)
                        }
                      
                        weekend.value = "Saturday and Sunday are excluded"
                    }else{
                        if (week_from <= week_to) {
                            date_diff = (weeks * 7) + (week_to - week_from)
                        } else {
                            date_diff = ((weeks + 1) * 7) - (week_from - week_to)
                        }
                        weekend.value = ""
                    }
                    date_diff -= iAdjust // take into account both days on weekend

                    form.number_of_day =  (date_diff + 1) - (form.until_extension == 1 ? 0.5 : 0) - (form.from_extension == 2 ? 0.5 : 0); //
                
                }

             

    }

    const submitLeaveApplication = ()=>{
        if((sig_image.value == null || sig_image.value == '' )){
            toast.fire({
                icon:'warning',
                title:'Upload digital signature first before you proceed!',
                background:'#c50000'
            })
            return;
        }

        if(sig_image.value != null &&  user.value.digital_signature == null){
             toast.fire({
                icon:'warning',
                title:'Please click save on the digital signature!',
                background:'#c50000'
            })
            return;
        }
        form.attachment = attachment_.value

        if((form.leave === 4 && sickfilter.value == 0 && form.id == null) || (form.leave === 4 && sickfilter.value == "")){
            toast.fire({
                icon:'warning',
                title:'Sick Leave need a attachment!',
                background:'#c50000'
            })
            return;
        }

       
      
        Object.entries(form).forEach(([key, value]) => {
            formData2.append(key, value);
        });

        if(form.id == undefined){
            btnCap.value = "Submitting..."

            axios.post("api/leave-application", formData2,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
            }).then((res)=>{
                toast.fire({
                    icon:'success',
                    title:'Leave Application submitted successfully!',
                })
                router.push({name:"myleave"})
                btnCap.value = "Submit"
                errors.value = []
            }).catch((err)=>{
                errors.value = err.response.data.errors
                btnCap.value = "Submit"
            })
        }else{
            btnCap.value = "Saving..."
             formData2.append('_method', 'put')
             axios.post("api/leave-application/"+form.id, formData2,
             {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
             }).then((res)=>{
                toast.fire({
                    icon:'success',
                    title:'Leave Application has ben modified!',
                })
                router.push({name:"myleave"})
                btnCap.value = "Save Changes"
                errors.value = []
            }).catch((err)=>{
                errors.value = err.response.data.errors
                btnCap.value = "Save Changes"
            })
            
        }
       
    }

    const openRtxt = ref(false);
    const openRange = (data)=>{
        errors.value = []
        form.emp_class_id = route.params.id
        if(data.leave_id != ""){
            openRtxt.value = true
        }else{
            openRtxt.value = false
        }
        checkBalance(data)
    }

    const checkBalance = (id)=>{
        let ret = 0
        leave_sy.value.forEach(val => {
            if(val.leave_id == id){
                ret += val.number_of_day
            }
        });

        balance.value = (allowedBal(id) == "NO LIMIT" ? 0: allowedBal(id))  - ret
    }

    const allowedBal = (id)=>{
        let ret = 0;
        leaves.value.forEach(val => {
            if(val.leave_id == id && val.is_no_limit == 0){
                ret = val.credits
            }else if(val.leave_id == id && val.is_no_limit == 1){
                ret = "NO LIMIT"
            }
        });
        allowed.value = ret
        return ret
    }

    const changeFile = ()=>{    
        let data = document.querySelector('.fileupload').files
        signatureUpload(data)
    }

    const changeFileAttach = ()=>{    
        let data = document.querySelector('.file-attach').files
        sickattach(data)
        // signatureUpload(data)
        
    }
    const attach_ = ref("")
    const sickattach = (data)=>{
        attach_.value = data[0].name
        attachment_.value = data[0]
        sickfilter.value = 1
    }

    const sigfile = ref({})
    const signatureUpload = (data)=>{
        sigfile.value = data[0]
        sig_image.value = URL.createObjectURL(data[0])

    }

    const uplaodSignature = ()=>{
        
        formData.append("id", user.value.id)
        formData.append("sigfile", sigfile.value)
        axios.post("api/upload-signature", formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
        }).then((res)=>{
            toast.fire({
                icon:'success',
                title:'Digital Signature uploaded successfully!',
                // background:'#c50000'
            })
            getAuthUser();
        })
    }

    const removeFile = ()=>{
        attach_.value = ""
        attachment_.value = ""
    }


</script>


<template>
    <div class="container mb-5">
        <h4 class="mt-5">LEAVE APPLICATION</h4>
        <!-- <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol> -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label>LEAVE APPLIED FOR:</label>
                                    <select class="form-control" v-model="form.leave" @change="openRange(form.leave)">
                                        <option v-for="(list, index) in leaves" :key="index" :value="list.leave_id">{{ list.leave_description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.leave">{{errors.leave[0]}}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label>APPLICATION DATE:</label>
                                    <VueDatePicker v-model="form.application_date" :format="format"></VueDatePicker>
                                    <span class="text-danger" v-if="errors.application_date">{{errors.application_date[0]}}</span>
                                </div>
                            </div>
                        </div>
                    <div class="form-group mt-3 mb-3">
                        <label>CAUSE / REASON</label>
                        <textarea class="form-control" v-model="form.cause"></textarea>
                        <span class="text-danger" v-if="errors.cause">{{errors.cause[0]}}</span>
                    </div>

                    <div class="card bg-success text-light">
                        <div class="card-body">
                            <label class="text-light me-3"><i class="fw-bold">ALLOWED: </i>
                                <span class="fs-6">{{ allowed }} </span>
                             </label>
                             <label class="text-light"><i class="fw-bold">AVAILABLE: </i>
                                <span class="fs-6">{{ balance }} </span>
                             </label>
                        </div>
                    </div>
                    <div class="row mb-4" v-if="openRtxt">
                        <strong>INCLUSIVE DATE </strong>
                        <div class="col-md-6 row me-4">
                            <div class="col-md-8 pr-0">
                                <div class="form-group">
                                    <label>FROM:</label>
                                    <VueDatePicker v-model="form.from" :format="format" :input="checkFromDate()"></VueDatePicker>
                                    <span class="text-danger" v-if="errors.from">{{errors.from[0]}}</span>
                                    
                                </div>
                            </div>
                        <div class="col-md-4 pl-1">
                                <div class="form-group">
                                    <label>AM/PM</label>
                                    <select class="form-control" v-model="form.from_extension">
                                        <option value="1">AM</option>
                                        <option value="2">PM</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.from_extension">{{errors.from_extension[0]}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 row">
                            <div class="col-md-8 pr-0">
                                <div class="form-group">
                                    <label>UNTIL:</label>
                                    <VueDatePicker v-model="form.until" :format="format" :input="checkUntilDate()"></VueDatePicker>
                                    <span class="text-danger" v-if="errors.until_extension">{{errors.until_extension[0]}}</span>
                                </div>
                            </div>
                        <div class="col-md-4 pl-1">
                                <div class="form-group">
                                    <label>AM/PM</label>
                                    <select class="form-control" v-model="form.until_extension">
                                        <option value="1">AM</option>
                                        <option value="2">PM</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.until_extension">{{errors.until_extension[0]}}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label>NUMBER OF DAYS: <small class="text-danger">{{ weekend }}</small></label>
                        <input type="text" disabled class="form-control" v-model="form.number_of_day" placeholder="">
                        <span class="text-danger" v-if="errors.number_of_day">{{errors.number_of_day[0]}}</span>
                    </div>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label>IINITIAL APPROVAL</label>
                                    <select class="form-control" v-model="form.initial_approval">
                                        <option v-for="(list, index) in initials" :key="index" :value="list.user_id">{{ list.initial }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.initial_approval">{{errors.initial_approval[0]}}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>FINAL APPROVAL</label>
                                    <select class="form-control" v-model="form.final_approval">
                                        <option v-for="(list, index) in finals" :key="index" :value="list.user_id">{{ list.final }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.final_approval">{{errors.final_approval[0]}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <button type="button" @click="submitLeaveApplication()" class="btn btn-success">
                                <i class="bi bi-send"></i>
                               {{ btnCap }} 
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                          <small class="text-muted">Attachment</small>
                          <hr>
                        </div>
                        <div class="form-group">
                            <label>DIGITAL SIGNATURE</label>
                            <div class="img mb-3 mt-1">
                                <input type="file" @change="changeFile" class="d-none fileupload" id="filesig" accept=".jpeg, .jpg, .png"/>
                                <img class="img-responsive digital-img"  :src="(sig_image == null || sig_image == '' )? '/img/digital.jpg':sig_image">
                            </div>
                           
                        </div>
                        <div class="btn-group">
                            <label for="filesig" class="btn btn-secondary btn-sm">
                                <i class="bi bi-upload"></i>
                                upload
                            </label>
                            <button type="button" @click="uplaodSignature" class="btn btn-success btn-sm">
                                <i class="bi bi-floppy"></i>
                                save
                            </button>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>MEDICAL CERTIFICATE</label>
                            <div class="img mb-3 mt-2" v-if="attach_ !=''">
                                
                                <p class="text-success">
                                 <strong class="me-2">   {{ attach_ }}</strong>
                                 <button type="button" @click="removeFile()" v-if="form.id == undefined" class="btn btn-outline-secondary btn-sm">
                                     <span class="bi bi-x"></span>
                                 </button>
                                </p>
                            </div>
                           
                        </div>
                        <input type="file" @change="changeFileAttach" class="d-none file-attach" id="attachfile" accept=""/>
                         <label for="attachfile" class="btn btn-secondary btn-sm mt-2" v-if="form.leave == 4">
                             <i class="bi bi-paperclip"></i>
                             Attachment
                        </label>
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
    
</style>
