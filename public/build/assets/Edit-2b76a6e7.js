import{Q as p}from"./QSelect-38babbb6.js";import{u as x,r,o as w,b as V,c as h,d as c,N as s,h as g,g as l,j as Q,Q as _,i as q,n as C}from"./app-709df95d.js";import{Q as B}from"./QForm-b214abb1.js";import{Q as I}from"./QPage-aa58c21f.js";const E={class:"row q-mx-auto bg-white col-10 col-sm-8"},U={__name:"Edit",setup(T){const d=x().params.id,n=r([]),u=r(!1),i=r(null),t=r({name:"",purpose_expression:""}),v=[{label:"DEBIT",value:"DEBIT"},{label:"CREDIT",value:"CREDIT"}],f=async()=>{try{const a=await axios.get(`/api/rules/${d}`,{params:{include:"category"}});t.value=a.data.data,i.value=t.value.category}catch{s.create({message:"Ошибка получения данных",color:"red",timeout:2e3})}};w(async()=>{await f()});const y=async()=>{var a;try{const e=await axios.put(`/api/rules/${d}`,t.value);(a=e==null?void 0:e.data)!=null&&a.success?s.create({message:"Данные успешно сохранены",color:"green",timeout:2e3}):s.create({message:"Ошибка сохранения данных",color:"red",timeout:2e3})}catch{s.create({message:"Ошибка сохранения данных",color:"red",timeout:2e3})}},b=async(a,e,o)=>{if(a.length>4){u.value=!0;try{const m=await axios.get("/api/categories",{params:{q:a}});n.value=m.data.data,e(()=>n.value)}catch{s.create({message:"Fetching categories failed",color:"red"})}u.value=!1}};return(a,e)=>(V(),h(I,{class:"row shadow-3 bg-grey-2"},{default:c(()=>[g("div",E,[l(C,{class:"bg-white q-px-xl blue col-12"},{default:c(()=>[e[3]||(e[3]=g("div",{class:"text-h4 q-mt-md"}," Редактирование правила ",-1)),l(B,{class:"q-mt-xl",onSubmit:Q(y,["prevent"])},{default:c(()=>[l(p,{class:"col-3 q-mt-md",dense:"",clearable:"",outlined:"",filled:"",modelValue:i.value,"onUpdate:modelValue":e[0]||(e[0]=o=>i.value=o),options:n.value,label:"Выберите категорию","option-value":"id","option-label":"name","use-input":"","input-debounce":"300",hint:"Start typing to search",onFilter:b,loading:u.value},null,8,["modelValue","options","loading"]),l(p,{class:"col-3 q-mt-md",dense:"",clearable:"",outlined:"",filled:"",modelValue:t.value.operation_type,"onUpdate:modelValue":e[1]||(e[1]=o=>t.value.operation_type=o),options:v,label:"Тип операции"},null,8,["modelValue"]),l(_,{"model-value":"name",label:"Регулярное выражение назначения",class:"col-3 q-mt-md",outlined:"",dense:"",modelValue:t.value.purpose_expression,"onUpdate:modelValue":e[2]||(e[2]=o=>t.value.purpose_expression=o),filled:""},null,8,["modelValue"]),l(q,{class:"q-mt-md",label:"Сохранить",type:"submit",color:"primary"})]),_:1})]),_:1})])]),_:1}))}};export{U as default};