import{Q as l}from"./QPage-aa58c21f.js";import{u,r as n,o as p,b as d,c as m,d as g,N as f,h as o,g as _}from"./app-709df95d.js";import{_ as x}from"./RulesTable-80351062.js";import"./QTable-b060046c.js";import"./QSelect-38babbb6.js";import"./QPagination-965ff517.js";const y={class:"row justify-center"},v={class:"justify-center col-12 q-px-md q-mt-lg"},k={__name:"Rules",setup(h){const c=u().params.id,s=n([]),r=n({page:1,rowsPerPage:20,rowsNumber:0}),e=async()=>{try{const t=await axios.get("/api/rules",{params:{contractor_id:c,load:["category,contractor"]}});console.log(t.data.data),s.value=t.data.data}catch{f.create({message:"Ошибка получения данных",color:"red",timeout:2e3})}};return p(()=>{e()}),(t,a)=>(d(),m(l,{class:"bg-grey-3"},{default:g(()=>[o("div",y,[o("div",v,[a[1]||(a[1]=o("p",{class:"text-h6 text-center"}," Правила ",-1)),_(x,{rules:s.value,pagination:r.value,"onUpdate:pagination":a[0]||(a[0]=i=>{r.value=i,e()}),onRefresh:e},null,8,["rules","pagination"])])])]),_:1}))}};export{k as default};
