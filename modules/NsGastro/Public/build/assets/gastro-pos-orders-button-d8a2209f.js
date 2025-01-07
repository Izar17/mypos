const n={name:"gastro-pos-ready-meals",template:`
    <div class="w-95vw h-95vh ns-box flex flex-col shadow-xl md:w-3/5-screen md:h-4/5-screen overflow-hidden">
        <div class="border-b ns-box-body p-2 flex justify-between items-center">
            <h3 class="font-semibold">{{ localization( 'Ready Meals', 'NsGastro' ) }}</h3>
            <div>
                <ns-close-button @click="popupResolver( false )"></ns-close-button>
            </div>
        </div>
        <div v-if="loaded && response.data.length === 0  " class="flex flex-auto justify-center items-center flex-col text-primary">
            <i class="go-text-9xl las la-laugh-wink"></i>
            <span>{{ localization( 'Looks like there is nothing to worry about.', 'NsGastro' ) }}</span>
        </div>
        <div class="overflow-y-auto flex-auto" v-if="loaded && response.data.length > 0">
            <table class="w-full ns-table">
                <thead>
                    <tr>
                        <th width="300" class="p-2 border text-left">{{ localization( 'Product', 'NsGastro' ) }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr :key="meal.id" v-for="meal of response.data" @click="serveMeal( meal )">
                        <td class="p-2 cursor-pointer border-b">
                            <h3 class="font-semibold">{{ meal.name }} (x{{ meal.quantity }})</h3>
                            <div class="grid grid-cols-2 text-sm">
                                <div>{{ localization( 'Placed By', 'NsGastro' ) }} : {{ meal.meal_placed_by_name || this.localization( 'N/A', 'NsGastro' ) }}</div>
                                <div>{{ localization( 'Order', 'NsGastro' ) }} : {{ meal.order.code }}</div>
                                <div>{{ localization( 'Table', 'NsGastro' ) }} : {{ meal.order.table_name || this.localization( 'N/A', 'NsGastro' ) }}</div>
                                <div>{{ localization( 'Type', 'NsGastro' ) }} : {{ meal.order.type }}</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="! loaded" class="overflow-y-auto flex-auto flex items-center justify-center">
            <ns-spinner></ns-spinner>
        </div>
        <div class="p-2 flex justify-between items-center ns-box-footer border-t">
            <div>
                <div v-if="response !== null" class="rounded-lg overflow-hidden flex">
                    <div class="ns-button hover-info" :key="index" v-for="(link,index) of response.links">
                        <button v-if="![ 'pagination.previous', 'pagination.next' ].includes( link.label )"  @click="gotToPage( link )" class="border rounded-lg text-sm mx-1 px-2 py-1" v-html="link.label"></button>
                    </div>
                </div>
            </div>
            <div class="go-flex go-flex-col md:go-flex-row md:-go-mx-2">
                <div class="md:go-px-2">
                    <ns-button @click="markAllServed()" type="info">{{ localization( 'Mark All Served', 'NsGastro' ) }}</ns-button>
                </div>
                <div class="md:go-px-2">
                    <ns-button @click="markListedAsServed()" type="info">{{ localization( 'Listed As Served', 'NsGastro' ) }}</ns-button>
                </div>
            </div>
        </div>
    </div>
    `,props:["popup"],mounted(){this.popupCloser(),this.getReadyMeals()},data(){return{response:null,prevPage:null,nextPage:null,loaded:!1}},methods:{localization:__m,popupCloser,popupResolver,async markAllServed(){try{const e=await new Promise((s,o)=>{Popup.show(nsConfirmPopup,{resolve:s,reject:o,title:this.localization("Mark All As Served ?","NsGastro"),message:this.localization("All ready meals will be marked as served.","NsGastro"),onAction:r=>{r&&nsHttpClient.get("/api/gastro/products/serve-all").subscribe(t=>{nsSnackBar.success(t.message).subscribe()},t=>{nsSnackBar.error(t.message||this.localization("An unexpected error occured.","NsGastro")).subscribe()})}})})}catch(e){console.log(e)}},async serveMeal(e){try{const s=await new Promise((o,r)=>{Popup.show(nsConfirmPopup,{resolve:o,reject:r,title:this.localization("Would You Mark As Served ?","NsGastro"),message:this.localization("The meal will be marked as served. Please confirm your action.","NsGastro"),onAction:t=>{t&&nsHttpClient.post(`/api/gastro/products/${e.id}/serve`).subscribe(a=>{const l=this.response.links.filter(i=>parseInt(i.label)===parseInt(this.response.current_page));l.length===1?this.gotToPage(l[0]):this.getReadyMeals(),nsSnackBar.success(a.message).subscribe()},a=>{nsSnackBar.error(a.message||this.localization("An unexpected error occured.","NsGastro")).subscribe()})}})})}catch(s){console.log(s)}},async markListedAsServed(){if(this.response.data.length===0)return nsSnackBar.error(this.localization("There is nothing to mark as served.","NsGastro")).subscribe();try{const e=await new Promise((s,o)=>{const r=this.response.data.map(t=>t.id);Popup.show(nsConfirmPopup,{resolve:s,reject:o,title:this.localization("Confirm Yout Action ?","NsGastro"),message:this.localization("Would you like to mark all listed products as served ?","NsGastro"),onAction:t=>{t&&nsHttpClient.post("/api/gastro/products/serve",{products:r}).subscribe(a=>{const l=this.response.links.filter(i=>parseInt(i.label)===parseInt(this.response.current_page));l.length===1?this.gotToPage(l[0]):this.getReadyMeals(),nsSnackBar.success(a.message).subscribe()},a=>{nsSnackBar.error(a.message||this.localization("An unexpected error occured.","NsGastro")).subscribe()})}})})}catch(e){console.log(e)}},getReadyMeals(){this.loaded=!1,nsHttpClient.get("/api/gastro/products/ready").subscribe(e=>{this.loaded=!0,this.response=e},e=>{nsSnackBar.error(e.message||this.localization("An unexpected error occured.","NsGastro")).subscribe()})},gotToPage(e){e.url!==null&&nsHttpClient.get(e.url).subscribe(s=>{this.loaded=!0,this.response=s},s=>{nsSnackBar.error(s.message||this.localization("An unexpected error occured.","NsGastro")).subscribe()})}}},d={name:"gastro-pos-orders-button",data(){return{readyMeals:0}},template:`
    <div class="ns-button hover-info">
        <button @click="openReadyOrder()" class="relative flex-shrink-0 h-12 flex items-center shadow rounded px-2 py-1 text-sm ">
            <i class="text-xl las la-check-circle"></i>
            <span class="ml-1">{{ localization( 'Ready Meals', 'NsGastro' ) }}</span>
            <span class="h-6 w-6 ml-1 justify-center rounded-full flex items-center bg-info-tertiary text-white fond-bold">{{ readyMeals }}</span>
        </button>
    </div>
    `,methods:{localization:__m,async openReadyOrder(){try{const e=await new Promise((s,o)=>{Popup.show(n,{resolve:s,reject:o})})}catch{}},getReadyMealCount(){nsHttpClient.get("/api/gastro/products/count-ready").subscribe(e=>{this.readyMeals=e.readyMeals})}},mounted(){this.getReadyMealCount(),typeof Echo<"u"?Echo.channel("default-channel").listen("Modules\\NsGastro\\Events\\KitchenAfterUpdatedOrderEvent",e=>{console.log(e),this.getReadyMealCount()}):setInterval(()=>{this.getReadyMealCount()},1e4)}};export{d as default};
