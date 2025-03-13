<script setup>
    import {ref, onMounted } from "vue"
    
    const format = (d_) => {
        let d = new Date(d_)
        const day =("0" + d.getDate()).slice(-2);
        const month = ("0"+(d.getMonth()+1)).slice(-2);
        const year =  d.getFullYear();

        return  month+ "/"+day+"/" + year;
    }

    const leave_data = ref({})
    const props = defineProps({
            leaveapp: {
            type: Object,
            required: true
          },
    })

    const leaves = ref([])
    const leaved = ref([])

    onMounted(()=>{
        leave_data.value = props.leaveapp
        getleaveByID(props.leaveapp.user_id)
        getLeave(props.leaveapp.emp_class_id)
    })

    const getleaveByID = (id)=>{
        axios.get("api/leave-app-id/"+id).then((res)=>{
            leaves.value = res.data
           
        })
    }

    const getLeave = (id) => {
         axios.get('/api/source-leave-id/'+id).then((res)=>{
            leaved.value = res.data
    
        })
    }

     const checkBalance = (id)=>{
        if(id === 4 || id === 5){
            let ret = 0
            leaves.value.forEach(val => {
                if(val.leave_id == id){
                    ret += val.number_of_day
                }
            });

        return (allowedBal(id) == "NO LIMIT" ? 0: allowedBal(id))  - ret
         }
    }

    const allowedBal = (id)=>{
        let ret = 0;
        leaved.value.forEach(val => {
            if(val.leave_id == id && val.is_no_limit == 0){
                ret = val.credits
            }else if(val.leave_id == id && val.is_no_limit == 1){
                ret = "NO LIMIT"
            }
        });
        return ret
    }


    const extractOffice = (data)=>{
        return data.office != null ? data.office.description : ""
    }

    const extractPosition = (data)=>{
        return data.position != undefined ? data.position.description : ""
    }

    const setExten = (data)=>{
        return data == 1 ? "AM" : "PM"
    }
    
    const extractDigitSig = (data)=>{
        if(data !== undefined ){
            if(data.digital_signature === null){
                return "/img/login.png"
            }else{
                const blob = b64toBlob(data.digital_signature, data.ds_type);
                return URL.createObjectURL(blob)
            }
        }   
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
    <div class=" print-leave d-none d-print-block row">
       <div class="print-header fw-bold">
           <div class="cap">NDMC FORM 114</div>
           <div class="cap"> APPLICATION FOR LEAVE</div>
           <div class="cap">(Non-Teaching Personnel)</div>
       </div>
       <div class="print-sub-header text-center">
           <div class="title fw-bold">NOTRE DAME OF MIDSAYAP COLLEGE</div>
           <div class="sub-title">Midsayap, Cotabato</div>
       </div>
       <div class="print-body">
           <div class="line-1 d-flex">
               <div class="name-cap">
                   Name:
               </div>
               <div class="name-content ps-2">
                   {{ leave_data.signature != null ? leave_data.signature.name : ""}}
               </div>
                 <div class="office-cap">
                   Unit:
               </div>
               <div class="office-content ps-2">
                   {{ leave_data.signature != null ? extractOffice(leave_data.signature) : ""}}
               </div>
           </div>

           <div class="line-2 d-flex">
               <div class="pos-cap">
                   Position:
               </div>
               <div class="pos-content ps-2">
                   {{ leave_data.signature != null ? extractPosition(leave_data.signature) :"" }}
               </div>
                 <div class="doe-cap">
                   Date of Employment:
               </div>
               <div class="doe-content ps-2">
                   {{ leave_data.signature != null ? format(leave_data.signature.date_of_employment) : "" }}
               </div>
           </div>
            <div class="line-3 d-flex">
                <div class="leave-for">
                    Leave applied for: (Please check)
                </div>
            </div>

            <div class="line-4 d-flex">
                <div class="official-content text-center">
                    <i class="bi bi-check text-danger fw-bold" v-if="leave_data.leave_id == 1"></i>
                </div>
                <div class="official-cap">
                    Official
                </div>

                <div class="official-content text-center">
                      <i class="bi bi-check text-danger fw-bold" v-if="leave_data.leave_id == 2"></i>
                </div>
                <div class="official-cap">
                    Maternity
                </div>
                <div class="official-content text-center">
                      <i class="bi bi-check text-danger fw-bold" v-if="leave_data.leave_id == 3"></i>
                </div>
                <div class="official-cap">
                    Absence
                </div>
                <div class="official-content text-center">
                      <i class="bi bi-check text-danger fw-bold" v-if="leave_data.leave_id == 4"></i>
                </div>
                <div class="official-cap">
                    Sick
                </div>
                <div class="official-content text-center">
                    <i class="bi bi-check text-danger fw-bold" v-if="leave_data.leave_id == 5"></i>
                </div>
                <div class="official-cap">
                    Vacation
                </div>
                <div class="official-content text-center">
                      <i class="bi bi-check text-danger fw-bold" v-if="leave_data.leave_id == 7"></i>
                </div>
                <div class="official-cap">
                    Study
                </div>
                <div class="official-content text-center">
                    <i class="bi bi-check text-danger fw-bold" v-if="leave_data.leave_id == 9"></i>  
                </div>
                <div class="official-cap">
                    Others
                </div>
            </div>
            <div class="line-4 d-flex">
               

                
              
                 <div class="official-content text-center">
                    <i class="bi bi-check text-danger fw-bold" v-if="leave_data.leave_id == 6"></i>
                </div>
                <div class="official-cap">
                    Emergency
                </div>
                <div class="official-content text-center">
                    <i class="bi bi-check text-danger fw-bold" v-if="leave_data.leave_id == 8"></i>  
                </div>
                <div class="official-cap">
                    Sabbatical
                </div>
            </div>
            <div class="line-5 d-flex">
                <div class="inc-date-cap">
                    Inclusive Dates: 
                </div>
                 <div class="inc-date-content ps-2">
                    {{ format(leave_data.from)  }} {{ setExten(leave_data.from_ext) }} -
                    {{ format(leave_data.until)  }} {{ setExten(leave_data.unti_ext) }}
                </div>
                 <div class="num-day-cap">
                   No. of days:
                </div>
                 <div class="num-day-content ps-3">
                  {{ leave_data.number_of_day }}
                </div>
            </div>
            <div class="line-6 d-flex">
                <div class="cause-cap">
                    Cause:
                </div>
                <div class="cause-content ps-2">
                    {{ leave_data.cause }}
                </div>
            </div>
            <div class="line-7 d-flex justify-content-between">
                <div class="text-center">
                    <div class="app-cap">
                        {{ format(new Date(leave_data.date_apply)) }}
                    </div>
                    <div class="app-content">
                        Date
                    </div>
                </div>

                <div class="text-center">
                    <div class="app-cap">
                        <img :src="extractDigitSig(leave_data.signature)" class="applicant-sig position-absolute">
                        {{ leave_data.signature != null ? leave_data.signature.name : ""}}
                    </div>
                    <div class="sig-content">
                        Name and Signature of Applicant
                    </div>
                </div>
            </div>
            <div class="line-8">
                <div class="endorse-cap">
                    ACTION TAKEN:
                </div>
            </div>
            <div class="line-9">
                <div class="list-endorse ms-4">
                    <div class="first-line d-flex">
                        <div class="line-content"></div>
                        <div class="line-cap">Recommending approval with pay</div>
                        
                    </div>
                    <div class="first-line d-flex">
                        <div class="line-content"></div>
                        <div class="line-cap">Recommending approval without pay</div>
                        
                    </div>
                    <div class="first-line d-flex">
                        <div class="line-content"></div>
                        <div class="line-cap">has a balance of </div>
                        <div class="line-content ps-2">{{ checkBalance(leave_data.leave_id) }}</div>
                        <div class="line-cap">day</div>
                        
                    </div>
                    <div class="first-line d-flex">
                        <div class="line-content"></div>
                        <div class="line-cap">has exhausted his/her sick/vacation leave privilege</div> 
                    </div>
                    <div class="first-line d-flex">
                        <div class="line-content"></div>
                        <div class="line-cap">Recommending disapproval because </div>
                        <div class="disapproval-content ps-2">{{ leave_data.remarks}}</div>
                        
                    </div>
                </div>
            </div>
            <div class="line-7 d-flex justify-content-between">
                <div class="text-center">
                    <div class="app-cap">
                        {{ leave_data.initial_appr_date !=null ? format(new Date(leave_data.initial_appr_date)) : "" }}
                    </div>
                    <div class="app-content">
                        Date
                    </div>
                </div>

                <div class="text-center">
                    <div class="app-cap">
                        <img :src="extractDigitSig(leave_data.initial)" class="applicant-sig position-absolute">
                        {{ leave_data.initial != null ? leave_data.initial.name : ""}}
                    </div>
                    <div class="sig-content">
                       Name and Signature of Dean/Principal
                    </div>
                </div>
            </div>

            <div class="line-10">
                <div class="taken-cap">
                    ACTION TAKEN:
                </div>
            </div>

             <div class="line-9">
                <div class="list-endorse">
                    <div class="first-line d-flex">
                        <div class="line-content text-center">
                             <i   v-if="leave_data.status == 2"  class="bi bi-check text-danger fw-bold"></i>
                        </div>
                        <div class="line-cap">Approved </div>
                    </div>
                    <div class="first-line d-flex">
                        <div class="line-content text-center">
                             <i v-if="leave_data.status == 3" class="bi bi-check text-danger fw-bold"></i>
                        </div>
                        <div class="line-cap">Disapproved </div>
                    </div>
                </div>
             </div>

            <div class="line-7 d-flex justify-content-between">
                <div class="text-center">
                    <div class="app-cap">
                     {{ leave_data.final_appr_date !=null ? format(new Date(leave_data.final_appr_date)) :"" }}
                    </div>
                    <div class="app-content">
                        Date
                    </div>
                </div>

                <div class="text-center">
                    <div class="app-cap">
                        <img :src="extractDigitSig(leave_data.final)" class="applicant-sig position-absolute">
                          {{ leave_data.final != null ? leave_data.final.name : ""}}
                    </div>
                    <div class="sig-content">
                       HRDM - DIRECTOR
                    </div>
                </div>
            </div>

            <div class="line-11">
                <div class="foot-cap d-flex justify-content-between">
                    <div class="cap-line">cc.</div>
                    <div class="cap-line">Applicant</div>
                    <div class="cap-line">VPAA</div>
                    <div class="cap-line">Finance Officer</div>
                    <div class="cap-line">HR</div>
                </div>
            </div>

       </div>
    </div>
</template>


<style lang="scss" scoped>
$border-line : .5px solid #000;
    .print-leave{
        font-weight: bold;
        margin: 48px 60px !important;
        .print-header{
           line-height: 1.08;
        }
        .print-sub-header{
            margin-top: 20px !important;
            line-height: 1.08;
        }
        .line-1{
            display: table;
            margin-top: 20px !important;
             line-height: 1.08;
            .name-content{
                display: table-cell;
                border-bottom: $border-line;
                margin-left: 2px;
                width: 415px;
            }
            .office-content{
                display: table-cell;
                border-bottom: $border-line;
                margin-left: 2px;
                width: 170px;
            }
    }
    .line-2{
            display: table;
            margin-top: 18px !important;
            line-height: 1.08;
            .pos-content{
                display: table-cell;
                border-bottom: $border-line;
                margin-left: 2px;
                width: 247px;
            }
            .doe-content{
                display: table-cell;
                border-bottom: $border-line;
                margin-left: 3px;
                width: 129px;
            }
    }
    .line-3{
            margin-top: 18px !important;
        display: table;
    }

        .line-4{
            margin-top: 18px !important;
            display: table;
            line-height: 1.08;
            .official-content{
                display: table-cell;
                border-bottom: $border-line;
                margin-left: 3px;
                width: 37px;
            }
        

        }
        .line-5{
            margin-top: 18px !important;
            display: table;
            line-height: 1.08;
            .inc-date-content{
                display: table-cell;
                border-bottom: $border-line;
                margin-left: 2px;
                width: 273px;
            }
            .num-day-content{
                display: table-cell;
                border-bottom: $border-line;
                margin-left: 2px;
                width: 120px;
            }
        }

        .line-6{
            margin-top: 18px !important;
            display: table;
            line-height: 1.08;
            .cause-content{
                display: table-cell;
                border-bottom: $border-line;
                margin-left: 2px;
                width: 586px;
            }
        }

         .line-7{
            margin-top: 54px !important;
            display: table;
            line-height: 1.08;
            .app-content{
                display: table-cell;
                border-top: $border-line;
                margin-left: 2px;
                width: 113px;
            }
             .sig-content{
                display: table-cell;
                border-top: $border-line;
                margin-left: 2px;
                width: 265px;
            }
        }

        
         .line-8{
            margin-top: 20px !important;
            line-height: 1.08;
            display: table;
            .endorse-content{
                display: table-cell;
                border-top: $border-line;
                margin-left: 2px;
                width: 113px;
            }
           
        }
        .line-9{
            margin-top: 20px !important;
            line-height: 1.08;
        }
        .line-10{
            .taken-cap{
                margin-top: 60px !important;
            }
        }
        .line-11{  
            margin-top: 40px !important;
        }

    }

    .applicant-sig{
        margin: 0 auto;
        width: 10rem;
        height: 10rem;
        margin-top: -4.8rem;
        z-index: -99 !important;
    }

    .first-line{
        display: table;
        .line-content{
            display: table-cell;
            border-bottom: $border-line;
            margin-left: 2px;
            width: 57px;
        }
        .disapproval-content{
            display: table-cell;
            border-bottom: $border-line;
            margin-left: 2px;
            width: 250px;
        }
    }
    
</style>

