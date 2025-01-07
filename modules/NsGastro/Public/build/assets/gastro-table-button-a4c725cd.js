import{g as o}from"./Gastro-b5be35a3.js";import"./gastro-kitchen-settings-c82407e2.js";/* empty css                      */const i={name:"gastro-table-button",template:`
    <div class="ns-button hover-info">
        <button @click="openTableManagement()" type="button" class="flex-shrink-0 h-12 flex items-center shadow rounded px-2 py-1 text-sm">
            <i class="text-xl las la-utensils"></i>
            <span>{{ localization( 'Tables', 'NsGastro' ) }}</span>
        </button>
    </div>
    `,methods:{localization:__m,async openTableManagement(){try{const t=await new Promise((e,a)=>{Popup.show(o,{resolve:e,reject:a,mode:"explore"})})}catch{}}}};export{i as default};
