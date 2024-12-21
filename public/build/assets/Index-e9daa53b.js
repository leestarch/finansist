import{r as y,k as z,U,o as s,c as x,w as g,P as q,N as v,b as r,a as t,f as p,e as j,q as d,s as C,t as f,F as w,l as c,V as F}from"./app-4df6b152.js";import{Q as T,a as E}from"./QTable-2b5d2408.js";import{Q as I}from"./QPagination-21b8a7aa.js";import{Q as M}from"./QPage-b9f7ef16.js";import"./QSelect-995ec182.js";const $={class:"bg-white q-ma-sm q-pa-sm"},D={class:"rounded-borders bg-white shadow-3 row items-center justify-between q-px-md"},L={class:""},S={class:"row justify-between items-center"},A={class:"rounded-borders bg-white shadow-3"},G={class:"row bg-white justify-center q-pb-md rounded-borders"},X={__name:"Index",setup(H){const m=y([]),b=y(""),o=y({page:1,rowsPerPage:30,rowsNumber:0}),u=async n=>{q.show();try{const a=await axios.get("/api/categories",{params:{q:n,load:"parent,children",paginate:o.value.rowsPerPage,page:o.value.page}});m.value=a.data.data,o.value.rowsNumber=a.data.meta.total}catch(a){console.log(a),v.create({message:"Fetching categories failed",color:"red"})}q.hide()},V=async n=>{var a;try{const l=await axios.delete(`/api/categories/${n}`);(a=l==null?void 0:l.data)!=null&&a.success&&(v.create({message:"Правило успешно удалено",color:"green",timeout:2e3}),await u())}catch{v.create({message:"Ошибка удаления",color:"red",timeout:2e3})}},N=async n=>{o.value.page=n,await u()},Q=[{name:"name",label:"Название",align:"left"},{name:"parent",label:"Родитель",align:"left"},{name:"children",label:"Дочерние",align:"left"},{name:"actions",label:"Действие",align:"left"}],B=async()=>{await u(b.value)};return z(()=>{u()}),(n,a)=>{const l=U("router-link");return s(),x(M,{class:"bg-grey-2"},{default:g(()=>[r("div",$,[r("div",D,[r("div",L,[t(p,{to:{name:"CategoriesCreate"},size:"md",color:"primary",label:"Добавить"})]),r("div",S,[t(j,{modelValue:b.value,"onUpdate:modelValue":a[0]||(a[0]=e=>b.value=e),class:"q-px-sm q-my-sm",clearable:"",dense:"",outlined:"",label:"Поиск"},null,8,["modelValue"]),r("div",null,[t(p,{onClick:B,size:"md",color:"primary",label:"поиск"})])])]),r("div",A,[m.value.length?(s(),x(T,{key:0,class:"q-mt-md q-mx-sm",rows:m.value,columns:Q,"row-key":"id",pagination:{rowsPerPage:o.value.rowsPerPage},"hide-bottom":"",flat:"",loading:!m.value.length},{"body-cell":g(e=>[t(E,null,{default:g(()=>{var _,h,k;return[e.col.name=="name"?(s(),d(w,{key:0},[C(f(e.row.name),1)],64)):c("",!0),e.col.name=="parent"?(s(),d(w,{key:1},[C(f((_=e.row.parent)==null?void 0:_.name),1)],64)):c("",!0),e.col.name=="children"?(s(!0),d(w,{key:2},F((h=e.row)==null?void 0:h.children,i=>(s(),d("div",{key:i.id},f(i==null?void 0:i.name)+", ",1))),128)):c("",!0),e.col.name=="actions"?(s(),d(w,{key:3},[t(l,{to:{name:"CategoriesEdit",params:{id:(k=e.row)==null?void 0:k.id}}},{default:g(()=>[t(p,{flat:"",size:"sm",color:"primary",dense:"",icon:"edit"})]),_:2},1032,["to"]),t(p,{flat:"",size:"sm",dense:"",color:"red",icon:"delete",onClick:i=>{var P;return V((P=e.row)==null?void 0:P.id)}},null,8,["onClick"])],64)):c("",!0)]}),_:2},1024)]),_:1},8,["rows","pagination","loading"])):c("",!0),r("div",G,[t(I,{modelValue:o.value.page,"onUpdate:modelValue":[a[1]||(a[1]=e=>o.value.page=e),N],max:Math.ceil(o.value.rowsNumber/o.value.rowsPerPage),"boundary-numbers":"","boundary-links":"","max-pages":10,class:"text-center q-mt-md"},null,8,["modelValue","max"])])])])]),_:1})}}};export{X as default};