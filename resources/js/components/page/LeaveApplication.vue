<script setup>
    import { ref, reactive, onMounted } from "vue"
    import { useRoute, useRouter } from "vue-router"

    const leaves = ref([])
    const initials = ref([])
    const finals = ref([])
    const errors = ref([])

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
        date_apply:"",
        cause:"",
        number_of_day:"",
        from:"",
        from_extension:"",
        until:"",
        until_extension:"",
        initial_approval:"",
        final_approval:""
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
    })

    const getAuthUser = ()=>{
        axios.get('/api/user').then((res)=>{
            user.value = res.data
        })
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
            form.date_apply = data.date_apply
            form.cause = data.cause
            form.from = data.from
            form.until = data.until
            form.until_extension = data.until_ext
            form.from_extension = data.from_ext
            form.number_of_day = data.number_of_day
            form.initial_approval = data.initial_appr_id
            form.final_approval = data.final_appr_id
        })
    }

    const checkFromDate = ()=>{
        let d = new Date();
            d.setDate(d.getDate() + 3);
            let date = d.toISOString().slice(0, 10);

            let day3 =  new Date(date);
            if(form.from != "" && (form.leave === 4 || form.leave === 5)){
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

                //  if(form.number_of_day > this.availeave){
                //         let id = this.leave_type.id;
                //         this.usable = this.extractNonLeaveType(id);
                //         return;
                //  }

    }

    const submitLeaveApplication = ()=>{
      
        if(form.id == undefined){
            btnCap.value = "Submitting..."
            axios.post("api/leave-application", form).then((res)=>{
                toast.fire({
                    icon:'success',
                    title:'Leave Application submitted successfully!',
                    // background:'#c50000'
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
             axios.put("api/leave-application/"+form.id, form).then((res)=>{
                toast.fire({
                    icon:'success',
                    title:'Leave Application has ben modified!',
                    // background:'#c50000'
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
    }

    const changeFile = ()=>{
        
        let data = document.querySelector('.fileupload').files
        signatureUpload(data)
    }
    const sig_image = ref("")
    const sigfile = ref({})
    const signatureUpload = (data)=>{
        sigfile.value = data[0]
        sig_image.value = URL.createObjectURL(data[0])

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

        })
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
                                <label>DATE APPLY:</label>
                                    <VueDatePicker v-model="form.date_apply" :format="format"></VueDatePicker>
                                    <span class="text-danger" v-if="errors.date_apply">{{errors.date_apply[0]}}</span>
                                </div>
                            </div>
                        </div>
                    <div class="form-group mt-3 mb-3">
                        <label>CAUSE</label>
                        <textarea class="form-control" v-model="form.cause"></textarea>
                        <span class="text-danger" v-if="errors.cause">{{errors.cause[0]}}</span>
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
                                    <label>EXT.</label>
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
                                    <label>EXT.</label>
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
                        </div>
                        <div class="form-group">
                            <label>DIGITAL SIGNATURE</label>
                            <div class="img mb-3">
                                <input type="file" @change="changeFile" class="d-none fileupload" id="filesig" accept=".jpeg, .jpg, .png"/>
                                <img class="img-responsive digital-img"  :src="sig_image">
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
                            <div class="img mb-3">
                                 <img class="img-responsive digital-img"  src="https://www.familyeducation.com/sites/default/files/styles/max_920w/public/2021-07/100%20Beautiful%20Girl%20Names_Featured.jpg.webp?itok=K_rgPBcE">
                            </div>
                           
                        </div>
                         <button type="button" class="btn btn-secondary btn-sm">
                             <i class="bi bi-paperclip"></i>
                             Attach
                        </button>
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
    }
</style>
