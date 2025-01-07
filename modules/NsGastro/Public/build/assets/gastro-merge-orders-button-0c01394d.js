import{b as s}from"./Gastro-b5be35a3.js";import"./gastro-kitchen-settings-c82407e2.js";/* empty css                      */const i={template:`
    <div class="ns-button hover-warning">
        <button @click="openMergeOrderPopup()" class="flex-shrink-0 h-12 flex items-center shadow rounded px-2 py-1 text-sm">
            <i class="text-xl las la-compress-arrows-alt mr-2"></i>
            <span class="ml-1">{{ localization( 'Merge Orders', 'NsGastro' ) }}</span>
            <span v-if="orderSelected > 0" class="h-6 w-6 ml-1 justify-center rounded-full flex items-center bg-info-tertiary text-white fond-bold">{{ orderSelected }}</span>
        </button>
    </div>
    `,mounted(){this.orderSelectedSubscription=Gastro.selectedOrdersSubject.subscribe(e=>{this.orderSelected=e.length})},beforeUnmount(){this.orderSelectedSubscription.unsubscribe()},data(){return{orderSelected:0,orderSelectedSubscription:null}},methods:{localization:__m,async openMergeOrderPopup(){try{const e=await new Promise((t,r)=>{Popup.show(s,{resolve:t,reject:r})})}catch(e){console.log(e)}}}};export{i as default};
