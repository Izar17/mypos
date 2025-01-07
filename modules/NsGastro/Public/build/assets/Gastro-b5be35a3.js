var g=Object.defineProperty;var x=(e,s,t)=>s in e?g(e,s,{enumerable:!0,configurable:!0,writable:!0,value:t}):e[s]=t;var c=(e,s,t)=>(x(e,typeof s!="symbol"?s+"":s,t),t);import{g as y}from"./gastro-kitchen-settings-c82407e2.js";/* empty css                      */const w="modulepreload",_=function(e){return"/modules/nsgastro/build/"+e},f={},h=function(s,t,o){if(!t||t.length===0)return s();const r=document.getElementsByTagName("link");return Promise.all(t.map(i=>{if(i=_(i),i in f)return;f[i]=!0;const a=i.endsWith(".css"),l=a?'[rel="stylesheet"]':"";if(!!o)for(let u=r.length-1;u>=0;u--){const p=r[u];if(p.href===i&&(!a||p.rel==="stylesheet"))return}else if(document.querySelector(`link[href="${i}"]${l}`))return;const d=document.createElement("link");if(d.rel=a?"stylesheet":w,a||(d.as="script",d.crossOrigin=""),d.href=i,document.head.appendChild(d),a)return new Promise((u,p)=>{d.addEventListener("load",u),d.addEventListener("error",()=>p(new Error(`Unable to preload CSS for ${i}`)))})})).then(()=>s()).catch(i=>{const a=new Event("vite:preloadError",{cancelable:!0});if(a.payload=i,window.dispatchEvent(a),!a.defaultPrevented)throw i})},G={name:"gastro-keyboard",template:`
    <div class="shadow-lg ns-box w-95vw md:w-2/5-screen">
        <div class="border-b ns-box-header p-2 flex go-items-center go-justify-between">
            <h3>{{ localization( 'Define Quantity:', 'NsGastro' ) }} {{ modifier.name }}</h3>
            <ns-close-button @click="closePopup()"></ns-close-button>
        </div>
        <div class="ns-box-body">
            <div class="text-3xl flex justify-end p-2">{{ modifier.quantity }}</div>
        </div>
        <div>
            <component  v-bind:is="keyboardComponent()" :value="modifier.quantity" @next="saveQuantity( $event )" @changed="updateModifierQuantity( $event )"></component>
        </div>
    </div>
    `,data(){return{keyboardComponent:()=>nsComponents.nsNumpad}},props:["popup"],methods:{localization:__m,closePopup(){this.popup.params.reject(!1),this.popup.close()},updateModifierQuantity(e){this.modifier.quantity=e,this.$forceUpdate()},saveQuantity(e){parseFloat(e)>0?(this.modifier.quantity=parseFloat(this.modifier.quantity),this.popup.close(),this.popup.params.resolve(this.modifier)):nsSnackBar.error(this.localization("Invalid quantity provided.","NsGastro")).subscribe()}},computed:{modifier(){return this.popup.params.modifier}}},O={name:"gastro-modifier-group",template:`
    <div class="shadow-lg ns-box h-95vh md:h-4/5-screen w-95vw md:w-3/5-screen flex flex-col">
        <div class="p-2 border-b ns-box-header flex justify-between items-center" v-if="modifierGroup !== null">
            <h3>{{ localization( 'Modifier:', 'NsGastro' ) }} {{ modifierGroup.name }}</h3>
            <ns-close-button @click="close()"></ns-close-button>
        </div>
        <div class="flex-auto flex items-center justify-center" v-if="modifierGroup === null">
            <ns-spinner></ns-spinner>
        </div>  
        <div class="overflow-hidden flex-auto flex flex-col" v-if="modifierGroup !== null">
            <div class="m-2 p-2 ns-notice success text-center">
                <p>{{ modifierGroup.description || 'No description provided.' }}</p>
            </div>
            <div class="flex-auto overflow-y-auto">
                <div class="go-grid go-grid-cols-4 go-flex-wrap">
                    <div @click="select( modifier )" :class="modifier.selected ? 'info' : ''" class="cursor-pointer border border-box-edge go-h-44 md:go-h-56" :key="modifier.id" v-for="modifier of modifierGroup.modifiers">
                        <div class="relative h-full w-full flex items-center justify-center overflow-hidden">
                            <div v-if="modifier.quantity > 0" class="flex items-center justify-center text-white absolute right-4 top-4 rounded-full h-8 w-8 bg-info-secondary font-bold">{{ modifier.quantity }}</div>
                            <img v-if="modifier.galleries[0]" :src="modifier.galleries[0].url" class="object-cover h-full" :alt="modifier.name">
                            <i class="las la-image text-secondary text-6xl" v-if="! modifier.galleries[0]"></i>
                        </div>
                        <div class="h-0 w-full">
                            <div class="relative w-full flex items-center justify-center -top-10 h-20 py-2 flex-col modifier-item">
                                <h3 class="font-bold text-primary py-2 text-center">{{ modifier.name }}</h3>
                                <span class="text-xs font-bold text-secondary py-1 text-center">{{ nsCurrency( modifier.unit_quantities[0].sale_price ) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t ns-box-footer border-gray p-2 flex justify-between items-center">
                <div></div>
                <div>
                    <ns-button @click="nextStep()" type="info">{{ localization( 'Continue', 'NsGastro' ) }}</ns-button>
                </div>
            </div>
        </div>      
    </div>
    `,props:["popup"],mounted(){this.loadModifierGroup()},data(){return{modifierGroup:null}},methods:{localization:__m,nsCurrency,select(e){if(!this.modifierGroup.multiselect){const s=this.modifierGroup.modifiers.indexOf(e);this.modifierGroup.modifiers.forEach((t,o)=>{o!==s&&(t.selected=!1,t.quantity=0)})}e.selected=!e.selected,this.modifierGroup.countable?e.selected?new Promise(async(s,t)=>{try{e.quantity=1,e=await Popup.show(G,{resolve:s,reject:t,modifier:e,product:this.popup.params.product})}catch(o){console.log(o),e.selected=!1}}):e.quantity=0:e.selected?e.quantity=1:e.quantity=0},loadModifierGroup(){nsHttpClient.get(`/api/gastro/modifiers-groups/${this.popup.params.modifierGroupId}`).subscribe(e=>{e.modifiers=e.modifiers.map(s=>{s.modifier_id=s.id,delete s.id;let t=[];if(this.popup.params.product.modifiersGroups){const o=this.popup.params.product.modifiersGroups.filter(r=>r.modifier_group_id===this.popup.params.modifierGroupId);o.length>0&&(t=o[0].modifiers.filter(r=>r.modifier_id===s.modifier_id))}return s.selected=t.length===0?!1:t[0].selected,s.quantity=t.length===0?0:t[0].quantity,s}),this.modifierGroup=e},e=>{nsSnackBar.error(e.message||"An unexpected error has occured.").subscribe()})},nextStep(){const e=this.modifierGroup;if(this.modifierGroup.modifiers.filter(s=>s.selected).length===0&&parseInt(e.forced)===1)return nsSnackBar.error("You must select a modifier before proceeding.").subscribe();if(this.modifierGroup.modifiers.filter(s=>s.selected).length>0&&parseInt(e.countable)===1&&parseInt(e.forced)===1&&this.modifierGroup.modifiers.map(t=>t.quantity).reduce((t,o)=>t+o)<=0)return nsSnackBar.error("The current modifier group is require modifier with valid quantities.").subscribe();e.modifier_group_id=e.id,e.modifiers=e.modifiers.filter(s=>s.selected),e.modifiers.forEach(s=>{s.unit_price=s.unit_quantities[0].sale_price,s.unit_quantity_id=s.unit_quantities[0].id,s.unit_id=s.unit_quantities[0].unit_id,s.total_price=s.unit_quantities[0].sale_price*s.quantity}),delete e.id,this.popup.params.resolve(e),this.popup.close()},close(){this.popup.params.reject(!1),this.popup.close()}}};class b{constructor(s){this.product=s}run(s){return new Promise(async(t,o)=>{const r=this.product;if(r.$original().gastro_item_type==="product"&&r.$original().modifiers_groups.length>0){const i=JSON.parse(r.$original().modifiers_groups),a=new Array;for(let l in i)try{a.push(await this.loadModifier(i[l],r))}catch(n){return o(n)}return t({modifiersGroups:a})}return t({})})}loadModifier(s,t){return new Promise((o,r)=>{Popup.show(O,{resolve:o,reject:r,product:t,modifierGroupId:s})})}computeProduct(s,t){const o=new Object;return["excl_tax_sale_price","excl_tax_wholesale_price","incl_tax_sale_price","incl_tax_wholesale_price","sale_price","sale_price_edit","sale_price_tax","wholesale_price","wholesale_price_edit","wholesale_price_tax"].forEach(i=>{o[i]=Object.values(s).map(a=>a.modifiers.map(l=>l.unit_quantities[0][i]*l.quantity)).flat().reduce((a,l)=>a+l)+t.$quantities()[i]}),()=>({...t.$quantities(),...o})}}class k{constructor(s){this.order=s}run(){return new Promise((s,t)=>{Popup.show(nsConfirmPopup,{title:"Send the order to the kitchen",message:"An order send to the kitchen can be seen and cooked by kitchen staff.",onAction:o=>{o?(this.order.gastro_order_status="pending",s(!0)):(this.order.gastro_order_status="hold",t(!1))}})})}}const S={name:"",template:`
    <div class="w-full flex" id="gastro-add-buttons">
        <div @click="submitAddToOrder()" id="kitchen-button" class="flex-shrink-0 w-1/4 flex items-center font-bold cursor-pointer justify-center bg-green-500 text-white hover:bg-green-600 border-r border-green-600 flex-auto">
            <i class="mr-2 text-xl lg:text-3xl las la-paper-plane"></i>
            <span class="text-lg lg:text-2xl">{{ localization( 'To Kitchen' ) }}</span>
        </div>
        <div @click="cancelAddToOrder()" id="hold-button" class="flex-shrink-0 w-1/4 flex items-center font-bold cursor-pointer justify-center bg-red-500 text-white border-r hover:bg-red-600 border-red-600 flex-auto">
            <i class="mr-2 text-xl lg:text-3xl las la-times"></i>
            <span class="text-lg lg:text-2xl">{{ localization( 'Cancel', 'NsGastro' ) }}</span>
        </div>
    </div>
    `,data(){return{selectedOrder:null,subscription:null}},mounted(){this.subscription=Gastro.selectedOrder.subscribe(e=>{this.selectedOrder=e})},methods:{localization:__m,cancelAddToOrder(){Gastro.selectedOrder.next({}),Gastro.setAddButtonsVisibility("hidden"),POS.reset()},submitAddToOrder(){const e=POS.products.getValue();if(e.length===0)return nsSnackBar.error(this.localization("Unable to submit if the cart is empty.","NsGastro"),null,{duration:4e3}).subscribe();Popup.show(nsConfirmPopup,{title:this.localization("Confirm Your Action","NsGastro"),message:this.localization("Would you like to add {products} items to the order {order}","NsGastro").replace("{products}",e.length).replace("{order}",this.selectedOrder.code),onAction:s=>{s&&nsHttpClient.post(`/api/gastro/orders/${this.selectedOrder.id}/add-products`,{products:e}).subscribe(t=>{nsSnackBar.success(t.message,"OK",{duration:3e3}).subscribe(),nsHooks.doAction("ns-gastro-after-add-products",{order:this.selectedOrder,products:t.data.orderProducts}),this.cancelAddToOrder()},t=>{nsSnackBar.error(t.message||this.localization("An unexpected error occured","NsGastro"),"OK",{duration:0}).subscribe()})}})}}},N={name:"gastro-pos-meal",template:`
    <div class="shadow-lg w-95vw md:w-3/5-screen lg:w-2/5-screen ns-box">
        <div class="p-2 flex justify-between border-b ns-box-header items-center">
            <h3 class="w-full">
                <span>{{ localization( 'Meal Status: ', 'NsGastro' ) }}</span>
                <span v-if="product">{{ product.name }}</span>
            </h3>
            <div>
                <ns-close-button @click="closePopup()"></ns-close-button>
            </div>
        </div>
        <div>
            <div class="go-grid go-grid-cols-2 text-primary">
                <div @click="printKitchen()" class="cursor-pointer hover:bg-info-secondary hover:go-text-white go-border border-box-edge go-h-36 flex items-center flex-col justify-center">
                    <i class="go-text-6xl las la-print"></i>
                    <span class="font-bold">{{ localization( 'Print', 'NsGastro' ) }}</span>
                </div>
                <div @click="cancelMeal()" class="cursor-pointer hover:bg-info-secondary hover:go-text-white go-border border-box-edge go-h-36 flex items-center flex-col justify-center">
                    <i class="go-text-6xl las la-ban"></i>
                    <span class="font-bold">{{ localization( 'Cancel', 'NsGastro' ) }}</span>
                </div>
                <div @click="addProductNote()" class="cursor-pointer hover:bg-info-secondary hover:go-text-white go-border border-box-edge go-h-36 flex items-center flex-col justify-center">
                    <i class="go-text-6xl las la-comment-alt"></i>
                    <span class="font-bold">{{ localization( 'Note', 'NsGastro' ) }}</span>
                </div>
            </div>
        </div>
    </div>
    `,props:["popup"],mounted(){this.product=this.popup.params.product},data(){return{product:null}},methods:{localization:__m,closePopup(){this.popup.params.reject(!1),this.popup.close()},printKitchen(){const e=this.popup.params.product;["pending","ongoing"].includes(e.cooking_status)&&e.id,nsSnackBar.error("Unable to print a meal that is not yet send at the kitchen or which is already cooked.").subscribe()},cancelMeal(){const e=this.popup.params.product;["pending","ongoing"].includes(e.cooking_status)&&e.id,nsSnackBar.error("Unable to cancel a meal that is not send to the kitchen or which is already cookied.").subscribe()},async addProductNote(){try{const e=await new Promise((s,t)=>{Popup.show(nsPromptPopup,{resolve:s,reject:t,input:this.popup.params.product.cooking_note,title:"Meal Note",message:"The following note will be visible at the kitchen and on the kitchen slip.",onAction:o=>{s(o)}})});e!==!1&&(this.popup.params.product.cooking_note=e),this.closePopup()}catch(e){console.log(e)}}}},v={template:`
    <div class="shadow-xl flex flex-col w-95vw h-95vh ns-box go-relative">
        <div class="flex justify-between border-b items-center ns-box-header p-2">
            <h2>{{ localization( 'Split Orders', 'NsGastro' ) }}</h2>
            <div>
                <ns-close-button @click="popupResolver()"></ns-close-button>
            </div>
        </div>
        <div class="p-2 ns-box-body flex-auto flex flex-col overflow-hidden" v-if="splitResult === null">
            <div class="rounded overflow-hidden border-2 flex flex-shrink-0 input-group info w-full">
                <input ref="searchField" :placeholder="this.localization( 'Order Code', 'NsGastro' )" v-model="search" type="text" class="flex-auto p-2 outline-none">
                <button @click="searchOrderWithQuery( search )" class="px-3 py-2">{{ localization( 'Search', 'NsGastro' ) }}</button>
            </div>
            <div class="h-0 relative">
                <div class="shadow elevation-surface w-full absolute z-10">
                    <ul>
                        <li 
                            v-for="order of searchResults" 
                            :key="order.id" 
                            @click="selectOrder( order )"
                            class="cursor-pointer p-2 border-b bg-box-elevation-edge flex flex-col justify-between">
                                <div class="flex justify-between">
                                    <h2 class="font-semibold text-primary">{{ order.code }}</h2>
                                    <span class="text-primary">{{ nsCurrency( order.total ) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <div>
                                        <span class="text-sm text-secondary">{{ localization( 'Customer: ', 'NsGastro' ) }} {{ order.customer.name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm text-secondary">{{ localization( 'Order Type: ', 'NsGastro' ) }} {{ getOrderType( order.type ) }}</span>
                                    </div>
                                </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="h-full w-full flex items-center justify-center" v-if="selectedOrder === null">
                <div class="flex flex-col justify-center items-center text-gray-500">
                    <i class="las la-smile go-text-8xl"></i>
                    <p class="text-sm">{{ localization( 'Search an order to get started.', 'NsGastro' ) }}</p>
                </div>
            </div>
            <div class="flex flex-auto go-mt-4 flex-wrap overflow-auto md:overflow-hidden" v-if="selectedOrder !== null">
                <div class="w-full md:w-1/2 md:h-full p-2 elevation-surface md:flex-auto md:overflow-y-auto">
                    <h1 class="text-secondary w-full py-2 border-b border-indigo-400 flex justify-between">
                        <span>{{ localization( 'Original Order', 'NsGastro' ) }}</span>
                        <span>{{ selectedOrder.code }}</span>
                    </h1>
                    <div class="py-2">
                        <p class="py-1">{{ localization( 'Define in how many slices you want to split the order.', 'NsGastro' ) }}</p>
                        <div class="flex mb-2">
                            <div class="rounded border-2 input-group info flex overflow-hidden flex-auto">
                                <input @keypress.enter="generatePortions()" ref="sliceField" type="number" v-model="slices" class="p-2 flex-auto outline-none">
                                <button @click="generatePortions()" class="px-3 py-1">{{ localization( 'Set Slices', 'NsGastro' ) }}</button>
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-1">
                            <div class="p-1 w-full lg:w-1/2" 
                                :key="product.id" 
                                v-for="product of orderProducts">
                                <div 
                                class="bg-info-tertiary text-white flex p-2">
                                    <div class="flex flex-auto">
                                        <div class="flex-auto">
                                            <span class="text-white">{{ product.name }} (x{{ product.displayed_quantity }})</span>
                                            <ul>
                                                <li class="text-sm text-white p-1 border-b border-blue-400 flex justify-between" v-for="modifier of product.modifiers" :key="modifier.id">
                                                    <span>{{ modifier.name }} (x{{ modifier.quantity }})</span>
                                                    <span>{{ nsCurrency( modifier.total_price ) }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <span class="flex justify-end">{{ nsCurrency( product.total_price ) }}</span>
                                    </div>
                                </div>
                                <div class="w-full flex" v-if="product.displayed_quantity > 0">
                                    <button @click="addProductToSelectedSlice( product )" v-if="sliceOrderSelected" class="flex-auto bg-blue-400 text-white font-bold outline-none p-1">{{ localization( 'Add', 'NsGastro' ) }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2 p-2 md:h-full md:flex-auto overflow-y-auto elevation-surface">
                    <div :class="order.selected ? 'shadow bg-box-background border-t-2 border-b-2 border-box-edge' : ''" class="p-2" :key="index" v-for="(order,index) of ordersPortions">
                        <div class="pb-2 go-flex go-items-center go-justify-between go-border-b">
                            <span>{{ localization( 'Slice {slice}', 'NsGastro' ).replace( '{slice}', index+1 ) }}</span>
                            <span>
                                <ns-icon-button @click="toggleCurrentOrder( order )" v-if="! order.selected" class-name="la-eye"></ns-icon-button>
                                <ns-icon-button @click="toggleCurrentOrder( order )" v-if="order.selected" class-name="la-eye-slash"></ns-icon-button>
                            </span>
                        </div>
                        <div v-if="order.selected">
                            <ns-field @change="detectOrderType( $event, order )" :field="field" v-for="(field,index) of order.fields" :key="index"></ns-field>
                            <div class="my-2 border border-box-elevation-edge" v-if="order.products.length > 0">
                                <div class="head p-2 text-center font-semibold border-b border-box-elevation-edge">{{ localization( 'Products', 'NsGastro' ) }}</div>
                                <div class="p-2">
                                    <div class="mb-2" v-for="product of order.products" :key="product.id">
                                        <div class="flex justify-between text-primary">
                                            <span><ns-icon-button @click="reduceProduct( product, order )" class-name="la-minus" type="error"></ns-icon-button> {{ product.name }} (x{{ product.quantity }})</span>
                                            <span>{{ nsCurrency( product.total_price ) }}</span>
                                        </div>
                                        <ul v-if="product.modifiers.length > 0">
                                            <li class="text-secondary py-1 flex justify-between text-xs" :key="modifier.id" v-for="modifier of product.modifiers">
                                                <span>{{ modifier.name }} (x{{ modifier.quantity }})</span>
                                                <span>{{ nsCurrency( modifier.total_price ) }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-2 flex-auto" v-if="splitResult">
            <div class="-mx-4 flex flex-wrap">
                <div class="w-full md:w-1/2 lg:w-1/3 px-4" :key="index" v-for="(result,index) of splitResult">
                    <div class="shadow elevation-surface">
                        <div class="header p-2 font-semibold border-b border-box-edge">{{ result.data.order.code }}</div>
                        <div class="p-2">
                            <ul>
                                <li class="text-sm text-secondary p-2 border-b border-box-edge flex justify-between">
                                    <span>{{ localization( 'Code', 'NsGastro' ) }}</span>
                                    <span>{{ result.data.order.code }}</span>
                                </li>
                                <li class="text-sm text-secondary p-2 border-b border-box-edge flex justify-between">
                                    <span>{{ localization( 'Table', 'NsGastro' ) }}</span>
                                    <span>{{ result.data.order.table_name }}</span>
                                </li>
                                <li class="text-sm text-secondary p-2 border-b border-box-edge flex justify-between">
                                    <span>{{ localization( 'Total', 'NsGastro' ) }}</span>
                                    <span>{{ nsCurrency( result.data.order.total ) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="isLoading" class="go-absolute go-flex go-items-center go-justify-center go-inset-0 go-bg-black go-opacity-50">
            <ns-spinner></ns-spinner>
        </div>
        <div class="p-2 flex justify-end border-t" v-if="splitResult === null">
            <ns-button @click="proceedSplit()" type="info">{{ localization( 'Proceed', 'NsGastro' ) }}</ns-button>
        </div>
    </div>
    `,name:"gastro-split-order",props:["popup"],data(){return{...GastroSettings,search:"",searchTimeout:null,searchResults:[],selectedOrder:null,validation:new FormValidation,orderProducts:[],splitResult:null,customers:[],tables:[],ordersPortions:[],isLoading:!1,slices:0,splitSlice:0,orderTypes:[],fields:[{type:"number",label:this.localization("Slices","NsGastro"),description:this.localization("In how much parts the order should be split","NsGastro"),validation:"required"}]}},computed:{sliceOrderSelected(){return this.ordersPortions.filter(e=>e.selected).length>0}},watch:{search(){clearTimeout(this.searchTimeout),this.searchTimeout=setTimeout(()=>{this.searchOrderWithQuery(this.search)},500)}},mounted(){this.popupCloser(),this.$refs.searchField.focus(),this.$refs.searchField.addEventListener("blur",()=>{setTimeout(()=>{this.searchResults=[]},300)}),this.popup.params.order!==void 0&&this.selectOrder(this.popup.params.order)},methods:{localization:__m,popupCloser,popupResolver,nsCurrency,toggleCurrentOrder(e){const s=this.ordersPortions.indexOf(e);s>=0&&(this.ordersPortions.forEach((t,o)=>{s!==o&&(t.selected=!1)}),this.ordersPortions[s].selected=!this.ordersPortions[s].selected)},detectOrderType(e,s){if(console.log(e),e.name==="type")if(e.value==="dine-in")s.fields.push({label:this.localization("Table","NsGastro"),name:"table_id",type:"select",options:this.tables,description:this.localization("Assign the order to a table.","NsGastro"),validation:"required"});else{const t=s.fields.filter(o=>o.name=="table_id");if(t.length>0){const o=s.fields.indexOf(t[0]);s.fields.splice(o,1)}}},loadTables(){nsHttpClient.get("/api/gastro/tables").subscribe(e=>{this.tables=e.map(s=>({label:s.name,value:s.id}))},e=>nsSnackBar.error(this.localization("An unexpected error has occured while fetching the tables.","NsGastro"),null,{duration:3e3}).subscribe())},reduceProduct(e,s){if(e.quantity--,e.quantity===0){const o=s.products.indexOf(e);s.products.splice(o,1)}let t=0;e.modifiers.length>0&&(t=e.modifiers.map(o=>o.total_price).reduce((o,r)=>o+r)),e.total_price=e.quantity*(e.unit_price+t),this.orderProducts.forEach(o=>{o.id===e.id&&(o.displayed_quantity+=1)})},addProductToSelectedSlice(e,s=1){const t=this.ordersPortions.filter(i=>i.selected),o=this.ordersPortions.filter(i=>!i.selected);if(t.length>0){const i=t[0].products.filter(l=>l.id===e.id),a=o.map(l=>l.products).flat().filter(l=>l.id===e.id);var r=0;if(a.length>0&&(r=a.map(l=>l.quantity).reduce((l,n)=>l+n)),i.length>0){if(e.quantity-(r+(i[0].quantity+s))<=-1)return nsSnackBar.error(this.localization("Unable to add more quantity.","NsGastro")).subscribe();i[0].quantity+=s;let l=0;i[0].modifiers.length>0&&(l=i[0].modifiers.map(n=>n.total_price).reduce((n,d)=>n+d)),i[0].total_price=(i[0].unit_price+l)*i[0].quantity,e.displayed_quantity=e.quantity-i[0].quantity-r}else{if(e.quantity-(r+s)<=-1)return nsSnackBar.error(this.localization("Unable to add more quantity.","NsGastro")).subscribe();const l=Object.assign({},e);l.quantity=s;let n=0;l.modifiers.length>0&&(n=l.modifiers.map(d=>d.total_price).reduce((d,u)=>d+u)),l.total_price=(l.unit_price+n)*l.quantity,t[0].products.push(l),e.displayed_quantity=e.quantity-s-r}}},selectOrderslice(e){this.ordersPortions.forEach(s=>{s.selected=!1}),e.selected=!0},selectOrder(e){this.selectedOrder=e,this.searchResults=[],this.search="",this.loadOrderProducts()},getOrderType(e){return this.typeLabels[e]||this.localization("Unknown","NsGastro")},loadCustomers(){nsHttpClient.get("/api/customers").subscribe(e=>{this.customers=e.map(s=>({label:`${s.first_name} ${s.last_name}`,value:s.id}))})},loadOrderProducts(){this.loadCustomers(),this.loadOrderType(),this.loadTables(),nsHttpClient.get(`/api/gastro/orders/${this.selectedOrder.id}/products`).subscribe(e=>{this.orderProducts=e,this.orderProducts.forEach(s=>{s.displayed_quantity=s.quantity}),this.$refs.sliceField.addEventListener("focus",()=>{this.$refs.sliceField.select()})},e=>{nsSnackBar.error(e.message||this.localization("An unexpected error occured.","NsGastro")).subscribe()})},loadOrderType(){nsHttpClient.get("/api/gastro/order-types").subscribe(e=>{this.orderTypes=Object.values(e.types).map(s=>({label:s.label,value:s.identifier}))})},proceedSplit(){if(this.ordersPortions.length===0)return nsSnackBar.error(this.localization("Unable to proceed if there is no portions are defined.","NsGastro")).subscribe();if(this.ordersPortions.map(s=>s.products.length===0).filter(s=>s===!0).length>0)return nsSnackBar.error(this.localization("Unable to proceed if an order slice is empty.","NsGastro")).subscribe();if(this.orderProducts.filter(s=>s.displayed_quantity>0).length>0)return nsSnackBar.error(this.localization("Unable to proceed, as there are unassigned products","NsGastro")).subscribe();if(this.ordersPortions.filter(s=>!this.validation.validateFields(s.fields)).length>0)return nsSnackBar.error(this.localization("Unable to proceed as one or more slice forms is invalid.","NsGastro")).subscribe();Popup.show(nsConfirmPopup,{title:this.localization("Confirm Your Action","NsGastro"),message:this.localization("Would you like to confirm the order split ?","NsGastro"),onAction:s=>{s&&this.confirmSplit()}})},confirmSplit(){const e=this.ordersPortions.map(s=>{const t=this.validation.extractFields(s.fields);return t.products=s.products,t});this.isLoading=!0,nsHttpClient.post("/api/gastro/orders/split",{original:this.selectedOrder,slices:e}).subscribe(s=>{this.isLoading=!1,this.splitResult=s.data},s=>{this.isLoading=!1,nsSnackBar.error(s.message||this.localization("An unexpected error has occured while splitting the order.","NsGastro")).subscribe()})},generatePortions(){return parseInt(this.slices)<=1||parseInt(this.slices)>5?nsSnackBar.error(this.localization("Invalid slices for the order. An order can be splited in 2 slices and up to 5 slices.","NsGastro")).subscribe():this.ordersPortions.length>0?Popup.show(nsConfirmPopup,{title:this.localization("Confirm Your Action","NsGastro"),message:this.localization("Looks like you already have defined some orders parts. Would you like to delete them ?","NsGastro"),onAction:e=>{e&&this.__generatePortions()}}):this.__generatePortions()},__generatePortions(){this.ordersPortions=new Array(parseInt(this.slices)).fill("").map(e=>({fields:this.validation.createFields([{type:"text",name:"name",label:this.localization("Name","NsGastro"),description:this.localization("A name can help you to identify the order quickly.","NsGastro")},{type:"search-select",options:this.customers,name:"customer_id",label:this.localization("Assigned Customer","NsGastro"),description:this.localization("Choose the customer that is assigned to the order.","NsGastro"),validation:"required"},{type:"select",options:this.orderTypes,label:this.localization("Order Type","NsGastro"),name:"type",description:this.localization("Define what is the order type.","NsGastro"),validation:"required"}]),type:null,discount:0,products:[],selected:!1})),this.slices=0},searchOrderWithQuery(e){e.length>0&&nsHttpClient.get(`/api/gastro/orders/search?search=${e}`).subscribe(s=>{s.length===0&&(this.$refs.searchField.focus(),this.$refs.searchField.select(),nsSnackBar.info(this.localization("No results match your query, please try again.","NsGastro","OK",{duration:4e3})).subscribe()),this.searchResults=s},s=>nsSnackBar.error(this.localization("An error has occured while searching orders","NsGastro"),"OK",{duration:4e3}).subscribe())}}},P={name:"gastro-pos-order-move",template:`
    <div v-if="order" class="shadow-full ns-box w-95vw h-95vh md:w-3/4-screen lg:w-3/6-screen md:h-half overflow-hidden flex flex-col">
        <div class="border-b ns-box-header p-2 flex flex-col md:flex-row justify-between items-center">
            <div class="flex-auto">
                <h3 class="font-semibold mb-1 md:mb-0">{{ localization( 'Move Order', 'NsGastro' ) }}</h3>
            </div>
            <div class="flex items-center justify-between w-full md:w-auto">
                <div class="px-1">
                    <ns-close-button @click="closePopup()"></ns-close-button>
                </div>
            </div>
        </div>
        <div class="p-2 ns-box-body flex-auto flex flex-col overflow-hidden">
            <p class="text-center mb-4 text-primary">{{ 
                this.localization( \`You're about to move the order {order}. Please select the table where you would like to move the order.\`, 'NsGastro' )
                    .replace( '{order}', this.order.code )
            }}</p>
            <div class="shadow rounded elevation-surface flex flex-col overflow-hidden">
                <div class="p-2 flex-col flex border-b">
                    <div class="input-group border-2 overflow-hidden">
                        <input v-model="tableName" type="text" class="w-full p-2" :placeholder="this.localization( 'Search a table', 'NsGastro' )">
                    </div>
                </div>
                <div class="overflow-y-auto">
                    <ul>
                        <li @click="moveTo( table )" v-for="table of tables" :key="table.id" class="hover:bg-blue-100 cursor-pointer text-primary p-2 border-b flex justify-between">
                            <span>{{ table.name }}</span>
                            <div>
                                <span v-if="! table.busy" class="rounded-full px-2 text-xs py-1 bg-green-400">{{ localization( 'Available', 'NsGastro' ) }}</span>
                                <span v-if="table.busy" class="rounded-full px-2 text-xs py-1 bg-yellow-400">{{ localization( 'Busy', 'NsGastro' ) }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    `,props:["popup"],data(){return{order:null,tableName:"",watchTimeout:null,tables:[]}},watch:{tableName(){clearTimeout(this.watchTimeout),this.watchTimeout=setTimeout(()=>{this.searchTables(this.tableName)},1e3)}},methods:{popupResolver,popupCloser,localization:__m,closePopup(){this.popupResolver(!1)},moveTo(e){Popup.show(nsConfirmPopup,{title:this.localization('Move The Order To "{table}" ?',"NsGastro").replace("{table}",e.name),message:this.localization("The order will be moved to a new table. Would you like to confirm ? ","NsGastro"),onAction:s=>{s&&this.proceedMove(this.order,e)}})},proceedMove(e,s){nsHttpClient.post(`/api/gastro/orders/${e.id}/change-table`,{table_id:s.id}).subscribe(t=>{nsSnackBar.success(t.message,"OK",{duration:3e3}).subscribe(),this.popupResolver(!0)},t=>{const o=t.message||this.localization("An unexpected error occured while moving the order.","NsGastro");nsSnackBar.error(o,"OK",{duration:3e3}).subscribe()})},searchTables(e){nsHttpClient.post("/api/gastro/tables/search",{search:e,ignore_table_id:this.order.table_id}).subscribe(s=>{this.tables=s})}},mounted(){this.popupCloser(),this.order=this.popup.params.order,this.searchTables()}},z={name:"gastro-pos-order-options",template:`
    <div class="shadow-full ns-box w-95vw h-1/2 md:w-2/4-screen lg:w-2/6-screen overflow-hidden flex flex-col">
        <div class="border-b ns-box-header p-2 flex flex-col md:flex-row justify-between items-center">
            <div class="flex-auto">
                <h3 class="font-semibold mb-1 md:mb-0">{{ localization( 'Order Options', 'NsGastro' ) }}</h3>
            </div>
            <div class="flex items-center justify-between w-full md:w-auto">
                <div class="px-1">
                    <ns-close-button @click="closePopup()"></ns-close-button>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2">
            <template v-for="(option, index) of options" :key="index">
                <div
                    @click="option.onClick( order )" 
                    v-if="option.visible( order )"
                    class="border ns-numpad-key flex cursor-pointer items-center justify-center go-h-52 flex-col">
                    <i :class="option.icon" class="las go-text-8xl mr-1"></i>
                    <span>{{ option.label }}</span>
                </div>
            </template>
        </div>
    </div>
    `,props:["popup"],data(){return{options:[],order:null}},mounted(){this.popupCloser(),this.order=this.popup.params.order,this.options=nsHooks.applyFilters("ns-gastro-order-options",[{label:this.localization("Move","NsGastro"),icon:"la-expand-arrows-alt ",visible:e=>!0,onClick:e=>this.moveOrder(e)},{label:this.localization("Request","NsGastro"),icon:"la-mitten",visible:e=>!0,onClick:e=>this.requestOrder(e)},{label:this.localization("Split","NsGastro"),icon:"la-cut",visible:e=>["hold","unpaid"].includes(e.payment_status),onClick:e=>this.splitOrder(e)},{label:this.localization("Select For Merge","NsGastro"),icon:"la-cart-plus",visible:e=>["hold","unpaid"].includes(e.payment_status),onClick:e=>this.selectForMerge(e)}]),this.popupCloser()},methods:{localization:__m,popupCloser,popupResolver,closePopup(){this.popupResolver(!1)},splitOrder(e){try{const s=new Promise((t,o)=>{Popup.show(v,{resolve:t,reject:o,order:e})})}catch{}},async requestOrder(e){if(e.gastro_order_status!=="ready")return nsSnackBar.error(this.localization("Unable to request an order that is not ready.","NsGastro")).subscribe();Popup.show(nsConfirmPopup,{title:this.localization("Confirm Request","NsGastro"),message:this.localization("The request will be submitted to the kitchen.","NsGastro"),onAction:s=>{s&&nsHttpClient.get(`/api/gastro/orders/${e.id}/request`).subscribe(t=>{this.popupResolver(!0),nsSnackBar.success(t.message,this.localization("Ok","NsGastro"),{duration:3e3}).subscribe()},t=>{nsSnackBar.error(t.message||this.localization("An unexpected error has occured.","NsGastro"),this.localization("Ok","NsGastro"),{duration:3e3}).subscribe()})}})},async selectForMerge(e){try{await Gastro.selectOrderForMerging(e),this.popup.close()}catch{}},async moveOrder(e){try{const s=await new Promise((t,o)=>{Popup.show(P,{resolve:t,reject:o,$parent:this,order:e})});this.popupResolver(!0)}catch(s){console.log(s)}}}},T={name:"gastro-pos-product-options",template:`
    <div class="ns-box shadow-lg w-95vw h-1/2 md:w-2/4-screen lg:w-2/6-screen overflow-hidden">
        <div class="p-2 border-b ns-box-header flex justify-between items-center">
            <span>{{ localization( 'Product Options', 'NsGastro' ) }}</span>
            <div>
                <ns-close-button @click="popupResolver( false )"></ns-close-button>
            </div>
        </div>
        <div class="grid grid-cols-2">
            <div @click="serveMeal()" :class="product.cooking_status === 'ready' ? 'cursor-pointer' : 'cursor-not-allowed'" class="h-32 border ns-numpad-key flex items-center justify-center flex-col">
                <i class="text-6xl las la-concierge-bell"></i>
                <span>{{ localization( 'Served', 'NsGastro' ) }}</span>
            </div>
            <div @click="cancelMeal()" :class="product.cooking_status !== 'canceled' ? 'cursor-pointer' : 'cursor-not-allowed'"  class="cursor-pointer h-32 border ns-numpad-key flex items-center justify-center flex-col">
                <i class="text-6xl las la-times"></i>
                <span>{{ localization( 'Cancel', 'NsGastro') }}</span>
            </div>
            <div @click="updateNote()" :class="product.cooking_status === 'pending' ? 'cursor-pointer' : 'cursor-not-allowed'" class="h-32 border ns-numpad-key flex items-center justify-center flex-col">
                <i class="text-6xl las la-comment-alt"></i>
                <span>{{ localization( 'Note', 'NsGastro') }}</span>
            </div>
        </div>
    </div>
    `,computed:{product(){return this.popup.params.product}},props:["popup"],mounted(){this.popupCloser()},methods:{localization:__m,popupResolver,popupCloser,async updateNote(){if(this.product.cooking_status!=="pending")return nsSnackBar.error(this.localization("Unable to edit this product notes.","NsGastro")).subscribe();try{const e=await new Promise((s,t)=>{Popup.show(nsPromptPopup,{resolve:s,reject:t,input:this.product.cooking_note,title:"Meal Note",message:"The following note will be visible at the kitchen and on the kitchen slip.",onAction:o=>{s(o)}})});this.product.cooking_note=e,nsHttpClient.post(`/api/gastro/products/${this.product.id}/note`,{note:e}).subscribe(s=>{this.popupResolver(this.product),nsSnackBar.success(s.message).subscribe()},s=>{nsSnackBar.error(s.message||this.localization("An unexpected error occured.","NsGastro")).subscribe()})}catch(e){console.log(e)}},async serveMeal(){if(this.product.cooking_status!=="ready")return nsSnackBar.error(this.localization("Unable to serve a meal that is not ready.","NsGastro")).subscribe();try{const e=await new Promise((s,t)=>{Popup.show(nsConfirmPopup,{title:this.localization("Would You Serve The Meal ?","NsGastro"),resolve:s,reject:t,message:this.localization(`You're about to serve the meal "{product}". note that this operation can't be canceled.`,"NsGastro").replace("{product}",this.product.name),onAction:o=>{o&&nsHttpClient.post(`/api/gastro/products/${this.product.id}/serve`,{reason:o}).subscribe(r=>{nsSnackBar.success(r.message).subscribe(),this.popupResolver(r)},r=>{nsSnackBar.error(r.message||this.localization("An unexpected error occured.","NsGastro")).subscribe()})}})})}catch(e){console.log(e)}},printCanceledMeal(e,s=[]){Gastro.printOrderCanceledMealKitchen(e,s)},async cancelMeal(){if(this.product.cooking_status==="canceled")return nsSnackBar.error(this.localization("Unable to cancel an already canceled product.","NsGastro")).subscribe();try{const e=await new Promise((s,t)=>{Popup.show(nsPromptPopup,{title:this.localization("Confirm Your Action","NsGastro"),resolve:s,reject:t,message:this.localization(`You're about to cancel "{product}". Please provide a reason for this action.`,"NsGastro").replace("{product}",this.product.name),onAction:o=>{typeof o=="string"&&nsHttpClient.post(`/api/gastro/products/${this.product.id}/cancel`,{reason:o}).subscribe(r=>{console.log(this.product),nsSnackBar.success(r.message).subscribe(),this.product=r.data.product,this.printCanceledMeal(this.product.order_id,[this.product])},r=>{nsSnackBar.error(r.message||this.localization("An unexpected error occured.","NsGastro")).subscribe()})}})})}catch(e){console.log(e)}}}},C={name:"gastro-pos-merge",template:`
    <div class="ns-box go-relative shadowl-lg w-95vw h-95vh lg:w-2/3-screen flex flex-col text-gray-700 overflow-hidden">
        <div class="border-b ns-box-header p-2 flex justify-between items-center">
            <span class="font-semibold">{{ localization( 'Merge Orders', 'NsGastro' ) }}</span>
            <div>
                <ns-close-button @click="popupResolver(false)"></ns-close-button>
            </div>
        </div>
        <div class="p-2 ns-box-body flex flex-col flex-auto overflow-hidden">
            <div class="rounded overflow-hidden border-2 flex flex-shrink-0 input-group info w-full">
                <input ref="searchField" :placeholder="this.localization( 'Order Code', 'NsGastro' )" v-model="search" type="text" class="flex-auto p-2 outline-none">
                <button @click="searchOrderWithQuery( search )" class="px-3 py-2">{{ localization( 'Search', 'NsGastro' ) }}</button>
            </div>
            <div class="h-0 relative">
                <div class="shadow elevation-surface w-full absolute z-10">
                    <ul>
                        <li 
                            v-for="order of searchResults" 
                            :key="order.id" 
                            @click="addToTheQueue( order )"
                            class="cursor-pointer p-2 border-b border-box-edge flex flex-col justify-between">
                                <div class="flex justify-between">
                                    <h2 class="font-semibold text-primary">{{ order.code }}</h2>
                                    <span class="text-primary">{{ nsCurrency( order.total ) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <div>
                                        <span class="text-sm text-secondary">{{ localization( 'Customer: ', 'NsGastro' ) }} {{ order.customer.name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm text-secondary">{{ localization( 'Order Type: ', 'NsGastro' ) }} {{ getOrderType( order.type ) }}</span>
                                    </div>
                                </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="py-2 md:py-4">
                <div class="-mx-2 md:-mx-4 flex flex-wrap">
                    <div class="px-2 md:px-4 w-1/2">
                        <div class="shadow elevation-surface border rounded p-2">
                            <h4 class="font-semibold">{{ localization( 'Total', 'NsGastro' ) }}</h4>
                            <h2 class="font-bold text-2xl md:text-4xl">{{ nsCurrency( totalOrders ) }}</h2>
                        </div>
                    </div>
                    <div class="px-2 md:px-4 w-1/2">
                        <div class="shadow elevation-surface border rounded p-2">
                            <h4 class="font-semibold">{{ localization( 'Products', 'NsGastro' ) }}</h4>
                            <h2 class="font-bold text-2xl md:text-4xl">{{ totalProducts }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-y-auto flex-auto">
                <div class="p-2">
                    <div class="flex -mx-2 md:flex-nowrap flex-wrap">
                        <div class="w-full md:w-1/2 lg:w-2/6 px-2">
                            <h3 class="border-b font-semibold text-sm border-info-tertiary py-2">{{ localization( 'Queued Orders', 'NsGastro' ) }}</h3>
                            <ul class="py-2">
                                <li v-for="order of queuedOrders" :key="order.id" class="shadow elevation-surface border rounded p-2 flex items-center justify-between">
                                    <div class="flex flex-auto justify-between">
                                        <span>{{ order.code }}</span>
                                        <span>{{ nsCurrency( order.total ) }}</span>
                                    </div>
                                    <div class="ml-2">
                                        <ns-close-button @click="removeOrderFromQueue( order )"></ns-close-button>
                                    </div>
                                </li>
                                <li v-if="queuedOrders.length === 0" class="p-2 shadow elevation-surface border text-center">{{ localization( 'No order has been queued.', 'NsGastro' ) }}</li>
                            </ul>
                        </div>
                        <div class="w-full md:w-1/2 lg:w-4/6 px-2">
                            <h3 class="border-b font-semibold text-sm border-info-tertiary py-2">{{ localization( 'Order Settings', 'NsGastro' ) }}</h3>
                            <div class="elevation-surface my-4 p-2">
                                <div class="-mx-2 flex flex-wrap">
                                    <div class="p-2 w-full md:w-1/2" :key="index" v-for="(field,index) of orderFields">
                                        <ns-field @change="detectOrderType( $event )" :field="field"></ns-field>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-2 flex justify-between border-t border-box-edge">
            <div></div>
            <div>
                <ns-button @click="submitOrderMerging()" type="info">{{ localization( 'Submit', 'NsGastro' ) }}</ns-button>
            </div>
        </div>
        <div v-if="isLoading" class="go-absolute go-flex go-items-center go-justify-center go-inset-0 go-bg-black go-opacity-50">
            <ns-spinner></ns-spinner>
        </div>
    </div>
    `,props:["popup"],watch:{search(){clearTimeout(this.searchTimeout),this.searchTimeout=setTimeout(()=>{this.searchOrderWithQuery(this.search)},500)}},mounted(){this.popupCloser(),this.$refs.searchField.focus(),this.$refs.searchField.addEventListener("blur",()=>{setTimeout(()=>{this.searchResults=[]},300)}),Gastro.selectedOrdersSubject.getValue().length>0&&Gastro.selectedOrdersSubject.getValue().forEach(e=>{this.addToTheQueue(e)}),RxJS.forkJoin([this.loadCustomers(),this.loadOrderType(),this.loadTables()]).subscribe(e=>{this.customers=e[0].map(s=>({label:`${s.first_name} ${s.last_name}`,value:s.id})),this.orderTypes=Object.values(e[1].types).map(s=>({label:s.label,value:s.identifier})),this.tables=e[2].map(s=>({label:s.name,value:s.id})),this.buildFields()})},data(){return{search:"",searchResults:[],validation:new FormValidation,isLoading:!1,orderFields:[],queuedOrders:[],customers:[],totalOrders:0,totalProducts:0,tables:[],orderTypes:[],typeLabels:{},mergeResult:{}}},methods:{localization:__m,popupCloser,popupResolver,nsCurrency,detectOrderType(e){if(e.name==="type")if(e.value==="dine-in")this.orderFields.push({label:this.localization("Table","NsGastro"),name:"table_id",type:"select",options:this.tables,description:this.localization("Assign the order to a table.","NsGastro"),validation:"required"});else{const s=this.orderFields.filter(t=>t.name=="table_id");if(s.length>0){const t=this.orderFields.indexOf(s[0]);this.orderFields.splice(t,1)}}},submitOrderMerging(){if(this.queuedOrders.length<2)return nsSnackBar.error(this.localization("There should be at least 2 queued orders for merging.","NsGastro")).subscribe();if(this.queuedOrders.length>5)return nsSnackBar.error(this.localization("At most 5 orders can be merged.","NsGastro")).subscribe();if(!this.validation.validateFields(this.orderFields))return nsSnackBar.error(this.localization("Unable to proceed the form is not valid.","NsGastro")).subscribe();Popup.show(nsConfirmPopup,{title:this.localization("Confirm Your Action","NsGastro"),message:this.localization("The provided order will be merged. Note that this operation can't be undone","NsGastro"),onAction:e=>{e&&this.proceedOrderMerging()}})},proceedOrderMerging(){this.isLoading=!0,nsHttpClient.post("/api/gastro/orders/merge",{orders:this.queuedOrders,fields:this.validation.extractFields(this.orderFields)}).subscribe(e=>{this.mergeResult=e.data,nsSnackBar.success(this.localization("The orders has been merged into {order} successfully.","NsGastro").replace("{order}",e.data.order.code),this.localization("Ok","NsGastro"),{duraton:1e4}).subscribe(),Gastro.selectedOrdersSubject.next([]),this.isLoading=!1,this.popupResolver(!0)},e=>{this.isLoading=!1,nsSnackBar.error(this.localization("An unexpected error has occured.","NsGastro")).subscribe()})},removeOrderFromQueue(e){const s=this.queuedOrders.indexOf(e);this.queuedOrders.splice(s,1),Gastro.selectedOrdersSubject.next(this.queuedOrders),this.computeOrders()},buildFields(){this.orderFields=this.validation.createFields([{label:this.localization("Customer","NsGastro"),name:"customer_id",type:"search-select",options:this.customers,validation:"required",description:this.localization("Assign a customer to the order.","NsGastro")},{label:this.localization("Name","NsGastro"),name:"name",type:"text",description:this.localization("Define the order name. Might be useful to retreive the order.","NsGastro")},{label:this.localization("Order Type","NsGastro"),name:"type",type:"select",options:this.orderTypes,validation:"required",description:this.localization("Set what is the order type.","NsGastro")}])},computeOrders(){this.queuedOrders.length>0?(this.totalOrders=this.queuedOrders.map(e=>e.total).reduce((e,s)=>e+s),this.totalProducts=this.queuedOrders.map(e=>e.products.map(s=>s.quantity).flat()).flat().reduce((e,s)=>e+s)):(this.totalOrders=0,this.totalProducts=0)},getOrderType(e){return this.typeLabels[e]||this.localization("Unknown","NsGastro")},loadCustomers(){return nsHttpClient.get("/api/customers")},loadOrderType(){return nsHttpClient.get("/api/gastro/order-types")},loadTables(){return nsHttpClient.get("/api/gastro/tables")},addToTheQueue(e){const s=this.queuedOrders.map(t=>t.id);if(this.search="",s.includes(e.id))return nsSnackBar.error(this.localization("The order has already been added to the queue.","NsGastro")).subscribe();e.products=[],this.queuedOrders.push(e),nsHttpClient.get(`/api/gastro/orders/${e.id}/products`).subscribe(t=>{e.products=t,this.computeOrders()})},searchOrderWithQuery(e){e.length>0&&nsHttpClient.get(`/api/gastro/orders/search?search=${e}`).subscribe(s=>{s.length===0&&(this.$refs.searchField.focus(),this.$refs.searchField.select(),nsSnackBar.info(this.localization("No results match your query, please try again.","NsGastro","OK",{duration:4e3})).subscribe()),this.searchResults=s},s=>nsSnackBar.error(this.localization("An error has occured while searching orders","NsGastro"),"OK",{duration:4e3}).subscribe())}}},j={template:`
    <div class="w-95vw h-95vh md:w-3/5-screen md:h-3/5-screen bg-white shadow-lg flex flex-col">
        <div class="header border-b border-gray-200 p-2 flex justify-between items-center">
            <h3 class="font-bold">{{ __m( 'Selected Orders', 'NsGastro' ) }}</h3>
            <div>
                <ns-close-button @click="closePopup()"></ns-close-button>
            </div>
        </div>
        <template  v-if="orders.length > 0">
            <div class="body flex-auto">
                <div v-for="order of orders" class="box-shadow border-b p-2 flex justify-between items-center mb-2">
                    <div>
                        <h3 class="font-bold">{{ order.code }}</h3>
                        <div>
                            <small>{{ __m( 'Table:', 'NsGastro' ) }} {{ order.table_name }}</small> | 
                            <small>{{ __m( 'Customer:', 'NsGastro' ) }} {{ order.customer ? order.customer.first_name : order.customer_first_name }} {{ order.customer ? order.customer.last_name : order.customer_last_name }}</small>
                        </div>
                    </div>
                    <div>
                        <ns-close-button @click="removeFromSelected(order)"></ns-close-button>
                    </div>
                </div>
            </div>
            <div class="footer p-2 -mx-2 flex justify-end border-t items-center">
                <div class="px-2">
                    <ns-button type="info" @click="cancelSelection()">{{ __m( 'Cancel' ) }}</ns-button>
                </div>
                <div class="px-2">
                    <ns-button type="success" @click="mergeSelectedOrders()">{{ __m( 'Merge Orders' ) }}</ns-button>
                </div>
            </div>
        </template>
        <div class="body flex-auto flex items-center justify-center" v-else>
            <div class="p-2">
                <div class="text-center">
                    <i class="las la-frown text-3xl"></i>
                    <h3 class="font-bold">{{ __m( 'No selected orders', 'NsGastro' ) }}</h3>
                </div>
            </div>
        </div>
    </div>
    `,data(){return{orders:[],selectedOrderSubscription:null}},mounted(){this.selectedOrderSubscription=Gastro.selectedOrdersSubject.subscribe(e=>{this.orders=e,this.$forceUpdate()})},beforeDestroy(){this.selectedOrderSubscription.unsubscribe()},props:["popup"],methods:{__m,popupCloser,popupResolver,closePopup(){this.popupResolver(!1)},removeFromSelected(e){const s=this.orders.filter(t=>t.id!==e.id);Gastro.selectedOrdersSubject.next(s)},cancelSelection(){Gastro.selectedOrdersSubject.next([]),this.closePopup(),nsSnackBar.success(__m("The selected orders were removed.","NsGastro")).subscribe()},async mergeSelectedOrders(){if(this.orders.length<2)return nsSnackBar.error(__m("You need to select at least 2 orders to merge them","NsGastro")).subscribe();try{await new Promise((e,s)=>{Popup.show(C,{resolve:e,reject:s})})}catch(e){return console.log(e),nsSnackBar.error(__m("An error occured while merging orders.","NsGastro")).subscribe()}}}},q={name:"gastro-keyboard",template:`
    <div class="shadow-lg ns-box w-95vw md:w-3/5-screen">
        <div class="border-b ns-box-header p-2 flex justify-between items-center">
            <h3>{{ localization( 'Select Seats', 'NsGastro' ) }}</h3>
            <ns-close-button @click="closePopup()"></ns-close-button>
        </div>
        <div class="p-2 border-b ns-box-body">
            <div class="bg-gray-100 text-3xl flex justify-end p-2">{{ table.selectedSeats }}</div>
        </div>
        <div class="p-2">
            <component  v-bind:is="keyboardComponent()" :value="selectedSeats" @next="saveQuantity( $event )" @changed="updateModifierQuantity( $event )"></component>
        </div>
    </div>
    `,props:["popup"],data(){return{keyboardComponent:()=>nsComponents.nsNumpad}},methods:{localization:__m,closePopup(){this.popup.params.reject(!1),this.popup.close()},updateModifierQuantity(e){this.table.selectedSeats=e,this.$forceUpdate()},saveQuantity(e){parseFloat(e)>0&&parseFloat(e)<=parseFloat(this.table.seats)?(this.table.selectedSeats=parseFloat(e),this.popup.close(),this.popup.params.resolve(this.table)):nsSnackBar.error("Invalid seats provided.").subscribe()}},computed:{selectedSeats(){return this.table.selectedSeats||1},table(){return this.popup.params.table}},mounted(){}},m={template:`
    <div class="shadow-full ns-box w-95vw h-95vh md:w-4/5-screen lg:w-4/6-screen md:h-4/5-screen overflow-hidden flex flex-col">
        <div class="border-b ns-box-header p-2 flex flex-col md:flex-row justify-between items-center" :class="selectedTable !== null ? ( selectedTable.busy ? 'bg-success-tertiary go-text-white' : 'text-primary' ) : ''" >
            <div class="flex-auto">
                <span class="font-semibold mb-1 md:mb-0">
                    <span v-if="! additionalTitle">{{ localization( 'Table Management', 'NsGastro' ) }}</span>
                    <span v-if="additionalTitle">{{ additionalTitle }}</span>
                </span>
            </div>
            <div class="flex items-center justify-between w-full md:w-auto">
                <div class="flex">
                    <div class="px-1" v-if="[ 'orders', 'sessions-orders' ].includes( screen )">
                        <button @click="openSettingsOptions()" class="outline-none rounded-full px-3 py-1 border ns-inset-button info">
                            <i class="las la-tools"></i>
                            <span>{{ localization( 'Settings', 'NsGastro' ) }}</span>
                        </button>
                    </div>
                    <div class="px-1" v-if="[ 'orders', 'sessions-orders', 'sessions' ].includes( screen ) && ns_gastro_enable_table_sessions">
                        <button :class="[ 'sessions', 'sessions-orders' ].includes( screen ) ? 'info' : ''" @click="toggleTableSessionHistory()" class="outline-none rounded-full px-3 py-1 border ns-inset-button">
                            <i class="las la-history"></i>
                            <span>{{ localization( 'Session History', 'NsGastro' ) }}</span>
                        </button>
                    </div>
                    <div class="px-1 -mx-1 flex flex-col md:flex-row" v-if="screen === 'tables'">
                        <div class="px-1">
                            <button :class="filterMode === 'busy' ? 'active' : ''" @click="filterOnlyBusy()" class="outline-none rounded-full px-3 py-1 border border-gray1200 ns-inset-button success">{{ localization( 'Only Busy Tables', 'NsGastro' ) }}</button>
                        </div>
                        <div class="px-1">
                            <button :class="filterMode === 'free' ? 'active' : ''" @click="filterOnlyAvailable()" class="outline-none rounded-full px-3 py-1 border border1gray-200 ns-inset-button success">{{ localization( 'Only Available Tables', 'NsGastro' ) }}</button>
                        </div>
                    </div>
                    <div class="px-1 -mx-2 flex" v-if="ns_gastro_areas_enabled && screen === 'tables'">
                        <div class="px-2">
                            <button @click="returnToAreas()" class="outline-none rounded-full px-3 py-1 ns-inset-button error border">{{ localization( 'Return', 'NsGastro' ) }}</button>
                        </div>
                    </div>
                    <div class="px-1" v-if="[ 'orders', 'sessions-orders' ].includes( screen )">
                        <button @click="returnToTables()" class="outline-none rounded-full px-3 py-1 ns-inset-button error border">{{ localization( 'Return', 'NsGastro' ) }}</button>
                    </div>
                </div>
                <div class="px-1">
                    <ns-close-button @click="closePopup()"></ns-close-button>
                </div>
            </div>
        </div>
        <div class="overflow-hidden flex flex-col flex-auto" v-if="[ 'orders', 'sessions-orders' ].includes( screen )">
            <div class="flex-auto overflow-y-auto" v-if="orders.length > 0">
                <div class="flex flex-wrap">
                    <div class="border border-box-edge w-full p-2 md:w-1/2" :key="order.id" v-for="order of orders">
                        <div class="flex justify-between p-2">
                            <h3 class="font-semibold">{{ order.code }}</h3>
                            <div class="-mx-1 flex items-center">
                                <div class="px-1" v-if="! [ 'canceled', 'ready' ].includes( order.gastro_order_status )">
                                    <!-- <button class="rounded-full px-3 bg-red-500 text-white">{{ localization( 'Cancel', 'NsGastro' ) }}</button> -->
                                </div>
                                <div class="px-1">
                                    <button @click="toggleDetails( order )" class="rounded-full px-3 ns-inset-button info border" v-if="! showDetails[ order.code ]">
                                        <i class="las la-eye"></i>
                                        <span class="text-sm">{{ localization( 'Show Details', 'NsGastro' ) }}</span>
                                    </button>
                                    <button @click="toggleDetails( order )" class="rounded-full px-3 ns-inset-button info border" v-if="showDetails[ order.code ]">
                                        <i class="las la-low-vision"></i>
                                        <span class="text-sm">{{ localization( 'Hide Details', 'NsGastro' ) }}</span>
                                    </button>
                                </div>
                                <div class="px-1">
                                    <button :disabled="true" v-if="order.gastro_order_status === 'ready'" class="rounded-full ns-inset-button border px-2 success">
                                        {{ localization( 'Ready', 'NsGastro' ) }}
                                    </button>
                                    <button :disabled="true" v-if="order.gastro_order_status === 'ongoing'" class="rounded-full ns-inset-button border px-2 info">
                                        {{ localization( 'Ongoing', 'NsGastro' ) }}
                                    </button>
                                    <button :disabled="true" v-if="order.gastro_order_status === 'pending'" class="rounded-full ns-inset-button border px-2 info">
                                        {{ localization( 'Pending', 'NsGastro' ) }}
                                    </button>
                                    <button :disabled="true" v-if="order.gastro_order_status === 'canceled'" class="rounded-full ns-inset-button border px-2 error">
                                        {{ localization( 'Canceled', 'NsGastro' ) }}
                                    </button>
                                    <button :disabled="true" v-if="order.gastro_order_status === 'requested'" class="rounded-full ns-inset-button border px-2 warning">
                                        {{ localization( 'Requested', 'NsGastro' ) }}
                                    </button>
                                    <button :disabled="true" v-if="order.gastro_order_status === 'processed'" class="rounded-full ns-inset-button border px-2 info">
                                        {{ localization( 'Processed', 'NsGastro' ) }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-if="showDetails[ order.code ]">
                            <div class="flex my-1 px-2 text-sm text-primary justify-between">
                                <span>{{ localization( 'Total', 'NsGastro' ) }}</span>
                                <span>{{ nsCurrency( order.total ) }}</span>
                            </div>
                            <div class="flex my-1 px-2 text-sm text-primary justify-between">
                                <span>{{ localization( 'Name', 'NsGastro' ) }}</span>
                                <span>{{ order.title || this.localization( 'N/A', 'NsGastro' ) }}</span>
                            </div>
                            <div class="flex my-1 px-2 text-sm text-primary justify-between">
                                <span>{{ localization( 'Waiter', 'NsGastro' ) }}</span>
                                <span>{{ order.user.username || this.localization( 'N/A', 'NsGastro' ) }}</span>
                            </div>
                            <div class="flex my-1 px-2 text-sm text-primary justify-between">
                                <span>{{ localization( 'Customer', 'NsGastro' ) }}</span>
                                <span>{{ order.customer.name || this.localization( 'N/A', 'NsGastro' ) }}</span>
                            </div>
                        </div>
                        <div class="bg-gray-100">
                            <div>
                                <div v-for="product of order.products" @click="showProductOptions( product )" 
                                    :class="getMealBGClass( product )"
                                    class="p-2 hover:bg-blue-100 mb-1 cursor-pointer" 
                                    :key="product.id">
                                    <div :class="getMealProductTextColor( product )" class="flex text-sm justify-between">
                                        <h4 class="font-semibold">{{ product.name }} (x{{ product.quantity }})</h4>
                                        <span>{{ nsCurrency( product.total_price ) }}</span>
                                    </div>
                                    <ul :class="getMealModifierTextColor( product )" class="text-xs">
                                        <li v-for="modifier of product.modifiers" :key="modifier.id" class="py-1 border-b border-dashed border-blue-400 flex justify-between">
                                            <span>
                                                {{ modifier.name }} (x{{ modifier.quantity }})
                                            </span>
                                            <span>
                                                {{ nsCurrency( modifier.total_price ) }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="flex flex-wrap">
                                <button v-if="order.payment_status !== 'paid'" @click="addProduct( order )" class="w-1/2 lg:w-1/4 flex items-center justify-center py-1 bg-blue-400 text-white font-semibold">
                                    <i class="las la-plus text-2xl mr-1"></i>
                                    <span>{{ localization( 'Add', 'NsGastro' ) }}</span>
                                </button>
                                <button v-if="order.payment_status !== 'paid'" @click="payOrder( order )" class="w-1/2 lg:w-1/4 flex items-center justify-center py-1 bg-green-400 text-white font-semibold">
                                    <i class="las la-money-bill-wave text-2xl mr-1"></i>
                                    <span>{{ localization( 'Pay', 'NsGastro' ) }}</span>
                                </button>
                                <button @click="printOrder( order )" class="w-1/2 lg:w-1/4 flex items-center justify-center py-1 elevation-surface border font-semibold">
                                    <i class="las la-print text-2xl mr-1"></i>
                                    <span>{{ localization( 'Print', 'NsGastro' ) }}</span>
                                </button>
                                <button @click="openOrderOption( order )" class="w-1/2 lg:w-1/4 flex items-center justify-center py-1 elevation-surface border font-semibold">
                                    <i class="las la-tools text-2xl mr-1"></i>
                                    <span>{{ localization( 'Options', 'NsGastro' ) }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="orders.length === 0 && ! ordersLoaded" class="w-full flex items-center justify-center flex-auto">
                <ns-spinner></ns-spinner>
            </div>
            <div v-if="orders.length === 0 && ordersLoaded" class="w-full flex-col text-primary flex items-center justify-center flex-auto">
                <i class="text-6xl las la-laugh-wink"></i>
                <h3 v-if="! ns_gastro_enable_table_sessions" class="font-semibold text-lg">{{ localization( 'No orders has been found', 'NsGastro' ) }}</h3>
                <div class="flex flex-col items-center" v-if="ns_gastro_enable_table_sessions">
                    <h3 class="font-semibold text-lg">{{ localization( 'No active sessions were found.', 'NsGastro' ) }}</h3>
                    <span class="text-sm">{{ localization( 'Please use the Session History to browse previous sessions', 'NsGastro' ) }}</span>
                </div>
            </div>
            <div class="p-2 flex md:items-center flex-col md:flex-row md:justify-between border-t ns-box-footer">
                <div class="flex -mx-2">
                    <div class="px-2">
                        <button @click="showTableHistory( selectedTable )" class="rounded-lg shadow hover:shadow-none border px-3 py-1 ns-inset-button">
                            <i class="las la-sync text-xl"></i>
                            <span class="go-hidden md:go-inline-block">{{ localization( 'Refresh', 'NsGastro' ) }}</span>
                        </button>
                    </div>
                    <div class="px-2" v-if="screen === 'orders'">
                        <button @click="setRange( 'today' ); showTableHistory( selectedTable )" class="outline-none rounded-lg px-3 py-1 ns-inset-button border">
                            <i class="las la-clock text-xl"></i>
                            <span>{{ localization( 'Today', 'NsGastro' ) }}</span>
                        </button>
                    </div>
                    <div class="px-2" v-if="screen === 'orders'">
                        <button @click="setRange( 'yesterday' ); showTableHistory( selectedTable )" class="outline-none rounded-lg px-3 py-1 ns-inset-button border">
                            <i class="las la-clock text-xl"></i>
                            <span>{{ localization( 'From Yesterday', 'NsGastro' ) }}</span>
                        </button>
                    </div>
                </div>
                <div class="w-full md:w-auto mt-2 md:mt-0 md:m-0 flex -mx-2">
                    <div class="px-2" v-if="selectedOrders.length > 0">
                        <span class="flex-auto justify-center flex items-center rounded cursor-pointer py-2 px-3 font-semibold shadow bg-info-secondary text-white" @click="handleSelectedOrders( selectedTable )" type="info">{{ localization( '{orderCount} Orders Selected', 'NsGastro' ).replace( '{orderCount}', selectedOrders.length ) }}</span>
                    </div>
                    <div class="px-2" v-if="selectedTable.busy">
                        <button class="flex-auto justify-center flex items-center rounded cursor-pointer py-2 px-3 font-semibold shadow bg-green-400 text-white" @click="setAvailable( selectedTable )" type="info">
                            <span class="go-hidden sm:go-inline"><i class="las la-door-open"></i> {{ localization( 'Set Free', 'NsGastro' ) }}</span>
                            <span class="go-inline sm:go-hidden"><i class="las la-door-open"></i></span>
                        </button>
                    </div>
                    <div class="px-2">
                        <button class="flex-auto justify-center flex items-center rounded cursor-pointer py-2 px-3 font-semibold shadow bg-blue-400 text-white" @click="proceedSelect( selectedTable )" type="info">
                            <span class="go-hidden sm:go-inline"><i class="las la-hand-pointer"></i> {{ localization( 'Select', 'NsGastro' ) }}</span>
                            <span class="go-inline sm:go-hidden"><i class="las la-hand-pointer"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-y-auto flex flex-col p-4 flex-auto bg-gray-100" v-if="[ 'sessions' ].includes( screen )">
            <div class="flex -m-2 flex-wrap">
                <div v-for="session of sessions" :key="session.id" class="w-full mb-2 md:w-1/2 p-2">
                    <div class="rounded flex elevation-surface shadow overflow-hidden">
                        <div class="p-2 flex items-center justify-center">
                            <div class="go-w-14 go-h-14 rounded-full bg-blue-400 font-bold text-3xl text-white flex items-center justify-center"><i class="las la-stopwatch"></i></div>
                        </div>
                        <div class="p-2 flex-auto justify-center flex flex-col">
                            <h3 class="font-semibold">{{ session.name || this.localization( 'Unnamed Session', 'NsGastro' ) }}</h3>
                            <p class="text-sm">{{ localization( 'Active : ', 'NsGastro' ) }} {{ session.active ? this.localization( 'Yes', 'NsGastro' ) : this.localization( 'No', 'NsGastro' ) }}</p>
                            <p class="text-sm">{{ localization( 'Orders : ', 'NsGastro' ) }} {{ session.ordersCount }}</p>
                        </div>
                        <div v-if="session.active === 1" @click="closeSession( session )" class="border-l border-box-edge go-w-14 flex hover:bg-error-primary hover:text-white cursor-pointer items-center justify-center"><i class="las la-lock"></i></div>
                        <div v-if="session.active === 0" @click="openSession( session )" class="border-l border-box-edge go-w-14 flex hover:bg-info-primary hover:text-white cursor-pointer items-center justify-center"><i class="las la-unlock"></i></div>
                        <div @click="loadSessionOrders( session )" class="border-l border-box-edge go-w-14 flex hover:bg-blue-400 hover:text-white cursor-pointer items-center justify-center"><i class="las la-eye"></i></div>
                    </div>
                </div>
            </div>
            <div v-if="sessions.length === 0" class="p-2 flex flex-auto overflow-y-auto flex-wrap">
                <div  class="w-full flex-col text-primary flex items-center justify-center flex-auto">
                    <i class="text-9xl las la-frown"></i>
                    <h3 class="font-semibold text-lg">{{ localization( 'No sessions have been found', 'NsGastro' ) }}</h3>
                </div>
            </div>
        </div>
        <div :class="tables.length === 0 ? 'flex-auto': ''" class="overflow-y-auto flex" v-if="screen === 'tables'">
            <div class="w-full grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 anim-duration-500 fade-in-entrance" v-if="tables.length > 0">
                <div 
                    @touchstart="debounceForAvailability( table, $event )"
                    @touchend="mouseDown = false"
                    @click="selectQuantity( table )"
                    :key="table.id" 
                    v-for="table of tables" 
                    :class="table.selected ? 'bg-info-secondary border-info-tertiary' : ( table.busy ? 'border-success-tertiary bg-success-secondary' : 'border-box-edge' )"
                    class="relative border cursor-pointer relative flex-col go-h-52">
                    <div v-if="table.preview" :style="{ 'background-image' : 'url(' + table.preview + ')' }" class="go-w-full go-h-full absolute go-z-0 go-bg-cover go-bg-center">
                    </div>
                    <div :style="{ 'background' : ( table.busy ? 'rgb(35 151 77 / 34%)' : '' ) }" class="absolute w-full h-full go-z-20 flex items-center justify-center flex-col">
                        <div v-if="! table.busy && ns_gastro_seats_enabled" class="rounded-full -mb-6 go-h-10 w-10 flex items-center justify-center font-bold fond-bold gastro-pill info">{{ table.seats }}</div>
                        <div v-if="table.busy" class="rounded-full text-center -mb-6 go-h-10 px-3 flex items-center justify-center font-bold gastro-pill success">{{ localization( 'Currently Busy', 'NsGastro' ) }}</div>
                        <img v-if="!table.preview" :src="icons.chair">
                        <h1 :class="table.preview ? 'bg-box-background' : ''" class="go-rounded-full go-text-xs md:go-text-sm go-p-3">{{ table.name }}</h1>
                    </div>
                </div>
            </div>
            <div v-if="tables.length === 0 && tableLoaded" class="w-full flex flex-col items-center justify-center flex-auto text-primary">
                <i class="go-text-9xl las la-frown"></i>
                <h3 class="font-semibold text-lg">{{ localization( 'No tables has been found', 'NsGastro' ) }}</h3>
                <p v-if="ns_gastro_tables_assignation_enabled" class="text-sm">{{ localization( 'You might check if tables are assigned to your account.', 'NsGastro' ) }}</p>
            </div>
            <div v-if="tables.length === 0 && ! tableLoaded" class="w-full flex items-center justify-center flex-auto">
                <ns-spinner></ns-spinner>
            </div>
        </div>
        <div :class="areas.length === 0 ? 'flex-auto' : ''" class="overflow-y-auto flex" v-if="screen === 'areas'">
            <div class="w-full grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 anim-duration-500 fade-in-entrance" v-if="areas.length > 0">
                <div @click="loadTables( area )" :key="area.id" v-for="area of areas" class="cursor-pointer border flex-col border-box-edge go-h-52 flex items-center justify-center">
                    <img :src="icons.menu">
                    <h1 class="my-3">{{ area.name }}</h1>
                </div>
            </div>
            <div v-if="areas.length === 0 && areasLoaded" class="w-full flex-col flex-auto flex items-center justify-center text-primary">
                <i class="text-9xl las la-frown"></i>
                <h3 class="font-semibold text-lg">{{ localization( 'No areas has been found', 'NsGastro' ) }}</h3>
            </div>
            <div v-if="areas.length === 0 && ! areasLoaded" class="w-full flex items-center justify-center flex-auto">
                <ns-spinner></ns-spinner>
            </div>
        </div>
    </div>
    `,props:["popup"],mounted(){this.ns_gastro_areas_enabled?(this.loadAreas(),this.screen="areas"):(this.loadTables(),this.screen="tables"),this.mode=this.popup.params.mode||"select",console.log({ns}),Echo.connector.socket&&Echo.connector.socket.connected?this.listenSockets():this.launchIntervalFetches(),Gastro.tableOpenedSubject.next(!0),this.popupCloser(),this.selectedOrdersSubscription=Gastro.selectedOrdersSubject.subscribe(e=>{this.selectedOrders=e,this.$forceUpdate()}),nsHooks.addAction("ns-pos-payment-destroyed","gastro-reset-cart",()=>{this.isPaying&&(POS.reset(),this.isPaying=!1)})},beforeUnmount(){Gastro.tableOpenedSubject.next(!1),clearTimeout(this.intervalFetches)},data(){const e=ns.date.getMoment().startOf("day").format(),s=ns.date.getMoment().endOf("day").format();return{selectedOrders:[],selectedOrdersSubscription:null,screen:"areas",tableLoaded:!1,mouseDown:!1,ordersLoaded:!1,intervalFetches:null,additionalTitle:null,areasLoaded:!1,selectedArea:null,selectedTable:null,selectedSessions:null,isPaying:!1,areas:[],tables:[],showDetails:{},sessions:[],orders:[],filterMode:"",mode:"select",settings:{range_starts:e,range_ends:s},...GastroSettings}},computed:{order(){return POS.order.getValue()}},methods:{localization:__m,nsCurrency,popupCloser,popupResolver,toggleDetails(e){this.showDetails[e.code]=!this.showDetails[e.code],this.$forceUpdate()},launchIntervalFetches(){this.intervalFetches=setInterval(()=>{this.screen==="orders"&&this.showTableHistory(this.selectedTable),this.screen==="sessions-orders"&&this.loadSessionOrders(this.selectedSessions)},5e3)},async handleSelectedOrders(){try{const e=await new Promise((s,t)=>{Popup.show(j,{resolve:s,reject:t})});console.log(e)}catch(e){console.error(e)}},toggleTableSessionHistory(){this.screen!=="sessions"?(this.sessions=[],nsHttpClient.post(`/api/gastro/tables/${this.selectedTable.id}/sessions`,this.settings).subscribe({next:e=>{this.sessions=e,this.screen="sessions"},error:e=>{nsSnackBar.error(this.localization("An unexpected error has occured.","NsGastro")).subscribe()}})):(this.screen="orders",this.showTableHistory(this.selectedTable))},loadSessionOrders(e){this.screen="sessions-orders",this.selectedSessions=e,nsHttpClient.post(`/api/gastro/tables/${this.selectedTable.id}/sessions/${e.id}/orders`,this.settings).subscribe({next:s=>{this.orders=s},error:s=>{nsSnackBar.error(this.localization("An unexpected error has occured.","NsGastro")).subscribe()}})},openSession(e){Popup.show(nsConfirmPopup,{title:this.localization("Confirm Your Action","NsGastro"),message:this.localization("Would you like to open this session ?","NsGastro"),onAction:s=>{s&&nsHttpClient.put(`/api/gastro/tables/${this.selectedTable.id}/sessions/${e.id}/action`,{action:"open"}).subscribe({next:t=>{this.toggleTableSessionHistory(),nsSnackBar.success(t.message,this.localization("Okay","NsGastro"),{duration:3e3}).subscribe()},error:t=>{nsSnackBar.error(this.localization("An unexpected error has occured.","NsGastro")).subscribe()}})}})},closeSession(e){Popup.show(nsConfirmPopup,{title:this.localization("Confirm Your Action","NsGastro"),message:this.localization("Would you like to close this session manually ?","NsGastro"),onAction:s=>{s&&nsHttpClient.put(`/api/gastro/tables/${this.selectedTable.id}/sessions/${e.id}/action`,{action:"close"}).subscribe({next:t=>{this.toggleTableSessionHistory(),nsSnackBar.success(t.message,this.localization("Okay","NsGastro"),{duration:3e3}).subscribe()},error:t=>{nsSnackBar.error(this.localization("An unexpected error has occured.","NsGastro")).subscribe()}})}})},listenSockets(){Echo.channel("default-channel").listen("App\\Events\\OrderAfterCreatedEvent",e=>{this.showTableHistory(this.selectedTable)}).listen("Modules\\NsGastro\\Events\\TableAfterUpdatedEvent",e=>{this.showTableHistory(this.selectedTable)}).listen("App\\Events\\OrderAfterUpdatedEvent",e=>{this.showTableHistory(this.selectedTable)})},filterOnlyBusy(){["","free"].includes(this.filterMode)?this.filterMode="busy":this.filterMode="",this.loadTables(this.selectedArea)},filterOnlyAvailable(){["","busy"].includes(this.filterMode)?this.filterMode="free":this.filterMode="",this.loadTables(this.selectedArea)},setRange(e){switch(e){case"today":this.settings.range_starts=moment(ns.date.current).startOf("day").format("YYYY/MM/DD HH:mm:ss"),this.settings.range_ends=moment(ns.date.current).endOf("day").format("YYYY/MM/DD HH:mm:ss");break;case"yesterday":this.settings.range_starts=moment(ns.date.current).subtract(1,"days").startOf("day").format("YYYY/MM/DD HH:mm:ss"),this.settings.range_ends=moment(ns.date.current).endOf("day").format("YYYY/MM/DD HH:mm:ss");break;case"week":this.settings.range_starts=moment(ns.date.current).subtract(6,"days").startOf("day").format("YYYY/MM/DD HH:mm:ss"),this.settings.range_ends=moment(ns.date.current).endOf("day").format("YYYY/MM/DD HH:mm:ss");break}},debounceForAvailability(e,s){e.busy&&(this.mouseDown=!0,setTimeout(()=>{this.mouseDown&&(this.setAvailable(e),s.preventDefault())},600))},async openOrderOption(e){try{const s=await new Promise((t,o)=>{Popup.show(z,{resolve:t,reject:o,order:e})});this.showTableHistory(this.selectedTable)}catch(s){console.log(s)}},addProduct(e){Gastro.selectedOrder.next(e),Gastro.setAddButtonsVisibility("visible"),this.popup.close()},printOrder(e){POS.print.process(e.id,"sale","aloud")},async payOrder(e){const s=Popup.show(nsPOSLoadingPopup),t=POS.order.getValue();try{if(await POS.loadOrder(e.id),POS.order.getValue().payment_status==="paid")return POS.reset(),nsSnackBar.error(this.localization("Unable to make a payment for an already paid order.","NsGastro")).subscribe();this.proceedCustomerLoading(t),this.isPaying=!0}catch(o){console.log(o)}s.close()},async proceedCustomerLoading(e){const s=[ProductsQueue,CustomerQueue,TypeQueue,PaymentQueue],t=POS.order.getValue();for(let o in s)try{const i=await new s[o](t).run()}catch{return!1}},async openSettingsOptions(){try{const e=await new Promise((s,t)=>{Popup.show(y,{fields:[{type:"datetimepicker",name:"range_starts",label:this.localization("Start Range","NsGastro"),value:this.settings.range_starts,description:this.localization("Define when from which moment the orders should be fetched.","NsGastro")},{type:"datetimepicker",name:"range_ends",label:this.localization("End Range","NsGastro"),value:this.settings.range_ends,description:this.localization("Define till which moment the orders should be fetched.","NsGastro")}],resolve:s,reject:t,settings:this.settings,title:this.localization("Settings","NsGastro")})});this.settings=e,this.showTableHistory(this.selectedTable)}catch(e){console.log(e)}},getMealBGClass(e){switch(e.cooking_status){case"ready":return"bg-success-secondary";case"ongoing":return"bg-info-secondary";case"canceled":return"bg-input-disabled";case"processed":return"bg-success-secondary";case"requested":return"bg-warning-secondary"}},getMealProductTextColor(e){switch(e.cooking_status){case"canceled":return"text-secondary";default:return"text-primary"}},getMealModifierTextColor(e){switch(e.cooking_status){case"canceled":return"text-secondary";default:return"text-primary"}},closePopup(){this.popup.close(),this.popup.params.reject(!1)},returnToAreas(){this.loadAreas()},returnToTables(){this.selectedTable=null,this.loadTables(this.selectedArea)},loadAreas(){this.screen="areas",this.areasLoaded=!1,nsHttpClient.get("/api/gastro/areas").subscribe({next:e=>{this.areasLoaded=!0,this.areas=e},error:e=>{nsSnackBar.error(e.message||this.localization("An unexpected error has occured.","NsGastro"),this.localization("OK","NsGastro"),{duration:0}).subscribe()}})},setAvailable(e){Popup.show(nsConfirmPopup,{title:this.localization("Set the table as available ?","NsGastro"),message:this.localization("You'll set the table as available, please confirm your action.","NsGastro"),onAction:s=>{s&&nsHttpClient.post(`/api/gastro/tables/${e.id}/change-availability`,{status:"available"}).subscribe({next:t=>{this.loadTables(this.selectedArea),nsSnackBar.success(t.message,this.localization("OK","NsGastro"),{duration:3e3}).subscribe()},error:t=>{nsSnackBar.error(t.message||this.localization("An unexpected error has occured.","NsGastro"),this.localization("OK","NsGastro"),{duration:3e3}).subscribe()}})}})},loadTables(e=null){this.selectedArea=e,this.screen="tables",this.tableLoaded=!1,this.additionalTitle=null,(e===null?nsHttpClient.get(`/api/gastro/tables?filter=${this.filterMode}`):nsHttpClient.get(`/api/gastro/areas/${e.id}/available-tables?filter=${this.filterMode}`)).subscribe({next:t=>{this.tableLoaded=!0,this.tables=t.map(o=>(o.selectedSeats=1,o.selected=this.order.table&&this.order.table.id===o.id?this.order.table:!1,o))},error:t=>{nsSnackBar.error(t.message||this.localization("An unexpected error has occured.","NsGastro"),this.localization("OK","NsGastro"),{duration:0}).subscribe()}})},selectQuantity(e){return this.mode==="select"?this.proceedSelect(e):this.showTableHistory(e)},showTableHistory(e){this.selectedTable=e,this.additionalTitle=this.localization("{table} : Orders History - {availability}","NsGastro").replace("{availability}",e.busy?this.localization("Busy","NsGastro"):this.localization("Available","NsGastro")).replace("{table}",e.name),this.screen="orders",this.ordersLoaded=!1,nsHttpClient.post(`/api/gastro/tables/${e.id}/orders`,this.settings).subscribe({next:s=>{this.ordersLoaded=!0,s.map(t=>(this.showDetails[t.code]===void 0&&(this.showDetails[t.code]=!1),t)),this.orders=s}})},async showProductOptions(e){try{const s=await new Promise((t,o)=>{Popup.show(T,{resolve:t,reject:o,product:e})});this.showTableHistory(this.selectedTable)}catch(s){console.log(s)}},async proceedSelect(e){if(this.ns_gastro_seats_enabled)try{this.tables.forEach(s=>{s.selected=!1,s.selectedSeats=1}),e=await new Promise((s,t)=>{Popup.show(q,{resolve:s,reject:t,table:e})})}catch{return nsSnackBar.error(this.localization("You need to define the seats before proceeding.","NsGastro")).subscribe()}e.selected=!0,this.order.table_id=e.id,this.order.table=e,this.order.area_id=e.area_id,this.order.type===void 0&&(this.order.type=Gastro.getType()),this.order.type.label=Gastro.getType().label,POS.order.next(this.order),this.popup.close(),this.popup.params.resolve(e)}}},A={name:"gastro-to-kitchen-button",template:`
    <button id="to-kitchen-button" 
        @click="submitToKitchen()"
        class="outline-none flex-shrink-0 w-1/4 flex items-center font-bold cursor-pointer justify-center go-bg-blue-500 text-white border-r hover:go-bg-blue-600 go-border-blue-600 flex-auto">
        <span><i class="las la-utensils text-2xl lg:text-xl"></i> 
        <span class="text-lg hidden md:inline lg:text-2xl">{{ localization( 'Kitchen', 'NsGastro' ) }}</span></span>
    </button>
    `,mounted(){POS.order.subscribe(e=>{this.order=e})},data(){return{order:{},increment:0}},methods:{localization:__m,async submitToKitchen(){const e=nsHooks.applyFilters("ns-hold-queue",[ProductsQueue,CustomerQueue,TypeQueue]);for(let t in e)try{const r=await new e[t](this.order).run()}catch{return!1}this.order.payment_status="hold",POS.order.next(this.order);const s=Popup.show(nsPOSLoadingPopup);try{const t=await POS.submitOrder();s.close(),nsSnackBar.success(t.message).subscribe()}catch(t){s.close(),nsSnackBar.error(t.message||this.localization("An unexpected error occured.","NsGastro")).subscribe()}}}};let B=class{constructor(){c(this,"addButtonsVisible",new RxJS.ReplaySubject);c(this,"tableOpenedSubject",new RxJS.ReplaySubject);c(this,"selectedOrdersSubject",new RxJS.BehaviorSubject([]));c(this,"tableOpenedStatus",!1);c(this,"defaultCartButtons",[]);c(this,"currentScreen");c(this,"addToOrderButton",null);c(this,"selectedOrder",new RxJS.BehaviorSubject);nsHooks.addAction("ns-pos-pending-orders-refreshed","gastro-add-controls",s=>{s.forEach(t=>{if(t.dom&&!t.dom.querySelector(".gastro-controls")){const o=document.createElement("button");o.setAttribute("class","gastro-controls info px-2"),o.innerHTML=`<i class="las la-cog"></i> ${__m("More","NsGastro")}`,o.addEventListener("click",async()=>{switch(await new Promise((i,a)=>{Popup.show(nsSelectPopup,{label:__m("Order Options","NsGastro"),description:__m("Select an option to apply to this order.","NsGastro"),options:[{label:__m("Split Order","NsGastro"),value:"split"},{label:__m("Select For Merge","NsGastro"),value:"merge"}],resolve:i,reject:a})})){case"split":Popup.show(v,{order:t.order});break;case"merge":this.selectOrderForMerging(t.order);break}}),t.dom.querySelector(".ns-buttons").appendChild(o)}})}),nsHooks.addAction("ns-pos-header","gastro-add-table-button",s=>this.addHeaderButton(s)),nsHooks.addAction("ns-after-product-computed","gastro-update-product",s=>this.computeProduct(s)),nsHooks.addAction("ns-cart-after-refreshed","gastro-build-modifier",s=>setTimeout(()=>this.buildModifierVue(s),100)),nsHooks.addAction("ns-before-load-order","gastro-catch-order",s=>this.retrictOrderEdition()),nsHooks.addFilter("ns-pending-orders-right-column","gastro-right-column",s=>(s.push({label:__m("Table Name","Gastro"),value:t=>t.table_name||__m("N/A","Gastro")}),s)),this.tableOpenedSubject.subscribe(s=>this.tableOpenedStatus=s),this.addButtonsVisible.subscribe(s=>{if(s){POS.cartButtons.next([]);const t={};t.nsGastroAddButtons=S,POS.cartButtons.next(t)}else POS.cartButtons.next(this.defaultCartButtons)}),nsExtraComponents.nsGastroTable=m}getType(){return{identifier:"dine-in",label:`Dine in ${(()=>(POS.order.getValue().table,""))()}`,icon:GastroSettings.icons.chair,selected:!1}}selectOrderForMerging(s){return new Promise((t,o)=>{const r=this.selectedOrdersSubject.getValue();r.filter(a=>a.code===s.code).length>0?(nsSnackBar.error(__m("The order is already selected.","NsGastro"),__m("Close","NsGastro")).subscribe(),o(!1)):(r.push(s),this.selectedOrdersSubject.next(r),nsSnackBar.success(__m('The order "{orderCode}" is selected.',"NsGastro").replace("{orderCode}",s.code),__m("Close","NsGastro")).subscribe(),t(!0))})}retrictOrderEdition(){if(!GastroSettings.permissions.gastroEditOrder&&!this.tableOpenedStatus)throw nsSnackBar.error(__("You're not allowed to edit orders.")).subscribe(),"Not allowed"}printOrderCanceledMealKitchen(s,t=[]){if(!GastroSettings.ns_gastro_allow_cancelation_print)return!1;const o=nsHooks.applyFilters("ns-gastro-print-order-canceled-meal",{status:"error",message:__m("No Print Handler for canceled meals","NsGastro"),data:{order_id:s,products_id:t}});o.status==="error"&&nsSnackBar.error(o.message).subscribe()}setAddButtonsVisibility(s){s==="visible"?this.addButtonsVisible.next(!0):this.addButtonsVisible.next(!1)}boot(){this.bindPromise(),this.registerCustomOrderType(),this.injectSendToKitchenPopup(),nsHooks.addAction("ns-after-cart-reset","ns-gastro-cart-buttons",()=>{this.removeHoldButton(),this.addToKitchenButton(),this.selectedOrdersSubject.next([]),this.defaultCartButtons=POS.cartButtons.getValue()},20)}removeHoldButton(){const s=POS.cartButtons.getValue();delete s.nsPosHoldButton,POS.cartButtons.next(s)}addToKitchenButton(){const s=POS.cartButtons.getValue(),t=ns.insertAfterKey(s,"nsPosPayButton","nsGastroToKitchen",markRaw(A));POS.cartButtons.next(t)}injectAddToOrderButtons(){}bindPromise(){POS.addToCartQueue.ModifierPromise=b}addHeaderButton(s){return GastroSettings.ns_pos_order_types&&GastroSettings.ns_pos_order_types.filter(o=>o==="dine-in").length>0&&(s.buttons.GastroTableButton=defineAsyncComponent(()=>h(()=>import("./gastro-table-button-a4c725cd.js"),["assets/gastro-table-button-a4c725cd.js","assets/gastro-kitchen-settings-c82407e2.js","assets/gastro-assets-61a733a8.css"]))),s.buttons.GastroOrdersButton=defineAsyncComponent(()=>h(()=>import("./gastro-pos-orders-button-d8a2209f.js"),[])),s.buttons.GastroSplitOrderButton=defineAsyncComponent(()=>h(()=>import("./gastro-split-orders-button-663bfb97.js"),["assets/gastro-split-orders-button-663bfb97.js","assets/gastro-kitchen-settings-c82407e2.js","assets/gastro-assets-61a733a8.css"])),s.buttons.GastroMergeOrderButton=defineAsyncComponent(()=>h(()=>import("./gastro-merge-orders-button-0c01394d.js"),["assets/gastro-merge-orders-button-0c01394d.js","assets/gastro-kitchen-settings-c82407e2.js","assets/gastro-assets-61a733a8.css"])),s}registerCustomOrderType(){if(POS.types.getValue(),!(GastroSettings.ns_pos_order_types.filter(t=>t==="dine-in").length>0))return!1;POS.orderTypeQueue.push({identifier:"gastro.table",promise:async t=>await new Promise((o,r)=>{t.identifier==="dine-in"?Popup.show(m,{resolve:o,reject:r}):o(!0)})})}computeProduct(s){if(s.modifiersGroups!==void 0&&s.modifiersGroups.length>0){let t=0;s.modifiersGroups.length>0&&s.modifiersGroups.forEach(o=>{o.modifiers.forEach(r=>{t+=r.total_price})}),s.modifiers_total=t*s.quantity,s.modifiers_net_total=t*s.quantity,s.modifiers_gross_total=t*s.quantity,s.total_price=(s.unit_price+t)*s.quantity,s.total_price_with_tax=(s.unit_price+t)*s.quantity,s.total_price_without_tax=(s.unit_price+t)*s.quantity}}buildModifierVue(s){s.products.forEach((t,o)=>{const r=document.querySelector(`[product-index="${o}"]`);if(r===null)return!1;r.querySelector(".modifier-container")!==null&&r.querySelector(".modifier-container").remove(),this.injectModifiersGroups(t,o),this.injectCutleryOptions(t,o)})}injectSendToKitchenPopup(){nsHooks.addFilter("ns-hold-queue","gastro-inject-send-to-kitchen",s=>(s.push(k),s))}injectModifiersGroups(s,t){if(s.modifiersGroups&&s.modifiersGroups.length>0){const o=document.querySelector(`[product-index="${t}"]`),r=document.createElement("div");r.className="modifier-container mt-2 text-sm cursor-pointer",r.setAttribute("product-reference",t),o.querySelector("div").appendChild(r),s.modifiersGroups.forEach(i=>{i.modifiers.forEach(a=>{const l=document.createElement("template"),n=`
                    <div class="single-modifier p-1 flex justify-between">
                        <span>${i.name} : ${a.name} (x${a.quantity})</span>
                        <div class="flex">
                            <span>${nsCurrency(a.total_price)}</span>
                            <ns-close-button></ns-close-button>
                        </div>
                    </div>
                    `;l.innerHTML=n.trim(),o.querySelector(".modifier-container").appendChild(l.content.firstChild)})}),r.addEventListener("click",async function(){const i=this.getAttribute("product-reference");let a=POS.order.getValue().products[i];try{const n=await new b(a).run(a),d={...a,...n};POS.updateProduct(a,d,i)}catch(l){console.log(l)}})}}injectCutleryOptions(s,t){const o=document.querySelector(`[product-index="${t}"]`);if(s=POS.products.getValue()[t],o.querySelectorAll(".cutlery-options").length===0){const r=document.createElement("template"),i=`
                <div class="px-1 cutlery-options">
                    <a class="hover:text-blue-600 cursor-pointer outline-none border-dashed py-1 border-b  text-sm border-blue-400">
                        <i class="las la-utensils text-xl"></i>
                    </a>
                </div>
            `;r.innerHTML=i.trim(),o.querySelector(".product-options").appendChild(r.content.firstChild),o.querySelector(".cutlery-options a").addEventListener("click",function(){s=POS.products.getValue()[t],new Promise((a,l)=>{Popup.show(N,{resolve:a,reject:l,product:s})}).then(a=>{console.log(a)}).catch(a=>console.log(a))})}}};window.Gastro=new B;document.addEventListener("DOMContentLoaded",()=>{window.Gastro.boot()});export{v as a,C as b,m as g};
