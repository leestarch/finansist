import{u as b,r as i,k as y,o as g,c as h,w as c,P as l,N as u,s as w,a as s,b as x,d as V,e as q,f as Q,g as _,l as C}from"./app-6990ba06.js";import{a as k}from"./QSelect-39cf87be.js";import{Q as B}from"./QForm-ce3dfc0a.js";import{Q as N}from"./QPage-85c522ef.js";const F={key:0,class:"row q-mx-auto bg-white col-10 col-sm-8"},$={__name:"Edit",setup(P){const d=b().params.id,r=i([]),n=i(!1),o=i({name:"",parent:null}),m=async()=>{l.show();try{const e=await axios.get(`/api/categories/${d}`,{params:{load:"parent"}});o.value=e.data.data,console.log(e.data.data)}catch(e){console.log(e),u.create({message:"Ошибка получения данных",color:"red"})}l.hide()},f=async(e,a,t)=>{if(e.length>=2){n.value=!0;try{const p=await axios.get("/api/categories",{params:{q:e}});r.value=p.data.data,a(()=>r.value)}catch{u.create({message:"Fetching categories failed",color:"red"})}n.value=!1}},v=async()=>{var e,a;l.show();try{o.value.parent_id=(e=o.value.parent)==null?void 0:e.id;const t=await axios.put(`/api/categories/${d}`,o.value);(a=t==null?void 0:t.data)!=null&&a.success&&(u.create({message:"Данные успешно сохранены",color:"green",timeout:2e3}),await m())}catch(t){console.log(t)}l.hide()};return y(()=>{m()}),(e,a)=>(g(),h(N,{class:"row shadow-3 bg-grey-2"},{default:c(()=>[o.value?(g(),w("div",F,[s(_,{class:"bg-white q-px-xl blue col-12"},{default:c(()=>[a[2]||(a[2]=x("div",{class:"text-h4 q-mt-md"}," Редактирование категории ",-1)),s(B,{class:"q-mt-xl",onSubmit:V(v,["prevent"])},{default:c(()=>[s(q,{label:"Название",dense:"",outlined:"",modelValue:o.value.name,"onUpdate:modelValue":a[0]||(a[0]=t=>o.value.name=t),class:"q-mt-md"},null,8,["modelValue"]),s(k,{class:"col-3 q-mt-md",dense:"",clearable:"",outlined:"",modelValue:o.value.parent,"onUpdate:modelValue":a[1]||(a[1]=t=>o.value.parent=t),options:r.value,label:"Родительская категория","option-value":"id","option-label":"name","use-input":"","input-debounce":"300",hint:"Введите не менее 3 символов",onFilter:f,loading:n.value},null,8,["modelValue","options","loading"]),s(Q,{type:"submit",label:"Сохранить",color:"primary",class:"q-mt-md"})]),_:1})]),_:1})])):C("",!0)]),_:1}))}};export{$ as default};
