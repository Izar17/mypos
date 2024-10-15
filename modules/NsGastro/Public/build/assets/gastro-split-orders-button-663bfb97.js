import{a}from"./Gastro-b5be35a3.js";import"./gastro-kitchen-settings-c82407e2.js";/* empty css                      */const l={template:`
    <div class="ns-button">
        <div class="ns-button hover-warning">
            <button @click="openSplitOrderPopup()" class="flex-shrink-0 h-12 flex items-center shadow rounded px-2 py-1 text-sm">
                <i class="text-xl las la-expand-arrows-alt mr-2"></i>
                <span>{{ localization( 'Split Orders', 'NsGastro' ) }}</span>
            </button>
        </div>
    </div>
    `,methods:{localization:__m,async openSplitOrderPopup(){try{const t=await new Promise((o,s)=>{Popup.show(a,{resolve:o,reject:s})})}catch(t){console.log(t)}}}};export{l as default};
