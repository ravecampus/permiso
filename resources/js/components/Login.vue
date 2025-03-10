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
    <div class="login-body bg-success min-vh-100 m-0">
        <div class="row d-flex justify-content-end">
            <div class="col-md-7 ">  
                <div class="logo w-100">
                    <img :src="'img/login.png'"  class="img-logo"/>
                </div>
                 <div class="">
                    <img :src="'/img/ndmc.png'" class="s-logo"/>
                    <img :src="'/img/cite.png'" class="s-logo-2" />
                </div>

            </div>
            <div class="col-md-5 mt-5">
                <div class="card mt-5 me-5 h-100 bg-login">
                    <div class="card-body ps-5 pe-5">
                        <div class="head text-center text-light mt-5 mb-4">
                            <h1>SIGN IN</h1>
                           
                            <p class="breadcrumb-item text-center mt-1">Please enter your School ID and Password</p>
                          
                        </div>
                        <div class="badge text-center text-light mb-3" v-if="errors.main"> 
                            {{ errors.main[0]}}
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" autocomplete="off" v-model="form.school_id" @keyup.enter.prevent="login" id="inputEmail" type="text" placeholder="Enter School ID" />
                            <label for="inputEmail">
                                <i class="bi bi-person fs-6"></i>
                                SCHOOL ID
                            </label>
                            <span class="text-light" v-if="errors.school_id">{{errors.school_id[0]}}</span>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" v-model="form.password" @keyup.enter.prevent="login" type="password" placeholder="Enter School ID" />
                            
                            <label for="inputEmail">
                                <i class="bi bi-lock fs-6"></i>
                                PASSWORD
                            </label>

                            <span class="text-light" v-if="errors.password">{{errors.password[0]}}</span>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="button" @click="login" class="btn btn-light btn-lg mt-2 text-login">{{ btnlog }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>


    .login-body{
        background-image: url("/img/background.png");
        background-size: cover;
        background-position: center top;
        overflow: hidden;
        height: 100%;
    }

    .img-logo{
        margin-top: 13em;
        margin-left: 10em;
        width: 60%;
    }
    .bg-login{
        background-color: #4a6248;
        border-radius: 2em;
        width: 24em;
    }
    .s-logo{
        margin-top: 3rem;
        width: 4em;
        margin-right: .5em;
        margin-left: 10em;
    }
    .s-logo-2{
        margin-top: 3rem;
        width: 3.6em;
    }
    .text-login{
        color:#4a6248;
    }

</style>

