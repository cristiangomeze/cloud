(window.webpackJsonp=window.webpackJsonp||[]).push([[18],{"+wI1":function(t,e,s){"use strict";s.r(e);var i=s("atn8"),l=s("GL1+"),a=s("yHmh"),r=s("e8Yr"),n=s("WtJL"),o=s("bmJg"),u={components:{TextareaInput:s("mhDw").a,Icon:i.a,Layout:l.a,LoadingButton:a.a,SelectInput:r.a,TextInput:n.a,TrashedMessage:o.a},props:{organization:Object,bill:Object},remember:"form",data:function(){return{sending:!1,form:{client:this.bill.client,id:this.bill.id,due_amount:this.bill.due_amount,paid_out:""}}},methods:{submit:function(){var t=this;this.paid_out>this.due_amount&&(this.paid_out=this.due_amount),this.sending=!0,this.$inertia.put(this.route("invoice.bills.update",{slug:this.slug,bill:this.bill}),this.form).then(function(){t.sending=!1})}},computed:{canPaidBill:function(){return"EN PROCESO"===this.bill.status?"form-input":"form-input bg-grey-lighter"},disabled:function(){return!("EN PROCESO"===this.bill.status||this.due_amount>0)}}},c=s("KHd+"),d=Object(c.a)(u,function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("layout",{attrs:{title:"Factura "+t.bill.id}},[s("h1",{staticClass:"mb-8 font-bold text-3xl"},[s("inertia-link",{staticClass:"text-green-light hover:text-green-dark",attrs:{href:t.route("invoice.bills.index",t.organization.slug)}},[t._v("Facturas")]),t._v(" "),s("span",{staticClass:"text-green-light font-medium"},[t._v("/")]),t._v("\n        Factura "+t._s(t.bill.id)+"\n    ")],1),t._v(" "),"PAGADA"===t.bill.status?s("div",{staticClass:"mb-4 p-4 bg-blue-light rounded border border-blue-dark flex items-center justify-between"},[s("div",{staticClass:"flex items-center"},[s("icon",{staticClass:"flex-no-shrink w-4 h-4 fill-blue-darker mr-2",attrs:{name:"exclamation"}}),t._v(" "),s("div",{staticClass:"text-blue-darker"},[t._v("\n                Esta factura ya sido pagada.\n            ")])],1)]):t._e(),t._v(" "),s("div",{staticClass:"bg-white rounded shadow overflow-hidden max-w-lg"},[s("form",{on:{submit:function(e){return e.preventDefault(),t.submit(e)}}},[s("div",{staticClass:"p-8 -mr-6 -mb-8 flex flex-wrap"},[s("text-input",{staticClass:"pr-6 pb-8 w-full",attrs:{"input-class":"form-input bg-grey-lighter",label:"Cliente",disabled:""},model:{value:t.form.client,callback:function(e){t.$set(t.form,"client",e)},expression:"form.client"}}),t._v(" "),s("text-input",{staticClass:"pr-6 pb-8 w-full",attrs:{"input-class":"form-input bg-grey-lighter",label:"Factura",disabled:""},model:{value:t.form.id,callback:function(e){t.$set(t.form,"id",e)},expression:"form.id"}}),t._v(" "),s("text-input",{staticClass:"pr-6 pb-8 w-full",attrs:{"input-class":"form-input bg-grey-lighter",label:"Monto pendiente",disabled:""},model:{value:t.form.due_amount,callback:function(e){t.$set(t.form,"due_amount",e)},expression:"form.due_amount"}}),t._v(" "),s("text-input",{staticClass:"pr-6 pb-8 w-full",attrs:{type:"number",errors:t.$page.errors.paid_out,"input-class":t.canPaidBill,label:"Monto pendiente",disabled:t.disabled},model:{value:t.form.paid_out,callback:function(e){t.$set(t.form,"paid_out",e)},expression:"form.paid_out"}})],1),t._v(" "),t.disabled?t._e():s("div",{staticClass:"px-8 py-4 bg-grey-lightest border-t border-grey-lighter flex items-center"},[s("loading-button",{staticClass:"btn-green ml-auto",attrs:{loading:t.sending,type:"submit"}},[t._v("Abonar deuda")])],1)])]),t._v(" "),s("div",{staticClass:"mt-12"},[s("iframe",{staticClass:"w-full h-screen",attrs:{src:t.route("invoice.bills.show",{slug:t.organization.slug,bill:t.bill})}})])])},[],!1,null,null,null);e.default=d.exports},bmJg:function(t,e,s){"use strict";var i={components:{Icon:s("atn8").a}},l=s("KHd+"),a=Object(l.a)(i,function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"p-4 bg-yellow-light rounded border border-yellow-dark flex items-center justify-between"},[s("div",{staticClass:"flex items-center"},[s("icon",{staticClass:"flex-no-shrink w-4 h-4 fill-yellow-darker mr-2",attrs:{name:"trash"}}),t._v(" "),s("div",{staticClass:"text-yellow-darker"},[t._t("default")],2)],1),t._v(" "),s("button",{staticClass:"text-yellow-darker hover:underline",attrs:{tabindex:"-1",type:"button"},on:{click:function(e){return t.$emit("restore")}}},[t._v("Restaurar")])])},[],!1,null,null,null);e.a=a.exports},e8Yr:function(t,e,s){"use strict";var i={inheritAttrs:!1,props:{id:{type:String,default:function(){return"select-input-".concat(this._uid)}},value:[String,Number,Boolean],label:String,selectClass:String,errors:{type:Array,default:function(){return[]}}},data:function(){return{selected:this.value}},watch:{selected:function(t){this.$emit("input",t)}},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()}}},l=s("KHd+"),a=Object(l.a)(i,function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[t.label?s("label",{staticClass:"form-label",attrs:{for:t.id}},[t._v(t._s(t.label)+":")]):t._e(),t._v(" "),s("select",t._b({directives:[{name:"model",rawName:"v-model",value:t.selected,expression:"selected"}],ref:"input",class:[t.selectClass?t.selectClass:"form-select",{error:t.errors.length}],attrs:{id:t.id},on:{change:function(e){var s=Array.prototype.filter.call(e.target.options,function(t){return t.selected}).map(function(t){return"_value"in t?t._value:t.value});t.selected=e.target.multiple?s:s[0]}}},"select",t.$attrs,!1),[t._t("default")],2),t._v(" "),t.errors.length?s("div",{staticClass:"form-error"},[t._v(t._s(t.errors[0]))]):t._e()])},[],!1,null,null,null);e.a=a.exports}}]);
//# sourceMappingURL=18.b23b47e3a8e7792ce76c.js.map