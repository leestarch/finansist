import{r as C,x as R,o as a,p as o,a as d,w as p,F as i,q as m,t as u,l as r,y as j,b as g,c as B,n as T,a1 as U,P as h,N as k}from"./app-7be9a94d.js";import{a as O,Q as E}from"./QTable-da06004b.js";import{Q as F}from"./QPagination-71612767.js";const I={key:0,class:"shadow-3"},M={key:0},$={class:"text-primary"},z={key:0},A={key:3},D={class:"row bg-white justify-center q-pb-md rounded-borders"},L={key:1,class:"row q-mt-sm bg-white justify-center q-pb-md rounded-borders shadow-3"},J={__name:"OperationTable",props:{operations:Array,pagination:Object},emits:["update:pagination","refresh"],setup(t,{emit:x}){const P=t,v=C([{name:"sber_amountRub",label:"Сумма",field:"sber_amountRub",align:"left"},{name:"categories",label:"Категория",field:"categories",align:"left"},{name:"is_manual",label:"is manual",field:"is_manual",align:"left"},{name:"actions",label:"Действия",align:"left"},{name:"date_at",label:"Дата",field:"date_at",align:"left"},{name:"sber_paymentPurpose",label:"Назначение",field:"sber_paymentPurpose",align:"left"}]),V=x,q=c=>{V("update:pagination",{...P.pagination,page:c})},N=async c=>{var n;h.show();try{const l=await axios.put(`/api/operations/${c.id}`,{is_manual:c.is_manual});(n=l==null?void 0:l.data)!=null&&n.success&&k.create({message:"Обновлено",color:"green",timeout:2e3})}catch{k.create({message:"Ошибка обновления",color:"red",timeout:2e3})}h.hide()};return(c,n)=>{const l=R("router-link");return t.operations.length?(a(),o("div",I,[d(E,{flat:"",class:"q-mt-md q-px-sm",rows:t.operations,pagination:{rowsPerPage:t.pagination.rowsPerPage},columns:v.value,"row-key":"id","hide-bottom":""},{"body-cell":p(e=>[d(O,{item:e},{default:p(()=>{var b,_,y,w,f;return[e.col.name==="sber_amountRub"?(a(),o(i,{key:0},[m(u((b=e.row)==null?void 0:b.sber_amountRub),1)],64)):r("",!0),e.col.name==="categories"?(a(),o(i,{key:1},[((_=e.row)==null?void 0:_.categories.length)>1?(a(),o("span",M,[(a(!0),o(i,null,j(e.row.categories,(s,Q)=>(a(),o("span",null,[m(u(s.name)+" ",1),g("span",$," ("+u(s.sber_amountRub)+") ",1),Q<e.row.categories.length-1?(a(),o("span",z,", ")):r("",!0)]))),256))])):(a(),o(i,{key:1},[m(u((y=e.row)==null?void 0:y.categories.map(s=>s.name).join(", ")),1)],64))],64)):r("",!0),e.col.name==="is_manual"?(a(),B(T,{key:2,"onUpdate:modelValue":[s=>e.row.is_manual=s,s=>N(e.row)],"model-value":e.row.is_manual},null,8,["onUpdate:modelValue","model-value"])):r("",!0),e.col.name==="actions"?(a(),o("div",A,[d(l,{to:{name:"OperationEdit",params:{id:e.row.id}},class:"cursor-pointer"},{default:p(()=>[d(U,{color:"primary",size:"xs",name:"edit",class:"cursor-pointer"})]),_:2},1032,["to"])])):r("",!0),e.col.name==="date_at"?(a(),o(i,{key:4},[m(u((w=e.row)==null?void 0:w.date_at),1)],64)):r("",!0),e.col.name==="sber_paymentPurpose"?(a(),o(i,{key:5},[m(u((f=e.row)==null?void 0:f.sber_paymentPurpose),1)],64)):r("",!0)]}),_:2},1032,["item"])]),_:1},8,["rows","pagination","columns"]),g("div",D,[d(F,{modelValue:t.pagination.page,"onUpdate:modelValue":[n[0]||(n[0]=e=>t.pagination.page=e),q],max:Math.ceil(t.pagination.rowsNumber/t.pagination.rowsPerPage),"boundary-numbers":"","boundary-links":"","max-pages":10,class:"text-center q-mt-md"},null,8,["modelValue","max"])])])):(a(),o("div",L,n[1]||(n[1]=[g("p",{class:"text-h6"}," Для этого контрагента операции не найдены ",-1)])))}}};export{J as _};
