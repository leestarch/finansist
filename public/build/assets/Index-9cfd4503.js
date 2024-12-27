import{r as v,k as z,A as j,o as s,c as P,w as g,P as q,N as y,b as r,a as t,f as p,e as F,s as d,v as C,t as f,F as w,l as c,x as T}from"./app-d946fa0e.js";import{Q as U,a as E}from"./QTable-2ba8fe71.js";import{Q as I}from"./QPagination-8b1bfbc1.js";import{Q as M}from"./QPage-6926e64f.js";import"./QSelect-a72fa5e0.js";const $={class:"bg-white q-ma-sm q-pa-sm"},A={class:"rounded-borders bg-white shadow-3 row items-center justify-between q-px-md"},D={class:""},L={class:"row justify-between items-center"},S={class:"rounded-borders bg-white shadow-3"},G={class:"row bg-white justify-center q-pb-md rounded-borders"},X={__name:"Index",setup(H){const m=v([]),b=v(""),o=v({page:1,rowsPerPage:30,rowsNumber:0}),u=async n=>{q.show();try{const a=await axios.get("/api/categories",{params:{q:n,load:"parent,children",paginate:o.value.rowsPerPage,page:o.value.page}});m.value=a.data.data,o.value.rowsNumber=a.data.meta.total}catch(a){console.log(a),y.create({message:"Fetching categories failed",color:"red"})}q.hide()},V=async n=>{var a;try{const l=await axios.delete(`/api/categories/${n}`);(a=l==null?void 0:l.data)!=null&&a.success&&(y.create({message:"Правило успешно удалено",color:"green",timeout:2e3}),await u())}catch{y.create({message:"Ошибка удаления",color:"red",timeout:2e3})}},N=async n=>{o.value.page=n,await u()},Q=[{name:"name",label:"Название",align:"left"},{name:"parent",label:"Родитель",align:"left"},{name:"children",label:"Дочерние",align:"left"},{name:"actions",label:"Действие",align:"left"}],B=async()=>{await u(b.value)};return z(()=>{u()}),(n,a)=>{const l=j("router-link");return s(),P(M,{class:"bg-grey-2"},{default:g(()=>[r("div",$,[r("div",A,[r("div",D,[t(p,{to:{name:"CategoriesCreate"},size:"md",color:"primary",label:"Добавить"})]),r("div",L,[t(F,{modelValue:b.value,"onUpdate:modelValue":a[0]||(a[0]=e=>b.value=e),class:"q-px-sm q-my-sm",clearable:"",dense:"",outlined:"",label:"Поиск"},null,8,["modelValue"]),r("div",null,[t(p,{onClick:B,size:"md",color:"green",label:"поиск"})])])]),r("div",S,[m.value.length?(s(),P(U,{key:0,class:"q-mt-md q-mx-sm",rows:m.value,columns:Q,"row-key":"id",pagination:{rowsPerPage:o.value.rowsPerPage},"hide-bottom":"",flat:"",loading:!m.value.length},{"body-cell":g(e=>[t(E,null,{default:g(()=>{var _,h,k;return[e.col.name=="name"?(s(),d(w,{key:0},[C(f(e.row.name),1)],64)):c("",!0),e.col.name=="parent"?(s(),d(w,{key:1},[C(f((_=e.row.parent)==null?void 0:_.name),1)],64)):c("",!0),e.col.name=="children"?(s(!0),d(w,{key:2},T((h=e.row)==null?void 0:h.children,i=>(s(),d("div",{key:i.id},f(i==null?void 0:i.name)+", ",1))),128)):c("",!0),e.col.name=="actions"?(s(),d(w,{key:3},[t(l,{to:{name:"CategoriesEdit",params:{id:(k=e.row)==null?void 0:k.id}}},{default:g(()=>[t(p,{flat:"",size:"sm",color:"primary",dense:"",icon:"edit"})]),_:2},1032,["to"]),t(p,{flat:"",size:"sm",dense:"",color:"red",icon:"delete",onClick:i=>{var x;return V((x=e.row)==null?void 0:x.id)}},null,8,["onClick"])],64)):c("",!0)]}),_:2},1024)]),_:1},8,["rows","pagination","loading"])):c("",!0),r("div",G,[t(I,{modelValue:o.value.page,"onUpdate:modelValue":[a[1]||(a[1]=e=>o.value.page=e),N],max:Math.ceil(o.value.rowsNumber/o.value.rowsPerPage),"boundary-numbers":"","boundary-links":"","max-pages":10,class:"text-center q-mt-md"},null,8,["modelValue","max"])])])])]),_:1})}}};export{X as default};