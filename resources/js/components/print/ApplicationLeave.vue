<template>
    <div class=" print-leave d-none d-print-block row">
       <div class="print-header fw-bold">
           <div class="cap">NDMC FORM 114</div>
           <div class="cap"> APPLICATION FOR LEAVE</div>
           <div class="cap">(Level III Administrators)</div>
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
               <div class="name-content">
Lavwin C. Campollo
               </div>
                 <div class="office-cap">
                   Office:
               </div>
               <div class="office-content">
                   CITE
               </div>
           </div>

           <div class="line-2 d-flex">
               <div class="pos-cap">
                   Position:
               </div>
               <div class="pos-content">
                   President
               </div>
                 <div class="doe-cap">
                   Date of Employment:
               </div>
               <div class="doe-content">
                   12/21/2025
               </div>
           </div>
            <div class="line-3 d-flex">
                <div class="leave-for">
                    Leave applied for: (Please check)
                </div>
            </div>

            <div class="line-4 d-flex">
                <div class="official-content">

                </div>
                <div class="official-cap">
                    Official
                </div>

                <div class="official-content">

                </div>
                <div class="official-cap">
                    Maternity
                </div>
                <div class="official-content">

                </div>
                <div class="official-cap">
                    Absence
                </div>
                <div class="official-content">

                </div>
                <div class="official-cap">
                    Sick
                </div>
                <div class="official-content">

                </div>
                <div class="official-cap">
                    Vacation
                </div>
                <div class="official-content">

                </div>
                <div class="official-cap">
                    Study
                </div>
                <div class="official-content">

                </div>
                <div class="official-cap">
                    Others
                </div>
            </div>
            <div class="line-5 d-flex">
                <div class="inc-date-cap">
                    Inclusive Dates: 
                </div>
                 <div class="inc-date-content">
                   
                </div>
                 <div class="num-day-cap">
                   No. of days:
                </div>
                 <div class="num-day-content">
                  123
                </div>
            </div>
            <div class="line-6 d-flex">
                <div class="cause-cap">
                    Cause:
                </div>
                <div class="cause-content">

                </div>
            </div>

       </div>
    </div>
</template>


<style lang="scss" scoped>
$border-line : .5px solid #000;
    .print-leave{
        font-weight: bold;
        margin: 48px 94px !important;
        .print-header{
           line-height: 1.08;
        }
        .print-sub-header{
            margin-top: 18px !important;
            line-height: 1.08;
        }
        .line-1{
            display: table;
            margin-top: 11x !important;
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
            margin-top: 11px !important;
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
                width: 113px;
            }
    }
    .line-3{
            margin-top: 18px !important;
        display: table;
    }

        .line-4{
            margin-top: 11px !important;
            display: table;
            .official-content{
                display: table-cell;
                border-bottom: $border-line;
                margin-left: 3px;
                width: 37px;
            }
        

        }
        .line-5{
            margin-top: 11px !important;
            display: table;
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
                width: 100px;
            }
        }

        .line-6{
            margin-top: 11px !important;
            display: table;
            .cause-content{
                display: table-cell;
                border-bottom: $border-line;
                margin-left: 2px;
                width: 586px;
            }
        }

    }
    
</style>

