<script setup>
    const reload =()=>{
        window.location.reload();
    }
</script>

<template>
    <div class="container page-notfound">
        <h1 class="font-title">Page not found!</h1>
        <p class="sub-font">PERMISO</p>
          <button type="button" @click="reload()" class="btn btn-outline-success btn-sm">
            <i class="bi bi-arrow-clockwise"></i>
            Reload
            </button>
    </div>
</template>
<style lang="scss" scoped>
    .page-notfound{
        margin-top: 15em;
    }

    .font-title{
        font-size: 3em;
    }
    .sub-font{
        font-size: 1.2em !important;
    }
</style>
