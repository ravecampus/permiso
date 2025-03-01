<script setup>
    import { ref, onMounted } from "vue"

    const user = ref({})

    
    const format = (d) => {
        const day =("0" + d.getDate()).slice(-2);
        const month = ("0"+(d.getMonth()+1)).slice(-2);
        const year =  d.getFullYear();

        return  month+ "/"+day+"/" + year;
    }

    onMounted(()=>{
        getAuthUser()
    })

    const getAuthUser = ()=>{
        axios.get("api/user").then((res)=>{
            user.value = res.data
        })
    }

    const extractRole = (data)=>{
        return data == 1 ? "ADMIN" : data == 2 ? "SCHOOL PRESIDENT": data == 3 ? "OFFICE HEAD" : data == 4 ? "FACULTY / STAFF" :" NONE"
    }
</script>

<template>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10 d-flex justify-content-between">
                        <div class="profile-pic me-4">
                            <img class="img-pic" :src="'/img/permiso.png'"/>
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
                    <div class="col-md-2">
                        <div class="btn-group">
                            <button type="button" class="btn btn-success btn-sm">Change Name</button>
                            <button type="button" class="btn btn-secondary btn-sm">Change Passord</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    .img-pic{
        width: 8rem;
        border: 1px solid #225a26;
        border-radius: 5px;
    }
</style>

