<script setup>
    import { ref, reactive, onMounted, watch } from "vue"
    import { useRouter, useRoute } from "vue-router"

    const leaves = ref([])
    const links = ref([])
    const searchData = ref("")

    const router = useRouter()

    const formdata = ()=>({
        description:"",
        id:null,
    })

    const format = (d) => {
        const day =("0" + d.getDate()).slice(-2);
        const month = ("0"+(d.getMonth()+1)).slice(-2);
        const year =  d.getFullYear();

        return  month+ "/"+day+"/" + year;
    }
    const form = reactive(formdata())
    const resetform = ()=> Object.assign(form, formdata())

    onMounted(()=>{
        getMyLeaves()
    })

    const getMyLeaves = ()=>{
        axios.get("api/my-leave").then((res)=>{
            leaves.value = res.data.data
            links.value = res.data.links
        })
    }

    const changePage = (link)=>{
        if(!link.url || link.active){
            return
        }
        axios.get(link.url).then((res)=>{
            leaves.value = res.data.data
            links.value = res.data.links
        })
    }
    watch(searchData, ()=>{
		// getData()
	})

    const noData = (data)=>{
        return data == undefined ? true : (data.length > 0) ? true : false;
    }

    const setExten = (data)=>{
        return data == 1 ? "AM" : "PM"
    }

    const extractSY = (data)=>{
        if(data != null){
            return data.start+" - "+data.end

        }
    }

    const setStatus = (data)=>{
        return data == 0 ? "Pending" : data == 1 ? "Initial Approved" : data == 2 ? "Final Approved" :"Disapproved"
    }

    const editLeave = (data)=>{
        router.push({name:'leaveapplication_edit', params:{id:data.emp_class_id, leave_id:data.id}})
    }

    const deleteLeave = (data)=>{
           Swal.fire({
            title: "Do you want to delete "+data.description+" ?",
            text: "Are you sure!",
            icon: "warning",
            showCancelButton: true,
            background: '#17a673',
            color: '#fff',
            confirmButtonColor: "#424242",
            cancelButtonColor: "#ffc107",
            confirmButtonText: "YES!"
            }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/api/leave-application/${data.id}`).then((res)=>{
                    toast.fire({
                        title: "REMOVED!",
                        text: "Leave application has been removed!",
                        icon: "success",
						confirmButtonColor: "#26884b",
                    });
                   getMyLeaves();
                })
                
            }
        });
    }

    const viewLeave = (data)=>{
        router.push({name:"viewleave", params:{id:data.id}})
    }

</script>
<template>
    <div class="container">
          <h4 class="mt-4">MY LEAVE</h4>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Provide information about your leave status</li>
        </ol>
        <div class="row card">
            <div class="col-md-12 card-body">
                <div class="d-flex justify-content-between mb-5">
                    <!-- <button type="button" class="btn btn-success ms-2" >
                        <i class="bi bi-plus-circle"></i>
                        Add
                    </button> -->
                    <!-- <div class="input-group w-50">
                        <input type="text" class="form-control" placeholder="Search..." aria-label="Example text with button addon" v-model="searchData" aria-describedby="button-addon1">
                        <button class="btn btn-success" type="button" id="button-addon1">
                            <i class="bi bi-search"></i>
                        </button>
                    </div> -->
                </div>
              
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead class="text-uppercase text-muted">
                            <tr>
                                <th>LEAVE TYPE</th>
                                <th>DATE APPLY</th>
                                <th>INCLUSIVE DATE</th>
                                <th># DAY/S</th>
                                <th>SY</th>
                                <th>STATUS</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list, index) in leaves" :key="index">
                                <td class="text-muted fw-bold">{{ list.description }}</td>
                                <td>{{ format(new Date(list.date_apply)) }}</td>
                                <td>
                                    {{ format(new Date(list.from))+" "+setExten(list.from_ext) }} -
                                    {{ format(new Date(list.until))+" "+setExten(list.until_ext) }}

                                </td>
                                <td>{{ list.number_of_day }}</td>
                                <td>
                                    {{ extractSY(list.schoolyear) }}
                               

                                </td>
                                <td class="text-center text-success">{{ setStatus(list.status) }}</td>
                                <td class="text-muted">
                                    <div class="btn-group">
                                        <button type="button" v-if="list.status == 0" @click="editLeave(list)" class="btn btn-success btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" @click="viewLeave(list)" class="btn btn-light btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button"  v-if="list.status == 0" class="btn btn-secondary btn-sm" @click="deleteLeave(list)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                             <tr>
                                <td colspan="7" v-show="!noData(leaves)">
                                    No Result Found!
                                </td>
                            </tr>
                        </tbody>
                    </table>
                     <nav class="mt-2 d-flex justify-content-start ms-1" aria-label="..." v-if="noData(leaves)">
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

                <!-- modal -->
<!--                 
                <div class="modal fade" ref="modalposition" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">POSITION</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start mb-3 row">
                           <div class="form-group">
                               <label>DESCRIPTION</label>
                               <input type="text" class="form-control" v-model="form.description">
                               <span class="text-danger" v-if="errors.description">{{errors.description[0]}}</span>
                           </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" @click="savePosition()">{{ btnCap }}</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                        </div>
                    </div>
                </div> -->

            </div>
        </div>
    </div>
</template>
