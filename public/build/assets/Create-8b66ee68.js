import{r as o,o as _,c as Q,w as d,b as x,a as t,f as V,s as B,d as I,e as N,g as T,N as u}from"./app-4df6b152.js";import{a as g}from"./QSelect-995ec182.js";import{Q as D}from"./QForm-a72fcf24.js";import{Q as E}from"./QPage-b9f7ef16.js";const F={class:"row q-mx-auto bg-white col-10 col-sm-8"},R={__name:"Create",setup(S){var y;const i=o([]),b=o([]),f=[{label:"DEBIT",value:"DEBIT"},{label:"CREDIT",value:"CREDIT"}],c=o(!1),p=o(!1),m=o([]),n=o(null),v=o(f[0]),s=o({purpose_expression:"",operation_type:(y=v==null?void 0:v.value)==null?void 0:y.value}),q=async()=>{var a;s.value.contractor_ids=m.value.map(e=>e.id),s.value.category_id=(a=n==null?void 0:n.value)==null?void 0:a.id;try{(await axios.post("/api/rules",s.value)).data.success?u.create({message:"Правило успешно создано",color:"green"}):u.create({message:"Правило не создано",color:"red"})}catch{u.create({message:"Ошибка создания правила",color:"red"})}},w=async(a,e,l)=>{if(a.length>3){c.value=!0;try{const r=await axios.get("/api/categories",{params:{q:a}});i.value=r.data.data,e(()=>i.value)}catch{u.create({message:"Ошибка получения категорий",color:"red"})}c.value=!1}},C=async(a,e,l)=>{if(a.length>3){p.value=!0;try{const r=await axios.get("/api/contractors",{params:{q:a}});b.value=r.data.data,e(()=>i.value)}catch{u.create({message:"Ошибка получения контрагентов",color:"red"})}}p.value=!1};return(a,e)=>(_(),Q(E,{class:"row shadow-3 bg-grey-2"},{default:d(()=>[x("div",F,[t(T,{class:"bg-white q-px-xl blue col-12"},{default:d(()=>[t(V,{class:"q-my-md",icon:"arrow_back",to:"/operations/rules"},{default:d(()=>e[4]||(e[4]=[B("Назад")])),_:1}),e[5]||(e[5]=x("div",{class:"text-h4 q-mt-md"}," Создание правила ",-1)),t(D,{class:"q-mt-xl",onSubmit:I(q,["prevent"])},{default:d(()=>[t(g,{class:"col-3 q-mt-md",dense:"",clearable:"",outlined:"",filled:"",modelValue:n.value,"onUpdate:modelValue":e[0]||(e[0]=l=>n.value=l),options:i.value,label:"Выберите категорию","option-value":"id","option-label":"name","use-input":"","input-debounce":"300",onFilter:w,loading:c.value},null,8,["modelValue","options","loading"]),t(N,{"model-value":"name",label:"Регулярное выражение назначения",class:"col-3 q-mt-md",outlined:"",dense:"",modelValue:s.value.purpose_expression,"onUpdate:modelValue":e[1]||(e[1]=l=>s.value.purpose_expression=l),filled:""},null,8,["modelValue"]),t(g,{class:"col-3 q-mt-md",dense:"",clearable:"",outlined:"",filled:"","option-value":"value","option-label":"label","emit-value":"",modelValue:s.value.operation_type,"onUpdate:modelValue":e[2]||(e[2]=l=>s.value.operation_type=l),options:f,label:"Тип операции"},null,8,["modelValue"]),t(g,{class:"col-3 q-mt-md",dense:"",clearable:"",outlined:"",filled:"",modelValue:m.value,"onUpdate:modelValue":e[3]||(e[3]=l=>m.value=l),options:b.value,label:"Выберете контрагентов","option-value":"id","option-label":"name","use-input":"",multiple:"","input-debounce":"300",onFilter:C,loading:p.value},null,8,["modelValue","options","loading"]),t(V,{class:"q-mt-md",label:"Создать",type:"submit",color:"primary"})]),_:1})]),_:1})])]),_:1}))}};export{R as default};