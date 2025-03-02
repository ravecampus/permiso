<script setup>
    import { ref,reactive, onMounted, watch } from "vue"
    import { Modal } from "bootstrap";

    const modalposition = ref(null)
    let vmodalposition = null;
    let errors = ref([])

    const btnCap = ref("Save")
    const listData = ref([])
    const listLeaves = ref([])
    let links = ref([])
    let leaves = ref([])
    let searchData = ref('')
    
    const format = (d) => {
        const day =("0" + d.getDate()).slice(-2);
        const month = ("0"+(d.getMonth()+1)).slice(-2);
        const year =  d.getFullYear();

        return  month+ "/"+day+"/" + year;
    }

    const datefrom = ref(new Date())
    const dateto = ref(new Date())

    onMounted(()=>{
        getData()
        getLeave()
    })

    const formdata = ()=>({
        datefrom: "",
        dateto: "",
        filter:0
    })

    const form = reactive(formdata())
    const resetform = ()=> Object.assign(form, formdata())


    const getData = ()=>{
        axios.post('/api/leave-report', form).then((res)=>{
            listLeaves.value = res.data.print
            listData.value = res.data.table.data
            links.value = res.data.table.links
        })
    }

    const changePage = (link)=>{
        if(!link.url || link.active){
            return
        }
        axios.get(link.url).then((res)=>{
            listData.value = res.data.table.data
            links.value = res.data.table.links
        })
    }
    watch(searchData, ()=>{
		getData()
	})

    const noData = (data)=>{
        return data == undefined ? true : (data.length > 0) ? true : false;
    }

    const filterReport = ()=>{
        getData()
    }

     const getLeave = () => {
         axios.get('/api/leave').then((res)=>{
            leaves.value = res.data
        })
    }

     const setExten = (data)=>{
        return data == 1 ? "AM" : "PM"
    }

    const setStatus = (data)=>{
        return data == 0 ? "Pending" : data == 1 ? "Initial Approved" : data == 2 ? "Final Approved" :"Disapproved"
    }

    const printReport = ()=>{
        window.print()
    }


</script>

<template>
    <div class="container">
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
        <h4 class="mt-5">LEAVE REPORT</h4>
        <ol class="breadcrumb mb-4 d-print-none">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="row">
            <div class="col-md-9 d-print-none">
                <div class="row card">
                    <div class="col-md-12 card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="text-uppercase text-muted">
                                    <tr>
                                        <th>DATE REQUEST</th>
                                        <th>NAME</th>
                                        <th>LEAVE TYPE</th>
                                        <th>FROM</th>
                                        <th>UNTIL</th>
                                        <th>NO. OF DAY/S</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(list, index) in listData" :key="index">
                                        <td class="text-muted">{{ format(new Date(list.date_apply)) }}</td>
                                        <td>{{ list.signature.name }}</td>
                                        <td>{{ list.description }}</td>
                                        <td>
                                            {{ format(new Date(list.from)) }}
                                            {{ setExten(list.from_ext) }}
                                        </td>
                                        <td>{{ format(new Date(list.until)) }}
                                            {{ setExten(list.until_ext) }}
                                        </td>
                                        <td class="text-success fw-bold">{{ list.number_of_day }}</td>
                                        <td>{{ setStatus(list.status) }}</td>
                                        
                                    </tr>
                                    <tr>
                                        <td colspan="2" v-show="!noData(listData)">
                                            No Result Found!
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <nav class="mt-2 d-flex justify-content-start ms-1" aria-label="..." v-if="noData(listData)">
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
                            </nav>
                        </div>

                    </div>

                </div>
            </div>
            <div class="col-md-3 d-print-none">
                <div class="card">
                    <div class="card-body">
                        <h5>Filter</h5>
                         <!-- <div class="d-flex justify-content-between mb-5">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search..." aria-label="Example text with button addon" v-model="searchData" aria-describedby="button-addon1">
                                <button class="btn btn-success" type="button" id="button-addon1">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div> -->
                        <!-- <div class="d-flex justify-content-between mb-2"> -->
                            <div class="form-group me-1 mb-3">
                                <label>FROM</label>
                                <VueDatePicker v-model="form.datefrom" :format="format"></VueDatePicker>
                            </div>
                            <div class="form-group mb-5">
                                <label>TO</label>
                                <VueDatePicker v-model="form.dateto" :format="format"></VueDatePicker>
                            </div>
                        <!-- </div> -->

                        <div class="form-group mt-3 mb-5">
                            <label>LEAVE TYPE</label>
                            <select class="form-control" v-model="form.filter" >
                                <option :value="0">ALL</option>
                                <option v-for="(list, index) in leaves" :key="index" :value="list.id">{{ list.description }}</option>
                            </select>
                        </div>
                        
                        <button type="button" @click="filterReport()" class="btn btn-success me-2">
                            <i class="bi bi-funnel"></i>
                            Filter
                        </button>
                        <button type="button" @click="printReport()" class="btn btn-outline-success">
                            <i class="bi bi-printer"></i>
                            Print
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-12 d-none d-print-block">
                <div>
                  Date :  {{ format(new Date(form.datefrom)) }} - {{ format(new Date(form.dateto)) }}
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="text-uppercase text-muted">
                            <tr>
                                <th>DATE REQUEST</th>
                                <th>NAME</th>
                                <th>LEAVE TYPE</th>
                                <th>FROM</th>
                                <th>UNTIL</th>
                                <th>NO. OF DAY/S</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list, index) in listLeaves" :key="index">
                                <td class="text-muted">{{ format(new Date(list.date_apply)) }}</td>
                                <td>{{ list.signature.name }}</td>
                                <td>{{ list.description }}</td>
                                <td>
                                    {{ format(new Date(list.from)) }}
                                    {{ setExten(list.from_ext) }}
                                </td>
                                <td>{{ format(new Date(list.until)) }}
                                    {{ setExten(list.until_ext) }}
                                </td>
                                <td class="text-success fw-bold">{{ list.number_of_day }}</td>
                                <td>{{ setStatus(list.status) }}</td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p>Print date: {{ format(new Date()) }}</p>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    .print-logo{
        width:4.5rem;
    }
</style>
