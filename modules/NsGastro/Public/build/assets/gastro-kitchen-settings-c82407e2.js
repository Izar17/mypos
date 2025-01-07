const e={name:"gastro-kitchen-settings",template:`
    <div class="w-95vw md:w-3/5-screen lg:w-2/5-screen shadow-xl ns-box">
        <div class="p-2 flex items-center border-b ns-box-header justify-between">
            <div class="h3 font-semibold">{{ title }}</div>
            <div>
                <ns-close-button @click="popupResolver( false )"></ns-close-button>
            </div>
        </div>
        <div class="p-2">
            <ns-field v-for="( field, index ) of fields" :key="index" :field="field"></ns-field>
        </div>
        <div class="p-2 border-t ns-box-body flex justify-between">
            <div></div>
            <div>
                <ns-button @click="saveForm()" type="info">{{ localization( 'Save', 'NsGastro' ) }}</ns-button>
            </div>
        </div>
    </div>
    `,props:["popup"],data(){return{validation:new FormValidation,fields:[]}},computed:{title(){return this.popup.params.title||this.localization("Untitled Popup","NsGastro")}},mounted(){this.fields=this.validation.createFields(this.popup.params.fields),this.popupCloser()},methods:{localization:__m,popupResolver,popupCloser,saveForm(){this.popupResolver(this.validation.extractFields(this.fields))},closePopup(){this.popupResolver(!1)}}};export{e as g};
