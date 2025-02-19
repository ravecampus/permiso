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
        return data == 0 ? "Pending" : data == 1 ? "Initial Approved" : data == 2 ? "Final Approved" :"Disapproved"
    }



</script>

<template>
    <div class="container">
        <h4 class="mt-4">VIEW MY LEAVE</h4>
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
                </div>

            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title text-muted">
                            Attachment
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
