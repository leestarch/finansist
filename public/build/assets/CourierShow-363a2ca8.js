import{y as S,z as N,r as u,L as $,o as l,n as i,d as s,v as n,t as o,u as B,F as q,I as f,q as r,a as x,w as m,i as k,P as a,N as V,c as T,g as Q,ag as E}from"./app-64dafc3a.js";import{Q as F}from"./QFile-eeadd18b.js";import"./QChip-18101fab.js";const I={class:"q-pa-lg q-mb-xl"},P={class:"row justify-center"},z={key:0,class:"col-sm-9"},A={class:"row"},L={class:"col-12 text-h5"},D=s("b",null,"Номер поставки: ",-1),O={class:"col-12 text-h5"},R=s("b",null,"Количество товаров: ",-1),U={class:"row q-mt-lg justify-center"},Y={class:"q-mt-lg"},G={key:0,class:"q-mt-xl"},H={class:"row justify-center"},J={class:"text-h6"},K=s("div",{class:"justify-center text-center"}," Акт Приема-передачи товара ",-1),M={class:"row q-my-md",style:{border:"1px solid lightgrey"}},W=s("div",{class:"col-12 row",style:{"border-bottom":"1px solid lightgrey"}},[s("div",{class:"col-6 text-center q-py-sm",style:{"border-right":"1px solid lightgrey"}}," Клиент "),s("div",{class:"col-6 text-center q-py-sm"}," Перевозчик ")],-1),X={class:"col-12 row"},Z={class:"col-6 q-pa-md",style:{"border-right":"1px solid lightgrey"}},ss={class:"col-6 q-pa-md"},es={class:"q-px-md"},ts={class:"row q-mt-sm",style:{border:"1px solid lightgrey"}},os={class:"col-12 row",style:{"border-bottom":"1px solid lightgrey"}},ls={class:"col-4 q-pa-sm text-center",style:{"border-right":"1px solid lightgrey"}},cs=s("div",{class:"col-4 q-pa-sm text-center",style:{"border-right":"1px solid lightgrey"}}," Перевозчик ",-1),is=s("div",{class:"col-4 q-pa-sm text-center"}," Получатель ",-1),rs=E('<div class="row col-12"><div class="col-4 row"><div class="col-4 text-center q-pt-xs" style="border-right:1px solid lightgrey;"> Место доставки товара </div><div class="col-4 text-center q-pt-xs" style="border-right:1px solid lightgrey;"> Кол-во мест к отправке, штук </div><div class="col-4 text-center q-pt-xs" style="border-right:1px solid lightgrey;"> Подпись </div></div><div class="col-4 row"><div class="col-6 text-center q-pt-xs" style="border-right:1px solid lightgrey;"> Принял кол-во Мест, штук </div><div class="col-6 text-center q-pt-xs" style="border-right:1px solid lightgrey;"> Подпись </div></div><div class="col-4 row"><div class="col-6 text-center q-pt-xs" style="border-right:1px solid lightgrey;"> Принял кол-во мест, штук </div><div class="col-6 text-center q-pt-xs"> Подпись </div></div></div>',1),ds={class:"row col-12",style:{"border-top":"1px solid lightgrey"}},as={class:"col-4 row"},ns=s("div",{class:"col-4",style:{"border-right":"1px solid lightgrey"}},null,-1),_s={class:"col-4 text-center",style:{"border-right":"1px solid lightgrey"}},hs=s("div",{class:"col-4",style:{"border-right":"1px solid lightgrey"}},null,-1),us={class:"col-4 row"},vs={class:"col-6 text-center",style:{"border-right":"1px solid lightgrey"}},ps=s("div",{class:"col-6",style:{"border-right":"1px solid lightgrey"}},null,-1),ys=s("div",{class:"col-4 row",style:{"border-right":"1px lightgrey"}},[s("div",{class:"col-6 text-center",style:{"border-right":"1px solid lightgrey"}}),s("div",{class:"col-6"})],-1),gs=s("div",{class:"q-mt-md"}," Вышеперечисленные услуги выполнены полностью и в срок. Заказчик претензий по объёму, количеству и срокам оказания услуг не имеет. Настоящий Акт подписан в 3х экземплярах по одному для каждой из сторон. ",-1),xs={class:"row q-mt-md"},ms=s("div",{class:"col-2"}," Перевозчик ",-1),bs={class:"col-4"},ws={class:"row q-mt-md"},qs=s("div",{class:"col-2"}," Клиент ",-1),fs=s("br",null,null,-1),ks=s("br",null,null,-1),Vs={key:1,class:"row justify-center col-12 q-mt-lg"},js=s("div",{class:"col-xs-3 col-sm-3"},[s("p",null,[s("b",null,"Видео:")])],-1),Cs={key:0,class:"col-xs-9 col-sm-6"},Ss=["src"],Ns={key:1,class:"col-xs-9 col-sm-6"},$s={class:"row justify-center col-12"},Bs={key:0,class:"row q-mt-lg justify-center"},Ts={key:2,class:"row justify-center col-12 q-mt-lg"},Qs=s("div",{class:"col-xs-3 col-sm-3"},[s("p",null,[s("b",null,"Статус:")])],-1),Es={class:"col-xs-9 col-sm-6"},Fs={class:"q-card--bordered rounded-borders q-py-sm q-px-sm"},Is={key:1,class:"col-sm-9"},Ps={class:"row justify-center"},zs={class:"text-red"},Us={__name:"CourierShow",setup(As){const b=S(),_=b.params.supplyId,v=N("user"),h=u(null),p=u([]),t=u({done:null,video:null,isAllTaken:!1,stores:[],closed_at:null,uploadedVideo:null}),y=u(),g=async()=>{a.show();try{const c=await axios.get(`/api/courier/${_}/wb`);if(await c.data.error)h.value=await c.data.error;else{t.value=await c.data.supply,y.value=await c.data.docs;const d=Object.keys(y.value);p.value=d.map(e=>e)}}catch(c){console.error(c)}a.hide()},j=c=>{var d;a.show();try{axios.post(`/api/courier/user/${(d=v==null?void 0:v.value)==null?void 0:d.id}/accept/${_}/store/${c}/wb`),g()}catch(e){console.log(e)}a.hide()},C=async()=>{a.show();try{(await axios.post(`/api/courier/${_}/wb`,{data:t.value},{responseType:"arraybuffer",headers:{"Content-Type":"multipart/form-data"}})).status===201?(V.create({color:"green",message:"Видео загружено"}),g()):V.create({color:"red",message:"Проблемы с загрузкой видео"})}catch(c){console.log(c)}a.hide()};return $(()=>{b.name=="SuppliesCourierShow"&&g()}),(c,d)=>(l(),i("div",I,[s("div",P,[t.value&&!h.value?(l(),i("div",z,[s("div",A,[s("div",L,[D,n(o(B(_)),1)]),s("div",O,[R,n(o(t.value.count),1)])]),p.value.includes(c.index)?r("",!0):(l(!0),i(q,{key:0},f(t.value.stores,(e,w)=>(l(),i("div",U,[p.value.includes(w)?r("",!0):(l(),T(k,{key:0,color:"green",onClick:Ls=>j(w)},{default:m(()=>[n("Принять поставку "+o(e),1)]),_:2},1032,["onClick"]))]))),256)),(l(!0),i(q,null,f(y.value,e=>(l(),i("div",Y,[e.carrier?(l(),i("div",G,[s("div",H,[s("p",J," От "+o(e.date),1)]),K,s("div",M,[W,s("div",X,[s("div",Z,o(e.client),1),s("div",ss,o(e.carrier),1)])]),s("div",es,[n(" Основание: Договор на оказание услуг перевозки груза № "),s("span",null,o(e.doc_number),1)]),s("div",ts,[s("div",os,[s("div",ls," Отправитель "+o(e.store),1),cs,is]),rs,s("div",ds,[s("div",as,[ns,s("div",_s,o(e.amount),1),hs]),s("div",us,[s("div",vs,o(e.amount),1),ps]),ys])]),gs,s("div",xs,[ms,s("div",bs," _____________ "+o(e.carrierSign),1)]),s("div",ws,[qs,s("div",null," _____________ "+o(e.EP),1)])])):r("",!0)]))),256)),fs,ks,t.value.isAllTaken?(l(),i("div",Vs,[js,t.value.uploadedVideo?(l(),i("div",Cs,[s("video",{controls:"",src:t.value.uploadedVideo,height:"360",width:"auto"}," Your browser does not support the video tag. ",8,Ss)])):r("",!0),t.value.uploadedVideo?r("",!0):(l(),i("div",Ns,[x(F,{outlined:"",modelValue:t.value.video,"onUpdate:modelValue":d[0]||(d[0]=e=>t.value.video=e),label:"Прикрепить",clearable:"",dense:"",accept:"video/*"},{default:m(()=>[x(Q,{name:"attach_file",class:"cursor-pointer",color:"grey",size:"1.2rem"})]),_:1},8,["modelValue"])]))])):r("",!0),s("div",$s,[t.value.video?(l(),i("div",Bs,[x(k,{color:"green",onClick:C},{default:m(()=>[n("Сохранить видео")]),_:1})])):r("",!0)]),t.value.done?(l(),i("div",Ts,[Qs,s("div",Es,[s("div",Fs," Cтатус: "+o(t.value.done?"Выполнено.":"Не выполнено")+" Закрыто: "+o(t.value.closed_at),1)])])):r("",!0)])):r("",!0),h.value?(l(),i("div",Is,[s("div",Ps,[s("h5",zs,o(h.value),1)])])):r("",!0)])]))}};export{Us as default};