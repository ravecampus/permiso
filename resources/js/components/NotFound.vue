<script setup>
    const reload =()=>{
        window.location.href="/";
    }
</script>

<template>
    <div class="container page-notfound">
        <div class="card">
            <div class="card-body text-center">
                <h1 class="font-title text-success">Page not found!</h1>
                <img :src="'/img/permiso.png'" class="img-logo"/>
                <p class="sub-font">PERMISO</p>
                <button type="button" @click="reload()" class="btn btn-outline-success btn-sm">
                <i class="bi bi-arrow-clockwise"></i>
                Reload
            </button>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    .page-notfound{
        margin-top: 5em;
    }

    .font-title{
        font-size: 3em;
    }
    .sub-font{
        font-size: 1.2em !important;
    }

    .img-logo{
        width: 8rem;
    }
</style>
