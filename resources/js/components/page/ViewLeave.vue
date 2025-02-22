<script setup>
    import { ref, reactive, onMounted }  from "vue"
    import { useRoute } from "vue-router"

    const route = useRoute()
    const leave = ref({})
    onMounted(()=>{
        let id = route.params.id
        getLeaveId(id)
    })

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

    const setStatus = (data)=>{
        return data == 0 ? "PENDING" : data == 1 ? "INITIAL APPROVED" : data == 2 ? "FINAL APPROVED" :"DISAPPROVED"
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
        Swal.fire({
            title: "Do you want to approved this leave ?",
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
                axios.post("/api/initial-approved/", leave.value).then((res)=>{
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


</script>

<template>
    <div class="container">
        <h4 class="mt-4">VIEW LEAVE</h4>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Provide information about your leave status</li>
        </ol>
        <div class="row" v-if="leave.id != null">
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
                                    <p>{{ format(leave.signature.date_of_employment) }}</p>
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
                                <small class="text-muted ">Cause:</small>
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

                            <div class="data-group col-md-6">
                                <small class="text-muted">Initial Approval:</small>
                                <blockquote class="blockquote">
                                    <p>{{ extractName(leave.initial) }}</p>
                                </blockquote>
                                <figcaption class="blockquote-footer" v-if="leave.initial_appr_date != null">
                                    Date approved: <cite title="Source Title">{{ format(leave.initial_appr_date) }}</cite>
                                </figcaption>
                                
                            </div>
                            <div class="data-group col-md-6">
                                <small class="text-muted">Final Approval:</small>
                                <blockquote class="blockquote">
                                    <p>{{ extractName(leave.final) }}</p>
                                </blockquote>
                                 <figcaption class="blockquote-footer" v-if="leave.final_appr_date != null">
                                    Date approved: <cite title="Source Title">{{ format(leave.final_appr_date) }}</cite>
                                </figcaption>
                            </div>

                        </div>
                    </div>
                  </div>
                  <div class="card-footer">
                      <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-secondary">Leave Status:</small>
                            <h4 class="text-muted"> {{ setStatus(leave.status) }} ...</h4>
                        </div>

                        <div>
                            <small class="text-secondary">School Year:</small>
                            <h5 class="text-success"> {{ extractSY(leave.schoolyear) }}</h5>
                        </div>
                      </div>
                  </div>
                    <div class="card-footer">
                        <div class="btn-group">
                            <button type="button" @click="initialApproved()" class="btn btn-success">APPROVED</button>
                            <button type="button" class="btn btn-secondary">DISAPPROVED</button>
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

