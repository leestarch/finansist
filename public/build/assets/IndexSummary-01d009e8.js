import{r as i,o as k,p as T,b as n,c as _,d,e as B,N as C,g as o,h as b,Q as p,i as M,j as I,l as u,m as D,t as c,F,q as V}from"./app-709df95d.js";import{Q as S}from"./QSelect-38babbb6.js";import{Q as U}from"./QForm-b214abb1.js";import{a as O,Q as q}from"./QTable-b060046c.js";import{Q as j}from"./QPage-aa58c21f.js";const z={class:"row justify-between"},J={class:"row q-mt-md"},A={__name:"IndexSummary",setup(L){const r=i([]),m=i([]),f=i([]),x=()=>{const t=new Date;return t.setDate(1),t.toISOString().split("T")[0]},Q=()=>{const t=new Date;return t.setMonth(t.getMonth()+1),t.setDate(0),t.toISOString().split("T")[0]},l=i({dateFrom:x(),dateTo:Q(),type:null,category:null}),N=()=>{JSON.parse(JSON.stringify(l.value)),y()},g=t=>new Intl.NumberFormat("ru-RU",{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}).format(t),y=async()=>{try{const t=await axios.get("/api/operations/summary",{params:l.value});r.value=t.data.data,m.value=t.data.categories,f.value=t.data.types,console.log(r.value)}catch{C.create({message:"Ошибка получения данных",color:"red",timeout:2e3})}};k(()=>{y()});const v=T(()=>{const t=[],a=new Date(l.value.dateFrom),e=new Date(l.value.dateTo);for(;a<=e;){const s=a.toLocaleDateString("en-GB").replace(/\//g,"-");t.push({name:s,label:s,field:s}),a.setDate(a.getDate()+1)}return[{name:"category",label:"Категория",field:"category",align:"left"},...t,{name:"total",label:"Итог за период",field:"total"}]});return(t,a)=>r.value?(n(),_(j,{key:0},{default:d(()=>[o(U,{onSubmit:I(N,["prevent"]),class:"items-center q-pa-md bg-grey-4"},{default:d(()=>[b("div",z,[o(p,{class:"col-2",clearable:"",dense:"",outlined:"",filled:"",modelValue:l.value.dateFrom,"onUpdate:modelValue":a[0]||(a[0]=e=>l.value.dateFrom=e),label:"Дата начала",type:"date"},null,8,["modelValue"]),o(p,{class:"col-2",dense:"",clearable:"",outlined:"",filled:"",modelValue:l.value.dateTo,"onUpdate:modelValue":a[1]||(a[1]=e=>l.value.dateTo=e),label:"Дата окончания",type:"date"},null,8,["modelValue"]),o(S,{class:"col-3",dense:"",clearable:"",outlined:"",filled:"",modelValue:l.value.type,"onUpdate:modelValue":a[2]||(a[2]=e=>l.value.type=e),options:f.value,label:"Фильтр по типам","option-value":"id","option-label":"name"},null,8,["modelValue","options"]),o(S,{class:"col-3",dense:"",clearable:"",outlined:"",filled:"",modelValue:l.value.category,"onUpdate:modelValue":a[3]||(a[3]=e=>l.value.category=e),options:m.value,label:"Фильтр по категориям","option-value":"id","option-label":"name"},null,8,["modelValue","options"])]),b("div",J,[o(M,{class:"text-right",dense:"",size:"sm",type:"submit",label:"Применить фильтры",color:"primary"})])]),_:1}),o(q,{rows:r.value,columns:v.value,"row-key":"category","rows-per-page-options":[0]},{"body-cell":d(e=>[o(O,{props:e},{default:d(()=>{var s,w;return[e.col.field==="category"?(n(),u(F,{key:0},[D(c(e.row.category),1)],64)):e.col.field==="total"?(n(),u("span",{key:1,class:V({"text-red":(s=e.row[e.col.field])==null?void 0:s.toString().startsWith("-"),"text-green":parseFloat(e.row[e.col.field])>0,"":parseFloat(e.row[e.col.field])===0})},c(g(e.row.total)),3)):v.value.some(h=>h.name===e.col.field)?(n(),u("span",{key:2,class:V({"text-red":(w=e.row[e.col.field])==null?void 0:w.toString().startsWith("-"),"text-green":parseFloat(e.row[e.col.field])>0,"":parseFloat(e.row[e.col.field])===0})},c(g(e.row[e.col.field]||0)),3)):(n(),u(F,{key:3},[D(c(e.row[e.col.field]),1)],64))]}),_:2},1032,["props"])]),_:1},8,["rows","columns"])]),_:1})):B("",!0)}};export{A as default};
