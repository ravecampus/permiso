<script setup>
    import { ref,reactive, onMounted, watch } from "vue"
    import { Modal } from "bootstrap";

    const modalposition = ref(null)
    let vmodalposition = null;
    let errors = ref([])

    const btnCap = ref("Save")
    const listData = ref([])
    const filter = ref("")
    let links = ref([])
    let searchData = ref('')
    let offices = ref([])

    onMounted(()=>{
        getData()
        getOffice()
    })

    const getData = ()=>{
        axios.get('/api/employee-print?filter='+filter.value).then((res)=>{
            listData.value = res.data

        })
    }

    const getOffice = () =>{
        axios.get('api/office-list').then((res)=>{
            offices.value = res.data
        })
    }

    const changePage = (link)=>{
        if(!link.url || link.active){
            return
        }
        axios.get(link.url).then((res)=>{
            listData.value = res.data.data
            links.value = res.data.links
        })
    }
    watch(searchData, ()=>{
		getData()
	})

    const noData = (data)=>{
        return data == undefined ? true : (data.length > 0) ? true : false;
    }

    const filterData = ()=>{
        getData();
    }

    const printData = ()=>{
        window.print()
    }

    const format = (d) => {
        const day =("0" + d.getDate()).slice(-2);
        const month = ("0"+(d.getMonth()+1)).slice(-2);
        const year =  d.getFullYear();

        return  month+ "/"+day+"/" + year;
    }

</script>

<template>
    <div class="container">
        <h4 class="mt-4 d-print-none">PRINT EMPLOYEE</h4>
        <!-- <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol> -->
        <div class="d-none d-print-block">
        <div class="print-header d-flex justify-content-around mt-4">
            <img class="print-logo me-3" :src="'/img/ndmc.png'" />
            <div class="print-caption text-center">
              
                <div class="text-success fw-bold fs-5"> Notre Dame of Midsayap College</div>
                <div> Midsayap, Cotabato</div>
            </div>
             <img class="print-logo me-3" :src="'/img/permiso.png'" />
        </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between mb-5 d-print-none">
                   
                    <div class="w-75">
                        <div class="input-group">
                            <select class="form-control" v-model="filter" @change="filterData(filter)">
                                <option value="0">All</option>
                                <option v-for="(list, index) in offices" :key="index" :value="list.id">{{ list.description }}</option>
                            </select>
                            <button class="btn btn-success" type="button" id="button-addon1">Office 
                                <i class="bi bi-funnel"></i>
                            </button>
                        </div>
                    </div>
                   <div>
                    <button type="button" @click="printData()" class="btn btn-success ms-3">
                        <i class="bi bi-printer"></i>
                        Print
                    </button>
                   </div>
                    
                </div>
                <h6 class="d-none d-print-block mt-5"> LIST OF EMPLOYEE</h6>
              
                <div class="table-responsive mt-2">
                    <table class="table table-sm table-bordered">
                        <thead class="text-uppercase text-muted">
                            <tr>
                                <th>SCHOOL ID</th>
                                <th>NAME</th>
                                <th>POSITION</th>
                                <th>OFFICE</th>
                                <th>DATE OF EMPLOYMENT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list, index) in listData" :key="index">
                                <td class="text-success fw-bold">{{ list.school_id }}</td>
                                <td class="fw-bold">{{ list.name }}</td>
                                <td>{{list.position != null ? list.position.description :"" }}</td>
                                <td>{{list.office != null ? list.office.description :"" }}</td>
                                <td>{{ format(new  Date(list.date_of_employment)) }}</td>
                                
                            </tr>
                           
                        </tbody>
                    </table>
                     <!-- <nav class="mt-2 d-flex justify-content-start ms-1" aria-label="..." v-if="noData(listData)">
                        <ul class="pagination pagination-sm custom-page bg-success">
                            <li class="page-item" aria-current="page" v-for="(link, index) in links" :key="index">
                                <a class="page-link"
                                href="#"
                            
                                v-html="link.label"
                                :class="{active:link.active, disabled: !link.url }"

                                @click="changePage(link)"
                                ></a>
                            </li>
                        </ul>
                    </nav> -->
                </div>
                 <p class="d-none d-print-block">Print date: {{ format(new Date()) }}</p>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .print-logo{
        width:4.5rem;
    }

    // @page {     
    //     margin: 0 !important;
    //     .container{
    //         margin:0 !important;
    //     }
    // }

    @media print {
         .container{
             margin: 0 !important;
            align-content: center;
         }
    }
</style>

