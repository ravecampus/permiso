<script setup>
    import { reactive, ref } from "vue"

    const errors = ref([])
    const btnlog = ref("LOG IN")
    const formdata = ()=>({
        school_id:"",
        password:""
    })

    const form = reactive(formdata())
    const resetform = Object.assign(form, formdata())

    const login = ()=>{
        errors.value = []
        btnlog.value = "LOGGING..."
        axios.post('api/login',form).then((res)=>{
            errors.value = []
            btnlog.value = "LOG IN"
            window.location.href="/"

        }).catch((err)=>{
            btnlog.value = "LOG IN"
            errors.value = err.response.data.errors
        })
    }


</script>

<template>
    <div class="container mt-5">
        <div class="row d-flex justify-content-end">
            <div class="col-md-8 text-center">  
                hello
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="head text-center">
                            <h1>SIGN IN</h1>
                           
                            <p class="breadcrumb-item text-center">Please enter your School ID and Password</p>
                          
                        </div>
                        <div class="badge text-center text-danger mb-3" v-if="errors.main"> 
                            {{ errors.main[0]}}
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" v-model="form.school_id" @keyup.enter.prevent="login" id="inputEmail" type="text" placeholder="Enter School ID" />
                            <label for="inputEmail">
                                <i class="bi bi-person"></i>
                                SCHOOL ID
                            </label>
                            <span class="text-danger" v-if="errors.school_id">{{errors.school_id[0]}}</span>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" v-model="form.password" @keyup.enter.prevent="login" type="password" placeholder="Enter School ID" />
                            
                            <label for="inputEmail">
                                <i class="bi bi-lock"></i>
                                PASSWORD
                            </label>

                            <span class="text-danger" v-if="errors.password">{{errors.password[0]}}</span>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="button" @click="login" class="btn btn-success mt-2">{{ btnlog }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
